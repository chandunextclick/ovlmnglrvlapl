@extends('layouts.app')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@section('content')


<div class="container pt-5">
    
  <div class="row m-2">
    <div class="col-md-12 card">
    <h4 class="mt-4">General Enquiry Report</h4>
    <div class="row">
    
    <div id="table-actions" class="flex-grow-1 align-items-center mt-4 col-md-4"> 
            <input type="text" id="gensearch" class="form-control height-35 f-14" placeholder="Search" autocomplete="off" style="width:350px;">
    </div>
   
    <div class="col-md-3 mt-4"><input type="text" id="date-range" class="form-control height-35 f-14" placeholder="Date-range" autocomplete="off" style="width:350px;"></div>
 
    </div>
    <table id="enqtable" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Course</th>
                <th>Enquiry</th>
                <th>Sales</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>

    </div>

    <div class="col-md-12 card">
    <h4 class="mt-4">Enquiry Report</h4>
    <div class="row">
    <div id="table-actions" class="flex-grow-1 align-items-center mt-4 col-md-4"> 
            <input type="text" id="detsearch" class="form-control height-35 f-14" placeholder="Search" autocomplete="off" style="width:350px;">
    </div>
    <div class="col-md-3 mt-4"><input type="text" id="date-range-enq" class="form-control height-35 f-14" placeholder="Date-range" autocomplete="off" style="width:350px;"></div>
    </div>
    <table id="enqtabledet" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Course</th>
                <th>ADS</th>
                <th>Organic</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>

    </div>


    <div class="col-md-12 card">
    <h4 class="mt-4">Sales Report</h4>
    <div class="row">
    <div id="table-actions" class="flex-grow-1 align-items-center mt-4 col-md-4"> 
            <input type="text" id="consearch" class="form-control height-35 f-14" placeholder="Search" autocomplete="off" style="width:350px;">
    </div>
    <div class="col-md-3 mt-4"><input type="text" id="date-range-sale" class="form-control height-35 f-14" placeholder="Date-range" autocomplete="off" style="width:350px;"></div>
    
    </div>
    <table id="enqtablecon" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Course</th>
                <th>ADS</th>
                <th>Organic</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>

    </div>

  </div>
</div>

@endsection



<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>




<script>


function updateenqdata(startdate,enddate,enqtype){

const apiUrl = 'https://edoxi.cyradrive.com/hrapi/enquirygeneraldata'; // Replace with your server's endpoint URL


// Create the data to send as JSON
const data = {
    param1: startdate,
    param2: enddate,
    enqtype: enqtype
};


// Send the POST request
fetch(apiUrl,{
method: 'POST',
body: new URLSearchParams(data),
})
.then(response => {
if (response.ok) {

  return response.json(); // Parse the response as JSON

} else {
  throw new Error('Request failed');
}
})
.then(data => {
// Handle the response data here

console.log(enqtype);

if(enqtype == "gen"){

    enqtable.clear().draw();

    data.enquiry_general.forEach((item) => {


        enqtable.row.add([item.lm_course_name,item.totalenq,item.totalsales]).draw();

    });


}else if(enqtype == "enq"){

    enqtabledet.clear().draw();

    data.enquiry_detailed.forEach((item) => {


        enqtabledet.row.add([item.lm_course_name,item.ads,item.organic]).draw();


    });

}else if(enqtype == "sale"){

    enqtablecon.clear().draw();

    data.enquiry_converted.forEach((item) => {


    enqtablecon.row.add([item.lm_course_name,item.ads,item.organic]).draw();


    });


}



})
.catch(error => {
// Handle any errors that occurred during the fetch
console.error('Error:', error);
});


}


$(document).ready(function() {



    var rangepicker = $('#date-range').daterangepicker({
    startDate: new Date("{{ $date1 }}"), // Assuming $date1 is in a format parsable by JavaScript Date
    endDate: new Date("{{ $date2 }}"), // Assuming $date2 is in a format parsable by JavaScript Date
    locale: {
            format: 'YYYY-MM-DD'
        }
    });

    var rangepickerenq = $('#date-range-enq').daterangepicker({
    startDate: new Date("{{ $date1 }}"), // Assuming $date1 is in a format parsable by JavaScript Date
    endDate: new Date("{{ $date2 }}"), // Assuming $date2 is in a format parsable by JavaScript Date
    locale: {
            format: 'YYYY-MM-DD'
        }
    });

    var rangepickersale = $('#date-range-sale').daterangepicker({
    startDate: new Date("{{ $date1 }}"), // Assuming $date1 is in a format parsable by JavaScript Date
    endDate: new Date("{{ $date2 }}"), // Assuming $date2 is in a format parsable by JavaScript Date
    locale: {
            format: 'YYYY-MM-DD'
        }
    });

    $('#date-range').on('apply.daterangepicker', function(ev, picker) {

        var dateRange = $('#date-range').data('daterangepicker');

        startDate = dateRange.startDate.format('YYYY-MM-DD');

        endDate = dateRange.endDate.format('YYYY-MM-DD');

        updateenqdata(startDate,endDate,"gen")


    });

    $('#date-range-enq').on('apply.daterangepicker', function(ev, picker) {

        var dateRange = $('#date-range-enq').data('daterangepicker');

        startDate = dateRange.startDate.format('YYYY-MM-DD');

        endDate = dateRange.endDate.format('YYYY-MM-DD');

        updateenqdata(startDate,endDate,"enq")


    });

$('#date-range-sale').on('apply.daterangepicker', function(ev, picker) {

        var dateRange = $('#date-range-sale').data('daterangepicker');

        startDate = dateRange.startDate.format('YYYY-MM-DD');

        endDate = dateRange.endDate.format('YYYY-MM-DD');

        updateenqdata(startDate,endDate,"sale")


    });
    

    enqtable=new DataTable('#enqtable');

    enqtabledet=new DataTable('#enqtabledet');

    enqtablecon=new DataTable('#enqtablecon');
    
   

    var dateRange = $('#date-range').data('daterangepicker');

    startDate = dateRange.startDate.format('YYYY-MM-DD');

    endDate = dateRange.endDate.format('YYYY-MM-DD');

    var dateRangeenq = $('#date-range-enq').data('daterangepicker');

    startDateenq = dateRangeenq.startDate.format('YYYY-MM-DD');

    endDateenq = dateRangeenq.endDate.format('YYYY-MM-DD');

    var dateRangesale = $('#date-range-sale').data('daterangepicker');

    startDatesale = dateRangesale.startDate.format('YYYY-MM-DD');

    endDatesale = dateRangesale.endDate.format('YYYY-MM-DD');


    updateenqdata(startDate,endDate,"gen");

    updateenqdata(startDateenq,endDateenq,"enq");

    updateenqdata(startDatesale,endDatesale,"sale")


    
$("#gensearch").keyup(function(){

if($(this).val()==null){

    enqtable.search("").draw();

}else{

    enqtable.search($(this).val()).draw();
    
}



});


$("#detsearch").keyup(function(){

if($(this).val()==null){

    enqtabledet.search("").draw();

}else{

    enqtabledet.search($(this).val()).draw();
    
}



});


$("#consearch").keyup(function(){

if($(this).val()==null){

    enqtablecon.search("").draw();

}else{

    enqtablecon.search($(this).val()).draw();
    
}



});




  
});


</script>









