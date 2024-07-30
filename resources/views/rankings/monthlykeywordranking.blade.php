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
        <div class="d-block d-lg-flex d-md-flex justify-content-between action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">

            <div class="row">
            <div class="col-md-6">
            
            <form method="POST" action="{{ route('rankings.monthlykeywordranking')}}">
            @csrf
           
            

            <div class="d-flex">

            

            <select class="form-control height-35 f-14 {{ user()->id == 54 ? 'd-none' : '' }}" placeholder="yearmonth"  name="yearmonth" id="yearmonth"  required>
                            
            <?php
            
            $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 6 months');
            $end = strtotime(date('Y').'-'.date('m').'-'.date('j').' + 1 months');
            while($month < $end){

                $selected = (date('F Y', $month)==$yearmonth)? 'selected' :'';

            ?>

                            <option <?= $selected ?> value="<?= date('F Y', $month) ?>"><?=date('F Y', $month)?></option>

            <?php
            $month = strtotime("+1 month", $month);
            }
            ?>
            </select> 



                <select class="form-control height-35 f-14" placeholder="client"  name="client" id="client"  required>                 
                <option <?= ($client == "EDOXI")? 'selected':'' ?> value="EDOXI">EDOXI</option>
                <option <?= ($client == "TIMEMASTER")? 'selected':'' ?> value="TIMEMASTER">TIME MASTER</option>
                <option <?= ($client == "TIMETRAINING")? 'selected':'' ?> value="TIMETRAINING">TIME TRAINING</option>                
                </select>  
            <button type="submit" class="btn btn-primary ml-2">Apply</button>
            </div>
            </form>
            
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
            <div id="table-actions" class="flex-grow-1 align-items-center mt-4"> 
                <input type="text" id="myInputTextField" class="form-control height-35 f-14" placeholder="Search" autocomplete="off">
            </div>
        </div></div>

   

            </div>
        </div>
    <!-- Task Box Start -->
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

    <div class="container">
    <table id="example" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Keyword ID</th>
                <th>Course Name</th>
                <th>Keyword Name</th>
                <th>Search Volume</th>
                <th>Google</th>
                <th>Google Map</th>
            </tr>
        </thead>
        <tbody>
        <?php  
            
            foreach($rankingkeyword as $value) 
            {  
                
        ?>
            <tr>
                
                <td><?=$value->id?></td>
                <td><?=$value->ranking_course?></td>
                <td><?=$value->ranking_keyword?></td>
                <td><?=$value->search_volume?></td>
                <td><input type="text" class="ranking" data-value="google" data-id="<?=$value->id?>" value="<?=$value->google_rank?>"></td>
                <td><input type="text" class="ranking" data-value="googlemap" data-id="<?=$value->id?>" value="<?=$value->googlemap_rank?>"></td>
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




$("#myInputTextField").keyup(function(){

    if($(this).val()==null){

        otable.search("").draw();

    }else{

        otable.search($(this).val()).draw();
        
    }



});


    $("#example").on( "change", ".ranking", function( event ) {


        var id = $(this).data('id');

        var val = $(this).data('value');

        var inputvalue = $(this).val();

        var yearmonth = $('#yearmonth').val();

        const ar = yearmonth.split(" ");

        var month = ar[0];

        var year = ar[1];

        console.log(month);

        console.log(year);

        updaterankings(id,val,inputvalue,month,year)


});




function updaterankings(id,val,inputvalue,month,year){


var url = "{{ route('rankings.updaterankings')}}";


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
            id:id,
            val:val, 
            inputvalue:inputvalue, 
            month:month,
            year:year,   
        
        },
        success: function(response) {
            if (response.status == 'success') {
                
                console.log("success");


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









