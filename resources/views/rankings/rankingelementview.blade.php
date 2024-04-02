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

                <x-forms.link-primary :link="route('rankings.rankingelementcreate')" class="mr-3 float-left" icon="plus">
                            @lang('app.add')
                            @lang('app.menu.rankingelement')
                </x-forms.link-primary>
            </div>
        </div>
    <!-- Task Box Start -->
    <div class="row mt-4">
  
                <div class="col-md-3">
                        <div class="form-group">
                
                        <select class="form-control height-35 f-14" placeholder="client"  name="client" id="client"  required>                 
                            <option value="EDOXI">EDOXI</option>
                            <option value="TIMEMASTER">TIME MASTER</option>
                            <option value="TIMETRAINING">TIME TRAINING</option>                
                        </select>  
                    </div>
                    
                </div> 
    </div>
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

    <div class="container">
    <table id="example" class="table table-striped table-responsive" style="min-height:100px;">
        <thead>
            <tr>
                <th>Element ID</th>
                <th>Element Name</th>
                <th>Increase Percent</th>
                <th>Client</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php  
            
            foreach($rankingelement as $value) 
            {  
                
        ?>
            <tr>
                
                <td><?=$value->id?></td>
                <td><?=$value->ranking_element?></td>
                <td><?=$value->increase_percent?></td>
                <td><?=$value->client?></td>
                <td><div class="dropdown">
                <span class="bi bi-three-dots-vertical dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('rankings.rankinelementedit',['id' => $value->id])}}">Edit</a>
                    <a class="dropdown-item delbtn" data-id="{{$value->id}}" >Delete</a>
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

    var client = $('#client').val();

    otable.column(3).search(client).draw();

    var val =  $("#myInputTextField").val();

    console.log(val);

$("#client").change(function(){


var client = $('#client').val();


console.log(client);


    otable.column(3).search(client).draw();



});


    $("#example").on( "click", ".delbtn", function( event ) {

        console.log("clicked");

        var id = $(this).data('id');

        Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('app.cancel')",
                    customClass: {
                        confirmButton: 'btn btn-primary mr-3',
                        cancelButton: 'btn btn-secondary'
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {

                        deleterecord(id);
                    }
                });
        

    });




    function deleterecord(id){

        
        var url = "{{ route('rankings.rankinelementdelete',':id')}}";

        url = url.replace(':id',id)

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
                dataType: 'json',
                data: {_token: csrfToken},
                success: function(response) {
                    if (response.status == 'success') {
                        
                        console.log("success");

                        location.reload();

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









