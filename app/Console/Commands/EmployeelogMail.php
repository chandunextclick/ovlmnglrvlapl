<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;


class EsslCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'empdaily-log';

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

        $data['date1'] = date("Y-m-d", strtotime("-1 day"));

        $date1 = $data['date1'];

        $query = "
        SELECT
        e1.empcode,
        users.name,
        e1.logdate,
        TIME_FORMAT(SEC_TO_TIME(SUM(
            CASE
                WHEN e1.direction = 'in'
                THEN TIMESTAMPDIFF(
                        SECOND,
                        STR_TO_DATE(CONCAT(e1.logdate, e1.logtime), '%Y-%m-%d %H:%i:%s'),
                        (SELECT MIN(STR_TO_DATE(CONCAT(e2.logdate, e2.logtime), '%Y-%m-%d %H:%i:%s'))
                         FROM employeelog e2
                         WHERE e2.empcode = e1.empcode
                           AND e2.logdate = e1.logdate
                           AND e2.logtime > e1.logtime
                           AND e2.direction = 'out')
                )
                ELSE 0
            END
        )), '%H:%i') AS total_working_time,
        TIME_FORMAT(SEC_TO_TIME(SUM(
            CASE
                WHEN e1.direction = 'out'
                THEN TIMESTAMPDIFF(
                        SECOND,
                        STR_TO_DATE(CONCAT(e1.logdate, e1.logtime), '%Y-%m-%d %H:%i:%s'),
                        (SELECT MIN(STR_TO_DATE(CONCAT(e2.logdate, e2.logtime), '%Y-%m-%d %H:%i:%s'))
                         FROM employeelog e2
                         WHERE e2.empcode = e1.empcode
                           AND e2.logdate = e1.logdate
                           AND e2.logtime > e1.logtime
                           AND e2.direction = 'in')
                )
                ELSE 0
            END
        )), '%H:%i') AS total_break_time
    FROM employeelog e1 
    LEFT JOIN employee_details ON employee_details.employee_id = e1.empcode
    LEFT JOIN users ON employee_details.user_id = users.id
    WHERE STR_TO_DATE(e1.logdate, '%Y-%m-%d') = '$date1'
    GROUP BY e1.empcode, e1.logdate,users.name
    ";
    
    $data['query']=$query;
    $data['essllog'] = DB::select($query);
    
    $user['name']='Next Click';
    Mail::send('employees.ajax.mail',$data,function($messages) use ($user){

    $messages->to('neerajnextclick@gmail.com');
    $messages->subject('Hello Chandu');

});



    }




}
