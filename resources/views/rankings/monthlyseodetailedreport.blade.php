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
        <h4 class="mt-4">Dropped Keywords</h4>

        <h5 class="mt-4 ml-3">Dropped From No 1</h5>
        <table id="noonekeyworddropped" class="table table-striped table-responsive" style="min-height:100px;">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Keyword Name</th>
                    <th>Current Position</th>
                </tr>
            </thead>
            <tbody>
            </tbody>   
        </table>
        <h5 class="mt-4 ml-3">Dropped From 2-5</h5>
        <table id="keyworddroppedfromtwo" class="table table-striped table-responsive" style="min-height:100px;">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Keyword Name</th>
                    <th>Previous Position</th>
                    <th>Current Position</th>
                </tr>
            </thead>
            <tbody>
            </tbody>   
        </table>
    </div>
    <div class="col-md-12 card">
        <h4 class="mt-4">Ranking Up</h4>

        <h5 class="mt-4 ml-3">Ranking up to 1st position</h5>
        <table id="noonekeywordup" class="table table-striped table-responsive" style="min-height:100px;">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Keyword Name</th>
                    <th>Previous Position</th>
                    <th>Current Position</th>
                </tr>
            </thead>
            <tbody>
            </tbody>   
        </table>
        <h5 class="mt-4 ml-3">Ranking up to 2-5</h5>
        <table id="keywordupfromtwotofive" class="table table-striped table-responsive" style="min-height:100px;">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Keyword Name</th>
                    <th>Previous Position</th>
                    <th>Current Position</th>
                    
                </tr>
            </thead>
            <tbody>
            </tbody>   
        </table>
    </div>
    <div class="col-md-12 card">
        <h4 class="mt-4">Dropped Niche</h4>
        <table id="droppedniche" class="table table-striped table-responsive" style="min-height:100px;">
            <thead>
                <tr>
                    <th>Course Name</th>
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
// keyword data
// -------------------------------------------------------------------

noonekeytable=new DataTable('#noonekeyworddropped');

upnoonekeytable=new DataTable('#noonekeywordup');

dropfromtwokeytable=new DataTable('#keyworddroppedfromtwo');

upfromtwotofivekeytable=new DataTable('#keywordupfromtwotofive');

droppedniche=new DataTable('#droppedniche');

var keywordmonth = '<?php echo $month; ?>';

var keywordyear = '<?php echo $year; ?>';

console.log(keywordmonth,keywordyear);

var keywordpreviousmonth = getPreviousMonth(keywordmonth,keywordyear);

const keywordprear = keywordpreviousmonth.split(" ");

var keywordpremonth = keywordprear[0];

var keywordpreyear = keywordprear[1];


updatedroppednoonekeyword(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

updateupnoonekeyword(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

updatedroppedfromtwokeyword(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

updatetwotofivekeyword(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

updatedroppedniche(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

function getPreviousMonth(currentMonthString,currentYearString) {

    // Create a Date object for the current month
    var currentDate = new Date(currentMonthString + ' 1,' + currentYearString);
    
    // Move the date to the previous month
    currentDate.setMonth(currentDate.getMonth() - 1);
    
    // Format the previous month as a string
    var previousMonthString = currentDate.toLocaleString('default', { month: 'long' }) + ' ' + currentDate.getFullYear();
    
    return previousMonthString;
}


// -----------------------------------------------------------------------------
// dropedniche
// ------------------------------------------------------------------------------


function updatedroppedniche(month,year,premonth,preyear){


let url = "{{ route('rankings.getdropedniche') }}";

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
                droppedniche.clear().draw();
                response.droppedniche.forEach((item) => {


                    droppedniche.row.add([item.ranking_course]).draw();

                });

            


            }
        }
 
    })


}


// -----------------------------------------------------------------------------
// dropednoone
// ------------------------------------------------------------------------------


function updatedroppednoonekeyword(month,year,premonth,preyear){


let url = "{{ route('rankings.getdropednooneword') }}";

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
                noonekeytable.clear().draw();
                response.droppednonone.forEach((item) => {

                    // console.log(item.ranking_element);

                    noonekeytable.row.add([item.ranking_course,item.ranking_keyword,item.dropped]).draw();

                });

            


            }
        }
 
    })


}

// -----------------------------------------------------------------------------
// upnoone
// ------------------------------------------------------------------------------


function updateupnoonekeyword(month,year,premonth,preyear){


let url = "{{ route('rankings.getupnooneword') }}";

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
                upnoonekeytable.clear().draw();
                response.upnonone.forEach((item) => {

                    // console.log(item.ranking_element);

                    upnoonekeytable.row.add([item.ranking_course,item.ranking_keyword,item.prevposition,item.upposition]).draw();

                });

            


            }
        }
 
    })


}

// -----------------------------------------------------------------------------
// dropedfromtwo
// ------------------------------------------------------------------------------

function updatedroppedfromtwokeyword(month,year,premonth,preyear){


let url = "{{ route('rankings.getdropedfromtwoword') }}";

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
                dropfromtwokeytable.clear().draw();
                response.dropfromtwo.forEach((item) => {

                    // console.log(item.ranking_element);

                    dropfromtwokeytable.row.add([item.ranking_course,item.ranking_keyword,item.google_rank,item.dropped]).draw();

                });

            


            }
        }
 
    })


}

// -----------------------------------------------------------------------------
// uptwotofive
// ------------------------------------------------------------------------------

function updatetwotofivekeyword(month,year,premonth,preyear){


let url = "{{ route('rankings.getuptwotofiveword') }}";

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
                upfromtwotofivekeytable.clear().draw();
                response.uptotwotofive.forEach((item) => {

                    // console.log(item.ranking_element);

                    upfromtwotofivekeytable.row.add([item.ranking_course,item.ranking_keyword,item.prevposition,item.currentposition]).draw();

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









