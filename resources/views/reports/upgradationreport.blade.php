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
    <h4 class="mt-4">Upgradation Report</h4>
    <div class="row">
        
        <div class="col-md-4">
        <select class="form-control height-35 f-14 mt-4" placeholder="Completion"  name="completion" id="completion">
                            <option value="notfinished">Not Finished</option>
                            <option value="finished">Finished</option>
        </select> 
        </div>                  
    </div>

   
    <table id="upgradation" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Url</th>
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

keytable=new DataTable('#upgradation');


var completion = $('#completion').val();

updateupgradationdata(completion);

$("#completion").change(function(){


    var completion = $('#completion').val();

    updateupgradationdata(completion);

});



function updateupgradationdata(completion){


let url = "{{ route('rankings.getupgradation') }}";

console.log(url);

// Get CSRF token value
var csrfToken = $('meta[name="csrf-token"]').attr('content');

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
            completion:completion,
        },
        success: function(response) {

            console.log(response.status);

            if (response.status == 'success') {

            

                response.upgradation.forEach((item) => {   


                    keytable.row.add([item.project_name,item.result]).draw();

                
                
                });

            


            }
        }
 
    })


}


  
});


</script>











