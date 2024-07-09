@extends('layouts.app')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@section('content')


<div class="container pt-5">
    
  <div class="row m-2">
    <div class="col-md-12 card">
    <h4 class="mt-4">Daily Report</h4>
    <div class="row">
    
    <div id="table-actions" class="flex-grow-1 align-items-center mt-4 col-md-4"> 
            <input type="text" id="gensearch" class="form-control height-35 f-14" placeholder="Search" autocomplete="off" style="width:350px;">
    </div>
   
    <div class="col-md-3 mt-4"><input type="text" id="date-range" class="form-control height-35 f-14" placeholder="Date-range" autocomplete="off" style="width:350px;"></div>
 
    </div>
    <table id="dttable" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Category</th>
                <th>Result</th>
                <th>Author</th>
                <th>Client</th>
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

        updatedtdata(startDate,endDate)


    });

    

dttable=new DataTable('#dttable');
 

var dateRange = $('#date-range').data('daterangepicker');

startDate = dateRange.startDate.format('YYYY-MM-DD');

endDate = dateRange.endDate.format('YYYY-MM-DD');


updatedtdata(startDate,endDate);


    
$("#gensearch").keyup(function(){

if($(this).val()==null){

    dttable.search("").draw();

}else{

    dttable.search($(this).val()).draw();
    
}



});



function updatedtdata(startDate,endDate){


let url = "{{ route('rankings.getdailytask') }}";

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
        
        },
        success: function(response) {

            if (response.status == 'success') {

                
                dttable.clear().draw();

                var coursename ="";

                var coursecount = 0;

                console.log(response.dltask);

                response.dltask.forEach((item) => {

                    if(item.result!=null){

                        dttable.row.add([item.category_name,item.result,item.Author,item.Client]).draw();

                    }

                        

                
                });

            


            }
        }
 
    })


}


  
});


</script>









