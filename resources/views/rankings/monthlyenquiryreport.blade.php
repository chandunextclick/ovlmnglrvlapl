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
    <div class="p-4">

    
    <div class="row">
        <div class="col-md-3"><input type="date" class="form-control enqdate" name="start_date" id="start_date" value="{{$date1}}"></div>
        
        <div class="col-md-3"><input type="date" class="form-control enqdate" name="end_date" id="end_date" value="{{$date2}}"></div>
    </div>
    </div>
  <div class="row m-2">
    <div class="col-md-12 card">
    <h4 class="mt-4">General Enquiry Report</h4>
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


function updateenqdata(startdate,enddate) {

const apiUrl = 'https://nextclickonline.cyradrive.com/testenqapplication/enquirygeneraldata'; // Replace with your server's endpoint URL


// Create the data to send as JSON
const data = {
    startdate: startdate,
    enddate: enddate
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
console.log(data);


})
.catch(error => {
// Handle any errors that occurred during the fetch
console.error('Error:', error);
});


}



$(document).ready(function() {


    enqtable=new DataTable('#enqtable');
   

    var startdate = $('#start_date').val()

    var enddate = $('#end_date').val()



    updateenqdata(startdate,enddate);



// function updateenqdata(startdate,enddate){

// let url = "{{ route('rankings.getenqgendata') }}";

// console.log(url);

// // Get CSRF token value
// var csrfToken = $('meta[name="csrf-token"]').attr('content');

// console.log(csrfToken);

// $.ajaxSetup({
// headers: {
//     'X-CSRF-TOKEN': csrfToken
// }
// });


// $.easyAjax({
//         url: url,
//         type: "POST",
//         data: {
//             _token: csrfToken,
//             startdate:startdate,
//             enddate:enddate,
//         },
//         success: function(response) {

//             if (response.status == 'success') {
//                 console.log(response);

//                 enqtable.clear().draw();
//                 response.enqdata.forEach((item) => {

//                     // console.log(item.ranking_element);

//                     enqtable.row.add([item.ranking_element,item.increase_percent,item.google_rank,item.google_rank_prev]).draw();

//                 });


//             }
//         }
 
//     })


// }



  
});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









