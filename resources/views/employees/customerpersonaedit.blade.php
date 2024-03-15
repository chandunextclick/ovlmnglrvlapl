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
        <form method="POST" action="{{ route('sprofile.personaupdate') }}">
            @csrf
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Add Customer Persona</h4>
                <div class="row p-20">
                    <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_name">Name
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Name" value="<?=$persona->name?>" name="persona_name" id="persona_name" autocomplete="off" required>
                        <input type="hidden" class="form-control height-35 f-14" placeholder="id" value="<?=$persona->id?>" name="persona_id" id="persona_id" autocomplete="off" required>
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_course">COURSE
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <input list="courselist" type="text" class="form-control height-35 f-14" value="<?=$persona->course ?>" placeholder="Course"  name="persona_course" id="persona_course"  required>
                        <datalist id="courselist">

                            @foreach($courses as $course) 
    
                            <option value="{{$course['course_name']}}"></option>

                            @endforeach
                        </datalist> 
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_dob">Date of Birth
                    </label>
                        <input type="date" class="form-control height-35 f-14" placeholder="" value="<?=$persona->dob?>" name="persona_dob" id="persona_dob" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_gender">Gender 
                    </label>
                        <select class="form-control height-35 f-14"  name="persona_gender">
                             @if($persona->gender!="")
                                <option><?=$persona->gender?></option>
                             @else
                                <option selected disabled>Select Gender</option>
                            @endif
                            @if($persona->gender!="Male")
                            <option>Male</option>
                            @endif
                            @if($persona->gender!="Female")
                            <option>Female</option>
                            @endif
                            @if($persona->gender!="Others") 
                            <option>Others</option>
                            @endif
                        </select>
                    </div>
                    
                </div>
                
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_email">Email
                    </label>
                        <input type="email" class="form-control height-35 f-14" placeholder="Email" value="<?=$persona->email?>" name="persona_email" id="persona_email" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_phone">Phone
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Phone" value="<?=$persona->phone?>" name="persona_phone" id="persona_phone" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_linkedin">Linkedin Url
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="LinkedIn" value="<?=$persona->linkedin?>" name="persona_linkedin" id="persona_linkedin" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_mothertongue">Mother Tongue
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Mother Tongue" value="<?=$persona->mother_togue?>" name="persona_mothertongue" id="persona_mothertongue" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_bloodgroup">Blood Group
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Blood Group" value="<?=$persona->bloodgroup?>" name="persona_bloodgroup" id="persona_bloodgroup" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_nationality">Nationality 
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Nationality" value="<?=$persona->nationality?>" name="persona_nationality" id="persona_nationality" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_state">State 
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="State"value="<?=$persona->state?>" name="persona_state" id="persona_state" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_district">District 
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="District" value="<?=$persona->district?>" name="persona_district" id="persona_district" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_martial">Martail Status 
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Martial Status" value="<?=$persona->martial_status?>" name="persona_martial" id="persona_martial" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_religion">Religion
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Religion" value="<?=$persona->religion?>" name="persona_religion" id="persona_religion" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_categories">Specific Categories
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Specific Categories" value="<?=$persona->spec_category?>" name="persona_categories" id="persona_categories" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_user_address">Address
                        </label>
                        <textarea class="form-control f-14 pt-2" rows="3" placeholder="User Address" name="persona_user_address" id="persona_user_address"><?=$persona->address?></textarea>
                    </div>
                    
                </div>

                <hr>
                <h4>Parent Details</h4>
                <hr>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_parentname">Parent/Guardian Name
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Parent/Guardian Name" value="<?=$persona->parent_name?>" name="persona_parentname" id="persona_parentname" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_parent_contact">Parent Contact No
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Parent Contact No" value="<?=$persona->parent_phone?>" name="persona_parent_contact" id="persona_parent_contact" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_parent_occupation">Parent Occupation
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Parent Occupation" value="<?=$persona->parent_occupation?>" name="persona_parent_occupation" id="persona_parent_occupation" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_parent_relation">Relation with parent
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Relation With Parent" value="<?=$persona->parent_relation?>" name="persona_parent_relation" id="persona_parent_relation" autocomplete="off">
                    </div>
                    
                </div>
                <hr>
                <h4>Academic Details</h4>
                <hr>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_education">Qualification   
                    </label>
                        <select class="form-control height-35 f-14" placeholder="Education" name="persona_education" id="persona_education" autocomplete="off">
                            @if($persona->education!="")
                                <option><?=$persona->education?></option>
                             @else
                             <option selected disabled>Select Qualification</option>
                            @endif    
                            @if($persona->education!="Primary education")
                            <option value="Primary education">Primary education</option>
                            @endif
                            @if($persona->education!="Secondary education")
                            <option value="Secondary education">Secondary education or high school</option>
                            @endif
                            @if($persona->education!="Bachelor Degree")
                            <option value="Bachelor Degree">Bachelor's Degree</option>
                            @endif
                            @if($persona->education!="Master Degree")
                            <option value="Master Degree">Master's Degree</option>
                            @endif
                        </select> 
                        
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_education_university">Studied University
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Studied University" value="<?=$persona->universityname?>" name="persona_education_university" id="persona_education_university" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_education_specification">Specialization   
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Specialization" value="<?=$persona->specialization?>" name="persona_education_specification" id="persona_education_specification" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_ug_completion">UG Year of Completion
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Year of Completion" value="<?=$persona->ug_completion_year?>" name="persona_ug_completion" id="persona_ug_completion" autocomplete="off">
                    </div>
                    
                </div>
                <hr>
                <h4>Job Record Details</h4>
                <hr>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_occupation">Occupation
                        </label>

                        <input type="text" class="form-control height-35 f-14" placeholder="Occupation" value="<?=$persona->occupation?>" name="persona_occupation" id="persona_occupation" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_experience">Experience
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Experience" value="<?=$persona->experience?>" name="persona_experience" id="persona_experience" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_companyname">Company Name
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Company Name" value="<?=$persona->company_name?>" name="persona_companyname" id="persona_companyname" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_companyemail">Company Email
                    </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Company Email" value="<?=$persona->company_email?>" name="persona_companyemail" id="persona_companyemail" autocomplete="off">
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_location">Location
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Location" value="<?=$persona->location?>" name="persona_location" id="persona_location" autocomplete="off">
                    </div>
                    
                </div>
                <hr>
                <h4>Others</h4>
                <hr>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_user_description">User Description
                        </label>
                        <textarea class="form-control f-14 pt-2" rows="3" placeholder="User Description" name="persona_user_description" id="persona_user_description"><?=$persona->user_description?></textarea>
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_Characteristics">Characteristics
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" data-role="tagsinput" placeholder="Personal Characteristics" name="persona_Characteristics" id="persona_Characteristics" value="<?=$persona->personal_characteristics?>">
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_goals">Goals
                        </label><br>
                        <input type="text" class="form-control height-35 f-14"  placeholder="Goals" name="persona_goals" id="persona_goals" data-role="tagsinput" value="<?=$persona->goals?>">
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_needs">Needs
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" placeholder="Needs" name="persona_needs" id="persona_needs" data-role="tagsinput" value="<?=$persona->needs?>">
                    </div>
                    
                </div>
                <div class="col-md-12">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_interest">Hobbies
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" placeholder="Hobbies and Interest" name="persona_interest" id="persona_interest" data-role="tagsinput" value="<?=$persona->hobbies_and_interest?>">
                    </div>
                    
                </div>
                <div class="col-md-6">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_Challenges">Challenges
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" placeholder="Challenges" name="persona_Challenges" id="persona_Challenges" data-role="tagsinput" value="<?=$persona->challenges?>">
                    </div>
                    
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-6">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_source">Source Info
                        </label><br>
                        <input type="text" class="form-control height-35 f-14" placeholder="Source Info"  name="persona_source" id="persona_source" data-role="tagsinput" autocomplete="off" value="<?= $persona->source_info ?>">
                    </div>
                </div>   
             
                <div class="w-100 border-top-grey d-block d-lg-flex d-md-flex justify-content-start px-4 py-3">
    <button type="submit" class="btn-primary rounded f-14 p-2 mr-3" id="save-persona-form">
            <svg class="svg-inline--fa fa-check fa-w-16 mr-1" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg><!-- <i class="fa fa-check mr-1"></i> Font Awesome fontawesome.com -->
        Update  
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













