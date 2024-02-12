<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;


use Mail;


class EsslCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'essl-log';

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

    

        $url = 'http://chandunextclick-001-site1.anytempurl.com/getessldata.php'; // Replace with the URL you want to fetch data from


        $ch = curl_init($url);
    
        // Set the request type to GET
        curl_setopt($ch, CURLOPT_HTTPGET, true);
    
        // Set other cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    
        // Execute the cURL session
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
    
        curl_close($ch);
    
    
        // Process the response
        $data['essl'] = json_decode($response, true);
    
    
    
        foreach ($data['essl'] as $record) {
    
            DB::table('employeelog')->insert($record);
    
        }
    
        $maxEsslValue = DB::table('employeelog')->max('id');
    
        $lastRecord = DB::table('employeelog')->find($maxEsslValue);
    
        $esslValue = $lastRecord->essl;
        
        print_r($esslValue);

        DB::commit();

    // -----------------------------------------------------------------------------------------------------------------

        $url = 'http://chandunextclick-001-site1.anytempurl.com/updatesync.php?lastid='.$esslValue; // Replace with the URL you want to fetch data from






        $ch = curl_init($url);
    
        // Set the request type to GET
        curl_setopt($ch, CURLOPT_HTTPGET, true);
    
        // Set other cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    
        // Execute the cURL session
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
    
        curl_close($ch);


        $sql = "
            DELETE e1
            FROM employeelog e1
            JOIN employeelog e2 ON e1.essl = e2.essl AND e1.id > e2.id
        ";

        DB::delete($sql);

        DB::commit();


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
    WHERE STR_TO_DATE(e1.logdate, '%Y-%m-%d') = '2024-02-09'
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
