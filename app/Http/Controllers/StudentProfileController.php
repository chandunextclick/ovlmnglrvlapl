<?php

namespace App\Http\Controllers;
use App\DataTables\EnquiryDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Mail;

class StudentProfileController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.enquiry';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('employees', $this->user->modules));

            return $next($request);
        });
    }
    

public function index(Request $request)
{

    $data['pageTitle']= $this->pageTitle;
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
    
    $url = 'https://nextclickonline.com/testenqapplication/enquiry'; // Replace with the URL you want to fetch data from

    // $ch = curl_init($url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // $response = curl_exec($ch);
    // $data['enquiry'] = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    // $this->env=$data['enquiry'];
    // curl_close($ch);

    $data['date2'] = date("Y-m-d"); // Get the current date in "YYYY-MM-DD" format

    // Calculate one month before the current date
    
    $data['date1'] = date("Y-m-d", strtotime("-1 month", strtotime($data['date2'])));

    


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



public function show($id)
{

    $data['pageTitle']= $this->pageTitle;
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
    

    $url = 'https://nextclickonline.com/testenqapplication/enquirydetail'; // Replace with the URL you want to fetch data from



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

    

    // var_dump($this->env->enquiry_details);

    // Check for errors

    if (!$data['enq']) {
        die('Failed to fetch data.');
    }



    return view('employees.ajax.sprofile',$data);

}


public function essldata(Request $request){



    $data['pageTitle']= $this->pageTitle;
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

    


    if ($request->isMethod('post')) {
        // Handle POST request
        $data['date1'] = $request->input('start_date');
        $data['date2'] = $request->input('end_date');
        
        // Retrieve and display data based on the POST data
    }

    $date1 = $data['date1'];
    $date2 = $data['date2'];


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

$messages->to('chandunextclick@gmail.com');
$messages->subject('Hello Chandu');

});

return view('employees.ajax.mail',$data);

}



}
