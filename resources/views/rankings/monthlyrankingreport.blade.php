@extends('layouts.app')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@2.1.25/daterangepicker.css" />

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>

input[type="date"] {
            /* Add a border with a shadow effect */
            border: 1px solid #ccc;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            padding: 5px; /* Add some padding for better appearance */
            width:180px;
        }
        
        ol li {list-style-type: disc;}

        .dataTables_length{

            display:none;
}


</style>




@section('content')

<div class="container pt-5">
  <div class="row">
    <div class="col-md-12 card">
    <h4 class="mt-4">SEO Brief Report <span id="span-brief"><?=$yearmonth?></span></h4>
    <h4 class="mt-4">Country Wise Traffic Report <span id="span-country"><?=$yearmonth?></span></h4>
    <div class="row">
        <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4" placeholder="yearmonth"  name="elementyearmonth" id="elementyearmonth"  required>
                            
                            <?php
                            
                            $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 4 months');
                            $end = strtotime(date('Y').'-'.date('m').'-'.date('j').' + 1 months');
                            while($month < $end){
                
                                $selected = (date('F Y', $month)==$yearmonth)? ' selected' :'';
                
                            ?>
                
                                            <option <?= $selected ?> value="<?= date('F Y', $month) ?>"><?=date('F Y', $month)?></option>
                
                            <?php
                            $month = strtotime("+1 month", $month);
                            }
                            ?>
    </select> 
    </div>
    <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4" placeholder="client"  name="elementclient" id="elementclient"  required>                 
                            <option value="EDOXI">EDOXI</option>
                            <option value="TIMEMASTER">TIME MASTER</option>
                            <option value="TIMETRAINING">TIME TRAINING</option>                
    </select>  
    </div>
    </div>
    <table id="elementimp" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Elements</th>
                <th>Target<br>(Increase percent)</th>
                <th id="th-elyearmonth"><?= $yearmonth ?></th>
                <th id="th-elprevyearmonth"><?= $prevyearmonth ?></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    <table id="elementtr" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Elements</th>
                <th>Target<br>(Increase percent)</th>
                <th id="th-eltryearmonth"><?= $yearmonth ?></th>
                <th id="th-eltrprevyearmonth"><?= $prevyearmonth ?></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    </div>
    <div class="col-md-12 card mt-4">
    <h4 class="mt-4">Country Wise Traffic Report <span id="span-country"><?=$yearmonth?></span></h4>
    <div class="row">
        <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4" placeholder="yearmonth"  name="countryyearmonth" id="countryyearmonth"  required>
                            
                            <?php
                            
                            $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 4 months');
                            $end = strtotime(date('Y').'-'.date('m').'-'.date('j').' + 1 months');
                            while($month < $end){
                
                                $selected = (date('F Y', $month)==$yearmonth)? ' selected' :'';
                
                            ?>
                
                                            <option <?= $selected ?> value="<?= date('F Y', $month) ?>"><?=date('F Y', $month)?></option>
                
                            <?php
                            $month = strtotime("+1 month", $month);
                            }
                            ?>
    </select> 
    </div>
    <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4" placeholder="client"  name="countryclient" id="countryclient"  required>                 
                            <option value="EDOXI">EDOXI</option>
                            <option value="TIMEMASTER">TIME MASTER</option>
                            <option value="TIMETRAINING">TIME TRAINING</option>                
    </select>  
    </div>
    </div>
    <table id="country" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Country Name</th>
                <th>Target<br>(Increase percent)</th>
                <th id="th-ctyearmonth"><?= $yearmonth ?></th>
                <th id="th-ctprevyearmonth"><?= $prevyearmonth ?></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    </div>
    <div class="col-md-12 card mt-4">
    <h4 class="mt-4">Keyword Rankings <span id="span-country"><?=$yearmonth?></span></h4>
    <div class="row">
        <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4" placeholder="yearmonth"  name="keywordyearmonth" id="keywordyearmonth"  required>
                            
                            <?php
                            
                            $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 4 months');
                            $end = strtotime(date('Y').'-'.date('m').'-'.date('j').' + 1 months');
                            while($month < $end){
                
                                $selected = (date('F Y', $month)==$yearmonth)? ' selected' :'';
                
                            ?>
                
                                            <option <?= $selected ?> value="<?= date('F Y', $month) ?>"><?=date('F Y', $month)?></option>
                
                            <?php
                            $month = strtotime("+1 month", $month);
                            }
                            ?>
    </select> 
    </div>
    <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4" placeholder="client"  name="keywordclient" id="keywordclient"  required>                 
                            <option value="EDOXI">EDOXI</option>
                            <option value="TIMEMASTER">TIME MASTER</option>
                            <option value="TIMETRAINING">TIME TRAINING</option>                
    </select>  
    </div>
    <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4 d-none" placeholder="location"  name="keywordlocation" id="keywordlocation">                 
                            <option value="ALL">ALL</option>
                            <option value="AbuDhabi">Abu Dhabi</option>
                            <option value="Mussafah">Mussafah</option>                
    </select>  
    </div>
    </div>
    <table id="keywordrankings" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Month</th>
                <th>1 to 5</th>
                <th>6 to 10</th>
                <th>11 to 20</th>
                <th>21 to 30</th>
                <th>Above 31</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    </div>
    <div class="col-md-12 card mt-4">
    <h4 class="mt-4">Top Pages By Clicks <span id="span-toppages"><?=$yearmonth?></span></h4>
    <div class="row">
        <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4" placeholder="yearmonth"  name="toppagesyearmonth" id="toppagesyearmonth"  required>
                            
                            <?php
                            
                            $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 4 months');
                            $end = strtotime(date('Y').'-'.date('m').'-'.date('j').' + 1 months');
                            while($month < $end){
                
                                $selected = (date('F Y', $month)==$yearmonth)? ' selected' :'';
                
                            ?>
                
                                            <option <?= $selected ?> value="<?= date('F Y', $month) ?>"><?=date('F Y', $month)?></option>
                
                            <?php
                            $month = strtotime("+1 month", $month);
                            }
                            ?>
    </select> 
    </div>
    <div class="col-md-4">
    <select class="form-control height-35 f-14 mt-4" placeholder="client"  name="toppagesclient" id="toppagesclient"  required>                 
                            <option value="EDOXI">EDOXI</option>
                            <option value="TIMEMASTER">TIME MASTER</option>
                            <option value="TIMETRAINING">TIME TRAINING</option>                
    </select>  
    </div>
    </div>
    <table id="toppage" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>URL</th>
                <th>Clicks</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    </div>
    <div class="col-md-10 card m-5 ">
        <button class="bg-primary"><a href="<?=route('rankings.monthlydetailedrankingreport')?>" class="text-black">Detailed Report</a></button>
    <div>
  </div>
