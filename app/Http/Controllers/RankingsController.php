<?php

namespace App\Http\Controllers;
use App\DataTables\EnquiryDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Helper\Reply;

use Mail;

class RankingsController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('employees', $this->user->modules));

            return $next($request);
        });
    }
    


    public function rankingelementcreate()
    {
    
        $data['pageTitle']= 'ADD Ranking Element';
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
    
    
    
        return view('rankings.rankingelementcreate',$data);
    }
    
    
    public function rankinelementedit($id)
    {
    
        $data['pageTitle']= 'Edit Ranking Element';
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
        
    
        $data['element'] =  DB::table('ranking_element')->where('id', $id)->first();
    
        $elementid = $data['element']->id;
    
        return view('rankings.rankingelementedit',$data);
    }
    
    
    public function rankingelementview()
    {
    
        $data['pageTitle']= 'Ranking Element';
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
        
        
        $query = "SELECT * FROM `ranking_course` ORDER by ranking_course";


        $data['courses'] = DB::select($query);
        
    
        $data['rankingelement']= DB::table('ranking_element')->get();
        
    
        return view('rankings.rankingelementview',$data);
    }
    
    
    public function rankinelementstore(Request $request) {
    
    
       
        $data['rankingelement'] = [
        
            'ranking_element' => $request->input('element_name'),
            'increase_percent' => $request->input('increase_percent'),
            'client' => $request->input('client')
        ];
        
        try{
        // Insert data into the persona table
        
        DB::table('ranking_element')->insert($data['rankingelement']);
        
        return back()->with('message','The Data Inserted');
        
        }catch(\Exception $e) {
        
            return back()->with('message',$e->getMessage());
        
        }
        
        
    }
    
    
    public function rankinelementupdate(Request $request){
    
    
        try{
    
        // Update data into the persona table
        DB::table('ranking_element')
                ->where('id', $request->input('element_id'))
                ->update(['ranking_element' => $request->input('element_name'),'increase_percent' => $request->input('increase_percent'),'client' => $request->input('client')]);
        
        return redirect()->route('rankings.rankingelementview');
        
        }catch(\Exception $e) {
        
            return back()->with('message',$e->getMessage());
        
        }
        
        
    }
    
    
    public function rankinelementdelete($id){
    
        try{
    
        // Update data into the persona table
    
        DB::table('ranking_element')
                ->where('id', $id)
                ->delete();
            
        
        return Reply::success(__('messages.deleteSuccess'));
        
        }catch(\Exception $e) {
    
        
            return back()->with('message',$e->getMessage());
        
        }
        
}


public function rankingcountrycreate()
{

    $data['pageTitle']= 'ADD Ranking Country';
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



    return view('rankings.rankingcountrycreate',$data);
}


public function rankincountryedit($id)
{

    $data['pageTitle']= 'Edit Ranking Country';
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
    

    $data['country'] =  DB::table('ranking_countries')->where('id', $id)->first();

    $elementid = $data['country']->id;

    return view('rankings.rankingcountryedit',$data);
}


public function rankingcountryview()
{

    $data['pageTitle']= 'Ranking Country';
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
    

    $query = "SELECT * FROM `ranking_course` ORDER by ranking_course";


    $data['courses'] = DB::select($query);

    $data['rankingcountry']= DB::table('ranking_countries')->get();
    

    return view('rankings.rankingcountryview',$data);
}


public function rankincountrystore(Request $request) {


   
    $data['rankingcountry'] = [
    
        'ranking_country' => $request->input('country_name'),
        'increase_percent' => $request->input('increase_percent'),
        'client' => $request->input('client')
    
    ];
    
    try{
    // Insert data into the persona table
    
    DB::table('ranking_countries')->insert($data['rankingcountry']);
    
    return back()->with('message','The Data Inserted');
    
    }catch(\Exception $e) {
    
        return back()->with('message',$e->getMessage());
    
    }
    
    
}


public function rankincountryupdate(Request $request){


    try{

    // Update data into the persona table
    DB::table('ranking_countries')
            ->where('id', $request->input('country_id'))
            ->update(['ranking_country' => $request->input('country_name'),'increase_percent' => $request->input('increase_percent'),'client' => $request->input('client')]);
    
    return redirect()->route('rankings.rankingcountryview');
    
    }catch(\Exception $e) {
    
        return back()->with('message',$e->getMessage());
    
    }
    
    
}


public function rankincountrydelete($id){

    try{

    // Update data into the persona table

    DB::table('ranking_countries')
            ->where('id', $id)
            ->delete();
        
    
    return Reply::success(__('messages.deleteSuccess'));
    
    }catch(\Exception $e) {

    
        return back()->with('message',$e->getMessage());
    
    }
    
}



public function rankingcoursecreate()
{

    $data['pageTitle']= 'ADD Ranking Course';
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



    return view('rankings.rankingcoursecreate',$data);
}


public function rankincourseedit($id)
{

    $data['pageTitle']= 'Edit Ranking Course';
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
    

    $data['courses'] =  DB::table('ranking_course')->where('id', $id)->first();

    $courseid = $data['courses']->id;

    return view('rankings.rankingcourseedit',$data);
}


public function rankingcourseview()
{

    $data['pageTitle']= 'Ranking Course';
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
    

    $data['rankingcourse']= DB::table('ranking_course')->get();
    

    return view('rankings.rankingcourseview',$data);
}


public function rankincoursestore(Request $request) {


   
    $data['rankingcourse'] = [
    
        'ranking_course' => $request->input('course_name')
    
    ];
    
    try{
    // Insert data into the persona table
    
    DB::table('ranking_course')->insert($data['rankingcourse']);
    
    return back()->with('message','The Data Inserted');
    
    }catch(\Exception $e) {
    
        return back()->with('message',$e->getMessage());
    
    }
    
    
}


public function rankincourseupdate(Request $request){


    try{

    // Update data into the persona table
    DB::table('ranking_course')
            ->where('id', $request->input('course_id'))
            ->update(['ranking_course' => $request->input('course_name')]);
    
    return redirect()->route('rankings.rankingcourseview');
    
    }catch(\Exception $e) {
    
        return back()->with('message',$e->getMessage());
    
    }
    
    
}


public function rankincoursedelete($id){

    try{

    // Update data into the persona table

    DB::table('ranking_course')
            ->where('id', $id)
            ->delete();


    DB::table('ranking_keyword')
            ->where('ranking_course', $id)
            ->delete();
        
    
    return Reply::success(__('messages.deleteSuccess'));
    
    }catch(\Exception $e) {

    
        return back()->with('message',$e->getMessage());
    
    }
    
}




public function rankingkeywordcreate()
{

    $data['pageTitle']= 'ADD Ranking Keyword';
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


    $query = "SELECT * FROM `ranking_course` ORDER by ranking_course";


    $data['courses'] = DB::select($query);


    return view('rankings.rankingkeywordcreate',$data);
}

public function rankinkeywordedit($id)
{

    $data['pageTitle']= 'Edit Ranking Keyword';
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
    
    $query = "SELECT * FROM `ranking_course` ORDER by ranking_course";

    $data['courses'] = DB::select($query);

    $data['keyword'] = DB::table('ranking_keyword')
                        ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                        ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','ranking_keyword.client')
                        ->where('ranking_keyword.id', $id)->first();


    return view('rankings.rankingkeywordedit',$data);
}


