@extends('layouts.app')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@2.1.25/daterangepicker.css" />

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" />


<!-- Bootstrap Tags Input CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet">


<style>

input[type="date"] {
            /* Add a border with a shadow effect */
            border: 1px solid #ccc;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            padding: 5px; /* Add some padding for better appearance */
            width:180px;
        }

.toast-title,.toast-message{

    color:black;

}

.bootstrap-tagsinput .tag {

background-color:black;
color:white;

}


</style>

@section('content')

<div class="add-client bg-white rounded">
        <form method="POST" action="{{ route('sprofile.store') }}">
            @csrf
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Add Customer Persona</h4>
                <div class="row p-20">
                    <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_name">Name
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Name" value="" name="persona_name" id="persona_name" autocomplete="off" required>
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_course">COURSE
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <input list="courselist" type="text" class="form-control height-35 f-14" placeholder="Course"  name="persona_course" id="persona_course"  required>
                        <datalist id="courselist">

                            @foreach($courses as $course) 
    
                            <option value="{{$course['course_name']}}"></option>

                            @endforeach
                        </datalist> 
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_age">AGE
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="AGE" value="" name="persona_age" id="persona_age" autocomplete="off" required >
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_education">Qualification
                        <sup class="f-14 mr-1">*</sup>    
                    </label>
                        <select class="form-control height-35 f-14" placeholder="Education" name="persona_education" id="persona_education" autocomplete="off" required>
                            <option selected disabled>Select Qualification</option>
                            <option value="Primary education">Primary education</option>
                            <option value="Secondary education">Secondary education or high school</option>
                            <option value="Bachelor Degree">Bachelor's Degree</option>
                            <option value="Master Degree">Master's Degree</option>
                        </select> 
                        
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_education">Subject 
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Subject" value="" name="persona_education_subject" id="persona_education_subject" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_education">Specialization   
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Specialization" value="" name="persona_education_specification" id="persona_education_specification" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_occupation">Occupation
                        </label>

                        <input type="text" class="form-control height-35 f-14" placeholder="Occupation" value="" name="persona_occupation" id="persona_occupation" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_experience">Experience
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Experience" value="" name="persona_experience" id="persona_experience" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="designation_name">Location
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Location" value="" name="persona_location" id="persona_location" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_user_description">User Description
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <textarea class="form-control f-14 pt-2" rows="3" placeholder="User Description" name="persona_user_description" id="persona_user_description" required></textarea>
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_Characteristics">Characteristics
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" data-role="tagsinput" placeholder="Personal Characteristics" name="persona_Characteristics" id="persona_Characteristics">
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_goals">Goals
                        </label><br>
                        <input type="text" class="form-control height-35 f-14"  placeholder="Goals" name="persona_goals" id="persona_goals" data-role="tagsinput">
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_needs">Needs
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" placeholder="Needs" name="persona_needs" id="persona_needs" data-role="tagsinput">
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_interest">Hobbies
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" placeholder="Hobbies and Interest" name="persona_interest" id="persona_interest" data-role="tagsinput">
                    </div>
                    
                </div>
                <div class="col-md-6">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_Challenges">Challenges
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" placeholder="Challenges" name="persona_Challenges" id="persona_Challenges" data-role="tagsinput">
                    </div>
                    
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-6">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_source">Source Info
                        <sup class="f-14 mr-1">*</sup>
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" placeholder="Source Info" value="" name="persona_source" id="persona_source" data-role="tagsinput" autocomplete="off"  required>
                    </div>
                </div>   
             
                <div class="w-100 border-top-grey d-block d-lg-flex d-md-flex justify-content-start px-4 py-3">
    <button type="submit" class="btn-primary rounded f-14 p-2 mr-3" id="save-persona-form">
            <svg class="svg-inline--fa fa-check fa-w-16 mr-1" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg><!-- <i class="fa fa-check mr-1"></i> Font Awesome fontawesome.com -->
        Save
    </button>
    </form>
</div>

            </div>

@endsection

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous"></script>



<!-- Bootstrap Tags Input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>




<script>

$(document).ready(function() {

    new DataTable('#example');



    @if (Session::has('message'))



        toastr.options = {
            "preventDuplicates": true,
            "closeButton": true,
            "progressBar": true,
            "positionClass": 'toast-top-right',
            "timeOut": 5000 // 5 seconds
        };

        toastr.success("{{ Session::get('message') }}",'Success!');

    @endif

});


</script>