</div>

@endsection



<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script>


$(document).ready(function() {

// ------------------------------------------------------------------
// element data
// -------------------------------------------------------------------

    elimptable=new DataTable('#elementimp');

    eltrtable=new DataTable('#elementtr');


    var elementyearmonth = $('#elementyearmonth').val()

    var elementclient = $('#elementclient').val()

    const elementar = elementyearmonth.split(" ");

    var elementmonth = elementar[0];

    var elementyear = elementar[1];

    updateelementdata(elementmonth,elementyear,elementclient);

    $("#elementyearmonth,#elementclient").change(function(){


    var elementyearmonth = $('#elementyearmonth').val()

    var elementclient = $('#elementclient').val()

    const elementar = elementyearmonth.split(" ");

    var elementmonth = elementar[0];

    var elementyear = elementar[1];

    $("#th-elyearmonth").html($('#elementyearmonth').val())
    $("#th-elprevyearmonth").html(getPreviousMonth(elementmonth,elementyear))
    $("#th-eltryearmonth").html($('#elementyearmonth').val())
    $("#th-eltrprevyearmonth").html(getPreviousMonth(elementmonth,elementyear))

    $("#span-brief").html($('#elementyearmonth').val())

    updateelementdata(elementmonth,elementyear,elementclient);

    });

// ------------------------------------------------------------------
// country data
// -------------------------------------------------------------------

    cttable=new DataTable('#country');

    var countryyearmonth = $('#countryyearmonth').val()

    var countryclient = $('#countryclient').val()

    const countryar = countryyearmonth.split(" ");

    var countrymonth = countryar[0];

    var countryyear = countryar[1];

    updatecountrydata(countrymonth,countryyear,countryclient);

    $("#countryyearmonth,#countryclient").change(function(){

        var countryyearmonth = $('#countryyearmonth').val()

        var countryclient = $('#countryclient').val()

        const countryar = countryyearmonth.split(" ");

        var countrymonth = countryar[0];

        var countryyear = countryar[1];

        $("#th-ctyearmonth").html($('#countryyearmonth').val())
        $("#th-ctprevyearmonth").html(getPreviousMonth(countrymonth,countryyear))

        $("#span-country").html($('#countryyearmonth').val())

        updatecountrydata(countrymonth,countryyear,countryclient);

    });


    // ------------------------------------------------------------------
// Top pages data
// -------------------------------------------------------------------

toppagetable=new DataTable('#toppage');

var toppageyearmonth = $('#toppagesyearmonth').val()

var toppageclient = $('#toppagesclient').val()

const toppagear = toppageyearmonth.split(" ");

var toppagemonth = toppagear[0];

var toppageyear = toppagear[1];

updatetoppagedata(toppagemonth,toppageyear,toppageclient);

$("#toppagesyearmonth,#toppagesclient").change(function(){

    var toppageyearmonth = $('#toppagesyearmonth').val()

    var toppageclient = $('#toppagesclient').val()

    const toppagear = toppageyearmonth.split(" ");

    var toppagemonth = toppagear[0];

    var toppageyear = toppagear[1];

    $("#span-toppages").html($('#toppagesyearmonth').val())

    updatetoppagedata(toppagemonth,toppageyear,toppageclient);

});

// ------------------------------------------------------------------
// keyword data
// -------------------------------------------------------------------

keytable=new DataTable('#keywordrankings');

var keywordyearmonth = $('#keywordyearmonth').val()

var keywordclient = $('#keywordclient').val()

var keywordlocation = $('#keywordlocation').val()

const keywordar = keywordyearmonth.split(" ");

var keywordmonth = keywordar[0];

var keywordyear = keywordar[1];

var keywordpreviousmonth = getPreviousMonth(keywordmonth,keywordyear);

const keywordprear = keywordpreviousmonth.split(" ");

var keywordpremonth = keywordprear[0];

var keywordpreyear = keywordprear[1];

console.log(keywordpremonth,keywordpreyear);

updatekeyworddata(keywordmonth,keywordyear,keywordpremonth,keywordpreyear,keywordclient,keywordlocation);

$("#keywordyearmonth,#keywordclient,#keywordlocation").change(function(){




    var keywordyearmonth = $('#keywordyearmonth').val();

    var keywordclient = $('#keywordclient').val()

    if(keywordclient == "TIMEMASTER"){

        $('#keywordlocation').removeClass("d-none");

    }else{

        $('#keywordlocation').prop('selectedIndex',0);

        $('#keywordlocation').addClass("d-none");

    }


    var keywordlocation = $('#keywordlocation').val()

    const keywordar = keywordyearmonth.split(" ");

    var keywordmonth = keywordar[0];

    var keywordyear = keywordar[1];

    var keywordpreviousmonth = getPreviousMonth(keywordmonth,keywordyear);

    const keywordprear = keywordpreviousmonth.split(" ");

    var keywordpremonth = keywordprear[0];

    var keywordpreyear = keywordprear[1];

    $("#th-kwyearmonth").html($('#keywordyearmonth').val())
    $("#th-kwprevyearmonth").html(getPreviousMonth(keywordmonth,keywordyear))

    console.log(keywordpremonth,keywordpreyear);

    updatekeyworddata(keywordmonth,keywordyear,keywordpremonth,keywordpreyear,keywordclient,keywordlocation);

});


$("#myInputTextField").keyup(function(){

if($(this).val()==null){

    keytable.search("").draw();

}else{

    keytable.search($(this).val()).draw();
    
}



});

$("#course_name").change(function(){

    console.log($(this).val());

if($(this).val()==null){

    keytable.search("").draw();

}else{

    var value=$(this).val();

    valregex="";

    keytable.column(2).search($(this).val()).draw();
}



});



function getPreviousMonth(currentMonthString,currentYearString) {

    // Create a Date object for the current month
    var currentDate = new Date(currentMonthString + ' 1,' + currentYearString);
    
    // Move the date to the previous month
    currentDate.setMonth(currentDate.getMonth() - 1);
    
    // Format the previous month as a string
    var previousMonthString = currentDate.toLocaleString('default', { month: 'long' }) + ' ' + currentDate.getFullYear();
    
    return previousMonthString;
}



function updateelementdata(month,year,client){


let url = "{{ route('rankings.getelementrankings') }}";

console.log(url);

// Get CSRF token value
var csrfToken = $('meta[name="csrf-token"]').attr('content');

console.log(csrfToken);

$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': csrfToken
}
});