public function rankinkeywordupdate(Request $request){


    try{

    // Update data into the persona table
    DB::table('ranking_keyword')
            ->where('id', $request->input('keyword_id'))
            ->update(['ranking_course' => $request->input('course_name'),'ranking_keyword' => $request->input('keyword_name'),'search_volume' => $request->input('search_volume'),'client' => $request->input('client')]);
    
    return redirect()->route('rankings.rankingkeywordview');
    
    }catch(\Exception $e) {
    
        return back()->with('message',$e->getMessage());
    
    }
    
    
}


public function rankinkeyworddelete($id){

    try{


    DB::table('ranking_keyword')
            ->where('id', $id)
            ->delete();
        
    
    return Reply::success(__('messages.deleteSuccess'));
    
    }catch(\Exception $e) {

    
        return back()->with('message',$e->getMessage());
    
    }
    
}



public function rankingkeywordview()
{

    $data['pageTitle']= 'Ranking Course';
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

    $query = "SELECT * FROM `ranking_course` ORDER by ranking_course";


    $data['courses'] = DB::select($query);
    

    $data['rankingkeyword']= DB::table('ranking_keyword')
                            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_keyword.client','ranking_course.ranking_course')
                            ->get();
    

    return view('rankings.rankingkeywordview',$data);
}



public function rankinkeywordstore(Request $request) {

    $clientval=$request->input('client');

    $client="";

    $location="";

    if($clientval == "EDOXI"){

        $client = "EDOXI";

        $location = "DUBAI";

    }elseif($clientval == "TIMETRAINING"){

        $client = "TIMETRAINING";

        $location = "AbuDhabi";

    }elseif($clientval == "TIMEMASTERA"){

        $client = "TIMEMASTER";

        $location = "AbuDhabi";

    }elseif($clientval == "TIMEMASTERM"){
    
        $client = "TIMEMASTER";

        $location = "Mussafah";
    
    }

   
    $data['rankingkeyword'] = [
    
        'ranking_keyword' => $request->input('keyword_name'),
        'ranking_course' => $request->input('course_name'),
        'search_volume' => $request->input('search_volume'),
        'client' => $client,
        'location' => $location
    
    ];
    
    try{
    // Insert data into the persona table
    
    DB::table('ranking_keyword')->insert($data['rankingkeyword']);
    
    return back()->with('message','The Data Inserted');
    
    }catch(\Exception $e) {
    
        return back()->with('message',$e->getMessage());
    
    }
    
    
    }



public function monthlykeywordranking(Request $request)
{

    $data['pageTitle']= 'Ranking Course';
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



    $yeardata = date('Y');

    $monthdata = date('F');

    $yearmonth = date('F Y');

    if(user()->id==54){

        $yeardata = "2024";

        $monthdata = "July";

        $yearmonth = "July 2024";

    }



    $client = "EDOXI";
    

    if ($request->isMethod('post')) {

        // Handle POST request

        $yearmonth = $request->input('yearmonth');

        $client = $request->input('client');

        $arr = explode(" ",$yearmonth);

        $yeardata = $arr[1];
        $monthdata = $arr[0];
        
        // Retrieve and display data based on the POST data
    }

    
    $data['yearmonth']=$yearmonth;

    $data['yeardata']=$yeardata;

    $data['monthdata']=$monthdata;

    $data['client']=$client;

    $data['rankingkeyword']= DB::table('ranking_keyword')
                            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                            ->leftJoin('monthy_keyword_rankings', function ($join) use ($yeardata,$monthdata) {
                                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                                    ->where('monthy_keyword_rankings.year', '=', $yeardata)
                                    ->where('monthy_keyword_rankings.month', '=', $monthdata);
                                    
                            })
                            ->where('ranking_keyword.client', '=', $client)
                            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank','monthy_keyword_rankings.googlemap_rank')
                            ->get();  
                            


    return view('rankings.monthlykeywordranking',$data);

}


public function updaterankings(Request $request) {

    
    $id = $request->input('id');
    $col = $request->input('val');
    $val = $request->input('inputvalue');
    $month = $request->input('month');
    $year = $request->input('year');

    $query="";

    if($col == "google"){

        $query="select google_rank from `monthy_keyword_rankings` where keyword_id='$id' and month='$month' and year='$year'";

        $result = DB::select($query);

        if(count($result)==0){

            DB::table('monthy_keyword_rankings')->insert(['keyword_id' => $id,'google_rank' => $val,'month' => $month,'year' => $year]);
    
        }else{

            DB::table('monthy_keyword_rankings')->where([
                ['keyword_id', '=', $id ],
                ['month', '=', $month],
                ['year', '=', $year]
            ])->update(['google_rank' => $val]);
        }


    }else{

        $query="select googlemap_rank from `monthy_keyword_rankings` where keyword_id='$id' and month='$month' and year='$year'";

        $result = DB::select($query);

        if(count($result)==0){

            DB::table('monthy_keyword_rankings')->insert(['keyword_id' => $id,'googlemap_rank' => $val,'month' => $month,'year' => $year]);
    
        }else{

            DB::table('monthy_keyword_rankings')->where([
                ['keyword_id', '=', $id ],
                ['month', '=', $month],
                ['year', '=', $year]
            ])->update(['googlemap_rank' => $val]);
        }

    }
    
    
}


