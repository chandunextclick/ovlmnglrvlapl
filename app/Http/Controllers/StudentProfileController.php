<?php

namespace App\Http\Controllers;
use App\DataTables\EnquiryDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Mail;

class StudentProfileController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('employees', $this->user->modules));

            return $next($request);
        });
    }
    

public function enquiry(Request $request)
{

    $data['pageTitle']= 'Enquiry';
    $data['pushSetting']= $this->pushSetting;
    $data['pusherSettings']= $this->pusherSettings;
    $data['checkListCompleted']= $this->checkListCompleted;
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
    
    $url = 'https://nextclickonline.cyradrive.com/testenqapplication/enquiry'; // Replace with the URL you want to fetch data from

    // $ch = curl_init($url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // $response = curl_exec($ch);
    // $data['enquiry'] = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    // $this->env=$data['enquiry'];
    // curl_close($ch);

    $data['date2'] = date("Y-m-d"); // Get the current date in "YYYY-MM-DD" format

    // Calculate one month before the current date
    
    $data['date1'] = date("Y-m-d", strtotime("-1 month", strtotime($data['date2'])));

    // dd($request->input('start_date'));    


    if ($request->isMethod('post')) {

        // Handle POST request
        $data['date1'] = $request->input('start_date');
        $data['date2'] = $request->input('end_date');
        
        // Retrieve and display data based on the POST data
    }


    
    $dateparam = array(
        'param1' => $data['date1'],
        'param2' => $data['date2']
    );
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dateparam)); // Set POST data
    $response = curl_exec($ch);
    $data['enquiry'] = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    $this->env = $data['enquiry'];
    curl_close($ch);




    if (!$data['enquiry']) {
        die('Failed to fetch data.');
    }


    return view('employees.enquiry',$data);
}


public function customerpersonacreate(Request $request)
{

    $data['pageTitle']= 'Customer Persona';
    $data['pushSetting']= $this->pushSetting;
    $data['pusherSettings']= $this->pusherSettings;
    $data['checkListCompleted']= $this->checkListCompleted;
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

    $url="https://api.edoxi.com/api/master-course-list";

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
    $data['response'] = json_decode($response, true);

    $data['courses'] = $data['response']["list"];
    

    return view('employees.customerpersonacreate',$data);
}

public function customerpersonaview()
{

    $data['pageTitle']= 'Customer Persona';
    $data['pushSetting']= $this->pushSetting;
    $data['pusherSettings']= $this->pusherSettings;
    $data['checkListCompleted']= $this->checkListCompleted;
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
    

    $url="https://api.edoxi.com/api/master-course-list";

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
    $data['response'] = json_decode($response, true);

    $data['courses'] = $data['response']["list"];

    $data['customerpersona']= DB::table('customer_persona')->get();
    

    return view('employees.customerpersonaview',$data);
}



public function enquirydetail($id)
{

    $data['pageTitle']= 'Enquiry Detail';;
    $data['pushSetting']= $this->pushSetting;
    $data['pusherSettings']= $this->pusherSettings;
    $data['checkListCompleted']= $this->checkListCompleted;
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
    

    $url = 'https://nextclickonline.cyradrive.com/testenqapplication/enquirydetail'; // Replace with the URL you want to fetch data from



    $passval= array(

        'id' => $id
        
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($passval)); // Set POST data
    $response = curl_exec($ch);
    $data['enq'] = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    $this->env=$data['enq'];
    curl_close($ch);




    // Check for errors

    if (!$data['enq']) {
        die('Failed to fetch data.');
    }



    return view('employees.ajax.sprofile',$data);

}


public function essldata(Request $request){



    $data['pageTitle']= 'Employee Log';
    $data['pushSetting']= $this->pushSetting;
    $data['pusherSettings']= $this->pusherSettings;
    $data['checkListCompleted']= $this->checkListCompleted;
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


    $data['date2'] = date("Y-m-d"); // Get the current date in "YYYY-MM-DD" format

    // Calculate one month before the current date
    
    $data['date1'] = date("Y-m-d");

    $data['userid'] = 0;


    if ($request->isMethod('post')) {
        // Handle POST request
        $data['date1'] = $request->input('start_date');
        $data['date2'] = $request->input('end_date');
        $data['userid'] = $request->input('userid');
        
        // Retrieve and display data based on the POST data
    }

    $userid=$data['userid'];
    $date1 = $data['date1'];
    $date2 = $data['date2'];

    $addon = "";
    $countaddon = "";

    if($data['userid']!=0){

        $addon = " users.id=$userid and";

        $countaddon = " user_id=$userid and";;

    }else{

        $addon = "";

        $countaddon = "";

    }





    $userquery="select * from users where company_id=4 and user_auth_id!=''";

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
    )), '%H:%i')), '%H:%i:%s') as total_working_time,
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
    )), '%H:%i:%s') AS total_break_time