$.easyAjax({
        url: url,
        type: "POST",
        data: {
            _token: csrfToken,
            month:month,
            year:year,
            client:client
        },
        success: function(response) {

            if (response.status == 'success') {
                console.log(response);
                // console.log(response.element);
                elimptable.clear().draw();
                eltrtable.clear().draw();
                response.impressionelement.forEach((item) => {

                    // console.log(item.ranking_element);

                    elimptable.row.add([item.ranking_element,item.increase_percent,item.google_rank,item.google_rank_prev]).draw();

                });

           
                
                

                response.trafficelement.forEach((item) => {


                eltrtable.row.add([item.ranking_element,item.increase_percent,item.google_rank,item.google_rank_prev]).draw();

                });


            


            }
        }
 
    })


}

function updatecountrydata(month,year,client){


let url = "{{ route('rankings.getcountryrankings') }}";

console.log(url);

// Get CSRF token value
var csrfToken = $('meta[name="csrf-token"]').attr('content');

console.log(csrfToken);

$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': csrfToken
}
});


$.easyAjax({
        url: url,
        type: "POST",
        data: {
            _token: csrfToken,
            month:month,
            year:year,
            client:client,
        },
        success: function(response) {

            if (response.status == 'success') {
                
                // console.log(response.element);
                cttable.clear().draw();
                response.country.forEach((item) => {

                    // console.log(item.ranking_element);

                    cttable.row.add([item.ranking_country,item.increase_percent,item.google_rank,item.google_rank_prev]).draw();

                });

            


            }
        }
 
    })


}


