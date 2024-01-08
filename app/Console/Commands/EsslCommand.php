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

    // -----------------------------------------------------------------------------------------------------------------

        $url = 'http://chandunextclick-001-site1.anytempurl.com/updatesync.php?lastid='.$esslValue; // Replace with the URL you want to fetch data from



        // $passval= array(
    
        //     'lastid' => $esslValue
            
        // );
    
        // print_r($passval);

        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($passval)); // Set POST data
        // $response = curl_exec($ch);
        // // print_r($response);
        // curl_close($ch);


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



    }




}
