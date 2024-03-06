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

<div class="container pt-5">
  <div class="row">
    <div class="col-md card">
    <select class="form-control height-35 f-14 mt-4" placeholder="yearmonth"  name="elementyearmonth" id="elementyearmonth"  required>
                            
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
    <table id="example" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Element ID</th>
                <th>Element Name</th>
                <th>Search Volume</th>
                <th>Rank</th>
                <th>Previous Rank</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        
    </table>
    </div>
    <div class="col-md card">
      One of three columns
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

    otable=new DataTable('#example');


    var elementyearmonth = $('#elementyearmonth').val()

    const elementar = elementyearmonth.split(" ");

    var month = elementar[0];

    var year = elementar[1];

    updateelementdata(month,year);






function updateelementdata(month,year){



let url = "{{ route('rankings.getelementrankings') }}";

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
        },
        success: function(response) {

            if (response.status == 'success') {
                
                // console.log(response.element);

                response.element.forEach((item) => {

                    // console.log(item.ranking_element);

                    otable.row.add([item.id,item.ranking_element,item.increase_percent,item.google_rank,item.google_rank_prev]).draw();

                });

            


            }
        }
 
    })


}


  
});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