FROM employeelog e1 
LEFT JOIN employee_details ON employee_details.employee_id = e1.empcode
LEFT JOIN users ON employee_details.user_id = users.id
WHERE $addon STR_TO_DATE(e1.logdate, '%Y-%m-%d') BETWEEN  '$date1' and '$date2'
GROUP BY e1.empcode, e1.logdate,users.name
";





$data['query']=$query;

$data['userquery']=DB::select($userquery);

$data['essllog'] = DB::select($query);

$halfday=DB::select("SELECT count(*) as count FROM `leaves` where $countaddon half_day_type!='' and leave_date between '$date1' and '$date2' ");

// var_dump($halfday[0]->count);

$countofhalfday = 0;

$countofhalfday = (($halfday[0]->count)*0.5);

$totalWorkTime=[];

foreach ($data['essllog'] as $value) {

                
    $totalWorkTime[]=$value->total_working_time;

}

$validTimes = array_filter($totalWorkTime, function ($time) {
    return $time !== null;
});

// Initialize variables to hold hours, minutes, and seconds
$totalHours = 0;
$totalMinutes = 0;
$totalSeconds = 0;
$timecount=0;
$caltotmin=0;
$caltothours=0;

// Parse and add each valid time string
foreach ($validTimes as $time) {
    list($hours, $minutes, $seconds) = sscanf($time, "%d:%d:%d");
    $totalHours += $hours;
    $totalMinutes += $minutes;
    $totalSeconds += $seconds;
    $timecount+=1;

}

$timecount-=$countofhalfday;

// var_dump($timecount);

 // Adjust minutes and seconds if they exceed their respective limits
        $totalMinutes += floor($totalSeconds / 60);
        $totalSeconds %= 60;
        
        $totalHours += floor($totalMinutes / 60);
        $totalMinutes %= 60;
        
        // Format the total time
        $data['totalTimeFormatted'] = sprintf("%02d:%02d:%02d", $totalHours, $totalMinutes, $totalSeconds);

        $caltotmin = ($totalHours * 60)+($totalMinutes);

        if($timecount!=0){

            $caltotmin /= $timecount; 

            $caltotmin = floor($caltotmin);

        }

        
        $caltothours += floor($caltotmin / 60);
        $caltotmin %=60;
        $data['avgTimeFormatted'] = sprintf("%02d:%02d", $caltothours, $caltotmin);

return view('employees.ajax.essllog',$data);

}



public function store(Request $request) {

// Validation rules
$rules = [
    'persona_age' => 'digits:2|numeric', // Validate age as a numeric value with exactly 2 digits
    // Add other validation rules for other fields as needed
];

// Custom validation messages
$messages = [
    'persona_age.digits' => 'The age must be exactly two digits.',
    'persona_age.numeric' => 'The age must be a number.',
    // Add custom messages for other validation rules as needed
];


   // Validate the request data
   $validator = Validator::make($request->all(), $rules, $messages);


      // Check if validation fails
      if ($validator->fails()) {
        
        dd($validator->errors()->all());
    }

   
$data['persona'] = [
    'name' => $request->input('persona_name'),
    'course' => $request->input('persona_course'),
    'age' => $request->input('persona_age'),
    'education' => $request->input('persona_education'),
    'subject' => $request->input('persona_education_subject'),
    'specialization' => $request->input('persona_education_specification'),
    'occupation' => $request->input('persona_occupation'),
    'experience' => $request->input('persona_experience'),
    'location' => $request->input('persona_location'),
    'user_description' => $request->input('persona_user_description'),
    'personal_characteristics' => $request->input('persona_Characteristics'),
    'goals' => $request->input('persona_goals'),
    'needs' => $request->input('persona_needs'),
    'hobbies_and_interest' => $request->input('persona_interest'),
    'challenges' => $request->input('persona_Challenges'),
    'source_info' => $request->input('persona_source'),
];

try{
// Insert data into the persona table

DB::table('customer_persona')->insert($data['persona']);

return back()->with('message','The Data Inserted');

}catch(\Exception $e) {

    return back()->with('message',$e->getMessage());

}


}



}