function updatetoppagedata(month,year,client){


let url = "{{ route('rankings.gettoppages') }}";

console.log(url);

// Get CSRF token value
var csrfToken = $('meta[name="csrf-token"]').attr('content');

console.log(csrfToken);

$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': csrfToken
}
});


$.easyAjax({
        url: url,
        type: "POST",
        data: {
            _token: csrfToken,
            month:month,
            year:year,
            client:client,
        },
        success: function(response) {

            if (response.status == 'success') {
                
                // console.log(response.element);
                toppagetable.clear().draw();
                response.toppages.forEach((item) => {

                    // console.log(item.ranking_element);

                    toppagetable.row.add(['<a href='+item.url+' target="_blank">'+item.url+'</a>',item.clicks]).draw();

                });

            


            }
        }
 
    })


}


function updatekeyworddata(month,year,premonth,preyear,client,location){


let url = "{{ route('rankings.getkeywordrangerankings') }}";

console.log(url);

// Get CSRF token value
var csrfToken = $('meta[name="csrf-token"]').attr('content');

console.log(csrfToken);

$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': csrfToken
}
});


$.easyAjax({
        url: url,
        type: "POST",
        data: {
            _token: csrfToken,
            month:month,
            year:year,
            premonth:premonth,
            preyear:preyear,
            client:client,
            location:location,
        },
        success: function(response) {

            if (response.status == 'success') {
                
            
                keytable.clear().draw();
                response.keywordrange.forEach((item) => {

                    // console.log(item.ranking_element);

                    keytable.row.add([item.month,item.count_1_to_5,item.count_6_to_10,item.count_11_to_20,item.count_21_to_30,item.count_31greater]).draw();

                });

            


            }
        }
 
    })


}


  
});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









