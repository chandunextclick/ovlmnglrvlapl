@extends('layouts.app')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@section('content')


<div class="container-fluid pt-5">
    
    <div class="row m-2">
        <div class="col-md-12 card">

        <div class="d-block text-capitalize">
                    
                    <div class="d-flex m-2">
                        
                            <p class="mb-0 f-21 font-weight-bold text-blue d-grid mr-5" id="pendingcount">
                                0<span class="f-12 font-weight-normal text-lightest">
                                   Pending</span>
                            </p>
                        
                            <p class="mb-0 f-21 font-weight-bold text-yellow d-grid mr-5" id="prcount">0<span class="f-12 font-weight-normal text-lightest">Priority</span>
                            </p>
                        
                            <p class="mb-0 f-21 font-weight-bold text-success d-grid mr-5" id="cmpcount">0<span class="f-12 font-weight-normal text-lightest">Completed</span>
                            </p>
                        
                    </div>
                </div>
            
        </div>
    </div>
</div>


<div class="container-fluid pt-5">
    
    <div class="row m-2">
        <div class="col-md-12 card">
            <div class="row">
            
                <div id="table-actions" class="flex-grow-1 align-items-center m-4 col-md-3 d-none"> 
                        <input type="text" id="gensearch" class="form-control height-35 f-14" placeholder="Search" autocomplete="off" style="width:350px;">
                </div>
        
                <div class="col-md-3 m-4"><p>Pick Date</p><input type="text" id="date-range" class="form-control height-35 f-14" placeholder="Date-range" autocomplete="off" style="width:350px;"></div>
        
                <div class="col-md-3 m-4">
                <p>Employees</p>
                <select class="form-control height-35 f-14" id="empid">
                        <option value="0">All</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                </select>
                </div>
                <div class="col-md-3 m-4">
                <p>Status</p>
                <select class="form-control height-35 f-14" id="tskstatus">
                        <option value="all">All</option>
                        <option value="Incomplete">Incomplete</option>
                        <option value="Completed">Completed</option>
                </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-5">

    <div class="row m-2">
        <div class="col-md-12 card">
            <table id="dttable" class="table table-striped table-responsive" style="min-height:100px;">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Project</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Assigned to</th>
                        <th>Status</th>
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

        empid = $('#empid').val();

        updatedtdata(startDate,endDate,empid)


    });

    $("#empid").change(function(){

        var dateRange = $('#date-range').data('daterangepicker');

        startDate = dateRange.startDate.format('YYYY-MM-DD');

        endDate = dateRange.endDate.format('YYYY-MM-DD');

        empid = $('#empid').val();

        updatedtdata(startDate,endDate,empid)

    });

    $("#tskstatus").change(function(){


        if($(this).val()=="all"){

            dttable.column(5).search("").draw();

        }else{

                dttable.column(5).search($(this).val()).draw();

        }
        

    });

    

dttable=new DataTable('#dttable');
 

var dateRange = $('#date-range').data('daterangepicker');

startDate = dateRange.startDate.format('YYYY-MM-DD');

endDate = dateRange.endDate.format('YYYY-MM-DD');

empid = $('#empid').val();

updatedtdata(startDate,endDate,empid);


    
$("#gensearch").keyup(function(){

if($(this).val()==null){

    dttable.search("").draw();

}else{

    dttable.search($(this).val()).draw();
    
}



});



function updatedtdata(startDate,endDate,empid){


let url = "{{ route('rankings.getweeklytask') }}";

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
            startdate:startDate,
            enddate:endDate,
            empid:empid
        
        },
        success: function(response) {


            if (response.status == 'success') {

                
                dttable.clear().draw();


                console.log(response.dltask);

                if(response.taskcount[0].pendingcount != null ){

                    $("#pendingcount").html(response.taskcount[0].pendingcount+'<span class="f-12 font-weight-normal text-lightest">Pending</span>');

                }

                if(response.taskcount[0].prcount != null ){

                    $("#prcount").html(response.taskcount[0].prcount+'<span class="f-12 font-weight-normal text-lightest">Priority</span>');

                }

                if(response.taskcount[0].cmpcount != null ){

                    $("#cmpcount").html(response.taskcount[0].cmpcount+'<span class="f-12 font-weight-normal text-lightest">Completed</span>');

                }

                

                console.log(response.taskcount[0].pendingcount);

                response.dltask.forEach((item) => {


                        dttable.row.add([item.heading,item.project_name,item.start_date,item.due_date,item.users_names,item.taskstatus]).draw();


                
                });

            


            }
        }
 
    })


}


  
});


</script>









