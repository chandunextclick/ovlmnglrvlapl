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
<div class="row">
    <div class="col-sm-12">
        <x-form id="save-holiday-data-form" method="post">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('app.add') @lang('app.menu.toppagesbyclick')</h4>
                <div class="row pl-20 pr-20 pt-20">
                    <div class="col-lg-3">
                        <!-- <x-forms.text class="date-picker" :fieldLabel="__('app.date')" fieldName="date[]"
                            fieldId="dateField1" :fieldPlaceholder="__('app.date')" fieldValue=""
                            fieldRequired="true" /> -->
                            <select class="form-control height-35 f-14 mt-5" placeholder="yearmonth"  name="yearmonth[]" id="yearmonth1"  required>
                            
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
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="form-group my-3">
                        <input type="text" name="url[]" id="url1" class="form-control height-35 f-14 mt-5" placeholder="URL">
                        </div>
                    </div>
                    <div class="col-lg-3 align-items-center justify-content-center">
                        <div class="form-group my-5">
                        <input type="text" name="clicks[]" id="clicks1" class="form-control height-35 f-14 mt-5" placeholder="No of Clicks">
                        </div>  
                    </div>
                </div>

                <div id="insertBefore"></div>

                <!--  ADD ITEM START-->
                <div class="row px-lg-4 px-md-4 px-3 pb-3 pt-0 mb-3  mt-2">
                    <div class="col-md-12">
                        <a class="f-15 f-w-500" href="javascript:;" id="add-item"><i
                                class="icons icon-plus font-weight-bold mr-1"></i> @lang('app.add')</a>
                    </div>
                </div>
                <!--  ADD ITEM END-->

                <x-form-actions>
                    <x-forms.button-primary id="save-holiday-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('holidays.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

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

        var $insertBefore = $('#insertBefore');
        var i = 1;

        // Add More Inputs
        $('#add-item').click(function() {
            i += 1;

            $(`<div id="addMoreBox${i}" class="row pl-20 pr-20 clearfix">
                <div class="col-lg-3 col-md-6 col-12"><select class="form-control height-35 f-14 mt-5" placeholder="yearmonth"  name="yearmonth[]" id="yearmonth${i}"required><?php $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 6 months');$end = strtotime(date('Y').'-'.date('m').'-'.date('j').' + 1 months');while($month < $end){ $selected = (date('F Y', $month)==$yearmonth)? ' selected' :''; ?><option <?= $selected ?> value="<?= date('F Y', $month) ?>"><?=date('F Y', $month)?></option><?php $month = strtotime("+1 month", $month);}?></select> 
                </div>  <div class="col-lg-4 col-md-5 col-10"> <div class="form-group my-3">
                <input type="text" name="url[]" id="url${i}" class="form-control height-35 f-14 mt-5" placeholder="URL">
                </div> </div> <div class="col-lg-3 align-items-center justify-content-center"><div class="form-group my-5"><input type="text" name="clicks[]" id="clicks${i}" class="form-control height-35 f-14 mt-5" placeholder="No of Clicks"></div>  
                </div> <div class="col-lg-2 col-md-1 col-2"><a href="javascript:;" class="d-flex align-items-center justify-content-center mt-5 remove-item" data-item-id="${i}"><i class="fa fa-times-circle f-20 text-lightest"></i></a></div></div>`)
                .insertBefore($insertBefore);

           
        });



        // Remove fields
        $('body').on('click', '.remove-item', function() {
            var index = $(this).data('item-id');
            $('#addMoreBox' + index).remove();
        });

    

        $('#save-holiday-form').click(function() {



    

            var formData = $('#save-holiday-data-form').serializeArray();

        

            const url = "{{ route('rankings.storetoppages') }}";
            $.easyAjax({
                url: url,
                container: '#save-holiday-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-holiday-form",
                data: formData,
                success: function(response) {
                    window.location.href = response.redirectUrl;
                }
            });
        });

        init(RIGHT_MODAL);


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
