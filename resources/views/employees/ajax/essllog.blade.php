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
</style>

@section('content')

    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        
    <div class="m-4">

<form method="POST" action="{{ route('sprofile.essldata') }}">
    @csrf
    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" id="start_date" value="{{ $date1 }}">
    <label for="end_date">End Date:</label>
    <input type="date" name="end_date" id="end_date" value="{{ $date2 }}">
    <label for="userid">users:</label>
    <select  name="userid" style="height:30px;width:150px;">
        <option selected disabled value='0'>Select User</option>
        <?php 
        foreach($userquery as $uservalue) 
        { 

        ?>
                <option <?= ($userid==$uservalue->id)?'selected':'' ?> value=<?=$uservalue->id?>><?=$uservalue->name?></option>

        <?php

        }
        ?>
    </select>
    <button type="submit" class="btn btn-primary ml-2">Apply</button>

</form>

</div>

        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

        <div class="container">
        <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Logdate</th>
                <th>Total Working Hours</th>
                <th>Break Time</th>
            </tr>
        </thead> 
        <tbody>
        <?php  
            
            foreach($essllog as $value) 
            {  
                
        ?>
            <tr>
                <td><?= $value->empcode ?></td>
                <td><?= $value->name ?></td>
                <td><?= $value->logdate ?></td>
                <td><?= $value->total_working_time ?></td>
                <td><?= $value->total_break_time ?></td>
            </tr>

            <?php
						
            }

            ?>
        </tbody>   
    </table>
    <p>Total Working Hours:<?=$totalTimeFormatted?></p>
    <p>Average Working Hours:<?=$avgTimeFormatted?></p>
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

    new DataTable('#example');


});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









