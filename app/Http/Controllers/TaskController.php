<?php


namespace App\Http\Controllers;

use App\DataTables\TasksDataTable;
use App\Events\TaskReminderEvent;
use App\Events\TaskEvent;
use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Http\Requests\Tasks\UpdateTask;
use App\Models\BaseModel;
use App\Models\Pinned;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectTimeLogBreak;
use App\Models\SubTask;
use App\Models\SubTaskFile;
use App\Models\Task;
use App\Models\TaskboardColumn;
use App\Models\TaskCategory;
use App\Models\TaskLabel;
use App\Models\TaskLabelList;
use App\Models\TaskUser;
use App\Models\User;
use App\Traits\ProjectProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\Files;
use App\Models\ProjectTimeLog;
use App\Models\TaskComment;
use App\Models\TaskCommentEmoji;
use App\Models\TaskFile;
use App\Models\TaskSetting;
use App\Models\ProjectTemplate;
use Illuminate\Support\Facades\Config;

use Mail;


class TaskController extends AccountBaseController
{

    use ProjectProgress;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.tasks';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('tasks', $this->user->modules));

            return $next($request);
        });
    }

    public function index(TasksDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_tasks');
        abort_403(!in_array($viewPermission, ['all', 'added', 'owned', 'both']));

        if (!request()->ajax()) {
            $this->projects = Project::allProjects();

            if (in_array('client', user_roles())) {
                $this->clients = User::client();
            }
            else {
                $this->clients = User::allClients();
            }

            $this->employees = User::allEmployees(null, true, ($viewPermission == 'all' ? 'all' : null));
            $this->taskBoardStatus = TaskboardColumn::all();
            $this->taskCategories = TaskCategory::all();
            $this->taskLabels = TaskLabelList::all();
            $this->milestones = ProjectMilestone::all();
        }

        return $dataTable->render('tasks.index', $this->data);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return array
     */
    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
        case 'delete':
            $this->deleteRecords($request);

            return Reply::success(__('messages.deleteSuccess'));
        case 'change-status':
            $this->changeBulkStatus($request);

            return Reply::success(__('messages.updateSuccess'));
        default:
            return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
        abort_403(user()->permission('delete_tasks') != 'all');

        Task::whereIn('id', explode(',', $request->row_ids))->delete();
    }

    protected function changeBulkStatus($request)
    {
        abort_403(user()->permission('edit_tasks') != 'all');

        Task::whereIn('id', explode(',', $request->row_ids))->update(['board_column_id' => $request->status]);
    }

    public function changeStatus(Request $request)
    {
        $projectpercent=0;
        $taskId = $request->taskId;
        $status = $request->status;
        $task = Task::with('project', 'users')->findOrFail($taskId);
        $taskUsers = $task->users->pluck('id')->toArray();

        $this->editPermission = user()->permission('edit_tasks');
        $this->changeStatusPermission = user()->permission('change_status');
        abort_403(!(
            $this->changeStatusPermission == 'all'
            || ($this->changeStatusPermission == 'added' && $task->added_by == user()->id)
            || ($this->changeStatusPermission == 'owned' && in_array(user()->id, $taskUsers))
            || ($this->changeStatusPermission == 'both' && (in_array(user()->id, $taskUsers) || $task->added_by == user()->id))
            || ($task->project && $task->project->project_admin == user()->id)
        ));

        $taskBoardColumn = TaskboardColumn::where('slug', $status)->first();
        $task->board_column_id = $taskBoardColumn->id;

        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = now()->format('Y-m-d H:i:s');
            $task->save();
        }
        else {
            $task->completed_on = null;
        }

        $task->save();

        if ($task->project_id != null) {

            if ($task->project->calculate_task_progress == 'true') {
                // Calculate project progress if enabled
                $projectpercent = $this->calculateProjectProgress($task->project_id, 'true');
                if($projectpercent == 100){

                    $prjsalestaskcheck=DB::table('adminsalestask')->where('project_id', $task->project_id)->count();
                    
                    if($prjsalestaskcheck!=0){

                        $url = 'https://edoxi.cyradrive.com/task-manager/admintaskstatusupdate'; // Replace with the URL you want to fetch data from


                        $tasknote = "";

                        $taskstatus = "Completed";

                        $dataparam = array(

                            'taskid' => $taskid,
                            'tasknote' => $tasknote,
                            'taskstatus' => $taskstatus,
                        );

                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataparam)); // Set POST data
                        $response = curl_exec($ch);

                        curl_close($ch);

                        $mailresult=Mail::send('employees.ajax.salestaskcompleted',$dataparam,function($messages) use ($taskid,$taskstatus){  
                            $messages->to("neerajnextclick@gmail.com");
                            $messages->subject('Sale Task'.$taskid.'-'.$taskstatus);
                
                        });

                    }

                    $admins = User::allAdmins($task->company->id);
                    event(new TaskEvent($task, $admins, 'TaskCompleted'));


                    
                }
            }
        }

        $this->selfActiveTimer = ProjectTimeLog::with('activeBreak')
            ->where('user_id', user()->id)
            ->whereNull('end_time')
            ->first();
        $clockHtml = view('sections.timer_clock', $this->data)->render();

        return Reply::successWithData(__('messages.updateSuccess'), ['clockHtml' => $clockHtml]);

    }

    public function destroy(Request $request, $id)
    {
        $task = Task::with('project')->findOrFail($id);

        $this->deletePermission = user()->permission('delete_tasks');

        $taskUsers = $task->users->pluck('id')->toArray();

        abort_403(!($this->deletePermission == 'all'
            || ($this->deletePermission == 'owned' && in_array(user()->id, $taskUsers))
            || ($task->project && ($task->project->project_admin == user()->id))
            || ($this->deletePermission == 'both' && (in_array(user()->id, $taskUsers) || $task->added_by == user()->id))
            || ($this->deletePermission == 'owned' && (in_array('client', user_roles()) && $task->project && ($task->project->client_id == user()->id)))
            || ($this->deletePermission == 'both' && (in_array('client', user_roles()) && ($task->project && ($task->project->client_id == user()->id)) || $task->added_by == user()->id))
        ));

        // If it is recurring and allowed by user to delete all its recurring tasks
        if ($request->has('recurring') && $request->recurring == 'yes') {
            Task::where('recurring_task_id', $id)->delete();
        }

        // Delete current task
        $task->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $this->pageTitle = __('app.add') . ' ' . __('app.task');

        $this->addPermission = user()->permission('add_tasks');
        $this->projectShortCode = '';
        $this->project = request('task_project_id') ? Project::with('projectMembers')->findOrFail(request('task_project_id')) : null;

        if (is_null($this->project) || ($this->project->project_admin != user()->id)) {
            abort_403(!in_array($this->addPermission, ['all', 'added']));
        }

        $this->task = (request()['duplicate_task']) ? Task::with('users', 'label', 'project')->findOrFail(request()['duplicate_task'])->withCustomFields() : null;
        $this->selectedLabel = TaskLabel::where('task_id', request()['duplicate_task'])->get()->pluck('label_id')->toArray();
        $this->projectMember = TaskUser::where('task_id', request()['duplicate_task'])->get()->pluck('user_id')->toArray();

        $this->projects = Project::allProjects();

        if (request('task_project_id')) {
            $project = Project::findOrFail(request('task_project_id'));
            $this->projectShortCode = $project->project_short_code;
            $this->milestones = ProjectMilestone::where('project_id', request('task_project_id'))->get();

        }
        else {
            if ($this->task && $this->task->project) {
                $this->milestones = $this->task->project->milestones;
            }
            else {
                $this->milestones = collect([]);
            }
        }

        $this->columnId = request('column_id');
        $this->categories = TaskCategory::all();
        $this->taskLabels = TaskLabelList::where('project_id', null)->get();
        $this->taskboardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();

        if (request()->has('default_assign') && request('default_assign') != '') {
            $this->defaultAssignee = request('default_assign');
        }

        $this->allTasks = $completedTaskColumn ? Task::where('board_column_id', '<>', $completedTaskColumn->id)->whereNotNull('due_date')->get() : [];

        if (!is_null($this->project)) {
            if ($this->project->public) {
                $this->employees = User::allEmployees(null, true, ($this->addPermission == 'all' ? 'all' : null));

            }
            else {

                $this->employees = $this->project->projectMembers;
            }
        }
        else if (!is_null($this->task) && !is_null($this->task->project_id)) {

            if ($this->task->project->public) {
                $this->employees = User::allEmployees(null, true, ($this->addPermission == 'all' ? 'all' : null));
            }
            else {

                $this->employees = $this->task->project->projectMembers;
            }
        }
        else {
            if (in_array('client', user_roles())) {
                $this->employees = collect([]); // Do not show all employees to client

            } else {
                $this->employees = User::allEmployees(null, true, ($this->addPermission == 'all' ? 'all' : null));
            }

        }

        $task = new Task();


        if (!empty($task->getCustomFieldGroupsWithFields())) {
            $this->fields = $task->getCustomFieldGroupsWithFields()->fields;
        }


        if (request()->ajax()) {
            $html = view('tasks.ajax.create', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'tasks.ajax.create';

        return view('tasks.create', $this->data);
    }

    // The function is called for duplicate code also
    public function store(StoreTask $request)
    {

        $project = request('project_id') ? Project::findOrFail(request('project_id')) : null;

        if (is_null($project) || ($project->project_admin != user()->id)) {
            $this->addPermission = user()->permission('add_tasks');
            abort_403(!in_array($this->addPermission, ['all', 'added']));
        }

        DB::beginTransaction();
        $ganttTaskArray = [];
        $gantTaskLinkArray = [];

        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

        $task = new Task();
        $task->heading = $request->heading;
        $task->description = trim_editor($request->description);
        $dueDate = ($request->has('without_duedate')) ? null : Carbon::createFromFormat($this->company->date_format, $request->due_date)->format('Y-m-d');
        $task->start_date = Carbon::createFromFormat($this->company->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = $dueDate;
        $task->project_id = $request->project_id;
        $task->task_category_id = $request->category_id;
        $task->priority = $request->priority;
        $task->board_column_id = $taskBoardColumn->id;

        if ($request->has('dependent') && $request->has('dependent_task_id') && $request->dependent_task_id != '') {
            $dependentTask = Task::findOrFail($request->dependent_task_id);

            if (!is_null($dependentTask->due_date) && !is_null($dueDate) && $dependentTask->due_date->greaterThan($dueDate)) {
                /* @phpstan-ignore-line */
                return Reply::error(__('messages.taskDependentDate'));
            }

            $task->dependent_task_id = $request->dependent_task_id;
        }

        $task->is_private = $request->has('is_private') ? 1 : 0;
        $task->billable = $request->has('billable') && $request->billable ? 1 : 0;
        $task->estimate_hours = $request->estimate_hours;
        $task->estimate_minutes = $request->estimate_minutes;

        if ($request->board_column_id) {
            $task->board_column_id = $request->board_column_id;
        }

        if ($request->milestone_id != '') {
            $task->milestone_id = $request->milestone_id;
        }

        // Add repeated task
        $task->repeat = $request->repeat ? 1 : 0;

        if ($request->has('repeat')) {
            $task->repeat_count = $request->repeat_count;
            $task->repeat_type = $request->repeat_type;
            $task->repeat_cycles = $request->repeat_cycles;
        }

        $task->save();

        $task->task_short_code = ($project) ? $project->project_short_code . '-' . Task::count() : null;
        $task->saveQuietly();

        // Save labels

        $task->labels()->sync($request->task_labels);


        if (!is_null($request->taskId)) {

            $taskExists = TaskFile::where('task_id', $request->taskId)->get();

            if ($taskExists) {

                foreach ($taskExists as $taskExist) {
                    $file = new TaskFile();
                    $file->user_id = $taskExist->user_id;
                    $file->task_id = $task->id;

                    $fileName = Files::generateNewFileName($taskExist->filename);

                    Files::copy(TaskFile::FILE_PATH . '/' . $taskExist->task_id . '/' . $taskExist->hashname, TaskFile::FILE_PATH . '/' . $task->id . '/' . $fileName);

                    $file->filename = $taskExist->filename;
                    $file->hashname = $fileName;
                    $file->size = $taskExist->size;
                    $file->save();


                    $this->logTaskActivity($task->id, $this->user->id, 'fileActivity', $task->board_column_id);
                    
                }
            }


            $subTask = SubTask::with(['files'])->where('task_id', $request->taskId)->get();


            if ($subTask) {
                foreach ($subTask as $subTasks) {
                    $subTaskData = new SubTask();
                    $subTaskData->title = $subTasks->title;
                    $subTaskData->task_id = $task->id;
                    $subTaskData->description = trim_editor($subTasks->description);

                    if ($subTasks->start_date != '' && $subTasks->due_date != '') {
                        $subTaskData->start_date = $subTasks->start_date;
                        $subTaskData->due_date = $subTasks->due_date;
                    }

                    $subTaskData->assigned_to = $subTasks->assigned_to;

                    $subTaskData->save();

                    if ($subTasks->files) {
                        foreach ($subTasks->files as $fileData) {
                            $file = new SubTaskFile();
                            $file->user_id = $fileData->user_id;
                            $file->sub_task_id = $subTaskData->id;

                            $fileName = Files::generateNewFileName($fileData->filename);

                            Files::copy(SubTaskFile::FILE_PATH . '/' . $fileData->sub_task_id . '/' . $fileData->hashname, SubTaskFile::FILE_PATH . '/' . $subTaskData->id . '/' . $fileName);

                            $file->filename = $fileData->filename;
                            $file->hashname = $fileName;
                            $file->size = $fileData->size;
                            $file->save();
                        }
                    }
                }
            }
        }

        // To add custom fields data
        if ($request->custom_fields_data) {
            $task->updateCustomFieldData($request->custom_fields_data);
        }

        // For gantt chart
        if ($request->page_name && !is_null($task->due_date) && $request->page_name == 'ganttChart') {
            $task = Task::find($task->id);
            $parentGanttId = $request->parent_gantt_id;

            /* @phpstan-ignore-next-line */
            
            $taskDuration = $task->due_date->diffInDays($task->start_date);
            /* @phpstan-ignore-line */
            $taskDuration = $taskDuration + 1;

            $ganttTaskArray[] = [
                'id' => $task->id,
                'text' => $task->heading,
                'start_date' => $task->start_date->format('Y-m-d'), /* @phpstan-ignore-line */
                'duration' => $taskDuration,
                'parent' => $parentGanttId,
                'taskid' => $task->id
            ];

            $gantTaskLinkArray[] = [
                'id' => 'link_' . $task->id,
                'source' => $task->dependent_task_id != '' ? $task->dependent_task_id : $parentGanttId,
                'target' => $task->id,
                'type' => $task->dependent_task_id != '' ? 0 : 1
            ];
        }


        DB::commit();

        if (request()->add_more == 'true') {
            unset($request->project_id);
            $html = $this->create();

            return Reply::successWithData(__('messages.recordSaved'), ['html' => $html, 'add_more' => true, 'taskID' => $task->id]);
        }

        if ($request->page_name && $request->page_name == 'ganttChart') {
                        
            return Reply::successWithData(
                'messages.recordSaved',
                [
                    'tasks' => $ganttTaskArray,
                    'links' => $gantTaskLinkArray
                ]
            );
        }

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('tasks.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl, 'taskID' => $task->id]);

    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editTaskPermission = user()->permission('edit_tasks');
        $this->task = Task::with('users', 'label', 'project')->findOrFail($id)->withCustomFields();
        $this->taskUsers = $taskUsers = $this->task->users->pluck('id')->toArray();
        $this->headIds = DB::table('project_departments')
                        ->leftJoin('teams', 'project_departments.team_id', 'teams.id') // connecting department with teams
                        ->where('project_id',$this->task->project_id)
                        ->pluck('head_id')
                        ->toArray();

    
        abort_403(!($editTaskPermission == 'all'
            || ($editTaskPermission == 'owned' && in_array(user()->id, $taskUsers))
            || ($editTaskPermission == 'added' && ($this->task->added_by == user()->id || in_array(user()->id,$headIds)))
            || ($this->task->project && ($this->task->project->project_admin == user()->id))
            || ($editTaskPermission == 'both' && (in_array(user()->id, $taskUsers) || ($this->task->added_by == user()->id || in_array(user()->id,$this->headIds))))
            || ($editTaskPermission == 'owned' && (in_array('client', user_roles()) && $this->task->project && ($this->task->project->client_id == user()->id)))
            || ($editTaskPermission == 'both' && (in_array('client', user_roles()) && ($this->task->project && ($this->task->project->client_id == user()->id)) || ($this->task->added_by == user()->id || in_array(user()->id,$this->headIds))))
        ));

        if (!empty($this->task->getCustomFieldGroupsWithFields())) {
            $this->fields = $this->task->getCustomFieldGroupsWithFields()->fields;
        }

        $this->pageTitle = __('app.update') . ' ' . __('app.task');
        $this->labelIds = $this->task->label->pluck('label_id')->toArray();
        $this->projects = Project::allProjects();
        $this->categories = TaskCategory::all();
        $projectId = $this->task->project_id;
        $this->taskLabels = TaskLabelList::where('project_id', $projectId)->orWhere('project_id', null)->get();
        $this->taskboardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();
        $this->changeStatusPermission = user()->permission('change_status');
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();


        

        if ($completedTaskColumn) {


            $this->allTasks = Task::where('id', '!=', $id)->where('project_id', $projectId)->get();
            // $this->allTasks = Task::whereNotNull('due_date')->where('id', '!=', $id)->where('project_id', $projectId)->get();
            // var_dump($this->allTasks );
        }
        else {
            $this->allTasks = [];
        }

        if ($this->task->project_id) {
            if ($this->task->project->public) {
                $this->employees = User::allEmployees(null, null, ($editTaskPermission == 'all' ? 'all' : null));

            }
            else {
                $this->employees = $this->task->project->projectMembers;
            }
        }
        else {
            if ($editTaskPermission == 'added' || $editTaskPermission == 'owned') {
                $this->employees = ((count($this->task->users) > 0) ? $this->task->users : User::allEmployees(null, null, ($editTaskPermission == 'all' ? 'all' : null)));

            }
            else {
                $this->employees = User::allEmployees(null, null, ($editTaskPermission == 'all' ? 'all' : null));
            }
        }


        $uniqueId = $this->task->task_short_code;
        // check if unuqueId contains -
        if (strpos($uniqueId, '-') !== false) {
            $uniqueId = explode('-', $uniqueId, 2);
            $this->projectUniId = $uniqueId[0];
            $this->taskUniId = $uniqueId[1];
        }
        else {
            $this->projectUniId = ($this->task->project_id != null) ? $this->task->project->project_short_code : null;
            $this->taskUniId = $uniqueId;
        }

        if (request()->ajax()) {
            $html = view('tasks.ajax.edit', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'tasks.ajax.edit';

        return view('tasks.create', $this->data);

    }

    public function update(UpdateTask $request, $id)
    {

        $task = Task::with('users', 'label', 'project')->findOrFail($id)->withCustomFields();

        $editTaskPermission = user()->permission('edit_tasks');
        $taskUsers = $task->users->pluck('id')->toArray();
        
        $this->headIds = DB::table('project_departments')
                        ->leftJoin('teams', 'project_departments.team_id', 'teams.id') // connecting department with teams
                        ->where('project_id',$task->project_id)
                        ->pluck('head_id')
                        ->toArray();

        
    
        abort_403(!($editTaskPermission == 'all'
            || ($editTaskPermission == 'owned' && in_array(user()->id, $taskUsers))
            || ($editTaskPermission == 'added' && ($task->added_by == user()->id || in_array(user()->id,$headIds)))
            || ($task->project && ($task->project->project_admin == user()->id))
            || ($editTaskPermission == 'both' && (in_array(user()->id, $taskUsers) || ($task->added_by == user()->id || in_array(user()->id,$this->headIds))))
            || ($editTaskPermission == 'owned' && (in_array('client', user_roles()) && $task->project && ($task->project->client_id == user()->id)))
            || ($editTaskPermission == 'both' && (in_array('client', user_roles()) && ($task->project && ($task->project->client_id == user()->id)) || ($task->added_by == user()->id || in_array(user()->id,$this->headIds))))
        ));

        
        
        $dueDate = ($request->has('without_duedate')) ? null : Carbon::createFromFormat($this->company->date_format, $request->due_date)->format('Y-m-d');
        $task->heading = $request->heading;

        $task->description = trim_editor($request->description);
        $task->start_date = Carbon::createFromFormat($this->company->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = $dueDate;
        $task->task_category_id = $request->category_id;
        $task->priority = $request->priority;

        

        if ($request->has('board_column_id')) {
            $task->board_column_id = $request->board_column_id;

            $taskBoardColumn = TaskboardColumn::findOrFail($request->board_column_id);

            if ($taskBoardColumn->slug == 'completed') {
                $task->completed_on = now()->format('Y-m-d H:i:s');
            }
            else {
                $task->completed_on = null;
            }
        }

       

        $task->dependent_task_id = $request->has('dependent') && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;
        $task->is_private = $request->has('is_private') ? 1 : 0;
        $task->billable = $request->has('billable') && $request->billable ? 1 : 0;
        $task->estimate_hours = $request->estimate_hours;
        $task->estimate_minutes = $request->estimate_minutes;

        if ($request->project_id != '') {
            $task->project_id = $request->project_id;
        }
        else {
            $task->project_id = null;
        }

        $task->milestone_id = $request->milestone_id;

        if ($request->has('dependent') && $request->has('dependent_task_id') && $request->dependent_task_id != '') {
            $dependentTask = Task::findOrFail($request->dependent_task_id);

            if (!is_null($dependentTask->due_date) && !is_null($dueDate) && $dependentTask->due_date->greaterThan($dueDate)) {
                return Reply::error(__('messages.taskDependentDate'));
            }

            $task->dependent_task_id = $request->dependent_task_id;
        }

        // Add repeated task
        $task->repeat = $request->repeat ? 1 : 0;

        if ($request->has('repeat')) {
            $task->repeat_count = $request->repeat_count;
            $task->repeat_type = $request->repeat_type;
            $task->repeat_cycles = $request->repeat_cycles;
        }

        $task->save();
        $task->load('project');

        $project = $task->project;

        $task->task_short_code = (!is_null($task->project_id) ? $project->project_short_code . '-' . $task->count() : null);
        $task->saveQuietly();

        // save labels
        $task->labels()->sync($request->task_labels);

        // To add custom fields data
        if ($request->custom_fields_data) {
            $task->updateCustomFieldData($request->custom_fields_data);
        }

        // Sync task users
        $task->users()->sync($request->user_id);

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => route('tasks.show', $id)]);
    }

    public function show($id)
    {
        
        $viewTaskFilePermission = user()->permission('view_task_files');
        $viewSubTaskPermission = user()->permission('view_sub_tasks');
        $this->viewTaskCommentPermission = user()->permission('view_task_comments');
        $this->viewTaskNotePermission = user()->permission('view_task_notes');
        $this->viewUnassignedTasksPermission = user()->permission('view_unassigned_tasks');


        $this->task = Task::with(['boardColumn', 'project', 'users', 'label', 'approvedTimeLogs',
                                'approvedTimeLogs.user', 'approvedTimeLogs.activeBreak','comments',
                                'comments.commentEmoji', 'comments.like', 'comments.dislike', 'comments.likeUsers',
                                'comments.dislikeUsers', 'comments.user', 'subtasks.files', 'userActiveTimer',
            'files' => function ($q) use ($viewTaskFilePermission) {
                if ($viewTaskFilePermission == 'added') {
                    $q->where('added_by', user()->id);
                }
            },
            'subtasks' => function ($q) use ($viewSubTaskPermission) {
                if ($viewSubTaskPermission == 'added') {
                    $q->where('added_by', user()->id);
                }
            }])
            ->withCount('subtasks', 'files', 'comments', 'activeTimerAll')
            ->findOrFail($id)->withCustomFields();

        $this->headIds = DB::table('project_departments')
        ->leftJoin('teams', 'project_departments.team_id', 'teams.id') // connecting department with teams
        ->where('project_id',$this->task->project_id)
        ->pluck('head_id')
        ->toArray();

        $this->taskUsers = $taskUsers = $this->task->users->pluck('id')->toArray();

        $this->taskSettings = TaskSetting::first();

        $viewTaskPermission = user()->permission('view_tasks');
        abort_403(!(
            $viewTaskPermission == 'all'
            || ($viewTaskPermission == 'added' && ($this->task->added_by == user()->id || in_array(user()->id,$headIds)))
            || ($viewTaskPermission == 'owned' && in_array(user()->id, $taskUsers))
            || ($viewTaskPermission == 'both' && (in_array(user()->id, $taskUsers) || $this->task->added_by == user()->id))

            || ($viewTaskPermission == 'owned' && in_array('client', user_roles()) && $this->task->project_id && $this->task->project->client_id == user()->id)
            || ($viewTaskPermission == 'both' && in_array('client', user_roles()) && $this->task->project_id && $this->task->project->client_id == user()->id)
            || ($this->viewUnassignedTasksPermission == 'all' && in_array('employee', user_roles()))
            || ($this->task->project_id && $this->task->project->project_admin == user()->id)
        ));

        if (!$this->task->project_id || ($this->task->project_id && $this->task->project->project_admin != user()->id)) {
            abort_403($this->viewUnassignedTasksPermission == 'none' && count($taskUsers) == 0);
        }

        $this->pageTitle = __('app.task') . ' # ' . $this->task->task_short_code;

        if (!empty($this->task->getCustomFieldGroupsWithFields())) {
            $this->fields = $this->task->getCustomFieldGroupsWithFields()->fields;
        }

        $this->employees = User::join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->leftJoin('project_time_logs', 'project_time_logs.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id');


        $this->employees = $this->employees->select(
            'users.name',
            'users.image',
            'users.id',
            'designations.name as designation_name'
        );

        $this->employees = $this->employees->where('project_time_logs.task_id', '=', $id);

        $this->employees = $this->employees->groupBy('project_time_logs.user_id')
            ->orderBy('users.name')
            ->get();

        $this->breakMinutes = ProjectTimeLogBreak::taskBreakMinutes($this->task->id);
        
        // Add Gitlab task details if available
        if (module_enabled('Gitlab')) {
            if (in_array('gitlab', user_modules()) && !is_null($this->task->project_id)) {
                /** @phpstan-ignore-next-line */
                $this->gitlabSettings = \Modules\Gitlab\Entities\GitlabSetting::where('user_id', user()->id)->first();

                if (!$this->gitlabSettings) {
                    /** @phpstan-ignore-next-line */
                    $this->gitlabSettings = \Modules\Gitlab\Entities\GitlabSetting::whereNull('user_id')->first();
                }

                if ($this->gitlabSettings) {
                    /** @phpstan-ignore-next-line */
                    Config::set('gitlab.connections.main.token', $this->gitlabSettings->personal_access_token);
                    /** @phpstan-ignore-next-line */
                    Config::set('gitlab.connections.main.url', $this->gitlabSettings->gitlab_url);

                    /** @phpstan-ignore-next-line */
                    $gitlabProject = \Modules\Gitlab\Entities\GitlabProject::where('project_id', $this->task->project_id)->first();
                    /** @phpstan-ignore-next-line */
                    $gitlabTask = \Modules\Gitlab\Entities\GitlabTask::where('task_id', $id)->first();

                    if ($gitlabTask) {
                        /** @phpstan-ignore-next-line */
                        $gitlabIssue = \GrahamCampbell\GitLab\Facades\GitLab::issues()->all(intval($gitlabProject->gitlab_project_id), ['iids' => [intval($gitlabTask->gitlab_task_iid)]]);
                        $this->gitlabIssue = $gitlabIssue[0];
                    }
                }
            }
        }

        $tab = request('view');
        
        switch ($tab) {
        case 'sub_task':
            $this->tab = 'tasks.ajax.sub_tasks';
            break;
        case 'comments':
            abort_403($this->viewTaskCommentPermission == 'none');

            $this->tab = 'tasks.ajax.comments';
            break;
        case 'notes':
            abort_403($this->viewTaskNotePermission == 'none');
            $this->tab = 'tasks.ajax.notes';
            break;
        case 'history':
            $this->tab = 'tasks.ajax.history';
            break;
        case 'time_logs':
            $this->tab = 'tasks.ajax.timelogs';
            break;
        case 'results':
            $this->tab = 'tasks.ajax.results';
            break;
        default:
       
            if ($this->taskSettings->files == 'yes' && in_array('client', user_roles())) {
                $this->tab = 'tasks.ajax.files';
                
            }
            elseif ($this->taskSettings->sub_task == 'yes' && in_array('client', user_roles())) {
                $this->tab = 'tasks.ajax.sub_tasks';
            }
            elseif ($this->taskSettings->comments == 'yes' && in_array('client', user_roles())) {
                abort_403($this->viewTaskCommentPermission == 'none');
                $this->tab = 'tasks.ajax.comments';
            }
            elseif ($this->taskSettings->time_logs == 'yes' && in_array('client', user_roles())) {
                abort_403($this->viewTaskNotePermission == 'none');
                $this->tab = 'tasks.ajax.timelogs';
            }
            elseif ($this->taskSettings->notes == 'yes' && in_array('client', user_roles())) {
                abort_403($this->viewTaskNotePermission == 'none');
                $this->tab = 'tasks.ajax.notes';
            }
            elseif ($this->taskSettings->history == 'yes' && in_array('client', user_roles())) {
                abort_403($this->viewTaskNotePermission == 'none');
                $this->tab = 'tasks.ajax.history';
            }
            elseif (!in_array('client', user_roles())) {
                $this->tab = 'tasks.ajax.files';
                
            }
            break;
        }

       


        if (request()->ajax()) {

            $view = (request('json') == true) ? $this->tab : 'tasks.ajax.show';

            $html = view($view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html,'title' => $this->pageTitle]);
        }


        $this->view = 'tasks.ajax.show';

        // var_dump($this->data);

        return view('tasks.create', $this->data);

    }

    public function storePin(Request $request)
    {
        $pinned = new Pinned();
        $pinned->task_id = $request->task_id;
        $pinned->project_id = $request->project_id;
        $pinned->save();

        return Reply::success(__('messages.pinnedSuccess'));
    }

    public function destroyPin(Request $request, $id)
    {
        $type = ($request->type == 'task') ? 'task_id' : 'project_id';

        Pinned::where($type, $id)->where('user_id', user()->id)->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

    public function checkTask($taskID)
    {
        $task = Task::findOrFail($taskID);
        $subTask = SubTask::where(['task_id' => $taskID, 'status' => 'incomplete'])->count();

        return Reply::dataOnly(['taskCount' => $subTask, 'lastStatus' => $task->boardColumn->slug]);
    }

    public function clientDetail(Request $request)
    {
        $project = Project::with('client')->findOrFail($request->id);

        if (!is_null($project->client)) {
            $data = '<h5 class= "mb-2 f-13"> ' . __('modules.projects.projectClient') . '</h5>';
            $data .= view('components.client', ['user' => $project->client]);            /* @phpstan-ignore-line */
        }
        else {
            $data = '<p> ' . __('modules.projects.projectDoNotHaveClient') . '</p>';
        }

        return Reply::dataOnly(['data' => $data]);
    }

    public function updateTaskDuration(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $task->due_date = (!is_null($task->due_date)) ? Carbon::createFromFormat('d/m/Y', $request->end_date)->addDay()->format('Y-m-d') : null;
        $task->save();

        return Reply::success('messages.updateSuccess');
    }

    public function projectTasks($id)
    {
        $options = '<option value="">--</option>';

        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();

        $tasks = Task::where('board_column_id', '<>', $completedTaskColumn->id)->whereNotNull('due_date');

        if ($id != 0 && $id != '') {
            $tasks = $tasks->where('project_id', $id);
        }

        $tasks = $completedTaskColumn ? $tasks->get() : [];

        foreach ($tasks as $item) {

            $options .= '<option  data-content="<div class=\'d-inline-block mr-1\'></div>  ' . $item->heading . ' ( Due date: ' .  $item->due_date->format(company()->date_format) . ' ) " value="' . $item->id . '"> ' . $item->heading . '  ' . $item->due_date . ' </option>';
        }

        return Reply::dataOnly(['status' => 'success', 'data' => $options]);
    }

    public function members($id)
    {
        $options = '<option value="">--</option>';

        $members = Task::with('activeUsers')->findOrFail($id);

        foreach ($members->activeUsers as $item) {
            $options .= '<option  data-content="<div class=\'d-inline-block mr-1\'><img class=\'taskEmployeeImg rounded-circle\' src=' . $item->image_url . ' ></div>  ' . $item->name . '" value="' . $item->id . '"> ' . $item->name . ' </option>';
        }

        return Reply::dataOnly(['status' => 'success', 'data' => $options]);
    }

    public function reminder()
    {
        $taskID = request()->id;
        $task = Task::with('users')->findOrFail($taskID);

        // Send  reminder notification to user
        event(new TaskReminderEvent($task));

        return Reply::success('messages.reminderMailSuccess');
    }



    public function salestasks(){

        $data['pageTitle']= 'Admin Tasks';
        $data['pushSetting']= $this->pushSetting;
        $data['pusherSettings']= $this->pusherSettings;
        if (in_array('admin', user_roles())){
    
            $data['checkListCompleted']= $this->checkListCompleted;
        }
        
        $data['checkListTotal']= $this->checkListTotal;
        $data['activeTimerCount']= $this->activeTimerCount;
        $data['unreadNotificationCount']= $this->unreadNotificationCount;
        $data['appTheme']= $this->appTheme;
        $data['appName']= $this->appName;
        $data['user']= $this->user;
        $data['sidebarUserPermissions']=$this->sidebarUserPermissions;
        $data['companyName']=$this->companyName;
        $data['userCompanies']=$this->userCompanies;
        $data['currentRouteName']=$this->currentRouteName;
        $data['unreadMessagesCount']=$this->unreadMessagesCount;
        $data['worksuitePlugins']=$this->worksuitePlugins;
        $data['company']=$this->company;


        $url = 'https://edoxi.cyradrive.com/task-manager/admintask'; // Replace with the URL you want to fetch data from
    
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        $data['admintask'] = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        $this->env = $data['admintask'];
        curl_close($ch);


        // var_dump($data['admintask']);
    
        if (!$data['admintask']) {
            die('Failed to fetch data.');
        }

        $data['salestaskids'] = DB::table('adminsalestask')->where('type','=','Project Creation')->pluck('ext_taskid')->toArray();

        $data['salestaskprjids'] = DB::table('adminsalestask')->where('project_id','!=',0)->pluck('ext_taskid')->toArray();


        return view('tasks.salestasks',$data);
    }



    public function salestaskscreateproject($id){

    $this->clients = User::allClients();

    $this->prjtemplates = DB::table('project_templates')->select('id','project_name')->get();

    $this->saletaskid = $id;

    return view('tasks.salestaskscreateproject',$this->data);

    }


    public function marketingtaskscreateproject($id){

        $this->clients = User::allClients();
    
        $this->prjtemplates = DB::table('project_templates')->select('id','project_name')->get();
    
        $this->taskid = $id;
    
        return view('tasks.marketingtaskscreateproject',$this->data);
    
    }

    public function salestasksupdateprj($id){

    
    
        $this->saletaskid = $id;
    
        return view('tasks.salestasksedit',$this->data);
    
    }


    public function marketingtaskcompleted(Request $request){

        $taskid = $request->comptaskid;  

        DB::table('marketingsalestask')->where('id',$taskid)->update(array('result'=>$request->compresult,'status'=>"completed"));
        

        return redirect()->route('dashboard');


    }


    public function updsalestask(Request $request){

    $taskid=$request->salestask_id;

    $tasknote=$request->tasknote;

    $taskstatus=$request->task_status;

    $taskcount = DB::table('adminsalestask')->where('ext_taskid', $taskid)->count();


    if($taskcount==0){

        $tasktoinsert[] = [

            'ext_taskid' => $taskid,
            'type' => "SalesTask Updation"  
        ];
    
        DB::table('adminsalestask')->insert($tasktoinsert);

        
    }else{


        DB::table('adminsalestask')->where('ext_taskid',$taskid)->update(array('type'=>"SalesTask Updation"));

    }



    $url = 'https://edoxi.cyradrive.com/task-manager/admintaskstatusupdate'; // Replace with the URL you want to fetch data from


    $dataparam = array(

        'taskid' => $taskid,
        'tasknote' => $tasknote,
        'taskstatus' => $taskstatus,
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataparam)); // Set POST data
    $response = curl_exec($ch);

    curl_close($ch);


    if($taskstatus == 'Completed'){



        $mailresult=Mail::send('employees.ajax.salestaskcompleted',$dataparam,function($messages) use ($taskid,$taskstatus){  
            $messages->to("neerajnextclick@gmail.com");
            $messages->subject('Sale Task'.$taskid.'-'.$taskstatus);

            });

        
    }


    return redirect()->route('tasks.salestasks');

    }


    public function storemarketingtaskprj(Request $request){

        $tempid=$request->template_name;
    
        $clientid=$request->client_name;
    
        $marketingtaskid=$request->task_id;
    
    
    
        $startDate = Carbon::parse($request->project_startdate)->format('Y-m-d');
    
        if($request->project_deadline != null){
    
            $deadline =  Carbon::parse($request->project_deadline)->format('Y-m-d');
    
        }else{
    
    
            $deadline=null;
    
    
        }
    
    
    
        $template = ProjectTemplate::with('projectMembers','projectDepartments')->findOrFail($tempid);
    
        $project = new Project();
        $project->project_name = $request->project_name;
        $project->project_short_code = 'PRJ'.Project::max('id')+1;
        $project->start_date = $startDate;
    
        $project->deadline = $deadline;
    
        $project->client_id = $request->client_name;
    
        $project->category_id = $template->category_id;
    
        $project->save();
    
    
    
        $projectTemplateMembers = $template->projectMembers ? $template->projectMembers->pluck('id')->toArray() : null;
        $projectTemplateDepartments = $template->projectDepartments ? $template->projectDepartments->pluck('id')->toArray() : null;
    
        $TeamToInsert = [];
    
        $MemberToInsert = [];
    
        foreach ($projectTemplateDepartments as $teamId) {
    
            $TeamToInsert[] = [
                'project_id' => $project->id,
                'team_id' => $teamId  
            ];
    
            
        }
    
        DB::table('project_departments')->insert($TeamToInsert);
    
        foreach ($projectTemplateMembers as $memberId) {
    
            $MemberToInsert[] = [
                'project_id' => $project->id,
                'user_id' => $memberId 
            ];
    
            
        }
    
        DB::table('project_members')->insert($MemberToInsert);
    
    
    
        // dd($template);
    
        foreach ($template->tasks as $task) {
    
            $projectTask = new Task();
            $projectTask->project_id = $project->id;
            $projectTask->heading = $task->heading;
            
            $projectTask->task_category_id = $task->project_template_task_category_id;
            $projectTask->description = trim_editor($task->description);
            $projectTask->start_date = $startDate;
            $projectTask->due_date = $deadline;
            $projectTask->is_private = 0;
            $projectTask->template_task_id = $task->id;
           
            $projectTask->save();
    
            foreach ($task->usersMany as $value) {
                TaskUser::create(
                    [
                        'user_id' => $value->id,
                        'task_id' => $projectTask->id
                    ]
                );
    
                
            }
    
            foreach ($task->subtasks as $value) {
                $projectTask->subtasks()->create(['title' => $value->title]);
            }
        }
    
        DB::statement('CALL sp_update_project_dependent(?)', [$project->id]);
    
    
    
        DB::table('marketingsalestask')->where('id',$marketingtaskid)->update(array('project_id'=>$project->id));
        

        DB::commit();
    
    
        return redirect()->route('projects.index');
    
    
    
        }


    public function storesalestaskprj(Request $request){

    $tempid=$request->template_name;

    $clientid=$request->client_name;

    $saletaskid=$request->salestask_id;



    $taskcount = DB::table('adminsalestask')->where('ext_taskid', $saletaskid)->where('type','=','Project Creation')->count();


    if($taskcount==0){



    $startDate = Carbon::parse($request->project_startdate)->format('Y-m-d');

    if($request->project_deadline != null){

        $deadline =  Carbon::parse($request->project_deadline)->format('Y-m-d');

    }else{


        $deadline=null;


    }



    $template = ProjectTemplate::with('projectMembers','projectDepartments')->findOrFail($tempid);

    $project = new Project();
    $project->project_name = $request->project_name;
    $project->project_short_code = 'PRJ'.Project::max('id')+1;
    $project->start_date = $startDate;

    $project->deadline = $deadline;

    $project->client_id = $request->client_name;

    $project->category_id = $template->category_id;

    $project->save();



    $projectTemplateMembers = $template->projectMembers ? $template->projectMembers->pluck('id')->toArray() : null;
    $projectTemplateDepartments = $template->projectDepartments ? $template->projectDepartments->pluck('id')->toArray() : null;

    $TeamToInsert = [];

    $MemberToInsert = [];

    foreach ($projectTemplateDepartments as $teamId) {

        $TeamToInsert[] = [
            'project_id' => $project->id,
            'team_id' => $teamId  
        ];

        
    }

    DB::table('project_departments')->insert($TeamToInsert);

    foreach ($projectTemplateMembers as $memberId) {

        $MemberToInsert[] = [
            'project_id' => $project->id,
            'user_id' => $memberId 
        ];

        
    }

    DB::table('project_members')->insert($MemberToInsert);



    // dd($template);

    foreach ($template->tasks as $task) {

        $projectTask = new Task();
        $projectTask->project_id = $project->id;
        $projectTask->heading = $task->heading;
        
        $projectTask->task_category_id = $task->project_template_task_category_id;
        $projectTask->description = trim_editor($task->description);
        $projectTask->start_date = $startDate;
        $projectTask->due_date = $deadline;
        $projectTask->is_private = 0;
        $projectTask->template_task_id = $task->id;
       
        $projectTask->save();

        foreach ($task->usersMany as $value) {
            TaskUser::create(
                [
                    'user_id' => $value->id,
                    'task_id' => $projectTask->id
                ]
            );

            
        }

        foreach ($task->subtasks as $value) {
            $projectTask->subtasks()->create(['title' => $value->title]);
        }
    }

    DB::statement('CALL sp_update_project_dependent(?)', [$project->id]);


    $tasktoinsert[] = [

        'ext_taskid' => $saletaskid,
        'project_id' => $project->id,
        'type' => "Project Creation"  
    ];

    DB::table('adminsalestask')->insert($tasktoinsert);
    
    DB::commit();

    $url = 'https://edoxi.cyradrive.com/task-manager/admintaskstatusupdate'; // Replace with the URL you want to fetch data from

    $tasknote = "";

    $taskstatus = "Working";

    $dataparam = array(

        'taskid' => $saletaskid,
        'tasknote' => $tasknote,
        'taskstatus' => $taskstatus,
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataparam)); // Set POST data
    $response = curl_exec($ch);
    curl_close($ch);


    return redirect()->route('projects.index');

    }else{

    $exttask = DB::table('adminsalestask')->where('ext_taskid', $saletaskid)->first();

    if($exttask->project_id==0){

        return redirect()->route('tasks.salestasks');

    }else{

        return redirect()->route('projects.show', [$exttask->project_id]);

    }
    
    }

    }


    public function storeindvidualtask(Request $request){


        $tasksubject = $request->task_subject;

        $taskdesc = $request->task_description;

        $taskassignee = $request->assignee_name;

        $filename = NULL;


        if($request->has('file')) {

            $filename = Files::uploadLocalOrS3($request->file,'indvidualtask-files/');

        

        }

        $indtaskarray[] = [
            'userid' => $taskassignee,
            'subject' => $tasksubject,
            'description' => $taskdesc,
            'filename' => $filename
        ];


        DB::table('marketingsalestask')->insert($indtaskarray);
        

        return redirect()->route('dashboard');

    }

    public function assignsalestask(Request $request){



        $taskid = $request->assigneetaskid;

        $taskassignee = $request->assignee_name;


        $taskcount = DB::table('adminsalestaskassign')->where('taskid', $taskid)->count();

        if($taskcount == 0){


            $assigntaskarray[] = [

                'userid' => $taskassignee,
                'taskid' => $taskid,
            ];
    
    
            DB::table('adminsalestaskassign')->insert($assigntaskarray);

        }else{


            DB::table('adminsalestaskassign')->where('taskid',$taskid)->update(array('userid'=>$taskassignee));


        }

        $taskcount1 = DB::table('adminsalestask')->where('ext_taskid', $taskid)->count();


        $tasktoinsert[] = [

            'ext_taskid' => $taskid,
            'type' => "SalesTask Assigned"  
        ];
    
        DB::table('adminsalestask')->insert($tasktoinsert);

    


        $url = 'https://edoxi.cyradrive.com/task-manager/admintaskstatusupdate'; // Replace with the URL you want to fetch data from


        $tasknote = "";

        $taskstatus = "Working";

        $dataparam = array(

            'taskid' => $taskid,
            'tasknote' => $tasknote,
            'taskstatus' => $taskstatus,
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataparam)); // Set POST data
        $response = curl_exec($ch);

        curl_close($ch);

        
        

        return redirect()->route('dashboard');

    }


    public function assignmarketingtask(Request $request){



        $taskid = $request->assigneetaskid;

        $taskassignee = $request->assignee_name;



        $assigntaskarray[] = [

            'userid' => $taskassignee,
            'taskid' => $taskid,
        ];


        DB::table('adminmarketingtaskassign')->insert($assigntaskarray);



        return redirect()->route('dashboard');

    }



    
}
