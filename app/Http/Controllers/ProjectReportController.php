<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Helper\Files;
use App\Helper\Reply;
use App\Models\Module;
use App\Models\Pinned;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Currency;
use App\Models\TaskFile;
use App\Models\TaskUser;
use App\Models\Discussion;
use App\Models\Permission;
use App\Models\ProjectFile;
use App\Models\ProjectNote;
use App\Scopes\ActiveScope;
use App\Models\BankAccount;
use App\Traits\ImportExcel;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use App\Imports\ProjectImport;
use App\Jobs\ImportProjectJob;
use App\Models\MessageSetting;
use App\Models\PermissionRole;
use App\Models\PermissionType;
use App\Models\ProjectSetting;
use App\Models\ProjectTimeLog;
use App\Models\UserPermission;
use App\Models\DiscussionReply;
use App\Models\ProjectActivity;
use App\Models\ProjectCategory;
use App\Models\ProjectTemplate;
use App\Models\TaskboardColumn;
use App\Traits\ProjectProgress;
use App\Models\ProjectMilestone;
use App\DataTables\TasksDataTable;
use App\Models\DiscussionCategory;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectTimeLogBreak;
use Illuminate\Support\Facades\Bus;
use App\Models\ProjectStatusSetting;
use Maatwebsite\Excel\Facades\Excel;
use App\DataTables\ExpensesDataTable;
use App\DataTables\InvoicesDataTable;
use App\DataTables\PaymentsDataTable;
use App\DataTables\ProjectsDataTable;
use App\DataTables\TimeLogsDataTable;
use App\DataTables\DiscussionDataTable;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\HeadingRowImport;
use App\DataTables\ArchiveTasksDataTable;
use App\DataTables\ProjectNotesDataTable;
use App\DataTables\ProjectReportDataTable;
use App\Http\Requests\Project\StoreProject;
use App\DataTables\ArchiveProjectsDataTable;
use App\Http\Requests\Project\UpdateProject;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\Http\Requests\Admin\Employee\ImportRequest;
use App\Http\Requests\Admin\Employee\ImportProcessRequest;
use App\Models\SubTask;
use App\Models\SubTaskFile;
use Illuminate\Database\Eloquent\Builder;


class ProjectReportController extends AccountBaseController
{
    

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projectReport';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('projects', $this->user->modules));

            return $next($request);
            
        });
    }

    

    public function index(ProjectReportDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_projects');
        abort_403(!in_array($viewPermission, ['all', 'added', 'owned', 'both']));

        if (!request()->ajax()) {

            if (in_array('client', user_roles())) {
                $this->clients = User::client();
            }
            else {
                $this->clients = User::allClients();
                $this->allEmployees = User::allEmployees(null, true, ($viewPermission == 'all' ? 'all' : null));
            }

            $this->categories = ProjectCategory::all();
            $this->departments = Team::all();
            $this->projectStatus = ProjectStatusSetting::where('status', 'active')->get();
        }

        return $dataTable->render('reports.projects.index', $this->data);
    }
    
    public function projectChartData(Request $request)
    {
        
        $viewPermission = user()->permission('view_projects');

        $data['labels'] = ['In progress','Not started','On hold','Canceled','Finished'];
        $data['colors'] = ['#00b5ff','#f2f2f4','#f5c308','#cc0000','#679c0d'];
        $data['values'] = [];

        $startDate = $endDate = null;

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->company->date_format, $request->startDate)->toDateString();
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->company->date_format, $request->endDate)->toDateString();
        }

        
        $data['count']=0;
        foreach ($data['labels'] as $label) {

            // $model = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            //     ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            //     ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            //     ->leftJoin('task_labels', 'task_labels.task_id', '=', 'tasks.id')
            //     ->where('tasks.board_column_id', $label->id);


            $model = Project::with('members', 'members.user', 'client', 'client.clientDetails', 'currency', 'client.session')
            ->leftJoin('project_members', 'project_members.project_id', 'projects.id')
            ->leftJoin('project_category', 'project_category.id', 'projects.category_id') 
            ->leftJoin('users', 'project_members.user_id', 'users.id')
            ->leftJoin('users as client', 'projects.client_id', 'users.id')
            ->where('projects.status', $label);

        if ($request->pinned == 'pinned') {
            $model->join('pinned', 'pinned.project_id', 'projects.id');
            $model->where('pinned.user_id', user()->id);
        }

        if (!is_null($request->status) && $request->status != 'all') {
            if ($request->status == 'not finished') {
                $model->where('projects.completion_percent', '!=', 100);
            }
            else if ($request->status == 'overdue') {
                $model->where('projects.completion_percent', '!=', 100);
                $model->where('projects.status', '<>', 'canceled');

                if ($request->deadLineStartDate == '' && $request->deadLineEndDate == '') {
                    $model->whereDate('projects.deadline', '<', now(company()->timezone)->toDateString());
                }
            }
            else {
                $model->where('projects.status', $request->status);
            }
        }

        if ($request->progress) {
            $model->where(function ($q) use ($request) {
                foreach ($request->progress as $progress) {
                    $completionPercent = explode('-', $progress);
                    $q->orWhereBetween('projects.completion_percent', [$completionPercent[0], $completionPercent[1]]);
                }
            });
        }

        if ($request->deadLineStartDate != '' && $request->deadLineEndDate != '') {
            
            $startDate = Carbon::createFromFormat($this->company->date_format, $request->deadLineStartDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->company->date_format, $request->deadLineEndDate)->toDateString();
            $model->whereRaw('Date(projects.deadline) >= ?', [$startDate])->whereRaw('Date(projects.deadline) <= ?', [$endDate]);
        }

        if ($request->clientID != '' && $request->clientID != null && $request->clientID != 'all') {


            $model->where('projects.client_id', $request->clientID);

        }

        if (!is_null($request->teamID) && $request->teamID != 'all') {
            $model->where('team_id', $request->team_id);
        }

        if (!is_null($request->categoryID) && $request->categoryID != 'all') {

            $model->where('category_id', $request->categoryID);

        }

        if (!is_null($request->employee_id) && $request->employee_id != 'all') {
            $model->where('project_members.user_id', $request->employee_id);
        }

        if ($viewPermission == 'added') {
            $model->where(function ($query) {

                return $query->where('projects.added_by', user()->id)
                    ->orWhere('projects.public', 1);
            });
        }

        if ($viewPermission == 'owned' && in_array('employee', user_roles())) {
            $model->where(function ($query) {
                return $query->where('project_members.user_id', user()->id)
                    ->orWhere('projects.public', 1);
            });
        }

        if ($viewPermission == 'both' && in_array('employee', user_roles())) {
            $model->where(function ($query) {
                return $query->where('projects.added_by', user()->id)
                    ->orWhere('project_members.user_id', user()->id)
                    ->orWhere('projects.public', 1);
            });
        }



        if ($request->startDate && $request->endDate) {
            $startDate = Carbon::createFromFormat($this->company->date_format, $request->startDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->company->date_format, $request->endDate)->toDateString();
            $model->whereBetween(DB::raw('DATE(projects.`created_at`)'), [$startDate, $endDate]);
        }

        

            $data['values'][] = $model->distinct('projects.id')->count();

            
        } 

        

        $this->chartData = $data;
        $html = view('reports.projects.chart', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
    }


}
