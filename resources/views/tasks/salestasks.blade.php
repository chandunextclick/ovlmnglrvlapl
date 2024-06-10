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

    <div class="row">
    <div class="col-md-3">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="Status">Status
                        </label>
                        <select class="form-control height-35 f-14"  name="task_status" id="task_status"  required>
                            <option value="All">All</option>
                            <option value="incompleted">Incompleted</option>
                            <option value="Completed">Completed</option>

                        </select> 
                    </div>
                </div>
    </div>
        
    <!-- Task Box Start -->
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

    <div class="container">
    <table id="example" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Task Title</th>
                <th>Task Note</th>
                <th>DeadLine</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 

        foreach($admintask as $value) 
            { 

            $taskid=$value['tm_task_id'];

            $taskstatus=$value['task_status'];
        
        ?>
            <tr>
                <td><?= $value['tm_task_id'] ?></td>
                <td><?= $value['task_tittle'] ?></td>
                <td><?= $value['task_note'] ?></td>
                <td><?= $value['task_dead_line'] ?></td>
                <td><?= $value['task_status'] ?></td>
                <td><div class="dropdown">
                <span class="bi bi-three-dots-vertical dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @if(!($taskstatus =='Completed' or $taskstatus =='Finished'))

                    @if(!in_array($taskid,$salestaskids))
                    <a class="dropdown-item" href="{{ route('tasks.salestaskscreateprj',['id' => "$taskid" ])}}">Create Project</a>
                    @endif
                    <a class="dropdown-item" href="{{ route('tasks.salestasksupdateprj',['id' => "$taskid"])}}">Update Status</a>
                    @if(in_array($taskid,$salestaskprjids))
                    <a class="dropdown-item" href="{{ route('tasks.salestasksupdateprj',['id' => "$taskid"])}}">View Project</a>
                    @endif

                @endif
                </div>
                </div>
                </td>
              
            </tr>
            <?php 

            }
            ?>
        
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



    
$("#task_status").change(function(){


console.log( $('#task_status').val());

var status = $('#task_status').val()


if(status=='Completed'){



    otable.column(4).search('Completed|Finished', true, false).draw();



}else if(status=='incompleted'){

    

    otable.column(4).search('^(?!Completed$).*$', true, false).draw();  

}else{


    otable.column(4).search('').draw();

}



});    



  
});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









