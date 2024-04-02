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


.toast-title,.toast-message{

    color:black;

}


</style>

@section('content')

<div class="add-client bg-white rounded">
        <form method="POST" action="{{ route('rankings.rankinkeywordstore') }}">
            @csrf
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Add Ranking Keywords</h4>
                <div class="row p-20">
                <div class="col-md-3">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="persona_course">COURSE
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <select class="form-control height-35 f-14" placeholder="Course"  name="course_name" id="course_name"  required>
                            <option selected disabled>Select Course</option>
                            @foreach($courses as $course) 
    
                            <option value="{{$course->id}}">{{ $course->ranking_course }}</option>

                            @endforeach
                        </select> 
                    </div>
                    
                </div>
                <div class="col-md-3">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="client_name">CLIENT
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <select class="form-control height-35 f-14" placeholder="client"  name="client" id="client"  required>                 
                            <option value="EDOXI">EDOXI</option>
                            <option value="TIMEMASTER">TIME MASTER</option>
                            <option value="TIMETRAINING">TIME TRAINING</option>                
                        </select>  
                    </div>
                    
                </div>
                    <div class="col-md-3">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="keyword_name">Keyword Name
                        <sup class="f-14 mr-1">*</sup>
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Keyword Name" value="" name="keyword_name" id="keyword_name" autocomplete="off" required>
                        </div>
                    
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                        <label class="f-14 text-dark-grey mb-12" data-label="true" for="keyword_name">Search Volume
                        </label>
                        <input type="text" class="form-control height-35 f-14" placeholder="Search Volume" value="" name="search_volume" id="search_volume" autocomplete="off" >
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













