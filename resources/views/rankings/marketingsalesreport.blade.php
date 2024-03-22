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


#observ-btn{

    top:400px;
    right:40px;
    position: fixed;

}

</style>




@section('content')

<div class="container pt-5">
  <div class="row">
    <div class="col-md-12 card">
    <h4 class="mt-4">Marketing Sales Report</h4>
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
                <th>Keyword Name</th>
                <th>Course Name</th>
                <th id="th-yearmonth"><?= $yearmonth ?></th>
                <th id="th-prevyearmonth"><?= $prevyearmonth ?></th>
                <th>Enquiry</th>
                <th>Ads</th>
                <th>Organic</th>
                <th>Call</th>
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

keytable=new DataTable('#keyword');

keytable.order([[0, 'asc'], [4, 'desc']]).draw();

var keywordyearmonth = $('#keywordyearmonth').val()


const keywordar = keywordyearmonth.split(" ");

var keywordmonth = keywordar[0];

var keywordyear = keywordar[1];

var keywordpreviousmonth = getPreviousMonth(keywordmonth,keywordyear);

const keywordprear = keywordpreviousmonth.split(" ");

var keywordpremonth = keywordprear[0];

var keywordpreyear = keywordprear[1];

console.log(keywordpremonth,keywordpreyear);

updatekeyworddata(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

$("#keywordyearmonth").change(function(){


    var selectedYearMonth = $('#keywordyearmonth').val();
    var newRoute = "{{ route('rankings.monthlydetailedseoreport',':yearmonth') }}";
    newRoute = newRoute.replace(':yearmonth',selectedYearMonth);
    $('#observ-btn a').attr('href', newRoute);

    var keywordyearmonth = $('#keywordyearmonth').val()

    const keywordar = keywordyearmonth.split(" ");

    var keywordmonth = keywordar[0];

    var keywordyear = keywordar[1];

    $("#th-yearmonth").html($('#keywordyearmonth').val())

    $("#th-prevyearmonth").html(getPreviousMonth(keywordmonth,keywordyear))

    var keywordpreviousmonth = getPreviousMonth(keywordmonth,keywordyear);

    const keywordprear = keywordpreviousmonth.split(" ");

    var keywordpremonth = keywordprear[0];

    var keywordpreyear = keywordprear[1];


    updatekeyworddata(keywordmonth,keywordyear,keywordpremonth,keywordpreyear);

});



$("#myInputTextField").keyup(function(){

if($(this).val()==null){

    keytable.search("").draw();

}else{

    console.log($(this).val());

    keytable.search($(this).val()).draw();
    
}



});

$("#course_name").change(function(){

    console.log($(this).val());

if($(this).val()==null){

    keytable.search("").draw();

}else{

    var value=$(this).val();

    keytable.column(0).search($(this).val()).draw();
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

                
                keytable.clear().draw();

                var coursename ="";

                var coursecount = 0;

                // console.log(response.keyword);

                response.keyword.forEach((item) => {

                    if(coursename !== item.ranking_course){

                        updatesalesdata(month,year,item.ranking_course,item,coursecount);

                        coursename = item.ranking_course;

                    }else{

                        keytable.row.add([item.ranking_keyword,"",item.google_rank,item.prerank,"","","",""]).draw();



                    }
    
                    
                
                
                });

            


            }
        }
 
    })


}


function updatesalesdata(month,year,course,item,coursecount){

const apiUrl = 'https://nextclickonline.cyradrive.com/testenqapplication/getsalesddata'; // Replace with your server's endpoint URL



// Create the data to send as JSON
const data = {
    month: month,
    year: year,
    course: course
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


keytable.row.add([item.ranking_keyword,item.ranking_course,item.google_rank,item.prerank,data.salesdata[0].totalenq,data.salesdata[0].ads,data.salesdata[0].organic,data.salesdata[0].callback]).draw();



})
.catch(error => {
// Handle any errors that occurred during the fetch
console.error('Error:', error);
});


}


  
});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