public function monthlyelementranking(Request $request)
{

    $data['pageTitle']= 'Ranking Element';
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



    $yeardata = date('Y',strtotime('-1 month'));

    $monthdata = date('F',strtotime('-1 month'));

    $yearmonth = date('F Y',strtotime('-1 month'));

    $client = "EDOXI";
    

    if ($request->isMethod('post')) {

        // Handle POST request

        $yearmonth = $request->input('yearmonth');

        $client = $request->input('client');

        $arr = explode(" ",$yearmonth);

        $yeardata = $arr[1];
        $monthdata = $arr[0];
        
        // Retrieve and display data based on the POST data
    }

    
    $data['yearmonth']=$yearmonth;

    $data['yeardata']=$yeardata;

    $data['monthdata']=$monthdata;

    $data['client']=$client;

    $data['rankingelement']= DB::table('ranking_element')
                            ->leftJoin('monthly_element_rankings', function ($join) use ($yeardata,$monthdata) {
                                $join->on('monthly_element_rankings.element_id', '=', 'ranking_element.id')
                                    ->where('monthly_element_rankings.year', '=', $yeardata)
                                    ->where('monthly_element_rankings.month', '=', $monthdata);
                            })
                            ->where('ranking_element.client', '=', $client)
                            ->select('ranking_element.id','ranking_element.ranking_element','ranking_element.increase_percent','monthly_element_rankings.google_rank','monthly_element_rankings.google_rank_prev')
                            ->get();  
                            


    return view('rankings.monthlyelementranking',$data);

}
    
    
    public function updateelementrankings(Request $request){
    
        
        $id = $request->input('id');
        $col = $request->input('val');
        $val = $request->input('inputvalue');
        $month = $request->input('month');
        $year = $request->input('year');
    
        $query="";
    
        if($col == "google"){
    
            $query="select google_rank from `monthly_element_rankings` where element_id='$id' and month='$month' and year='$year'";
    
            $result = DB::select($query);
    
            if(count($result)==0){
    
                DB::table('monthly_element_rankings')->insert(['element_id' => $id,'google_rank' => $val,'month' => $month,'year' => $year]);
        
            }else{
    
                DB::table('monthly_element_rankings')->where([
                ['element_id', '=', $id ],
                ['month', '=', $month],
                ['year', '=', $year]
            ])->update(['google_rank' => $val]);
            }
    
    
        }else{


            $query="select google_rank_prev from `monthly_element_rankings` where element_id='$id' and month='$month' and year='$year'";
    
            $result = DB::select($query);
    
            if(count($result)==0){
    
                DB::table('monthly_element_rankings')->insert(['element_id' => $id,'google_rank_prev' => $val,'month' => $month,'year' => $year]);
        
            }else{
    
                DB::table('monthly_element_rankings')->where([
                ['element_id', '=', $id ],
                ['month', '=', $month],
                ['year', '=', $year]
            ])->update(['google_rank_prev' => $val]);
            }




        }
        
        
    }

    public function monthlycountryranking(Request $request)
    {
    
        $data['pageTitle']= 'Ranking Country';
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
    
    
    
        $yeardata = date('Y',strtotime('-1 month'));
    
        $monthdata = date('F',strtotime('-1 month'));
    
        $yearmonth = date('F Y',strtotime('-1 month'));

        $client = "EDOXI";
        
    
        if ($request->isMethod('post')) {
    
            // Handle POST request
    
            $yearmonth = $request->input('yearmonth');

            $client = $request->input('client');
    
            $arr = explode(" ",$yearmonth);
    
            $yeardata = $arr[1];
            $monthdata = $arr[0];
            
            // Retrieve and display data based on the POST data
        }
    
        
        $data['yearmonth']=$yearmonth;
    
        $data['yeardata']=$yeardata;
    
        $data['monthdata']=$monthdata;

        $data['client']=$client;
    
        $data['rankingcountry']= DB::table('ranking_countries')
                                ->leftJoin('monthly_country_rankings', function ($join) use ($yeardata,$monthdata) {
                                    $join->on('monthly_country_rankings.country_id', '=', 'ranking_countries.id')
                                        ->where('monthly_country_rankings.year', '=', $yeardata)
                                        ->where('monthly_country_rankings.month', '=', $monthdata);
                                })
                                ->where('ranking_countries.client', '=', $client)
                                ->select('ranking_countries.id','ranking_countries.ranking_country','ranking_countries.increase_percent','monthly_country_rankings.google_rank','monthly_country_rankings.google_rank_prev')
                                ->get();  
                           
    
        return view('rankings.monthlycountryranking',$data);
    
    }
    
    
    public function updatecountryrankings(Request $request) {
    
        
        $id = $request->input('id');
        $col = $request->input('val');
        $val = $request->input('inputvalue');
        $month = $request->input('month');
        $year = $request->input('year');
    
        $query="";
    
        if($col == "google"){
    
            $query="select google_rank from `monthly_country_rankings` where country_id='$id' and month='$month' and year='$year'";
    
            $result = DB::select($query);
    
            if(count($result)==0){
    
                DB::table('monthly_country_rankings')->insert(['country_id' => $id,'google_rank' => $val,'month' => $month,'year' => $year]);
        
            }else{
    
                DB::table('monthly_country_rankings')->where([
                    ['country_id', '=', $id ],
                    ['month', '=', $month],
                    ['year', '=', $year]
                ])->update(['google_rank' => $val]);
            }
    
    
        }else{


            $query="select google_rank_prev from `monthly_country_rankings` where country_id='$id' and month='$month' and year='$year'";
    
            $result = DB::select($query);
    
            if(count($result)==0){
    
                DB::table('monthly_country_rankings')->insert(['country_id' => $id,'google_rank_prev' => $val,'month' => $month,'year' => $year]);
        
            }else{
    
                DB::table('monthly_country_rankings')->where([
                ['country_id', '=', $id ],
                ['month', '=', $month],
                ['year', '=', $year]
            ])->update(['google_rank_prev' => $val]);
            }




        }
        
        
    }


    public function monthlyrankingreport(Request $request)
    {
    
        $data['pageTitle']= 'Digital Marketing Dashboard';
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
    
    
    
        $yeardata = date('Y',strtotime('-1 month'));
    
        $monthdata = date('F',strtotime('-1 month'));
    
        $yearmonth = date('F Y',strtotime('-1 month'));

        $prevyearmonth = date('F Y',strtotime('-2 month'));
        
    
        if ($request->isMethod('post')) {
    
            // Handle POST request
    
            $yearmonth = $request->input('yearmonth');
    
            $arr = explode(" ",$yearmonth);
    
            $yeardata = $arr[1];
            $monthdata = $arr[0];
            
            // Retrieve and display data based on the POST data
        }
    
        
        $data['yearmonth']=$yearmonth;

        $data['prevyearmonth']=$prevyearmonth;
    
        $data['yeardata']=$yeardata;
    
        $data['monthdata']=$monthdata;
    
        $query = "SELECT * FROM `ranking_course` ORDER by ranking_course";


        $data['courses'] = DB::select($query);
                                
    
    
        return view('rankings.monthlyrankingreport',$data);
    
    }



    public function monthlydetailedrankingreport(Request $request)
    {
    
        $data['pageTitle']= 'Digital Marketing Dashboard';
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
    
    
    
        $yeardata = date('Y',strtotime('-1 month'));
    
        $monthdata = date('F',strtotime('-1 month'));
    
        $yearmonth = date('F Y',strtotime('-1 month'));

        $prevyearmonth = date('F Y',strtotime('-2 month'));
        
    
        if ($request->isMethod('post')) {
    
            // Handle POST request
    
            $yearmonth = $request->input('yearmonth');
    
            $arr = explode(" ",$yearmonth);
    
            $yeardata = $arr[1];
            $monthdata = $arr[0];
            
            // Retrieve and display data based on the POST data
        }
    
        
        $data['yearmonth']=$yearmonth;

        $data['prevyearmonth']=$prevyearmonth;
    
        $data['yeardata']=$yeardata;
    
        $data['monthdata']=$monthdata;
    
        $query = "SELECT * FROM `ranking_course` ORDER by ranking_course";


        $data['courses'] = DB::select($query);
                                
    
    
        return view('rankings.monthlyrankingdetailedreport',$data);
    
    }


    public function monthlydetailedseoreport($yearmonth,$client,$location)
    {
    
        $data['pageTitle']= 'Seo Report';
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
        

        $yearmonthar = explode(" ",$yearmonth);

        $data['month'] = $yearmonthar[0];

        $data['year'] = $yearmonthar[1];

        $data['client'] = $client;

        $data['location'] = $location;

        return view('rankings.monthlyseodetailedreport',$data);
    }


