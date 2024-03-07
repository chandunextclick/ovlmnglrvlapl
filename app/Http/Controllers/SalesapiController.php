<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helper\Reply;

use Mail;

class SalesapiController 
{


    public function salestasksdata(Request $request){


        $date = $request->input('date');
        $tasktitle = $request->input('tasktitle');
        $desc = $request->input('desc');
        $deadline = $request->input('deadline');
        $refid = $request->input('refid');        

        $result = DB::table('sales_tasks')->insert(['date' => $date,'tasktitle' => $tasktitle,'description' => $desc,'deadline' => $deadline,'refid' => $refid]);

        var_dump($result);

        return Reply::dataOnly(['status' => 'success','result'=>$result]);
    }

}
