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

            <x-forms.link-primary :link="route('sprofile.customerpersonacreate')" class="mr-3 float-left" icon="plus">
                        @lang('app.add')
                        @lang('app.menu.personal')
            </x-forms.link-primary>

            </div>
        </div>
    <!-- Task Box Start -->
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

    <div class="container">
    <table id="example" class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Occupation</th>
                <th>Experience</th>
                <th>Location</th>
                <th>Description</th>
                <th>Characteristics</th>
                <th>Goals</th>
                <th>Needs</th>
                <th>Hobbies/Interest</th>
                <th>Challenges</th>
                <th>Source Info</th>
            </tr>
        </thead>
        <tbody>
        <?php  
            
            foreach($customerpersona as $value) 
            {  
                
        ?>
            <tr>
                
                <td><?=$value->name?></td>
                <td><?=$value->age?></td>
                <td><?=$value->occupation?></td>
                <td><?=$value->experience?></td>
                <td><?=$value->location?></td>-
                <td><?=$value->user_description?></td>
                <td>
                    <ol>
                        <?php
                        $characteristics = explode(',', $value->personal_characteristics);
                        foreach ($characteristics as $characteristic) {
                            if($characteristic!=""){

                                echo '<li>' . trim($characteristic) . '</li>';
                            }
                            
                        }
                        ?>
                    </ol>
                </td>
                <td>
                    <ol>
                        <?php
                        $goals=explode(',',$value->goals);
                        foreach($goals as $goal){

                            if($goal!=""){
                                
                                echo '<li>' . trim($goal) . '</li>';
                            }

                        }
                        
                        ?>
                    </ol>
                </td>
                <td>
                    <ol>
                        <?php
                        $needs=explode(',',$value->needs);
                        foreach($needs as $need){

                            if($need!=""){
                                
                                echo '<li>' . trim($need) . '</li>';
                            }

                        }
                        
                        ?>
                    </ol>
                </td>
                <td>
                    <ol>
                        <?php
                        $hobbies=explode(',',$value->hobbies_and_interest);
                        foreach($hobbies as $hobbie){

                            if($hobbie!=""){
                                
                                echo '<li>' . trim($hobbie) . '</li>';
                            }

                        }
                        
                        ?>
                    </ol>
                </td>
                <td>
                <ol>
                        <?php
                        $challenges=explode(',',$value->challenges);
                        foreach($challenges as $challenge){

                            if($challenge!=""){
                                
                                echo '<li>' . trim($challenge) . '</li>';
                            }

                        }
                        
                        ?>
                    </ol>
                </td>
                <td>
                <ol>
                        <?php
                        $source_infos=explode(',',$value->source_info);
                        foreach($source_infos as $source_info){

                            if($source_info!=""){
                                
                                echo '<li>' . trim($source_info) . '</li>';
                            }

                        }
                        
                        ?>
                    </ol>
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

    new DataTable('#example');


});


</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>









