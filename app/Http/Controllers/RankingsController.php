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
    
    
    
        return view('rankings.rankingelementcreate',$data);
    }
    
    
    public function rankinelementedit($id)
    {
    
        $data['pageTitle']= 'Edit Ranking Element';
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
        
    
        $data['element'] =  DB::table('ranking_element')->where('id', $id)->first();
    
        $elementid = $data['element']->id;
    
        return view('rankings.rankingelementedit',$data);
    }
    
    
    public function rankingelementview()
    {
    
        $data['pageTitle']= 'Ranking Element';
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
        
    
        $data['rankingelement']= DB::table('ranking_element')->get();
        
    
        return view('rankings.rankingelementview',$data);
    }
    
    
    public function rankinelementstore(Request $request) {
    
    
       
        $data['rankingelement'] = [
        
            'ranking_element' => $request->input('element_name'),
            'increase_percent' => $request->input('increase_percent')
        
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
                ->update(['ranking_element' => $request->input('element_name'),'increase_percent' => $request->input('increase_percent')]);
        
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



    return view('rankings.rankingcountrycreate',$data);
}


public function rankincountryedit($id)
{

    $data['pageTitle']= 'Edit Ranking Country';
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
    

    $data['country'] =  DB::table('ranking_countries')->where('id', $id)->first();

    $elementid = $data['country']->id;

    return view('rankings.rankingcountryedit',$data);
}


public function rankingcountryview()
{

    $data['pageTitle']= 'Ranking Country';
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
    

    $data['rankingcountry']= DB::table('ranking_countries')->get();
    

    return view('rankings.rankingcountryview',$data);
}


public function rankincountrystore(Request $request) {


   
    $data['rankingcountry'] = [
    
        'ranking_country' => $request->input('country_name'),
        'increase_percent' => $request->input('increase_percent')
    
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
            ->update(['ranking_country' => $request->input('country_name'),'increase_percent' => $request->input('increase_percent')]);
    
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



    return view('rankings.rankingcoursecreate',$data);
}


public function rankincourseedit($id)
{

    $data['pageTitle']= 'Edit Ranking Course';
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
    

    $data['courses'] =  DB::table('ranking_course')->where('id', $id)->first();

    $courseid = $data['courses']->id;

    return view('rankings.rankingcourseedit',$data);
}


public function rankingcourseview()
{

    $data['pageTitle']= 'Ranking Course';
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


    $query = "SELECT * FROM `ranking_course`";


    $data['courses'] = DB::select($query);


    return view('rankings.rankingkeywordcreate',$data);
}

public function rankinkeywordedit($id)
{

    $data['pageTitle']= 'Edit Ranking Keyword';
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
    
    $query = "SELECT * FROM `ranking_course`";

    $data['courses'] = DB::select($query);

    $data['keyword'] = DB::table('ranking_keyword')
                        ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                        ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course')
                        ->where('ranking_keyword.id', $id)->first();


    return view('rankings.rankingkeywordedit',$data);
}


public function rankinkeywordupdate(Request $request){


    try{

    // Update data into the persona table
    DB::table('ranking_keyword')
            ->where('id', $request->input('keyword_id'))
            ->update(['ranking_course' => $request->input('course_name'),'ranking_keyword' => $request->input('keyword_name'),'search_volume' => $request->input('search_volume')]);
    
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
    

    $data['rankingkeyword']= DB::table('ranking_keyword')
                            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course')
                            ->get();
    

    return view('rankings.rankingkeywordview',$data);
}



public function rankinkeywordstore(Request $request) {

   
    $data['rankingkeyword'] = [
    
        'ranking_keyword' => $request->input('keyword_name'),
        'ranking_course' => $request->input('course_name'),
        'search_volume' => $request->input('search_volume')
    
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

    $data['rankingkeyword']= DB::table('ranking_keyword')
                            ->join('ranking_course', 'ranking_keyword.ranking_course', '=', 'ranking_course.id')
                            ->leftJoin('monthy_keyword_rankings', function ($join) use ($yeardata,$monthdata) {
                                $join->on('monthy_keyword_rankings.keyword_id', '=', 'ranking_keyword.id')
                                    ->where('monthy_keyword_rankings.year', '=', $yeardata)
                                    ->where('monthy_keyword_rankings.month', '=', $monthdata);
                            })
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

    $data['rankingelement']= DB::table('ranking_element')
                            ->leftJoin('monthly_element_rankings', function ($join) use ($yeardata,$monthdata) {
                                $join->on('monthly_element_rankings.element_id', '=', 'ranking_element.id')
                                    ->where('monthly_element_rankings.year', '=', $yeardata)
                                    ->where('monthly_element_rankings.month', '=', $monthdata);
                            })
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
    
        $data['rankingcountry']= DB::table('ranking_countries')
                                ->leftJoin('monthly_country_rankings', function ($join) use ($yeardata,$monthdata) {
                                    $join->on('monthly_country_rankings.country_id', '=', 'ranking_countries.id')
                                        ->where('monthly_country_rankings.year', '=', $yeardata)
                                        ->where('monthly_country_rankings.month', '=', $monthdata);
                                })
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
    
        $data['pageTitle']= 'Ranking Report';
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
    
        $query = "SELECT * FROM `ranking_course`";


        $data['courses'] = DB::select($query);
                                
    
    
        return view('rankings.monthlyrankingreport',$data);
    
    }


public function getelementrankings(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
    

        $rankingelement= DB::table('ranking_element')
                            ->leftJoin('monthly_element_rankings', function ($join) use ($year,$month) {
                                $join->on('monthly_element_rankings.element_id', '=', 'ranking_element.id')
                                    ->where('monthly_element_rankings.year', '=', $year)
                                    ->where('monthly_element_rankings.month', '=', $month);
                            })
                            ->select('ranking_element.id','ranking_element.ranking_element','ranking_element.increase_percent','monthly_element_rankings.google_rank','monthly_element_rankings.google_rank_prev')
                            ->get();  
        
                            
        return Reply::dataOnly(['status' => 'success','element' => $rankingelement]);
        
    }

    public function getcountryrankings(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
    

        $rankingcountry= DB::table('ranking_countries')
                            ->leftJoin('monthly_country_rankings', function ($join) use ($year,$month) {
                                $join->on('monthly_country_rankings.country_id', '=', 'ranking_countries.id')
                                    ->where('monthly_country_rankings.year', '=', $year)
                                    ->where('monthly_country_rankings.month', '=', $month);
                            })
                            ->select('ranking_countries.id','ranking_countries.ranking_country','ranking_countries.increase_percent','monthly_country_rankings.google_rank','monthly_country_rankings.google_rank_prev')
                            ->get();  
        
                            
        return Reply::dataOnly(['status' => 'success','country' => $rankingcountry]);
        
    }

    public function getkeywordrankings(Request $request) {
    
        
        $month = $request->input('month');
        $year = $request->input('year');
        $premonth = $request->input('premonth');
        $preyear = $request->input('preyear');
    

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
                            ->select('ranking_keyword.id','ranking_keyword.ranking_keyword','ranking_keyword.search_volume','ranking_course.ranking_course','monthy_keyword_rankings.google_rank','monthy_keyword_rankings.googlemap_rank','mkr.google_rank as prerank','mkr.googlemap_rank as premaprank')
                            ->get();   
        
                            
        return Reply::dataOnly(['status' => 'success','keyword' => $rankingkeyword]);
        
    }






    


}
