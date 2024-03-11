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


</style>




@section('content')

<div class="container pt-5">
  <div class="row">
    <div class="col-md-6 card">
    <h4 class="mt-4">Element Monthly Rankings</h4>
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
    <table id="element" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Element ID</th>
                <th>Element Name</th>
                <th>Search Volume</th>
                <th>Rank</th>
                <th>Previous Rank</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    </div>
    <div class="col-md-6 card">
    <h4 class="mt-4">Coutry Monthly Rankings</h4>
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
    <table id="country" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Country ID</th>
                <th>Country Name</th>
                <th>Search Volume</th>
                <th>Rank</th>
                <th>Previous Rank</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    </div>
    <div class="col-md-12 card">
    <h4 class="mt-4">Keyword Monthly Rankings</h4>
    <div class="row">
        <div class="col-md-4">
        <select class="form-control height-35 f-14 mt-4" placeholder="yearmonth"  name="kewordyearmonth" id="keywordyearmonth"  required>
                            
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
        <div id="table-actions" class="flex-grow-1 align-items-center mt-4"> 
            <input type="text" id="myInputTextField" class="form-control height-35 f-14" placeholder="Search" autocomplete="off">
            </div>
        </div>  
        <div class="col-md-4">
        <select class="form-control height-35 f-14 mt-4" placeholder="Course"  name="course_name" id="course_name">
                            <option selected disabled>Select Course</option>
                            @foreach($courses as $course) 
    
                            <option value="{{$course->ranking_course}}">{{ $course->ranking_course }}</option>

                            @endforeach
                        </select> 
        </div>                  
    </div>

   
    <table id="keyword" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Keyword ID</th>
                <th>Course Name</th>
                <th>Keyword Name</th>
                <th>Search Volume</th>
                <th>Rank</th>
                <th>Previous Rank</th>
                <th>Google Map Rank</th>
                <th>Google Map Previous Rank</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    </div>
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

    eltable=new DataTable('#element');


    var elementyearmonth = $('#elementyearmonth').val()

    const elementar = elementyearmonth.split(" ");

    var elementmonth = elementar[0];

    var elementyear = elementar[1];

    updateelementdata(elementmonth,elementyear);

    $("#elementyearmonth").change(function(){

    var elementyearmonth = $('#elementyearmonth').val()

    const elementar = elementyearmonth.split(" ");

    var elementmonth = elementar[0];

    var elementyear = elementar[1];

    updateelementdata(elementmonth,elementyear);

    });

// ------------------------------------------------------------------
// country data
// -------------------------------------------------------------------

    cttable=new DataTable('#country');

    var countryyearmonth = $('#countryyearmonth').val()


    const countryar = countryyearmonth.split(" ");

    var countrymonth = countryar[0];

    var countryyear = countryar[1];

    updatecountrydata(countrymonth,countryyear);

    $("#countryyearmonth").change(function(){

        var countryyearmonth = $('#countryyearmonth').val()

        const countryar = countryyearmonth.split(" ");

        var countrymonth = countryar[0];

        var countryyear = countryar[1];

        updatecountrydata(countrymonth,countryyear);

    });

// ------------------------------------------------------------------
// keyword data
// -------------------------------------------------------------------

keytable=new DataTable('#keyword');

var keywordyearmonth = $('#keywordyearmonth').val()


const keywordar = keywordyearmonth.split(" ");

var keywordmonth = keywordar[0];

var keywordyear = keywordar[1];

var keywordpreviousmonth = getPreviousMonth(keywordmonth);

const keywordprear = keywordpreviousmonth.split(" ");

var keywordpremonth = keywordprear[0];

var keywordpreyear = keywordprear[1];

console.log(keywordpremonth,keywordpreyear);

updatekeyworddata(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

$("#keywordyearmonth").change(function(){

    var keywordyearmonth = $('#keywordyearmonth').val()

    const keywordar = keywordyearmonth.split(" ");

    var keywordmonth = keywordar[0];

    var keywordyear = keywordar[1];

    var keywordpreviousmonth = getPreviousMonth(keywordmonth);

    const keywordprear = keywordpreviousmonth.split(" ");

    var keywordpremonth = keywordprear[0];

    var keywordpreyear = keywordprear[1];

    console.log(keywordpremonth,keywordpreyear);

    updatekeyworddata(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

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

    keytable.column(2).search($(this).val()).draw();
}



});



function getPreviousMonth(currentMonthString) {

    // Create a Date object for the current month
    var currentDate = new Date(currentMonthString + ' 1, 2024');
    
    // Move the date to the previous month
    currentDate.setMonth(currentDate.getMonth() - 1);
    
    // Format the previous month as a string
    var previousMonthString = currentDate.toLocaleString('default', { month: 'long' }) + ' ' + currentDate.getFullYear();
    
    return previousMonthString;
}



function updateelementdata(month,year){


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
        },
        success: function(response) {

            if (response.status == 'success') {
                
                // console.log(response.element);
                eltable.clear().draw();
                response.element.forEach((item) => {

                    // console.log(item.ranking_element);

                    eltable.row.add([item.id,item.ranking_element,item.increase_percent,item.google_rank,item.google_rank_prev]).draw();

                });

            


            }
        }
 
    })


}

function updatecountrydata(month,year){


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
        },
        success: function(response) {

            if (response.status == 'success') {
                
                // console.log(response.element);
                cttable.clear().draw();
                response.country.forEach((item) => {

                    // console.log(item.ranking_element);

                    cttable.row.add([item.id,item.ranking_country,item.increase_percent,item.google_rank,item.google_rank_prev]).draw();

                });

            


            }
        }
 
    })


}


function updatekeyworddata(month,year,premonth,preyear){


let url = "{{ route('rankings.getkeywordrankings') }}";

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
        },
        success: function(response) {

            if (response.status == 'success') {
                
                // console.log(response.element);
                keytable.clear().draw();
                response.keyword.forEach((item) => {

                    // console.log(item.ranking_element);

                    keytable.row.add([item.id,item.ranking_course,item.ranking_keyword,item.search_volume,item.google_rank,item.prerank,item.googlemap_rank,item.premaprank]).draw();

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









