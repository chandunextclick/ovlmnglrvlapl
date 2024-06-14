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
        

        <div class="row">
            <div class="col-md-2">
                <div class="card text-primary bg-info" id="card1" style="height:100px;justify-content:center;align-items:center">
                    <h4>{{ $mail1count }}</h4>
                </div>
                <p class="ml-4">1st Email Count</p>
            </div>
            <div class="col-md-2">
                <div class="card text-primary" id="card2" style="height:100px;justify-content:center;align-items:center">
                    <h4>{{ $mail2count }}</h4>
                </div>
                <p class="ml-4">2nd Email Count</p>
            </div>

            <div class="col-md-2">
                <div class="card text-primary" id="card3" style="height:100px;justify-content:center;align-items:center">
                    <h4>{{ $quiz1attendedcount }}</h4>
                </div>
                <p class="ml-4">1st Quiz Attended</p>
            </div>
            
            <div class="col-md-2">
                <div class="card text-primary" id="card4" style="height:100px;justify-content:center;align-items:center">
                    <h4>{{ $quiz2attendedcount }}</h4>
                </div>
                <p class="ml-4">2nd Quiz Attended</p>
            </div>
        </div>

        <div class="container bg-white mt-2" id="card1div">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                 
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                <?php  
                    
                    foreach($mail1 as $value) 
                    {  
                        
                ?>
                    <tr>
 
                        <td><?=$value['name']?></td>
                        <td><?=$value['phone']?></td>
                        <td><?=$value['email']?></td>
                        <td><?=$value['city']?></td>
                        <td><?=$value['course']?></td>
                    </tr>

                    <?php
                                
                    }

                    ?>
                </tbody>
                
            </table>
    
        </div>


        <div class="container bg-white mt-2 d-none" id="card2div">
            <table id="example1" class="table table-striped" style="width:100%">
                <thead>
                    <tr>

                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                <?php  
                    
                    foreach($mail2 as $value) 
                    {  
                        
                ?>
                    <tr>
       
                        <td><?=$value['name']?></td>
                        <td><?=$value['phone']?></td>
                        <td><?=$value['email']?></td>
                        <td><?=$value['city']?></td>
                        <td><?=$value['course']?></td>
                    </tr>

                    <?php
                                
                    }

                    ?>
                </tbody>
                
            </table>
    
        </div>

        

        <div class="container bg-white mt-2 d-none" id="card3div">
            <table id="example2" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
     
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                <?php  
                    
                    foreach($quiz1attended as $value) 
                    {  
                        
                ?>
                    <tr>
   
                        <td><?=$value['name']?></td>
                        <td><?=$value['phone']?></td>
                        <td><?=$value['email']?></td>
                        <td><?=$value['city']?></td>
                        <td><?=$value['course']?></td>
                    </tr>

                    <?php
                                
                    }

                    ?>
                </tbody>
                
            </table>
    
        </div>

        <div class="container bg-white mt-2 d-none" id="card4div">
            <table id="example3" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
          
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                <?php  
                    
                    foreach($quiz2attended as $value) 
                    {  
                        
                ?>
                    <tr>
               
                        <td><?=$value['full_name']?></td>
                        <td><?=$value['mobile']?></td>
                        <td><?=$value['email']?></td>
                        <td><?=$value['city']?></td>
                        <td><?=$value['title']?></td>
                    </tr>

                    <?php
                                
                    }

                    ?>
                </tbody>
                
            </table>
    
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

    new DataTable('#example');

    new DataTable('#example1');

    new DataTable('#example2');

    new DataTable('#example3');


    $('#card1').click(function(){

        $('#card1').addClass('bg-info');

        $('#card1div').removeClass('d-none');

        $('#card2').removeClass('bg-info');

        $('#card2div').addClass('d-none');

        $('#card3').removeClass('bg-info');

        $('#card3div').addClass('d-none');

        $('#card4').removeClass('bg-info');

        $('#card4div').addClass('d-none');
    

    });


    $('#card2').click(function(){

        $('#card2').addClass('bg-info');

        $('#card2div').removeClass('d-none');

        $('#card1').removeClass('bg-info');

        $('#card1div').addClass('d-none');

        $('#card3').removeClass('bg-info');

        $('#card3div').addClass('d-none');

        $('#card4').removeClass('bg-info');

        $('#card4div').addClass('d-none');


    });

    $('#card3').click(function(){

        $('#card3').addClass('bg-info');

        $('#card3div').removeClass('d-none');

        $('#card2').removeClass('bg-info');

        $('#card2div').addClass('d-none');

        $('#card1').removeClass('bg-info');

        $('#card1div').addClass('d-none');

        $('#card4').removeClass('bg-info');

        $('#card4div').addClass('d-none');


    });

    $('#card4').click(function(){


        console.log("tt");

        $('#card4').addClass('bg-info');

        $('#card4div').removeClass('d-none');

        $('#card2').removeClass('bg-info');

        $('#card2div').addClass('d-none');

        $('#card3').removeClass('bg-info');

        $('#card3div').addClass('d-none');

        $('#card1').removeClass('bg-info');

        $('#card1div').addClass('d-none');


    });

});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









