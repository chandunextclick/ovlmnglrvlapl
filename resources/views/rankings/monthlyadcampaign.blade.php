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
            
            <form method="POST" action="{{ route('rankings.monthlyadcampaign')}}">
            @csrf
            <div class="d-flex">
            <select class="form-control height-35 f-14" placeholder="yearmonth"  name="yearmonth" id="yearmonth"  required>
                            
            <?php
            
            $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 6 months');
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
            <button type="submit" class="btn btn-primary ml-2">Apply</button>
            </div>
            </form>
            
            </div>
            </div>

   

            </div>
        </div>
    <!-- Task Box Start -->
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

    <div class="container">
    <table id="example" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Campaign Name</th>
                <th>Budget</th>
                <th>Impr</th>
                <th>Cost</th>
                <th>Clicks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php  
            
            foreach($campaignarray as $key=> $value) 
            {  
                
        ?>
            <tr>
                <td id="cmp<?=$key?>"><?= $value['campaign'] ?></td>
                <td><input type="text"    value="<?=$value['budget']?>" id="bdg<?=$key?>"></td>
                <td><input type="text"    value="<?=$value['impr']?>" id="impr<?=$key?>"></td>
                <td><input type="text"    value="<?=$value['cost']?>" id="cost<?=$key?>"></td>
                <td><input type="text"    value="<?=$value['clicks']?>" id="clk<?=$key?>" ></td>
                @if($value['upd']==0)
                <td><button class="btn-primary clickbtn" data-value="insert" data-id="<?=$value['id']?>" id=<?=$key?> >Insert</button></td>
                @else
                <td><button class="btn-primary clickbtn" data-value="update" data-id="<?=$value['id']?>" id=<?=$key?> >Update</button></td>
                @endif
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


    $("#example").on( "click", ".clickbtn", function( event ) {


        var dataid = $(this).data('id');

        var id = $(this).attr('id');

        var val = $(this).data('value');


        console.log($('#cmp'+id).html());

        var campaign = $('#cmp'+id).html();

        var budget = $('#bdg'+id).val();

        var impr = $('#impr'+id).val();

        var cost = $('#cost'+id).val();

        var clicks = $('#clk'+id).val();

        var yearmonth = $('#yearmonth').val()

        const ar = yearmonth.split(" ");

        var month = ar[0];

        var year = ar[1];


        updatecampaign(id,val,campaign,budget,impr,cost,clicks,month,year,dataid)

        if(val === "insert"){

            $(this).attr("data-value","update");

            $(this).html("Update");

        }


});




function updatecampaign(id,val,campaign,budget,impr,cost,clicks,month,year,dataid){


var url = "{{ route('rankings.updateadcampaigns')}}";


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
            campaign:campaign, 
            budget:budget,
            impr:impr, 
            cost:cost, 
            clicks:clicks,
            month:month,
            year:year,  
            dataid:dataid
        
        },
        success: function(response) {
            if (response.status == 'success') {

                $('#'+id).attr("data-id"    ,response.maxid);


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









