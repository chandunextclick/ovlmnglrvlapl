<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use Mail;


class EmployeelogMail extends Command
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


        // Step 1: Create temporary table
        DB::statement('CREATE TEMPORARY TABLE temp_table AS
        SELECT e1.id
        FROM employeelog e1
        WHERE e1.direction = (
        SELECT e2.direction
        FROM employeelog e2
        WHERE e1.empcode = e2.empcode
        AND e1.logdate = e2.logdate
        AND CAST(e2.essl AS SIGNED) > CAST(e1.essl AS SIGNED)
        ORDER BY e2.logtime ASC
        LIMIT 1
        )and STR_TO_DATE(e1.logdate, "%Y-%m-%d")=(CURDATE() - INTERVAL 1 DAY)');

        // Step 2: Delete from employeelog using the temporary table
        DB::table('employeelog as e1')
        ->join('temp_table as temp', 'e1.id', '=', 'temp.id')
        ->delete();

        // Step 3: Drop the temporary table
        DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_table');

        DB::commit();

        $data['date1'] = date("Y-m-d", strtotime("-1 day"));

        $date1 = $data['date1'];

        $users = DB::table('users')
        ->where('company_id',4)
        ->where('email', '!=', '')
        ->get();

    foreach ($users as $user) {

        $query1 = "
                    SELECT
                    e1.empcode,
                    users.name,
                    e1.logdate,
                    TIME_FORMAT(SUBTIME(TIME_FORMAT(SEC_TO_TIME(
                        TIMESTAMPDIFF(
                            SECOND,
                            (SELECT MIN(STR_TO_DATE(CONCAT(e2.logdate, e2.logtime), '%Y-%m-%d %H:%i:%s'))
                             FROM employeelog e2
                             WHERE e2.empcode = e1.empcode
                               AND e2.logdate = e1.logdate
                               AND e2.direction = 'in'),
                            (SELECT MAX(STR_TO_DATE(CONCAT(e2.logdate, e2.logtime), '%Y-%m-%d %H:%i:%s'))
                             FROM employeelog e2
                             WHERE e2.empcode = e1.empcode
                               AND e2.logdate = e1.logdate
                               AND e2.direction = 'out')
                        )
                    ), '%H:%i'),TIME_FORMAT(SEC_TO_TIME(SUM(
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
                    )), '%H:%i')), '%H:%i') as total_working_time,
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
                    WHERE STR_TO_DATE(e1.logdate, '%Y-%m-%d') = '$date1' and users.id=$user->id
                    GROUP BY e1.empcode, e1.logdate,users.name
                    ";

            

            $data['essllog'] = DB::select($query1);

            Mail::send('employees.ajax.mail',$data,function($messages) use ($user){

            $messages->to($user->email);
            $messages->subject('Log( '.date("Y-m-d", strtotime("-1 day")).' ) of '. $user->name);
            
            });

        
    }

        $query = "
        SELECT
        e1.empcode,
        users.name,
        e1.logdate,
        TIME_FORMAT(SUBTIME(TIME_FORMAT(SEC_TO_TIME(
            TIMESTAMPDIFF(
                SECOND,
                (SELECT MIN(STR_TO_DATE(CONCAT(e2.logdate, e2.logtime), '%Y-%m-%d %H:%i:%s'))
                 FROM employeelog e2
                 WHERE e2.empcode = e1.empcode
                   AND e2.logdate = e1.logdate
                   AND e2.direction = 'in'),
                (SELECT MAX(STR_TO_DATE(CONCAT(e2.logdate, e2.logtime), '%Y-%m-%d %H:%i:%s'))
                 FROM employeelog e2
                 WHERE e2.empcode = e1.empcode
                   AND e2.logdate = e1.logdate
                   AND e2.direction = 'out')
            )
        ), '%H:%i'),TIME_FORMAT(SEC_TO_TIME(SUM(
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
        )), '%H:%i')), '%H:%i') as total_working_time,
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
    
    $useradmin['name']='Next Click';
    Mail::send('employees.ajax.mail',$data,function($messages) use ($useradmin){

    $messages->to(['chandunextclick@gmail.com', 'neerajnextclick@gmail.com']);
    $messages->subject('Hello Admin');

});


    }




}
