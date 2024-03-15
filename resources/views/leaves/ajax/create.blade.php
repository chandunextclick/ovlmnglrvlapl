{{-- this plugin is used only in leaves create form --}}
@php
    $addLeadAgentPermission = user()->permission('manage_leave_setting');
    $approveRejectPermission = user()->permission('approve_or_reject_leaves');
@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/css/daterangepicker.css') }}">

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-lead-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.leaves.assignLeave')</h4>
                <div class="row p-20">

                    <div class="col-lg-4 col-md-6">
                        @if (isset($defaultAssign))
                            <x-forms.label class="mt-3" fieldId="" :fieldLabel="__('app.name')" fieldRequired="true">
                            </x-forms.label>
                            <input type="hidden" name="user_id" id="user_id" value="{{ $defaultAssign->id }}">
                            <input type="text" value="{{ $defaultAssign->name }}"
                                class="form-control height-35 f-15 readonly-background" readonly>
                        @else
                            <x-forms.select fieldId="user_id" :fieldLabel="__('modules.messages.chooseMember')" fieldName="user_id" search="true"
                                fieldRequired="true">
                                <option value="">--</option>
                                @foreach ($employees as $employee)
                                    <x-user-option :user="$employee" :selected="request()->has('default_assign') &&
                                        request('default_assign') == $employee->id" />
                                @endforeach
                            </x-forms.select>
                        @endif
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <x-forms.label class="mt-3" fieldId="" :fieldLabel="__('modules.leaves.leaveType')" fieldRequired="true">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="leave_type_id" id="leave_type_id"
                                data-live-search="true">
                                <option value="">--</option>
                                @if (isset($leaveTypes))
                                    @foreach ($leaveTypes as $leaveType)
                                        <option value="{{ $leaveType->id }}">{{ mb_ucwords($leaveType->type_name) }}
                                        </option>
                                    @endforeach
                                @endif
                                @if (isset($leaveQuotas))
                                    @foreach ($leaveQuotas as $leaveQuota)
                                        @if ($remainingLeaves == 0 && $leaveQuota->leaveType->id == 15)
                                            <option value="{{ $leaveQuota->leaveType->id }}">
                                                {{ mb_ucwords($leaveQuota->leaveType->type_name) }}
                                            </option>
                                        @endif
                                        @if ($leaveQuota->no_of_leaves > 0 && $leaveQuota->leaveType->id != 15)
                                            <option value="{{ $leaveQuota->leaveType->id }}">
                                                {{ mb_ucwords($leaveQuota->leaveType->type_name) }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>

                            @if ($addLeadAgentPermission == 'all' || $addLeadAgentPermission == 'added')
                                <x-slot name="append">
                                    <button type="button"
                                        class="btn btn-outline-secondary border-grey add-lead-type2"
                                        data-toggle="tooltip" data-original-title="{{ __('modules.leaves.addLeaveType') }}">@lang('app.add')</button>
                                </x-slot>
                            @endif
                        </x-forms.input-group>
                    </div>

                    

                    <div class="col-md-6 col-lg-4" id="divduration">
                        <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100" for="usr">@lang('modules.leaves.selectDuration')</label>
                            <div class="d-block d-lg-flex d-md-flex">
                                <x-forms.radio fieldId="duration_single" :fieldLabel="__('modules.leaves.single')" fieldName="duration"
                                    fieldValue="single" checked="true">
                                </x-forms.radio>
                                <x-forms.radio fieldId="duration_multiple" :fieldLabel="__('modules.leaves.multiple')" fieldValue="multiple"
                                    fieldName="duration"></x-forms.radio>

                                <x-forms.radio fieldId="half_day_first" :fieldLabel="__('modules.leaves.firstHalf')" fieldName="duration"
                                    fieldValue="first_half">
                                </x-forms.radio>
                                <x-forms.radio fieldId="half_day_second" :fieldLabel="__('modules.leaves.secondHalf')" fieldValue="second_half"
                                    fieldName="duration"></x-forms.radio>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 single_date_div">
                        <x-forms.text :fieldLabel="__('app.date')" fieldName="leave_date" fieldId="single_date" :fieldPlaceholder="__('app.date')"
                            :fieldValue="Carbon\Carbon::today()->translatedFormat(company()->date_format)" />
                    </div>

                    <div class="col-lg-4 col-md-6 d-none multi_date_div">
                        <x-forms.text :fieldLabel="__('messages.selectMultipleDates')" fieldName="multi_date" fieldId="multi_date" :fieldPlaceholder="__('messages.selectMultipleDates')"
                            :fieldValue="Carbon\Carbon::today()->translatedFormat(company()->date_format)" />
                    </div>

                    <div class="col-lg-4 col-md-6 optional_date_div d-none">
                        
                        <x-forms.label class="mt-3" fieldId="" :fieldLabel="__('app.date')" fieldRequired="true">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="optional_type_id" id="optional_type_id"
                                data-live-search="true">
                                <option value="">--</option>
                                @if (isset($optionaldates))
                                    @foreach ($optionaldates as $optionaldate)
                                        <option value="{{ $optionaldate->date->format('d-m-Y') }}" data-custom="{{ $optionaldate->occassion }}" data-chkid="0">{{ mb_ucwords($optionaldate->occassion) }} ( {{ mb_ucwords($optionaldate->date->format('d-m-Y')) }} )
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                        </x-forms.input-group>
                    </div>
                    
                    @if ($approveRejectPermission == 'all')
                        <div class="col-lg-4 col-md-6">
                            <x-forms.select fieldId="status" :fieldLabel="__('app.status')" fieldName="status" search="true">
                                <option value="approved">@lang('app.approved')</option>
                                <option value="pending">@lang('app.pending')</option>
                            </x-forms.select>
                        </div>
                    @endif

                    <div class="col-lg-2 col-md-6 mt-5">
                        <p id="users" class="mt-2 badge badge-secondary"></p>
                    </div>

                    

                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('modules.leaves.reason')" fieldName="reason"
                                fieldId="reason" fieldRequired="true" :fieldPlaceholder="__('placeholders.leave.reason')">
                            </x-forms.textarea>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <x-forms.file-multiple class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.add') . ' ' . __('app.file')" fieldName="file" :popover="__('messages.leaveFileMessage')" fieldId="leave-file-upload-dropzone" />
                        <input type="hidden" name="leaveID" id="leaveID">
                    </div>

                </div>

                <x-form-actions>
                    <x-forms.button-primary id="save-leave-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('leaves.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>

{{-- this plugin is used only in leaves create form --}}
<script src="{{ asset('vendor/jquery/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>
<script src="{{ asset('vendor/jquery/daterangepicker.min.js')}}" defer=""></script>


<script>
    $(document).ready(function() {



        Dropzone.autoDiscover = false;
        //Dropzone class
        myDropzone = new Dropzone("div#leave-file-upload-dropzone", {
            dictDefaultMessage: "{{ __('app.dragDrop') }}",
            url: "{{ route('leave-files.store') }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            paramName: "file",
            maxFilesize: DROPZONE_MAX_FILESIZE,
            maxFiles: 10,
            autoProcessQueue: false,
            uploadMultiple: true,
            addRemoveLinks: true,
            parallelUploads: 10,
            acceptedFiles: DROPZONE_FILE_ALLOW,
            init: function() {
                myDropzone = this;
            }
        });
        myDropzone.on('sending', function(file, xhr, formData) {
            var ids = $('#leaveID').val();
            formData.append('leave_id', ids);
        });
        myDropzone.on('uploadprogress', function() {
            $.easyBlockUI();
        });
        myDropzone.on('completemultiple', function() {
            var redirect_url = $('#redirect_url').val();
            if (redirect_url != '') {
                window.location.href = decodeURIComponent(redirect_url);
            }
            window.location.href = "{{ route('leaves.index') }}"
        });

        getDate();
        const dp1 = datepicker('#single_date', {
            onSelect: function () {
                getDate();
            },
            position: 'bl',
            ...datepickerConfig
        });

        const dp2 = $('#multi_date').daterangepicker({
            linkedCalendars: false,
            multidate: true,
            todayHighlight: true,
            format: 'yyyy-mm-d'

        });

        $('input[type=radio][name=duration]').change(function() {

            var leavetypeid=$('#leave_type_id').val();
            var currentDate = new Date();
            if (this.value == 'multiple') {

                const dp2 = $('#multi_date').daterangepicker('clearDates').daterangepicker({
                    linkedCalendars: false,
                    multidate: true,
                    todayHighlight: true,
                    format: 'yyyy-mm-d'

                });
            }

            if(leavetypeid == 10){

                var currentDate = new Date();

                // Add 7 days to the current date
                currentDate.setDate(currentDate.getDate() + 7);

                // Format the date (you can adjust the format as needed)
                var nextweekday = new Date(currentDate.toISOString().split('T')[0]);

                $('#multi_date').daterangepicker({minDate:nextweekday});

            }
            
            
        });

        setMinDate($('#user_id').val());

        $('#user_id').on('change', function(e) {
            setMinDate(e.target.value);
        });

        function setMinDate(employeeID) {
            var employees = @json($employees);

            

            var employee = employees.filter(function(item) {
                return item.id == employeeID;
            });

            console.log(employee);

            $('#optional_type_id').find('option[data-chkid="1"]').remove();

            $('#optional_type_id').selectpicker('refresh');

            if(employees.length > 0 && employee[0] !== undefined)
            {

                var currentDate = new Date();

                var minDate = new Date(employee[0].employee_detail.joining_date);
    

                if(employee[0].employee_detail.date_of_birth){

                    

                        var birthdate = new Date(employee[0].employee_detail.date_of_birth);

                        var year = currentDate.getFullYear();

                        birthdate.setFullYear(year);

                        var month = ('0' + (birthdate.getMonth() + 1)).slice(-2);

                        var day = ('0' + birthdate.getDate()).slice(-2);

                        var birthday = day + '-' + month + '-' + year;

                        console.log(birthdate);

                        console.log(currentDate);
                        

                        if (birthdate > currentDate) {


                            $('#optional_type_id').append($('<option>', {
                                value: birthday,
                                text: 'Birthday(' +birthday+' )',
                                'data-custom': 'Birthday',
                                'data-chkid':'1'
                            }));

                            
                            $('#optional_type_id').selectpicker('refresh');


                            console.log(document.getElementById('optional_type_id')); 

                        }
                        
                }  
                if(employee[0].employee_detail.marriage_anniversary_date){

                    

                    var mrandate = new Date(employee[0].employee_detail.marriage_anniversary_date);

                    

                    var year = currentDate.getFullYear();

                    mrandate.setFullYear(year);

                    var month = ('0' + (mrandate.getMonth() + 1)).slice(-2);

                    var day = ('0' + mrandate.getDate()).slice(-2);

                    var mranday = day + '-' + month + '-' + year;

                    console.log(mrandate,currentDate);

                    if (mrandate > currentDate) {

                        console.log("mrentrytest");

                        $('#optional_type_id').append($('<option>', {
                            value: mranday,
                            text: 'Marriage Anniversary(' +mranday+' )',
                            'data-custom': 'MarriageAnniversary'
                        }));
                        


                    }

                }    

            
                dp1.setMin(minDate);
                $('#multi_date').daterangepicker('setStartDate', minDate);
            }
        }

        $('#save-leave-form').click(function() {
            var dateRange = $('#multi_date').data('daterangepicker');
            startDate = dateRange.startDate.format('{{ company()->moment_date_format }}');
            endDate = dateRange.endDate.format('{{ company()->moment_date_format }}');
            
            var multiDate = [];
            multiDate = [startDate, endDate];
            $('#multi_date').val(multiDate);

            const url = "{{ route('leaves.store') }}";

            $.easyAjax({
                url: url,
                container: '#save-lead-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-leave-form",
                data: $('#save-lead-data-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        $('#leaveID').val(response.leaveID);
                        myDropzone.processQueue();
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        $('body').on('click', '.add-lead-type2', function() {
            var url = "{{ route('leaveType.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $("input[name=duration]").click(function() {
            ($('input[name=duration]:checked').val() == "multiple") ? getDate('multiple') : getDate();

            $(this).val() == 'multiple' ? $('.multi_date_div').removeClass('d-none') : $(
                '.multi_date_div').addClass('d-none');
            $(this).val() == 'multiple' ? $('.single_date_div').addClass('d-none') : $(
                '.single_date_div').removeClass('d-none');
        });

        $('#user_id').change(function() {
            var id = $(this).val();
            if (id == '') {
                id = 0;
            }
            // console.log("hee");
            var url = "{{ route('employee-leaves.employee_leave_types', ':id') }}";
            url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                type: "GET",
                container: '#save-lead-data-form',
                blockUI: true,
                success: function(data) {
                    // $('#leave_type_id').html(data.data);
                    $('#leave_type_id').selectpicker('refresh');
                }
            })
        });


        $('#leave_type_id').change(function() {

            console.log($(this).val());
            var currentDate = new Date();
        

            if($(this).val() == '11'){

                $('#single_date').prop('readonly', true);

                $('#reason').prop('readonly', false);

                $('#reason').val('');

                $('.single_date_div').removeClass('d-none');

                $('#divduration').removeClass('d-none')

                $('.optional_date_div').addClass('d-none');

    

            }else if($(this).val() == '12'){

                $('#single_date').prop('readonly', true);

                $('#reason').prop('readonly', false);

                $('#reason').val('');
                
                $('.optional_date_div').removeClass('d-none');

                $('.single_date_div').addClass('d-none');

                $('.multi_date_div').addClass('d-none');

                $('#divduration').addClass('d-none')


            }else if($(this).val() == '15'){

                $('#single_date').prop('readonly', true);

                $('#reason').prop('readonly', false);

                $('#reason').val('');
                
                $('.single_date_div').removeClass('d-none');

                $('#divduration').removeClass('d-none')

                $('.optional_date_div').addClass('d-none');


            }else{

                $('#single_date').prop('readonly', true);

                $('#reason').prop('readonly', false);

                $('#reason').val('');

                $('.optional_date_div').addClass('d-none');

                $('.single_date_div').removeClass('d-none');

                $('#divduration').removeClass('d-none')

                // Add 7 days to the current date
                currentDate.setDate(currentDate.getDate() + 7);

                // Format the date (you can adjust the format as needed)
                var nextweekday = new Date(currentDate.toISOString().split('T')[0]);

                
                console.log(nextweekday);
                

                dp1.setDate(new Date(nextweekday));

                dp1.setMin(nextweekday);


            }

            

        });


        $('#optional_type_id').change(function () {
            // Get the selected option
            var selectedOption = $('option:selected', this);

            // Get the value of the 'data-custom' attribute
            var customAttributeValue = selectedOption.data('custom');

            var optionaldate = $('#optional_type_id').val();

            const optionaldatear = optionaldate.split(" ");

            $('#single_date').val(optionaldatear[0]);


            // Display the value
            $('#reason').val(customAttributeValue);

            $('#reason').prop('readonly', true);

        });


        function getDate(value) {
            let url = "{{ route('leaves.date') }}";
            date = $('#single_date').val();

            if (value == 'multiple') {
                date = '';
            }

            $.easyAjax({
                type: 'GET',
                url: url,
                container: '#save-lead-data-form',
                data: {
                    'date': date
                },
                success: function(response) {
                    if(response.status == 'success'){
                        if(response.users > 0 && response.users < 2){
                            $('#users').text(response.users+' @lang('modules.leaves.employeeOnLeave')');
                        }else if(response.users > 0){
                            $('#users').text(response.users+' @lang('modules.leaves.employeesOnLeave')');
                        }else{
                            $('#users').text('');
                        }
                    }
                }
            });
        };

        init(RIGHT_MODAL);
    });
</script>
