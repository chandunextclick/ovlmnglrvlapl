@extends('layouts.app')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@section('content')


<div class="container pt-5">
    <div class="p-4">
    <div class="row">
        <div class="col-md-3"><input type="text" id="date-range" class="form-control height-35 f-14" placeholder="Date-range" autocomplete="off" style="width:350px;"></div>
        
    </div>
    </div>
  <div class="row m-2">
    <div class="col-md-12 card">
    <h4 class="mt-4">General Enquiry Report</h4>
    <div id="table-actions" class="flex-grow-1 align-items-center mt-4"> 
            <input type="text" id="gensearch" class="form-control height-35 f-14" placeholder="Search" autocomplete="off" style="width:350px;">
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
    <div id="table-actions" class="flex-grow-1 align-items-center mt-4"> 
            <input type="text" id="detsearch" class="form-control height-35 f-14" placeholder="Search" autocomplete="off" style="width:350px;">
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
    <div id="table-actions" class="flex-grow-1 align-items-center mt-4"> 
            <input type="text" id="consearch" class="form-control height-35 f-14" placeholder="Search" autocomplete="off" style="width:350px;">
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


function updateenqdata(startdate,enddate){

const apiUrl = 'https://nextclickonline.cyradrive.com/testenqapplication/enquirygeneraldata'; // Replace with your server's endpoint URL


// Create the data to send as JSON
const data = {
    param1: startdate,
    param2: enddate
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


enqtable.clear().draw();

enqtabledet.clear().draw();

enqtablecon.clear().draw();

data.enquiry_general.forEach((item) => {


enqtable.row.add([item.lm_course_name,item.totalenq,item.totalsales]).draw();

});

data.enquiry_detailed.forEach((item) => {



    enqtabledet.row.add([item.lm_course_name,item.ads,item.organic]).draw();


});

data.enquiry_converted.forEach((item) => {


enqtablecon.row.add([item.lm_course_name,item.ads,item.organic]).draw();


});



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

    $('#date-range').on('apply.daterangepicker', function(ev, picker) {

        var dateRange = $('#date-range').data('daterangepicker');

        startDate = dateRange.startDate.format('YYYY-MM-DD');

        endDate = dateRange.endDate.format('YYYY-MM-DD');

        updateenqdata(startDate,endDate)


    });
    

    enqtable=new DataTable('#enqtable');

    enqtabledet=new DataTable('#enqtabledet');

    enqtablecon=new DataTable('#enqtablecon');
    
   

    // var startdate = $('#start_date').val()


    // var enddate = $('#end_date').val()

    // document.getElementById('end_date').setAttribute("min",startdate);

    var dateRange = $('#date-range').data('daterangepicker');

    startDate = dateRange.startDate.format('YYYY-MM-DD');

    endDate = dateRange.endDate.format('YYYY-MM-DD');


    updateenqdata(startDate,endDate);



    
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




$(".enqdate").change(function(){


var startdate = $('#start_date').val();

var enddate = $('#end_date').val()


updateenqdata(startdate,enddate);

});


$('#start_date').on('change',function(){

  
    var startdate = $('#start_date').val();

    document.getElementById('end_date').setAttribute("min",startdate);

    
});


  
});


</script>