public function getelementrankings(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $client = $request->input('client');
    

        $impressionelement= DB::table('ranking_element')
                            ->leftJoin('monthly_element_rankings', function ($join) use ($year,$month) {
                                $join->on('monthly_element_rankings.element_id', '=', 'ranking_element.id')
                                    ->where('monthly_element_rankings.year', '=', $year)
                                    ->where('monthly_element_rankings.month', '=', $month);
                            })
                            ->select('ranking_element.id','ranking_element.ranking_element','ranking_element.increase_percent','monthly_element_rankings.google_rank','monthly_element_rankings.google_rank_prev')
                            ->where('ranking_element.client', '=',$client)
                            ->where('ranking_element.elements_type', '=',0)
                            ->orderBy('ranking_element.elements_type')
                            ->get();  

        $trafficelement= DB::table('ranking_element')
                            ->leftJoin('monthly_element_rankings', function ($join) use ($year,$month) {
                                $join->on('monthly_element_rankings.element_id', '=', 'ranking_element.id')
                                    ->where('monthly_element_rankings.year', '=', $year)
                                    ->where('monthly_element_rankings.month', '=', $month);
                            })
                            ->select('ranking_element.id','ranking_element.ranking_element','ranking_element.increase_percent','monthly_element_rankings.google_rank','monthly_element_rankings.google_rank_prev')
                            ->where('ranking_element.client', '=',$client)
                            ->where('ranking_element.elements_type', '=',1)
                            ->orderBy('ranking_element.elements_type')
                            ->get();  
        
                            
        return Reply::dataOnly(['status' => 'success','impressionelement' => $impressionelement,'trafficelement' => $trafficelement]);
        
    }

    public function getcountryrankings(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $client = $request->input('client');
    

        $rankingcountry= DB::table('ranking_countries')
                            ->leftJoin('monthly_country_rankings', function ($join) use ($year,$month) {
                                $join->on('monthly_country_rankings.country_id', '=', 'ranking_countries.id')
                                    ->where('monthly_country_rankings.year', '=', $year)
                                    ->where('monthly_country_rankings.month', '=', $month);
                            })
                            ->where('ranking_countries.client', '=', $client)
                            ->select('ranking_countries.id','ranking_countries.ranking_country','ranking_countries.increase_percent','monthly_country_rankings.google_rank','monthly_country_rankings.google_rank_prev')
                            ->get();  
        
                            
        return Reply::dataOnly(['status' => 'success','country' => $rankingcountry]);
        
    }

    public function gettoppages(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $client = $request->input('client');

        $toppages= DB::table('monthly_toppages')
                            ->where('monthly_toppages.year', '=', $year)
                            ->where('monthly_toppages.month', '=', $month)
                            ->where('monthly_toppages.client', '=', $client)
                            ->select('monthly_toppages.id','monthly_toppages.url','monthly_toppages.clicks','monthly_toppages.month','monthly_toppages.year')
                            ->get();  
        
                            
        return Reply::dataOnly(['status' => 'success','toppages' => $toppages]);
        
    }

    public function getkeywordrankings(Request $request){
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $premonth = $request->input('premonth');
        $preyear = $request->input('preyear');
        $client = $request->input('client');
        $location = $request->input('location');



        if($client == "TIMEMASTER" && $location != "ALL"){


            $rankingkeyword= DB::table('ranking_keyword')
            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
            ->leftJoin('monthy_keyword_rankings', function ($join) use ($year,$month) {
                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                    ->where('monthy_keyword_rankings.year', '=', $year)
                    ->where('monthy_keyword_rankings.month', '=', $month);
            })
            ->leftJoin('monthy_keyword_rankings as mkr', function ($join) use ($preyear,$premonth) {
                $join->on('mkr.keyword_id', '=', 'ranking_keyword.id')
                    ->where('mkr.year', '=', $preyear)
                    ->where('mkr.month', '=', $premonth);
            })
            ->where('ranking_keyword.client', '=', $client)
            ->where('ranking_keyword.location', '=', $location)
            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank','monthy_keyword_rankings.googlemap_rank','mkr.google_rank as prerank','mkr.googlemap_rank as premaprank')
            ->orderby('ranking_keyword.ranking_keyword')
            ->get();   


        }else{

            $rankingkeyword= DB::table('ranking_keyword')
                            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                            ->leftJoin('monthy_keyword_rankings', function ($join) use ($year,$month) {
                                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                                    ->where('monthy_keyword_rankings.year', '=', $year)
                                    ->where('monthy_keyword_rankings.month', '=', $month);
                            })
                            ->leftJoin('monthy_keyword_rankings as mkr', function ($join) use ($preyear,$premonth) {
                                $join->on('mkr.keyword_id', '=', 'ranking_keyword.id')
                                    ->where('mkr.year', '=', $preyear)
                                    ->where('mkr.month', '=', $premonth);
                            })
                            ->where('ranking_keyword.client', '=', $client)
                            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank','monthy_keyword_rankings.googlemap_rank','mkr.google_rank as prerank','mkr.googlemap_rank as premaprank')
                            ->orderby('ranking_keyword.ranking_keyword')
                            ->get();   


        }
        
    

        
        
                            
        return Reply::dataOnly(['status' => 'success','keyword' => $rankingkeyword]);
        
    }

    public function getkeywordrangerankings(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $premonth = $request->input('premonth');
        $preyear = $request->input('preyear');
        $client = $request->input('client');

        $location = $request->input('location');



        if($client == "TIMEMASTER" && $location != "ALL"){

            

            $prevmonthQuery = DB::table('ranking_keyword')
            ->leftJoin('ranking_course as rc', 'rc.id', '=', 'ranking_keyword.ranking_course')
            ->leftJoin('monthy_keyword_rankings as mkr', function ($join) use ($premonth,$preyear){
                $join->on('mkr.keyword_id', '=', 'ranking_keyword.id')
                    ->where('mkr.month', '=', $premonth)
                    ->where('mkr.year', '=', $preyear);
            })
            ->where('ranking_keyword.client', '=', $client)
            ->where('ranking_keyword.location', '=', $location)
            ->selectRaw('mkr.month,
                SUM(CASE WHEN mkr.google_rank BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS count_1_to_5,
                SUM(CASE WHEN mkr.google_rank BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS count_6_to_10,
                SUM(CASE WHEN mkr.google_rank BETWEEN 11 AND 20 THEN 1 ELSE 0 END) AS count_11_to_20,
                SUM(CASE WHEN mkr.google_rank BETWEEN 21 AND 30 THEN 1 ELSE 0 END) AS count_21_to_30,
                SUM(CASE WHEN mkr.google_rank > 30 THEN 1 ELSE 0 END) AS count_31greater
            ');

            $curmonthQuery = DB::table('ranking_keyword')
            ->leftJoin('ranking_course as rc', 'rc.id', '=', 'ranking_keyword.ranking_course')
            ->leftJoin('monthy_keyword_rankings as mkr', function ($join) use ($month,$year){
                $join->on('mkr.keyword_id', '=', 'ranking_keyword.id')
                    ->where('mkr.month', '=', $month)
                    ->where('mkr.year', '=', $year);
            })
            ->where('ranking_keyword.client', '=', $client)
            ->where('ranking_keyword.location', '=', $location)
            ->selectRaw('mkr.month,
                SUM(CASE WHEN mkr.google_rank BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS count_1_to_5,
                SUM(CASE WHEN mkr.google_rank BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS count_6_to_10,
                SUM(CASE WHEN mkr.google_rank BETWEEN 11 AND 20 THEN 1 ELSE 0 END) AS count_11_to_20,
                SUM(CASE WHEN mkr.google_rank BETWEEN 21 AND 30 THEN 1 ELSE 0 END) AS count_21_to_30,
                SUM(CASE WHEN mkr.google_rank > 30 THEN 1 ELSE 0 END) AS count_31greater
            '); 




        }else{
        
        $prevmonthQuery = DB::table('ranking_keyword')
                ->leftJoin('ranking_course as rc', 'rc.id', '=', 'ranking_keyword.ranking_course')
                ->leftJoin('monthy_keyword_rankings as mkr', function ($join) use ($premonth,$preyear){
                    $join->on('mkr.keyword_id', '=', 'ranking_keyword.id')
                        ->where('mkr.month', '=', $premonth)
                        ->where('mkr.year', '=', $preyear);
                })
                ->where('ranking_keyword.client', '=', $client)
                ->selectRaw('mkr.month,
                    SUM(CASE WHEN mkr.google_rank BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS count_1_to_5,
                    SUM(CASE WHEN mkr.google_rank BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS count_6_to_10,
                    SUM(CASE WHEN mkr.google_rank BETWEEN 11 AND 20 THEN 1 ELSE 0 END) AS count_11_to_20,
                    SUM(CASE WHEN mkr.google_rank BETWEEN 21 AND 30 THEN 1 ELSE 0 END) AS count_21_to_30,
                    SUM(CASE WHEN mkr.google_rank > 30 THEN 1 ELSE 0 END) AS count_31greater
                ');

        $curmonthQuery = DB::table('ranking_keyword')
                ->leftJoin('ranking_course as rc', 'rc.id', '=', 'ranking_keyword.ranking_course')
                ->leftJoin('monthy_keyword_rankings as mkr', function ($join) use ($month,$year){
                    $join->on('mkr.keyword_id', '=', 'ranking_keyword.id')
                        ->where('mkr.month', '=', $month)
                        ->where('mkr.year', '=', $year);
                })
                ->where('ranking_keyword.client', '=', $client)
                ->selectRaw('mkr.month,
                    SUM(CASE WHEN mkr.google_rank BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS count_1_to_5,
                    SUM(CASE WHEN mkr.google_rank BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS count_6_to_10,
                    SUM(CASE WHEN mkr.google_rank BETWEEN 11 AND 20 THEN 1 ELSE 0 END) AS count_11_to_20,
                    SUM(CASE WHEN mkr.google_rank BETWEEN 21 AND 30 THEN 1 ELSE 0 END) AS count_21_to_30,
                    SUM(CASE WHEN mkr.google_rank > 30 THEN 1 ELSE 0 END) AS count_31greater
                '); 

        }

        $results = $prevmonthQuery->unionAll($curmonthQuery)->get();
        
                            
        return Reply::dataOnly(['status' => 'success','keywordrange' => $results]);
        
    }

    public function getdropedniche(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $premonth = $request->input('premonth');
        $preyear = $request->input('preyear');
        $client =  $request->input('client');
        $location =  $request->input('location');

        if($client == "TIMEMASTER" && $location != "ALL"){

            $droppedniche = DB::table('ranking_keyword')
                        ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                        ->Join('monthy_keyword_rankings', function ($join) use ($preyear,$premonth) {
                            $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                                ->where('monthy_keyword_rankings.year', '=', $preyear)
                                ->where('monthy_keyword_rankings.month', '=', $premonth);
                        })
                        ->Join('monthy_keyword_rankings as mkr', function ($join) use ($year,$month) {
                            $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                                ->where('mkr.year', '=', $year)
                                ->where('mkr.month', '=', $month)
                                ->whereRaw('mkr.google_rank > monthy_keyword_rankings.google_rank');
                        })
                        ->where('ranking_keyword.client', '=', $client)
                        ->where('ranking_keyword.location', '=', $location)
                        ->select('ranking_course.ranking_course')
                            ->groupBy('ranking_course.ranking_course')
                            ->havingRaw('COUNT(ranking_course.ranking_course) > 1')
                            ->get();  


        }else{

        $droppedniche = DB::table('ranking_keyword')
                        ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                        ->Join('monthy_keyword_rankings', function ($join) use ($preyear,$premonth) {
                            $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                                ->where('monthy_keyword_rankings.year', '=', $preyear)
                                ->where('monthy_keyword_rankings.month', '=', $premonth);
                        })
                        ->Join('monthy_keyword_rankings as mkr', function ($join) use ($year,$month) {
                            $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                                ->where('mkr.year', '=', $year)
                                ->where('mkr.month', '=', $month)
                                ->whereRaw('mkr.google_rank > monthy_keyword_rankings.google_rank');
                        })
                        ->where('ranking_keyword.client', '=', $client)
                        ->select('ranking_course.ranking_course')
                            ->groupBy('ranking_course.ranking_course')
                            ->havingRaw('COUNT(ranking_course.ranking_course) > 1')
                            ->get();  

        }
        
                            
        return Reply::dataOnly(['status' => 'success','droppedniche' => $droppedniche]);
        
    }


    public function getdropednooneword(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $premonth = $request->input('premonth');
        $preyear = $request->input('preyear');
        $client =  $request->input('client');
        $location =  $request->input('location');

        if($client == "TIMEMASTER" && $location != "ALL"){


            $droppednonone = DB::table('ranking_keyword')
                            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                            ->Join('monthy_keyword_rankings', function ($join) use ($preyear,$premonth) {
                                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                                    ->where('monthy_keyword_rankings.year', '=', $preyear)
                                    ->where('monthy_keyword_rankings.month', '=', $premonth)
                                    ->where('monthy_keyword_rankings.google_rank', '=',1);
                            })
                            ->Join('monthy_keyword_rankings as mkr', function ($join) use ($year,$month) {
                                $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                                    ->where('mkr.year', '=', $year)
                                    ->where('mkr.month', '=', $month)
                                    ->where('mkr.google_rank', '>', 1);
                            })
                            ->where('ranking_keyword.client', '=', $client)
                            ->where('ranking_keyword.location', '=', $location)
                            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank','mkr.google_rank as dropped')
                            ->get();  


        }else{

        $droppednonone = DB::table('ranking_keyword')
                            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                            ->Join('monthy_keyword_rankings', function ($join) use ($preyear,$premonth) {
                                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                                    ->where('monthy_keyword_rankings.year', '=', $preyear)
                                    ->where('monthy_keyword_rankings.month', '=', $premonth)
                                    ->where('monthy_keyword_rankings.google_rank', '=',1);
                            })
                            ->Join('monthy_keyword_rankings as mkr', function ($join) use ($year,$month) {
                                $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                                    ->where('mkr.year', '=', $year)
                                    ->where('mkr.month', '=', $month)
                                    ->where('mkr.google_rank', '>', 1);
                            })
                            ->where('ranking_keyword.client', '=', $client)
                            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank','mkr.google_rank as dropped')
                            ->get();  
                            
        }
        
                            
        return Reply::dataOnly(['status' => 'success','droppednonone' => $droppednonone]);
        
    }

    public function getupnooneword(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $premonth = $request->input('premonth');
        $preyear = $request->input('preyear');
        $client =  $request->input('client');
        $location =  $request->input('location');


        if($client == "TIMEMASTER" && $location != "ALL"){


            $upnonone = DB::table('ranking_keyword')
            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
            ->Join('monthy_keyword_rankings', function ($join) use ($preyear,$premonth) {
                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                    ->where('monthy_keyword_rankings.year', '=', $preyear)
                    ->where('monthy_keyword_rankings.month', '=', $premonth)
                    ->where('monthy_keyword_rankings.google_rank', '>',1);
            })
            ->Join('monthy_keyword_rankings as mkr', function ($join) use ($year,$month) {
                $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                    ->where('mkr.year', '=', $year)
                    ->where('mkr.month', '=', $month)
                    ->where('mkr.google_rank', '=', 1);
            })
            ->where('ranking_keyword.client', '=', $client)
            ->where('ranking_keyword.location', '=', $location)
            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank as prevposition','mkr.google_rank as upposition')
            ->get();  



        }else{


        $upnonone = DB::table('ranking_keyword')
                            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                            ->Join('monthy_keyword_rankings', function ($join) use ($preyear,$premonth) {
                                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                                    ->where('monthy_keyword_rankings.year', '=', $preyear)
                                    ->where('monthy_keyword_rankings.month', '=', $premonth)
                                    ->where('monthy_keyword_rankings.google_rank', '>',1);
                            })
                            ->Join('monthy_keyword_rankings as mkr', function ($join) use ($year,$month) {
                                $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                                    ->where('mkr.year', '=', $year)
                                    ->where('mkr.month', '=', $month)
                                    ->where('mkr.google_rank', '=', 1);
                            })
                            ->where('ranking_keyword.client', '=', $client)
                            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank as prevposition','mkr.google_rank as upposition')
                            ->get();   

        }
        
                            
        return Reply::dataOnly(['status' => 'success','upnonone' => $upnonone]);
        
    }

    public function getuptwotofiveword(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $premonth = $request->input('premonth');
        $preyear = $request->input('preyear');
        $client =  $request->input('client');
        $location =  $request->input('location');


        if($client == "TIMEMASTER" && $location != "ALL"){


            $uptotwotofive = DB::table('ranking_keyword')
            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
            ->Join('monthy_keyword_rankings', function ($join) use ($year,$month) {
                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                    ->where('monthy_keyword_rankings.year', '=', $year)
                    ->where('monthy_keyword_rankings.month', '=', $month)
                    ->whereIn('monthy_keyword_rankings.google_rank',array(2,3,4,5));
            })
            ->Join('monthy_keyword_rankings as mkr', function ($join) use ($preyear,$premonth) {
                $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                    ->where('mkr.year', '=', $preyear)
                    ->where('mkr.month', '=', $premonth)
                    ->whereRaw('mkr.google_rank > monthy_keyword_rankings.google_rank');
            })
            ->where('ranking_keyword.client', '=', $client)
            ->where('ranking_keyword.location', '=', $location)
            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank as currentposition','mkr.google_rank as prevposition')
            ->get();  






        }else{



            $uptotwotofive = DB::table('ranking_keyword')
            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
            ->Join('monthy_keyword_rankings', function ($join) use ($year,$month) {
                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                    ->where('monthy_keyword_rankings.year', '=', $year)
                    ->where('monthy_keyword_rankings.month', '=', $month)
                    ->whereIn('monthy_keyword_rankings.google_rank',array(2,3,4,5));
            })
            ->Join('monthy_keyword_rankings as mkr', function ($join) use ($preyear,$premonth) {
                $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                    ->where('mkr.year', '=', $preyear)
                    ->where('mkr.month', '=', $premonth)
                    ->whereRaw('mkr.google_rank > monthy_keyword_rankings.google_rank');
            })
            ->where('ranking_keyword.client', '=', $client)
            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank as currentposition','mkr.google_rank as prevposition')
            ->get();   

        }


        
                            
        return Reply::dataOnly(['status' => 'success','uptotwotofive' => $uptotwotofive]);
        
    }


    public function getdropedfromtwoword(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $premonth = $request->input('premonth');
        $preyear = $request->input('preyear');
        $client =  $request->input('client');
        $location =  $request->input('location');


        if($client == "TIMEMASTER" && $location != "ALL"){


            $dropfromtwo = DB::table('ranking_keyword')
            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
            ->Join('monthy_keyword_rankings', function ($join) use ($preyear,$premonth) {
                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                    ->where('monthy_keyword_rankings.year', '=', $preyear)
                    ->where('monthy_keyword_rankings.month', '=', $premonth)
                    ->whereIn('monthy_keyword_rankings.google_rank',array(2,3,4,5));
            })
            ->Join('monthy_keyword_rankings as mkr', function ($join) use ($year,$month) {
                $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                    ->where('mkr.year', '=', $year)
                    ->where('mkr.month', '=', $month)
                    ->whereRaw('mkr.google_rank > monthy_keyword_rankings.google_rank');
            })
            ->where('ranking_keyword.client', '=', $client)
            ->where('ranking_keyword.location', '=', $location)
            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank','mkr.google_rank as dropped')
            ->get(); 



        }else{


            $dropfromtwo = DB::table('ranking_keyword')
            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
            ->Join('monthy_keyword_rankings', function ($join) use ($preyear,$premonth) {
                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                    ->where('monthy_keyword_rankings.year', '=', $preyear)
                    ->where('monthy_keyword_rankings.month', '=', $premonth)
                    ->whereIn('monthy_keyword_rankings.google_rank',array(2,3,4,5));
            })
            ->Join('monthy_keyword_rankings as mkr', function ($join) use ($year,$month) {
                $join->on('mkr.keyword_id', '=', 'monthy_keyword_rankings.keyword_id')
                    ->where('mkr.year', '=', $year)
                    ->where('mkr.month', '=', $month)
                    ->whereRaw('mkr.google_rank > monthy_keyword_rankings.google_rank');
            })
            ->where('ranking_keyword.client', '=', $client)
            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank','mkr.google_rank as dropped')
            ->get();    

        }

                            
        return Reply::dataOnly(['status' => 'success','dropfromtwo' => $dropfromtwo]);
        
    }


public function toppagesbyclick(Request $request)
{

    $data['pageTitle']= 'Top Pages By Clicks';
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



    $yeardata = date('Y',strtotime('-1 month'));

    $monthdata = date('F',strtotime('-1 month'));

    $yearmonth = date('F Y',strtotime('-1 month'));
    

    if ($request->isMethod('post')) {

        // Handle POST request

        $yearmonth = $request->input('yearmonth');

        $arr = explode(" ",$yearmonth);

        $yeardata = $arr[1];
        $monthdata = $arr[0];
        
        // Retrieve and display data based on the POST data
    }

    
    $data['yearmonth']=$yearmonth;

    $data['yeardata']=$yeardata;

    $data['monthdata']=$monthdata;

                      
    return view('rankings.monthlytoppages',$data);

}


public function storetoppages(Request $request)
{
   

    $yearmonths = $request->yearmonth;
    $urls = $request->url;
    $clicks = $request->clicks;
    $client = $request->client;


    foreach ($yearmonths as $index => $value) {
        if ($value != '') {

        
            $yearmontharray= explode(" ",$yearmonths[$index]);

            $data['toppages'] = [
        
                'url' => $urls[$index],
                'clicks' => $clicks[$index],
                'month' => $yearmontharray[0],
                'year' => $yearmontharray[1],
                'client' => $client[$index],
            
            ];

            DB::table('monthly_toppages')->insert($data['toppages']);
 
        }
    }

    $redirectUrl = route('rankings.toppagesbyclick');
 
    return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
        
        

}



public function monthlyenquiryreport(Request $request)
{

    $data['pageTitle']= 'Enquiry Report';
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



    $data['date2'] = date("Y-m-d"); 

    
    $data['date1'] = date("Y-m-d", strtotime("-1 month", strtotime($data['date2'])));
    

    return view('rankings.monthlyenquiryreport',$data);

}



public function dailytaskreport(Request $request)
{

    $data['pageTitle']= 'Daily Task Report';
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



    $data['date2'] = date("Y-m-d"); 

    
    $data['date1'] = date("Y-m-d", strtotime("-1 month", strtotime($data['date2'])));


    return view('rankings.dailytaskreport',$data);

}

public function weeklytaskreport(Request $request)
{

    $data['pageTitle']= 'Weekly Task Report';
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



    $data['date2'] = date("Y-m-d"); 

    
    $data['date1'] = date("Y-m-d", strtotime("-1 week", strtotime($data['date2'])));

    $data['employees'] = DB::select("SELECT users.name,users.id FROM `users`
                                    where status = 'active' 
                                    and user_auth_id is not null
                                    and company_id=4;");


    return view('rankings.weeklytaskreport',$data);

}



public function monthlyadcampaign(Request $request)
{

    $data['pageTitle']= 'AD Campaign';
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


    $url="https://edoxi.cyradrive.com/hrapi/getadcampaign";


    $yeardata = date('Y',strtotime('-1 month'));

    $monthdata = date('F',strtotime('-1 month'));

    $yearmonth = date('F Y',strtotime('-1 month'));

    $monthfirst = date('Y-m-01',strtotime('-1 month')); 

    $monthlast = date('Y-m-t',strtotime('-1 month')); 

    

    if ($request->isMethod('post')) {

        // Handle POST request

        $yearmonth = $request->input('yearmonth');

        $arr = explode(" ",$yearmonth);

        $yeardata = $arr[1];
        $monthdata = $arr[0];

        $monthfirst = date('Y-m-01',strtotime($monthdata.'01,'.$yeardata)); 

        $monthlast = date('Y-m-t',strtotime($monthdata.'01,'.$yeardata)); 

    }


    $dateparam = array(
        'param1' => $monthfirst,
        'param2' => $monthlast
    );
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dateparam)); // Set POST data
    $response = curl_exec($ch);
    $data['response'] = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    curl_close($ch);

    $data['campaign'] = $data['response']['campaign'];

    $campaign = $data['campaign'];

    $campaignarray = array();

foreach ($campaign as $value) { 

    $query = "SELECT * FROM `monthly_adcampaign` WHERE month='$monthdata' AND year='$yeardata' AND campaign='{$value['utm_campaign']}'";

    $result = DB::select($query);


    if (count($result) == 0) {

        $campaignarray[] = array(
            "campaign" => $value['utm_campaign'],
            "budget" => "",
            "impr" => "",
            "cost" => "",
            "clicks" => "",
            "month" => $monthdata,
            "year" => $yeardata,
            "upd" => "0",
            "id" => "0"
        );

    } else {

        $row = $result[0];

        $campaignarray[] = array(
            "campaign" => $value['utm_campaign'],
            "budget" => $row->budget,
            "impr" => $row->impr,
            "cost" => $row->cost,
            "clicks" => $row->clicks,
            "month" => $monthdata,
            "year" => $yeardata,
            "upd" => "1",
            "id" => $row->id
        );

    }

}


    $data['campaignarray'] = $campaignarray;

    $data['yearmonth']=$yearmonth;

    $data['yeardata']=$yeardata;

    $data['monthdata']=$monthdata;


    return view('rankings.monthlyadcampaign',$data);

}


public function updateadcampaigns(Request $request) {

    
    $id = $request->input('id');
    $val = $request->input('val');
    $campaign = $request->input('campaign');
    $budget = $request->input('budget');
    $impr = $request->input('impr');
    $cost = $request->input('cost');
    $clicks = $request->input('clicks');
    $month = $request->input('month');
    $year = $request->input('year');
    $dataid = $request->input('dataid');


    if($val == "insert"){

        DB::table('monthly_adcampaign')->insert(['campaign' => $campaign,'budget' => $budget,'impr' => $impr,'cost' => $cost,'clicks' => $clicks,'month' => $month,'year' => $year]);

        $query = "SELECT max(id) as maxid from monthly_adcampaign";

        $result = DB::select($query);

        $row = $result[0];

        return Reply::dataOnly(['status' => 'success','maxid' => $row->maxid]);

    }else{

        DB::table('monthly_adcampaign')->where([
            ['id', '=', $dataid ]
        ])->update(['campaign' => $campaign,'budget' => $budget,'impr' => $impr,'cost' => $cost,'clicks' => $clicks,'month' => $month,'year' => $year]);

        return Reply::dataOnly(['status' => 'success']);
    
    }

    

    
}


public function monthlyadcampaignreport(Request $request)
{

    $data['pageTitle']= 'AD Campaign Report';
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


    $url="https://edoxi.cyradrive.com/hrapi/getadcampaignreport";


    $yeardata = date('Y',strtotime('-1 month'));

    $monthdata = date('F',strtotime('-1 month'));

    $yearmonth = date('F Y',strtotime('-1 month'));

    $monthfirst = date('Y-m-01',strtotime('-1 month')); 

    $monthlast = date('Y-m-t',strtotime('-1 month')); 

    

    if ($request->isMethod('post')) {

        // Handle POST request

        $yearmonth = $request->input('yearmonth');

        $arr = explode(" ",$yearmonth);

        $yeardata = $arr[1];
        $monthdata = $arr[0];

        $monthfirst = date('Y-m-01',strtotime($monthdata.'01,'.$yeardata)); 

        $monthlast = date('Y-m-t',strtotime($monthdata.'01,'.$yeardata)); 

    }


    $dateparam = array(
        'param1' => $monthfirst,
        'param2' => $monthlast
    );
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dateparam)); // Set POST data
    $response = curl_exec($ch);
    $data['response'] = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    curl_close($ch);

    $data['campaign'] = $data['response']['campaign'];

    $campaign = $data['campaign'];

    $campaignarray = array();

    foreach ($campaign as $value) { 

    // $query = "SELECT * FROM `monthly_adcampaign` WHERE month='$monthdata' AND year='$yeardata' AND campaign='{$value['utm_campaign']}'";

    $result = DB::select("SELECT * FROM monthly_adcampaign WHERE month = ? AND year = ? AND campaign = ?", [$monthdata, $yeardata, $value['utm_campaign']]);


    if (count($result) == 0) {

        $campaignarray[] = array(
            "campaign" => $value['utm_campaign'],
            "medium" => $value['utm_medium'],
            "budget" => "",
            "impr" => "",
            "cost" => "",
            "clicks" => "",
            "month" => $monthdata,
            "year" => $yeardata,
            "enquiry" => $value['count'],
            "converted" => $value['sales'],
            "trashed" => $value['trashed']
        );

    } else {

        $row = $result[0];

        $campaignarray[] = array(
            "campaign" => $value['utm_campaign'],
            "medium" => $value['utm_medium'],
            "budget" => $row->budget,
            "impr" => $row->impr,
            "cost" => $row->cost,
            "clicks" => $row->clicks,
            "month" => $monthdata,
            "year" => $yeardata,
            "enquiry" => $value['count'],
            "converted" => $value['sales'],
            "trashed" => $value['trashed']
        );

    }

}


    $data['campaignarray'] = $campaignarray;

    $data['yearmonth']=$yearmonth;

    $data['yeardata']=$yeardata;

    $data['monthdata']=$monthdata;


    return view('rankings.monthlyadcampaignreport',$data);

}


public function monthlymarketingsalesreport(Request $request)
{

    $data['pageTitle']= 'Marketing Sales Dashboard';
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



    $yeardata = date('Y',strtotime('-1 month'));

    $monthdata = date('F',strtotime('-1 month'));

    $yearmonth = date('F Y',strtotime('-1 month'));

    $prevyearmonth = date('F Y',strtotime('-2 month'));

    $monthfirst = date('Y-m-01',strtotime('-1 month')); 

    $monthlast = date('Y-m-t',strtotime('-1 month')); 
    

    if ($request->isMethod('post')) {

        // Handle POST request

        $yearmonth = $request->input('yearmonth');

        $arr = explode(" ",$yearmonth);

        $yeardata = $arr[1];
        $monthdata = $arr[0];

        $monthfirst = date('Y-m-01',strtotime($monthdata.'01,'.$yeardata)); 

        $monthlast = date('Y-m-t',strtotime($monthdata.'01,'.$yeardata)); 
        
        // Retrieve and display data based on the POST data
    }

    
    $data['yearmonth']=$yearmonth;

    $data['prevyearmonth']=$prevyearmonth;

    $data['yeardata']=$yeardata;

    $data['monthdata']=$monthdata;

    $query = "SELECT * FROM `ranking_course` ORDER by ranking_course";


    $data['courses'] = DB::select($query);
                            


    return view('rankings.marketingsalesreport',$data);

}

public function upgradation(Request $request)
{

    $data['pageTitle']= 'Upgradation Page Report';
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


    return view('reports.upgradationreport',$data);

}

public function getupgradation(Request $request){
    
        
    $completion = $request->input('completion');



        $upgradationqry= DB::table('projects as p')
        ->join('project_category as pc', 'pc.id', '=', 'p.category_id')
        ->leftJoin('tasks as t', function ($join) {
            $join->on('t.project_id', '=', 'p.id')
                ->where('t.heading', '=', 'publishing');
        })
        ->leftJoin('task_results as tr','tr.task_id', '=', 't.id')
        ->where('pc.category_name', '=', 'Course Page Upgradation/CTR improvement')
        ->where('p.completion_percent',($completion == "notfinished")?'!=':'=', 100)
        ->select('p.project_name','pc.category_name','p.status','t.heading','tr.result')
        ->get();   

                        
    return Reply::dataOnly(['status' => 'success','upgradation' => $upgradationqry]);
    
}


public function getdailytaskreport(Request $request){
    
        
    $date1 = $request->input('startdate');

    $date2 = $request->input('enddate');



    $dltask = DB::table('projects as p')
    ->select('pc.category_name', 'u.name as Client')
    ->selectSub(function ($query) use ($date1, $date2) {
        $query->select('tr.result')
            ->from('task_results as tr')
            ->where('tr.task_id', function ($subquery) {
                $subquery->select(DB::raw('min(tasks.id)'))
                    ->from('tasks')
                    ->whereRaw('tasks.project_id = p.id');
            })
            ->whereRaw('STR_TO_DATE(tr.created_at, "%Y-%m-%d") BETWEEN ? AND ?', [$date1, $date2]);
    }, 'result')
    ->selectSub(function ($query) {
        $query->select('users.name')
            ->from('task_results as res')
            ->join('users', 'res.user_id', '=', 'users.id')
            ->where('res.task_id', function ($subquery) {
                $subquery->select(DB::raw('min(ts.id)'))
                    ->from('tasks as ts')
                    ->whereRaw('ts.project_id = p.id');
            });
    }, 'Author')
    ->join('project_category as pc', 'p.category_id', '=', 'pc.id')
    ->join('tasks as t', 't.project_id', '=', 'p.id')  // Assuming this join is necessary
    ->join('users as u', 'u.id', '=', 'p.client_id')
    ->where('p.completion_percent', '=', 100)
    ->where('pc.dltask', '=', 1)
    ->groupBy('p.project_name', 'pc.category_name', 'u.name') // Add 'u.name' to group by client name
    ->orderByDesc('pc.category_name')
    ->get();

         

                        
    return Reply::dataOnly(['status' => 'success','dltask' => $dltask]);
    
}

public function getweeklytaskreport(Request $request){
    
        
    $date1 = $request->input('startdate');

    $date2 = $request->input('enddate');

    $empid = $request->input('empid');


    if($empid!=0){


        $taskcount = DB::select("SELECT
                SUM(CASE WHEN tc.column_name = 'Completed' THEN 1 ELSE 0 END) AS cmpcount,
                SUM(CASE WHEN tc.column_name <> 'Completed' THEN 1 ELSE 0 END) AS pendingcount,
                SUM(CASE When p.priority='1' Then 1 Else 0 End) as prcount
                FROM tasks t
                INNER JOIN taskboard_columns tc ON tc.id = t.board_column_id
                INNER JOIN projects p ON p.id = t.project_id
                LEFT join task_users tu on tu.task_id = t.id
                WHERE 
                tu.user_id='$empid' AND 
                (t.start_date BETWEEN '$date1' AND '$date2'
                OR t.due_date BETWEEN '$date1' AND '$date2');");


        $dltask = DB::select("SELECT 
        t.heading,
        p.project_name,
        t.start_date,
        t.due_date,
        GROUP_CONCAT(DISTINCT u.name ORDER BY u.name SEPARATOR ', ') AS users_names,
        tc.column_name AS taskstatus
        FROM tasks t
        INNER JOIN projects p ON p.id = t.project_id
        INNER JOIN task_users tu ON tu.task_id = t.id
        INNER JOIN users u ON u.id = tu.user_id
        INNER JOIN taskboard_columns tc ON tc.id = t.board_column_id
        WHERE 
        u.id='$empid' and
        (t.start_date BETWEEN '$date1' AND '$date2'
        OR t.due_date BETWEEN '$date1' AND '$date2')
        GROUP BY t.id");




    }else{


        $taskcount = DB::select("SELECT
    SUM(CASE WHEN tc.column_name = 'Completed' THEN 1 ELSE 0 END) AS cmpcount,
    SUM(CASE WHEN tc.column_name <> 'Completed' THEN 1 ELSE 0 END) AS pendingcount,
    SUM(CASE When p.priority='1' Then 1 Else 0 End) as prcount
    FROM tasks t
    INNER JOIN taskboard_columns tc ON tc.id = t.board_column_id
    INNER JOIN projects p ON p.id = t.project_id    
    WHERE 
    (t.start_date BETWEEN '$date1' AND '$date2'
    OR t.due_date BETWEEN '$date1' AND '$date2');");


        $dltask = DB::select("SELECT 
        t.heading,
        p.project_name,
        t.start_date,
        t.due_date,
        GROUP_CONCAT(DISTINCT u.name ORDER BY u.name SEPARATOR ', ') AS users_names,
        tc.column_name AS taskstatus
        FROM tasks t
        INNER JOIN projects p ON p.id = t.project_id
        INNER JOIN task_users tu ON tu.task_id = t.id
        INNER JOIN users u ON u.id = tu.user_id
        INNER JOIN taskboard_columns tc ON tc.id = t.board_column_id
        WHERE 
        t.start_date BETWEEN '$date1' AND '$date2'
        OR t.due_date BETWEEN '$date1' AND '$date2'
        GROUP BY t.id");



    }
 

                        
    return Reply::dataOnly(['status' => 'success','dltask' => $dltask,'taskcount' => $taskcount]);
    
}
    


}
