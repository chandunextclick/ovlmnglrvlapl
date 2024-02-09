<?php

namespace App\Console\Commands;

use App\Models\ProjectTemplate;

use App\Models\Project;

use App\Models\Company;

use App\Models\ProjectStatusSetting;

use App\Models\Task;

use App\Models\TaskboardColumn;

use App\Models\TaskUser;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;


class MonthlyReportProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly-report-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

    
        $projecttemplates = DB::select(DB::raw("
                    SELECT DISTINCT(project_template_id) FROM `project_template_trigger`
                "));

        $icount=1;    

        foreach ($projecttemplates  as $temp) {        

            ${'template'.$icount} = ProjectTemplate::with('projectMembers','projectDepartments')->findOrFail($temp->project_template_id);

            $icount++;

        }

        for($i=1;$i<$icount;$i++){

            if($i==1){

                $template=$template1;

            }elseif($i==2){

                $template=$template2;

            }else{

                $template=$template3;
            }

            $clients = DB::select(DB::raw("

                    SELECT DISTINCT(client_id) FROM `project_template_trigger` where project_template_id=".$template->id."

                "));
        

            foreach ($clients  as $client) {        


                $this->store($template,$client->client_id);
               
            }
            

        }


        


    }

    public function store($template1,$clientid){


                // Get the current month abbreviation
                $currentMonth = date('M');

                // Get the current year
                $currentYear = date('Y');

                $currentDate = date('Y-m-d');

                $startDate = date('Y-m-d');

                $deadline = date('Y-m-d', strtotime($currentDate . ' +4 days'));
        
                $project = new Project();
                $project->project_name = $template1->project_name.' '.$currentMonth . '' . $currentYear;
                $project->project_short_code = 'PRJ'.Project::max('id')+1;
                $project->company_id = 4;
                $project->start_date = date('Y-m-d');
                $project->deadline = date('Y-m-d', strtotime($currentDate . ' +4 days'));
                $project->client_id = $clientid;
        
                $project->category_id = $template1->category_id;
        
        
                $project->allow_client_notification = 'disable';

                $project->public = 0;
        
        
                $defaultsStatus = ProjectStatusSetting::where('default_status', 1)->get();
        
                foreach ($defaultsStatus as $default) {
                    $project->status = $default->status_name;
                }
        
    
                $project->save();
        
                $team_ids=$template1->projectDepartments->pluck('id')->toArray();

                $member_ids=$template1->projectMembers->pluck('id')->toArray();
            
        
                if (!empty($team_ids)) {
        
                    $dataToInsert = [];
                    foreach ($team_ids as $teamId) {
                        $dataToInsert[] = [
                            'project_id' => $project->id,
                            'team_id' => $teamId  
                        ];
                    }
                    DB::table('project_departments')->insert($dataToInsert);
        
                }

                if (!empty($member_ids)) {
        
                    $dataToInsert1 = [];
                    foreach ($member_ids as $memberId) {
                        $dataToInsert1[] = [
                            'project_id' => $project->id,
                            'user_id' => $memberId
                        ];
                    }
                    DB::table('project_members')->insert($dataToInsert1);
        
                }
        
                $taskBoard = TaskboardColumn::where('slug', 'incomplete')->where('company_id',4)->first();

                foreach ($template1->tasks as $task) {
        
                    $projectTask = new Task();
                    $projectTask->project_id = $project->id;
                    $projectTask->company_id = 4;
                    $projectTask->heading = $task->heading;
                    
                    $projectTask->task_category_id = $task->project_template_task_category_id;
                    $projectTask->description = trim_editor($task->description);
                    $projectTask->start_date = $startDate;
                    $projectTask->due_date = $deadline;
                    $projectTask->board_column_id = $taskBoard->id;
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



    }




}
