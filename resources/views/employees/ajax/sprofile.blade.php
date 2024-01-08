@extends('layouts.app')
<script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
<style>
    .card-img {
        width: 100px;
        height: 100px;
    }

    .card-img img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    .appreciation-count {
        top: -6px;
        right: 10px;
    }

</style>

@section('content')

<div class="d-lg-flex">

    <div class="w-100 py-0 py-lg-3 py-md-0">
        <!-- ROW START -->
        <div class="row ml-4">
            <!--  USER CARDS START -->
            <div class="col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4 mb-md-0">
                <div class="row">
                    <div class="col-xl-7 col-md-6 mb-4 mb-lg-0">

                    <div class="card border-0 b-shadow-4">
                        <div class="card-horizontal align-items-center">
                            <div class="card-img">
                                <img class="" src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg" alt="">
                            </div>
                            <div class="card-body border-0 pl-0">
                            <div class="row">
                                <div class="col-10">
                                    <h4 class="card-title f-15 f-w-500 text-darkest-grey mb-0">
                                        {{ $enq['enquiry_details']['name'] }}
                                    </h4>
                                </div>
                            </div>
                            </div>
                        </div>

                    </div>
                        

                        <x-cards.data :title="__('Basic Details')" class=" mt-4">

                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Full Name</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> {{ $enq['enquiry_details']['name'] }} </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Gender</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Date of Birth</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Email</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> {{ $enq['enquiry_details']['email'] }} </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Phone</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> {{ $enq['enquiry_details']['mobile'] }}</p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Linkedin Url</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Mother Tongue</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Blood Group</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Nationality</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">State</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">District</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Marital Status</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Religion </p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Specific Categories</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Address</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>

                        </x-cards.data>
                        <x-cards.data :title="__('Parents/Guardian Details')" class=" mt-2">

                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Parent/Guardian Name</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Contact Number</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Parent Occupation</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div> <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Relation</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                        </x-cards.data>
                        <x-cards.data :title="__('Academic Record Details')" class=" mt-2">

                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Highest education: drop down (Doctorate, Masters, Bachelors, 10+2, 10)</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Specialisation</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Studied University</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div> <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">UG Year of Completion</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                        </x-cards.data>
                        <x-cards.data :title="__('Job Record Details')" class=" mt-2">

                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Current Job</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Company</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div>
                            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                                <p class="mb-0 text-lightest f-14 w-30 text-capitalize">Company email</p>
                                <p class="mb-0 text-dark-grey f-14 w-70 text-wrap"> Value </p>
                            </div> 
                        </x-cards.data>
                    </div>

                    <div class="col-xl-5 col-lg-6 col-md-6"> 
                        <div class="bg-white" style="min-height:10px;max-height:400px;overflow-y:scroll;">
                            <h3 class="m-2">Sales Activity</h3>
                            @foreach ($enq['enquiry_log'] as $item)      
                            <div class="card border-0 b-shadow-4 mt-1">
                                <div class="card-horizontal align-items-center">
                                    <div class="card-body" style="border-right:4px solid blue;max-width:80px;">
                                        <p>{{ $item['timedata'] }}</p>
                                        <p>{{ $item['daymonth'] }}</p>
                                    </div>
                                    <div class="card-body border-0 pl-4">
                                        <div class="row">
                                            <div class="col-10">
                                                <h4 class="card-title f-15 f-w-500 text-darkest-grey mb-0">
                                                {{ $item['lm_log_status'] }}
                                                </h4>
                                                <p>{{ $item['lm_log_description'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-2 bg-white" style="min-height:10px;max-height:400px;overflow-y:scroll;">
                            <h3 class="m-2">Visited Pages</h3>  
                            
                            @foreach($enq['enquiry_shistory'] as $item1) 
                            <div class="card border-0 b-shadow-4">
                                <div class="card-horizontal align-items-center">
                                    <div class="card-body m-4">
                                        <h1>{{ $loop->index + 1 }}</h1>
                                    </div>
                                    <div class="card-body border-0 pl-0">
                                        <div class="row">
                                            <div class="col-10">
                                                <h6 class="card-title f-15 f-w-500 text-darkest-grey mb-0">
                                                {{ $item1['url'] }}
                                                </h6>
                                                <p><i class="fas fa-calendar"></i>  {{ $item1['lastupdated'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-2 bg-white" style="min-height:10px;max-height:400px;overflow-y:scroll;">
                            <h3 class="m-2">Course Feedback</h3>  
                            
                            <div class="card border-0 b-shadow-4">
                                <div class="card-horizontal align-items-center">
                                    <div class="card-body m-4" style="border-right:4px solid blue;max-width:80px;">
                                        <p>02:00</p>
                                        <p>24 oct</p>
                                    </div>
                                    <div class="card-body border-0 pl-0">
                                        <div class="row mt-5">
                                            <div class="col-10">
                                                
                                                <p>Something something something Something something something Something something something Something something something Something something something Something something something Something something somethingSomething something somethingSomething something something</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 bg-white" style="min-height:10px;max-height:400px;overflow-y:scroll;">
                            <h3 class="m-2">Call Feedback</h3>  
                            
                            <div class="card border-0 b-shadow-4">
                                <div class="card-horizontal align-items-center">
                                    <div class="card-body m-4" style="border-right:4px solid blue;max-width:80px;">
                                        <p>02:00</p>
                                        <p>24 oct</p>
                                    </div>
                                    <div class="card-body border-0 pl-0">
                                        <div class="row mt-5">
                                            <div class="col-10">
                                                <p>Something something something Something something something Something something something Something something something Something something something Something something something Something something somethingSomething something somethingSomething something something</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  USER CARDS END -->

        </div>
        <!-- ROW END -->
        
        <!-- ROW START -->
            <div class="row mt-4">
                <div class="col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4 mb-md-0 bg-white p-4">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4" style="min-height:10px;max-height:400px;overflow-y:scroll;">
                        @foreach ($enq['enquiry_history'] as $item2) 
                            <div class="card border-0 b-shadow-4 clickable" id="{{ $item2['lm_enquiry_id'] }}" >
                                <div class="card-horizontal align-items-center">
                                    <div class="card-body m-4">
                                        <h1>{{ $loop->index + 1 }}</h1>
                                    </div>
                                    <div class="card-body border-0 pl-0">
                                        <div class="row">
                                            <div class="col-10">
                                                <h6 class="card-title f-15 f-w-500 text-darkest-grey mb-0">
                                                {{ $item2['lm_enquiry_type'] }} via {{ $item2['lm_enquiry_source'] }} || {{ $item2['lm_enquiry_id'] }}
                                                </h6>
                                                <p>{{ $item2['couse_name'] }}</p>
                                                <p><i class="fas fa-calendar"></i>  {{ $item2['added_datetime'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-8" id="ot-enq">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="card border-0 b-shadow-4 d-none" id="ot-enq-card">
                                        <div class="card-horizontal align-items-center">
                                            <div class="card-img">
                                                <img class="" src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg" alt="">
                                            </div>
                                            <div class="card-body border-0 pl-0">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <h4 class="card-title f-15  text-darkest-grey mb-0" id="enq-name">
                                                            Arya
                                                        </h4>
                                                        <p id="enq-loc">Mumbai,India</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="f-15" >Email : <span id="enq-email">arya@gmail.com</span></p>
                                            <p class="f-15" >Mobile : <span id="enq-mobile">0525399501</span></p>
                                            <p class="f-15" >course : <span id="enq-course">certified ethical hacker</span></p>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6" id="enq-log" style="min-height:10px;max-height:400px;overflow-y:scroll;">
                                    
                                </div>     
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        <!-- ROW END -->
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>


function callPhpApi(enqid) {

    const apiUrl = 'https://nextclickonline.com/testenqapplication/enquirylog'; // Replace with your server's endpoint URL
    const id = enqid; // Replace with the ID you want to send

// Create the data to send as JSON
const data = {
  id: id
};


// Send the POST request
fetch(apiUrl,{
    method: 'POST',
    body: new URLSearchParams(data),
})
  .then(response => {
    if (response.ok) {
      return response.json(); // Parse the response as JSON
    } else {
      throw new Error('Request failed');
    }
  })
  .then(data => {
    // Handle the response data here
    console.log(data.enquiry_log);
            
            $("#enq-log").empty();

            const container = document.getElementById("enq-log");

            data.enquiry_log.forEach(item => {

                const div = document.createElement("div");
                div.className = "card border-0 b-shadow-4";
                div.innerHTML = '<div class="card-horizontal align-items-center"><div class="card-body" style="border-right:4px solid blue;max-width:80px;"><p>' + item.timedata + '</p><p>' + item.daymonth + '</p></div><div class="card-body border-0 pl-4"><div class="row"><div class="col-10"><h4 class="card-title f-15 f-w-500 text-darkest-grey mb-0">' + item.lm_log_status + '</h4><p>' + item.lm_log_description + '</p></div></div></div></div>'
                container.appendChild(div);


            });
  })
  .catch(error => {
    // Handle any errors that occurred during the fetch
    console.error('Error:', error);
  });



}

$(document).ready(function() {


    var myArray = @json($enq['enquiry_history']);

    console.log(myArray);

    $(document).on('click', '.clickable', function(event) {

        var clickedDiv = event.currentTarget;

        var divID = $(clickedDiv).attr('id');

        console.log('Div ' + divID + ' was clicked!');

        const filteredenq = myArray.filter(function (enq) {
            
            return enq.lm_enquiry_id === divID;
    
        })

        $("#ot-enq-card").removeClass("d-none");

        $("#enq-name").text(filteredenq[0].name);
        $("#enq-loc").text(filteredenq[0].location);
        $("#enq-email").text(filteredenq[0].email);
        $("#enq-mobile").text(filteredenq[0].mobile);
        $("#enq-course").text(filteredenq[0].couse_name);

        callPhpApi(divID);


    });

});

</script>

<!-- <script>

fetch('https://jsonplaceholder.typicode.com/todos/1')
      .then(response => response.json())
      .then(data => document.getElementById("demo").innerHTML =JSON.stringify(data[0].title))

</script> -->


