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

    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        
    <!-- Task Box Start -->
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

    <div class="container">
    <table id="example" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Task Title</th>
                <th>Desc</th>
                <th>Deadline</th>
            </tr>
        </thead>
        <tbody>
   
        </tbody>
        
    </table>
    
</div>

        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->


@endsection



<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script>


$(document).ready(function() {

    otable=new DataTable('#example');


  
});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









