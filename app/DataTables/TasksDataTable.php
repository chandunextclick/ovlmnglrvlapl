<?php

namespace App\DataTables;

use App\Models\BaseModel;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\CustomField;
use App\Models\TaskboardColumn;
use App\Models\CustomFieldGroup;
use Carbon\CarbonInterval;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class TasksDataTable extends BaseDataTable
{

    private $editTaskPermission;
    private $deleteTaskPermission;
    private $viewTaskPermission;
    private $changeStatusPermission;
    private $viewUnassignedTasksPermission;

    public function __construct()
    {
        parent::__construct();

        $this->editTaskPermission = user()->permission('edit_tasks');
        $this->deleteTaskPermission = user()->permission('delete_tasks');
        $this->viewTaskPermission = user()->permission('view_tasks');
        $this->changeStatusPermission = user()->permission('change_status');
        $this->viewUnassignedTasksPermission = user()->permission('view_unassigned_tasks');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $taskBoardColumns = TaskboardColumn::orderBy('priority')->get();

        $datatables = datatables()->eloquent($query);
        $datatables->addColumn('check', function ($row) {
            return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
        });
        $datatables->addColumn('action', function ($row) {
            $taskUsers = $row->users->pluck('id')->toArray();

            $headIds = DB::table('project_departments')
                        ->leftJoin('teams', 'project_departments.team_id', 'teams.id') // connecting department with teams
                        ->where('project_id',$row->project_id)
                        ->pluck('head_id')
                        ->toArray();

            $action = '<div class="task_view">

                    <div class="dropdown">
                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                            id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-options-vertical icons"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

            $action .= '<a href="' . route('tasks.show', [$row->id]) . '" class="dropdown-item openRightModal"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

            if ($this->editTaskPermission == 'all'
                || ($this->editTaskPermission == 'owned' && in_array(user()->id, $taskUsers))
                || ($this->editTaskPermission == 'added' &&  (user()->id == $row->added_by || in_array(user()->id, $headIds)))
                || ($row->project_admin == user()->id)
                || ($this->editTaskPermission == 'both' && (in_array(user()->id, $taskUsers) || (user()->id == $row->added_by || in_array(user()->id, $headIds))))
                || ($this->editTaskPermission == 'both' && (in_array(user()->id, $taskUsers) || $row->added_by == user()->id || in_array('client', user_roles())))
                || ($this->editTaskPermission == 'owned' && in_array('client', user_roles()))
            ) {
                $action .= '<a class="dropdown-item openRightModal" href="' . route('tasks.edit', [$row->id]) . '">
                                <i class="fa fa-edit mr-2"></i>
                                ' . trans('app.edit') . '
                            </a>';
            }

            if ($this->deleteTaskPermission == 'all'
                || ($this->deleteTaskPermission == 'owned' && in_array(user()->id, $taskUsers))
                || ($this->deleteTaskPermission == 'added' && $row->added_by == user()->id)
                || ($row->project_admin == user()->id)
                || ($this->deleteTaskPermission == 'both' && (in_array(user()->id, $taskUsers) || $row->added_by == user()->id))
                || ($this->deleteTaskPermission == 'both' && (in_array(user()->id, $taskUsers) || $row->added_by == user()->id || in_array('client', user_roles())))
                || ($this->deleteTaskPermission == 'owned' && in_array('client', user_roles()))
            ) {
                $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-user-id="' . $row->id . '">
                                <i class="fa fa-trash mr-2"></i>
                                ' . trans('app.delete') . '
                            </a>';
            }

            if ($this->editTaskPermission == 'all'
                || ($this->editTaskPermission == 'owned' && in_array(user()->id, $taskUsers))
                || ($this->editTaskPermission == 'added' && $row->added_by == user()->id)
                || ($this->editTaskPermission == 'both' && (in_array(user()->id, $taskUsers) || $row->added_by == user()->id))
                || ($row->project_admin == user()->id)
            ) {
                $action .= '<a class="dropdown-item openRightModal" href="' . route('tasks.create') . '?duplicate_task=' . $row->id . '">
                                <i class="fa fa-clone"></i>
                                ' . trans('app.duplicate') . '
                            </a>';
            }

            if($row->task_time_id!=0){

            
                if (in_array('admin', user_roles())) {

                    $action .= '<a class="dropdown-item" href="javascript:;" data-time-id='.$row->task_time_id.' id="pause-timer-btn">
                                    <i class="fa fa-pause"></i>
                                    ' . trans('app.pause') . '
                                </a>';

                }
            
            }

            $action .= '</div>
                    </div>
                </div>';

            return $action;
        });


        $datatables->editColumn('due_date', function ($row) {
            if (is_null($row->due_date)) {
                return '--';
            }

            if ($row->due_date->endOfDay()->isPast()) {
                return '<span class="text-danger">' . $row->due_date->translatedFormat($this->company->date_format) . '</span>';
            }
            elseif ($row->due_date->isToday()) {
                return '<span class="text-success">' . __('app.today') . '</span>';
            }

            return '<span >' . $row->due_date->translatedFormat($this->company->date_format) . '</span>';
        });

        $datatables->editColumn('created_at', function ($row) {
            
            return  $row->created_at;
        });

        $datatables->editColumn('updated_at', function ($row) {
            
            return  $row->updated_at;
        });

        $datatables->editColumn('completed_on', function ($row) {
            
            if (is_null($row->completed_on)) {
                return '--';
            }

            return  $row->completed_on;
        });
        

        $datatables->editColumn('users', function ($row) {
            if (count($row->users) == 0) {
                return '--';
            }

            $members = '<div class="position-relative">';

            foreach ($row->users as $key => $member) {
                if ($key < 4) {
                    $img = '<img data-toggle="tooltip" data-original-title="' . mb_ucwords($member->name) . '" src="' . $member->image_url . '">';
                    $position = $key > 0 ? 'position-absolute' : '';

                    $members .= '<div class="taskEmployeeImg rounded-circle ' . $position . '" style="left:  ' . ($key * 13) . 'px"><a href="' . route('employees.show', $member->id) . '">' . $img . '</a></div> ';
                }
            }

            if (count($row->users) > 4 && $key) {
                $members .= '<div class="taskEmployeeImg more-user-count text-center rounded-circle border bg-amt-grey position-absolute" style="left:  ' . (($key - 1) * 13) . 'px"><a href="' . route('tasks.show', [$row->id]) . '" class="text-dark f-10">+' . (count($row->users) - 4) . '</a></div> ';
            }

            $members .= '</div>';

            return $members;
        });

        $datatables->editColumn('short_code', function ($row) {

            if (is_null($row->task_short_code)) {
                return ' -- ';
            }

            return '<a href="' . route('tasks.show', [$row->id]) . '" class="text-darkest-grey">' . $row->task_short_code . '</a>';
        });

        $datatables->addColumn('name', function ($row) {
            $members = [];

            foreach ($row->users as $member) {
                $members[] = $member->name;
            }

            return implode(',', $members);
        });
        $datatables->addColumn('timer', function ($row) {
            if ($row->boardColumn->slug == 'completed' || is_null($row->is_task_user)) {
                return null;
            }

            if (is_null($row->userActiveTimer)) {
                return '<a href="javascript:;" class="text-primary btn border f-15 start-timer" data-task-id="' . $row->id . '" data-project-id="' . $row->project_id . '" data-toggle="tooltip" data-original-title="' . __('modules.timeLogs.startTimer') . '"><i class="bi bi-play-circle-fill"></i></a>';
            }

            if (is_null($row->userActiveTimer->activeBreak)) {
                $timerButtons = '<div class="btn-group d-none" role="group">';
                // $timerButtons .= '<a href="javascript:;" class="text-secondary btn border f-15 pause-timer" data-time-id="' . $row->userActiveTimer->id . '" data-toggle="tooltip" data-original-title="' . __('modules.timeLogs.pauseTimer') . '"><i class="bi bi-pause-circle-fill"></i></a>';
                $timerButtons .= '<a href="javascript:;" class="text-secondary btn border f-15 stop-timer" data-time-id="' . $row->userActiveTimer->id . '" data-toggle="tooltip" data-original-title="' . __('modules.timeLogs.stopTimer') . '"><i class="bi bi-stop-circle-fill"></i></a>';
                $timerButtons .= '</div>';
                return $timerButtons;
            }

            $timerButtons = '<div class="btn-group" role="group">';
            // $timerButtons .= '<a href="javascript:;" class="text-secondary btn border f-15 resume-timer" data-time-id="' . $row->userActiveTimer->activeBreak->id . '" data-toggle="tooltip" data-original-title="' . __('modules.timeLogs.resumeTimer') . '"><i class="bi bi-play-circle-fill"></i></a>';
            $timerButtons .= '<a href="javascript:;" class="text-secondary btn border f-15 stop-timer" data-time-id="' . $row->userActiveTimer->id . '" data-toggle="tooltip" data-original-title="' . __('modules.timeLogs.stopTimer') . '"><i class="bi bi-stop-circle-fill"></i></a>';
            $timerButtons .= '</div>';
            return $timerButtons;
        });

        $datatables->editColumn('clientName', function ($row) {
            return ($row->clientName) ? mb_ucwords($row->clientName) : '-';
        });

        $datatables->addColumn('task', function ($row) {
            return ucfirst($row->heading);
        });
        $datatables->addColumn('timeLogged', function ($row) {

            $timeLog = '--';

            if ($row->timeLogged) {
                $totalMinutes = $row->timeLogged->sum('total_minutes');

                $breakMinutes = $row->breakMinutes();

                $timeLog = CarbonInterval::formatHuman($totalMinutes - $breakMinutes);
            }

            return $timeLog;
        });
        $datatables->editColumn('heading', function ($row) {
            $subTask = $labels = $private = $pin = $timer = '';

            if ($row->is_private) {
                $private = '<span class="badge badge-secondary mr-1"><i class="fa fa-lock"></i> ' . __('app.private') . '</span>';
            }

            if (($row->pinned_task)) {
                $pin = '<span class="badge badge-secondary mr-1"><i class="fa fa-thumbtack"></i> ' . __('app.pinned') . '</span>';
            }

            if ($row->active_timer_all_count > 1) {
                $timer .= '<span class="badge badge-primary mr-1" ><i class="fa fa-clock"></i> ' . $row->active_timer_all_count . ' ' . __('modules.projects.activeTimers') . '</span>';
            }

            if ($row->activeTimer && $row->active_timer_all_count == 1) {
                $timer .= '<span class="badge badge-primary mr-1 d-none" data-toggle="tooltip" data-original-title="' . __('modules.projects.activeTimers') . '" ><i class="fa fa-clock"></i> ' . $row->activeTimer->timer . '</span>';
            }

            if ($row->subtasks_count > 0) {
                $subTask .= '<a href="' . route('tasks.show', [$row->id]) . '?view=sub_task" class="openRightModal"><span class="border rounded p-1 f-11 mr-1 text-dark-grey" data-toggle="tooltip" data-original-title="' . __('modules.tasks.subTask') . '"><i class="bi bi-diagram-2"></i> ' . $row->completed_subtasks_count .'/' . $row->subtasks_count . '</span></a>';
            }

            foreach ($row->labels as $label) {
                $labels .= '<span class="badge badge-secondary mr-1" style="background-color: ' . $label->label_color . '">' . $label->label_name . '</span>';
            }

            return BaseModel::clickAbleLink(route('tasks.show', [$row->id]), $row->heading, $subTask . ' ' . $private . ' ' . $pin . ' ' . $timer . ' ' . $labels);

        });
        $datatables->editColumn('board_column', function ($row) use ($taskBoardColumns) {
            $taskUsers = $row->users->pluck('id')->toArray();

            if (
                $this->changeStatusPermission == 'all'
                || ($this->changeStatusPermission == 'added' && $row->added_by == user()->id)
                || ($this->changeStatusPermission == 'owned' && in_array(user()->id, $taskUsers))
                || ($this->changeStatusPermission == 'both' && (in_array(user()->id, $taskUsers) || $row->added_by == user()->id))
                || ($row->project_admin == user()->id)
            ) {

                $status = '<select class="form-control select-picker change-status" data-task-id="' . $row->id . '" data-project-id="' . $row->project_id . '">';

                
                
                foreach ($taskBoardColumns as $item) {

                    if(($item->column_name == 'Question Requested')||($item->column_name == 'Answered Received')){

                        if (str_contains(strtolower($row->heading), 'upgradation')) {


                            $status .= '<option ';

                            if ($item->id == $row->board_column_id) {
                                $status .= 'selected';
                            }

                            if (($item->column_name == 'Completed')&&($row->userActiveTimer)) {
                                $status .= 'stop-timer data-time-id="'. $row->userActiveTimer->id .'"';
                            }


                            $status .= '  data-content="<i class=\'fa fa-circle mr-2\' style=\'color: ' . $item->label_color . '\'></i> ' . $item->column_name . '" value="' . $item->slug . '">' . $item->column_name . '</option>';
                        


                        }


                    }else{


                    $status .= '<option ';

                    if ($item->id == $row->board_column_id) {
                        $status .= 'selected';
                    }

                    if (($item->column_name == 'Completed')&&($row->userActiveTimer)) {
                        $status .= 'stop-timer data-time-id="'. $row->userActiveTimer->id .'"';
                    }


                    $status .= '  data-content="<i class=\'fa fa-circle mr-2\' style=\'color: ' . $item->label_color . '\'></i> ' . $item->column_name . '" value="' . $item->slug . '">' . $item->column_name . '</option>';
                


                    }
  
                
                }

                $status .= '</select>';

                return $status;

            }
            else {
                return '<i class="fa fa-circle mr-1 text-yellow"
                    style="color: ' . $row->boardColumn->label_color . '"></i>' . $row->boardColumn->column_name;
            }
        });
        $datatables->addColumn('status', function ($row) {
            return ucfirst($row->boardColumn->column_name);
        });
        $datatables->editColumn('project_name', function ($row) {

            if (is_null($row->project_id)) {
                return ' -- ';
            }

            return '<a href="' . route('projects.show', $row->project_id) . '" class="text-darkest-grey">' . ucfirst($row->project_name) . '</a>';
        });

        $datatables->addColumn('category', function ($row) {
            return $row->category_name;
        });


        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });
        $datatables->setRowClass(function ($row) {
            return $row->pinned_task ? 'alert-primary' : '';
        });
        $datatables->rawColumns(['short_code', 'board_column', 'action', 'project_name', 'clientName', 'due_date', 'users', 'heading', 'check', 'timeLogged', 'timer']);
        $datatables->removeColumn('project_id');
        $datatables->removeColumn('image');
        $datatables->removeColumn('created_image');
        $datatables->removeColumn('label_color');

        // CustomField For export
        CustomField::customFieldData($datatables, Task::CUSTOM_FIELD_MODEL);

        return $datatables;
    }

    /**
     * @param Task $model
     * @return mixed
     */
    public function query(Task $model)
    {
        $request = $this->request();
        $startDate = null;
        $endDate = null;

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->company->date_format, $request->startDate)->toDateString();
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->company->date_format, $request->endDate)->toDateString();
        }

        $projectId = $request->projectId;
        $taskBoardColumn = TaskboardColumn::completeColumn();

        $model = $model->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->leftJoin('project_category', 'project_category.id', '=', 'projects.category_id')
            ->leftJoin('users as client', 'client.id', '=', 'projects.client_id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id');

        if (($this->viewUnassignedTasksPermission == 'all'
                && !in_array('client', user_roles())
                && ($request->assignedTo == 'unassigned' || $request->assignedTo == 'all'))
            || ($request->has('project_admin') && $request->project_admin == 1)
        ) {
            $model->leftJoin('task_users', 'task_users.task_id', '=', 'tasks.id')
                ->leftJoin('users as member', 'task_users.user_id', '=', 'member.id');
        }
        else {
            $model->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                ->join('users as member', 'task_users.user_id', '=', 'member.id');
        }

        $model->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->leftJoin('task_labels', 'task_labels.task_id', '=', 'tasks.id')
            ->leftJoin('project_departments', 'project_departments.project_id', 'tasks.project_id') //connecting taks with project_department
            ->leftJoin('teams', 'project_departments.team_id', 'teams.id') // connecting department with teams
            ->leftJoin('tasks as dependent', 'dependent.id', '=', 'tasks.dependent_task_id')
            ->leftJoin('taskboard_columns as dependentboard', 'dependentboard.id', '=', 'dependent.board_column_id')
            ->selectRaw('tasks.id, tasks.task_short_code, tasks.added_by, projects.project_name, projects.project_admin, tasks.heading, client.name as clientName, creator_user.name as created_by, creator_user.image as created_image, tasks.board_column_id,
             tasks.due_date,tasks.created_at,tasks.updated_at,tasks.completed_on,taskboard_columns.column_name as board_column, taskboard_columns.label_color,dependentboard.slug,project_category.category_name,
              tasks.project_id, tasks.is_private,tasks.task_time_id,tasks.task_priority,( select count("id") from pinned where pinned.task_id = tasks.id and pinned.user_id = ' . user()->id . ') as pinned_task')
            ->addSelect('tasks.company_id') // Company_id is fetched so the we have fetch company relation with it)
            ->with('users', 'activeTimerAll', 'boardColumn', 'activeTimer', 'timeLogged', 'timeLogged.breaks', 'userActiveTimer', 'userActiveTimer.activeBreak', 'labels', 'taskUsers')
            ->withCount('activeTimerAll', 'completedSubtasks', 'subtasks')
            ->groupBy('tasks.id');


        if ($request->pinned == 'pinned') {
            $model->join('pinned', 'pinned.task_id', 'tasks.id');
            $model->where('pinned.user_id', user()->id);
        }

        if ($request->pinned == 'priority') {
        
            $model->where('projects.priority',1);
        }

        if ($request->pinned == 'paused') {
        
            $model->where('tasks.task_time_id', '!=', 0);
        }


        if (!in_array('admin', user_roles())) {
            if ($request->pinned == 'private') {
                $model->where(
                    function ($q2) {
                        $q2->where('tasks.is_private', 1);
                        $q2->where(
                            function ($q4) {
                                $q4->where('task_users.user_id', user()->id);
                                $q4->orWhere('tasks.added_by', user()->id);
                            }
                        );
                    }
                );

            }
            else {
                $model->where(
                    function ($q) {
                        $q->where('tasks.is_private', 0);
                        $q->orWhere(
                            function ($q2) {
                                $q2->where('tasks.is_private', 1);
                                $q2->where(
                                    function ($q5) {
                                        $q5->where('task_users.user_id', user()->id);
                                        $q5->orWhere('tasks.added_by', user()->id);
                                    }
                                );
                            }
                        );
                    }
                );
            }
        }

        if ($request->assignedTo == 'unassigned' && $this->viewUnassignedTasksPermission == 'all' && !in_array('client', user_roles())) {
            $model->whereDoesntHave('users');
        }

        if ($startDate !== null && $endDate !== null) {
            $model->where(function ($q) use ($startDate, $endDate) {
                if (request()->date_filter_on == 'due_date') {
                    $q->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);

                }
                elseif (request()->date_filter_on == 'start_date') {
                    $q->whereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);

                }
                elseif (request()->date_filter_on == 'completed_on') {
                    $q->whereBetween(DB::raw('DATE(tasks.`completed_on`)'), [$startDate, $endDate]);
                }

            });
        }

        if ($request->overdue == 'yes' && $request->status != 'all') {
            $model->where(DB::raw('DATE(tasks.`due_date`)'), '<', now(company()->timezone)->toDateString());
        }

        if ($projectId != 0 && $projectId != null && $projectId != 'all') {
            $model->where('tasks.project_id', '=', $projectId);
        }

        if ($request->clientID != '' && $request->clientID != null && $request->clientID != 'all') {
            $model->where('projects.client_id', '=', $request->clientID);
        }

        if ($request->assignedTo != '' && $request->assignedTo != null && $request->assignedTo != 'all' && $request->assignedTo != 'unassigned') {
            $model->where('task_users.user_id', '=', $request->assignedTo);
        }

        if (($request->has('project_admin') && $request->project_admin != 1) || !$request->has('project_admin')) {
            
            if ($this->viewTaskPermission == 'owned') {

                $model->where(function ($q) use ($request) {  
                    $q->where('task_users.user_id', '=', user()->id);
                    $q->where(function ($query) {
                        
                        $query->where('dependentboard.slug', '=', 'completed');
                        $query->orWhereNull('tasks.dependent_task_id');
                    });
                    
                    if ($this->viewUnassignedTasksPermission == 'all' && !in_array('client', user_roles()) && $request->assignedTo == 'all') {
                        $q->orWhereDoesntHave('users');
                    }

                    if (in_array('client', user_roles())) {
                        $q->orWhere('projects.client_id', '=', user()->id);
                    }
                });

                // if ($projectId != 0 && $projectId != null && $projectId != 'all' && !in_array('client', user_roles())) {
                //     $model->where('projects.project_admin', '<>', user()->id);
                // }

            }

            if ($this->viewTaskPermission == 'added') {


                $model->where(function ($q) use ($request) {
                    $q->Where('teams.head_id', user()->id);
                    $q->orWhere('tasks.added_by', '=', user()->id);
                });
              
            }

            if ($this->viewTaskPermission == 'both') {
            
                $model->where(function ($q) use ($request) {
                    $q->where('task_users.user_id', '=', user()->id);
                    $q->orWhere('tasks.added_by', '=', user()->id);
                    $q->orWhere('teams.head_id', user()->id);
                    

                    if (in_array('client', user_roles())) {
                        $q->orWhere('projects.client_id', '=', user()->id);
                    }

                    if ($this->viewUnassignedTasksPermission == 'all' && !in_array('client', user_roles()) && ($request->assignedTo == 'unassigned' || $request->assignedTo == 'all')) {
                        $q->orWhereDoesntHave('users');
                    }

                });

            }
        }

        if ($request->assignedBY != '' && $request->assignedBY != null && $request->assignedBY != 'all') {
            $model->where('creator_user.id', '=', $request->assignedBY);
        }

        if ($request->status != '' && $request->status != null && $request->status != 'all') {
            if ($request->status == 'not finished' || $request->status == 'pending_task') {
                $model->where('tasks.board_column_id', '<>', $taskBoardColumn->id);
            }
            else {
                $model->where('tasks.board_column_id', '=', $request->status);
            }
        }

        if ($request->label != '' && $request->label != null && $request->label != 'all') {
            $model->where('task_labels.label_id', '=', $request->label);
        }

        if ($request->category_id != '' && $request->category_id != null && $request->category_id != 'all') {
            $model->where('tasks.task_category_id', '=', $request->category_id);
        }

        if ($request->billable != '' && $request->billable != null && $request->billable != 'all') {
            $model->where('tasks.billable', '=', $request->billable);
        }

        if ($request->milestone_id != '' && $request->milestone_id != null && $request->milestone_id != 'all') {
            $model->where('tasks.milestone_id', $request->milestone_id);
        }

        if ($request->searchText != '') {
            $model->where(function ($query) {
                $query->where('tasks.heading', 'like', '%' . request('searchText') . '%')
                    ->orWhere('member.name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('projects.project_name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('project_category.category_name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('projects.project_short_code', 'like', '%' . request('searchText') . '%')
                    ->orWhere('tasks.task_short_code', 'like', '%' . request('searchText') . '%');
            });
        }

        if ($request->trashedData == 'true') {
            $model->whereNotNull('projects.deleted_at');
        }
        else {
            $model->whereNull('projects.deleted_at');
        }

        if ($request->type == 'public') {
            $model->where('tasks.is_private', 0);
        }

        $model->orderbyRaw('pinned_task desc');

        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return parent::setBuilder('allTasks-table')
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["allTasks-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("#allTasks-table .select-picker").selectpicker();
                    $(".bs-tooltip-top").removeClass("show");
                }',
                'createdRow' => 'function (row, data, dataIndex) {
                    if (data.slug == "completed") {
                        $(row).addClass("bg-success"); // Add the "row-green" class for rows with priority 1

                    }
                    if (data.task_priority == "1") {
                            $(row).addClass("bg-warning"); // Add the "row-yellow" class for rows with priority 1
                    }
                    
                }',
            ])
            ->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> ' . trans('app.exportExcel')]));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {


        $data = [
            'check' => [
                'title' => '<input type="checkbox" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            __('app.id') => ['data' => 'id', 'name' => 'id', 'title' => __('app.id'), 'visible' => showId()],
            __('modules.taskCode') => ['data' => 'short_code', 'name' => 'task_short_code', 'title' => __('modules.taskCode')],
            __('timer') . ' ' => ['data' => 'timer', 'name' => 'timer', 'exportable' => false, 'searchable' => false, 'sortable' => false, 'title' => '', 'class' => 'text-right'],
            __('app.task') => ['data' => 'heading', 'name' => 'heading', 'exportable' => false, 'title' => __('app.task')],
            __('app.menu.tasks') . ' ' => ['data' => 'task', 'name' => 'heading', 'visible' => false, 'title' => __('app.menu.tasks')],
            __('app.project') => ['data' => 'project_name', 'name' => 'projects.project_name', 'title' => __('app.project')],
            __('modules.projects.projectCategory') => ['data' => 'category_name', 'name' => 'category_name', 'title' => __('modules.projects.projectCategory')], // modified by chandu to add project category column on the datatable on 18/01/24 
            __('app.client') => ['data' => 'clientName', 'name' => 'clientName', 'title' => __('app.client')],
            __('modules.tasks.assigned') => ['data' => 'name', 'name' => 'name', 'visible' => false, 'title' => __('modules.tasks.assigned')],
            __('app.dueDate') => ['data' => 'due_date', 'name' => 'due_date', 'title' => __('app.dueDate')],
            __('app.created') => ['data' => 'created_at', 'name' => 'created_at', 'title' => __('app.created')],
            __('app.updated') => ['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('app.updated')],
            __('app.completed') => ['data' => 'completed_on', 'name' => 'completed_on', 'title' => __('app.completed')],
            __('modules.employees.hoursLogged') => ['data' => 'timeLogged', 'name' => 'timeLogged', 'title' => __('modules.employees.hoursLogged')],
            __('modules.tasks.assignTo') => ['data' => 'users', 'name' => 'member.name', 'exportable' => false, 'title' => __('modules.tasks.assignTo')],
            __('app.columnStatus') => ['data' => 'board_column', 'name' => 'board_column', 'exportable' => false, 'searchable' => false, 'title' => __('app.columnStatus')],
            __('app.task') . ' ' . __('app.status') => ['data' => 'status', 'name' => 'board_column_id', 'visible' => false, 'title' => __('app.task')],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];

        return array_merge($data, CustomFieldGroup::customFieldsDataMerge(new Task()));

    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'task-' . now()->format('Y-m-d-H-i-s');
    }

}
