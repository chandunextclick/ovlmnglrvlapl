@php
$addDesignationPermission = user()->permission('add_designation');
@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/tagify.css') }}">

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-employee-data-form">

            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.employees.accountDetails')</h4>
                <div class="row p-20">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="employee_id" :fieldLabel="__('modules.employees.employeeId')"
                                    fieldName="employee_id" :fieldValue="($lastEmployeeID+1)" fieldRequired="true"
                                    :fieldPlaceholder="__('modules.employees.employeeIdInfo')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="name" :fieldLabel="__('modules.employees.employeeName')"
                                    fieldName="name" fieldRequired="true" :fieldPlaceholder="__('placeholders.name')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="email" :fieldLabel="__('modules.employees.employeeEmail')"
                                    fieldName="email" fieldRequired="true" :fieldPlaceholder="__('placeholders.email')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.datepicker fieldId="date_of_birth" :fieldLabel="__('modules.employees.dateOfBirth')"
                                    fieldName="date_of_birth" :fieldPlaceholder="__('placeholders.date')" />
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.label class="my-3" fieldId="category_id"
                                    :fieldLabel="__('app.designation')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="designation"
                                        id="employee_designation" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </x-forms.input-group>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.label class="my-3" fieldId="category_id"
                                    :fieldLabel="__('app.department')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="department"
                                        id="employee_department" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                        @endforeach
                                    </select>
                                </x-forms.input-group>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <x-forms.file allowedFileExtensions="png jpg jpeg svg" class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('modules.profile.profilePicture')" fieldName="image" fieldId="image"
                            fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="country" :fieldLabel="__('app.country')" fieldName="country"
                            search="true">
                            <option value="">--</option>
                            @foreach ($countries as $item)
                                <option data-tokens="{{ $item->iso3 }}"
                                    data-content="<span class='flag-icon flag-icon-{{ strtolower($item->iso) }} flag-icon-squared'></span> {{ $item->nicename }}"
                                    value="{{ $item->id }}">{{ $item->nicename }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.tel fieldId="mobile" :fieldLabel="__('app.mobile')" fieldName="mobile"
                           :fieldPlaceholder="__('placeholders.mobile')"></x-forms.tel>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="gender" :fieldLabel="__('modules.employees.gender')"
                            fieldName="gender">
                            <option value="male">@lang('app.male')</option>
                            <option value="female">@lang('app.female')</option>
                            <option value="others">@lang('app.others')</option>
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.datepicker fieldId="joining_date" :fieldLabel="__('modules.employees.joiningDate')"
                            fieldName="joining_date" :fieldPlaceholder="__('placeholders.date')" fieldRequired="true"
                            :fieldValue="now(company()->timezone)->format(company()->date_format)" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="reporting_to" :fieldLabel="__('modules.employees.reportingTo')"
                            fieldName="reporting_to" :fieldPlaceholder="__('placeholders.date')" search="true">
                            <option value="">--</option>
                            @foreach ($employees as $item)
                                <x-user-option :user="$item" />
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="locale" :fieldLabel="__('app.language')"
                            fieldName="locale" search="true">
                            @foreach ($languages as $language)
                                <option {{ user()->locale == $language->language_code ? 'selected' : '' }}
                                data-content="<span class='flag-icon flag-icon-{{ ($language->flag_code == 'en') ? 'gb' : strtolower($language->flag_code) }} flag-icon-squared'></span> {{ $language->language_name }}"
                                value="{{ $language->language_code }}">{{ $language->language_name }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="role" :fieldLabel="__('app.role')" fieldName="role">
                            @foreach ($roles as $role)
                                <option {{ ($role->name == 'employee') ? 'selected' : '' }} value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.address')"
                                fieldName="address" fieldId="address" :fieldPlaceholder="__('placeholders.address')">
                            </x-forms.textarea>
                        </div>
                    </div>
                    <!-- <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.about')"
                                fieldName="about_me" fieldId="about_me" fieldPlaceholder="">
                            </x-forms.textarea>
                        </div>
                    </div> -->
                    <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="guardianname" :fieldLabel="__('modules.employees.nameGuardian')"
                                    fieldName="guardianname" fieldRequired="true" :fieldPlaceholder="__('placeholders.name')">
                                </x-forms.text>
                    </div>
                    <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="aadhar" :fieldLabel="__('modules.employees.aadhar')"
                                    fieldName="aadhar" fieldRequired="true" :fieldPlaceholder="__('placeholders.aadhar')" >
                                </x-forms.text>
                    </div>
                    <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="accno" :fieldLabel="__('modules.employees.accountNo')"
                                    fieldName="accno" fieldRequired="true" :fieldPlaceholder="__('placeholders.accountno')">
                                </x-forms.text>
                    </div>
                    <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="panno" :fieldLabel="__('modules.employees.panNo')"
                                    fieldName="panno" fieldRequired="false" :fieldPlaceholder="__('placeholders.panno')">
                                </x-forms.text>
                    </div>
                    <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="esino" :fieldLabel="__('modules.employees.esiNo')"
                                    fieldName="esino" fieldRequired="false" :fieldPlaceholder="__('placeholders.esino')">
                                </x-forms.text>
                    </div>

                </div>


                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.employees.nomineeDetails')</h4>
                <div class="row p-20">
                    <div class="col-lg-9">
                        <div class="row">
                            
                            <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="nominee" :fieldLabel="__('modules.employees.nomineeName')"
                                    fieldName="nominee" fieldRequired="true" :fieldPlaceholder="__('placeholders.name')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="relation" :fieldLabel="__('modules.employees.relationEmployee')"
                                    fieldName="relation" fieldRequired="true" :fieldPlaceholder="__('placeholders.relation')">
                                </x-forms.text>
                            </div>
                           
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.tel fieldId="guardianmobile" :fieldLabel="__('app.mobile')" fieldName="guardianmobile" fieldRequired="true"
                           :fieldPlaceholder="__('placeholders.mobile')"></x-forms.tel>
                    </div>
                
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.address')"
                                fieldName="guardianaddress" fieldId="guardianaddress" fieldRequired="true" :fieldPlaceholder="__('placeholders.address')">
                            </x-forms.textarea>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="guardianaadhar" :fieldLabel="__('modules.employees.aadhar')"
                                    fieldName="guardianaadhar" fieldRequired="true" :fieldPlaceholder="__('placeholders.aadhar')">
                                </x-forms.text>
                    </div>
                </div>

                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.employees.familyMembers')</h4>
                <div class="row p-20" id="memberDetails">

                        <div class="row member-row m-2" data-row-id="1">
                            
                            <div class="col-lg-3 col-md-6">
                                <x-forms.text fieldId="member1" :fieldLabel="__('modules.employees.name')"
                                    fieldName="member[]" fieldRequired="true" :fieldPlaceholder="__('placeholders.name')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <x-forms.text fieldId="mbrelation1" :fieldLabel="__('modules.employees.relationEmployee')"
                                    fieldName="mbrelation[]" fieldRequired="true" :fieldPlaceholder="__('placeholders.relation')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-3 col-md-6">
                            <x-forms.datepicker fieldId="date_of_birth1" :fieldLabel="__('modules.employees.dateOfBirth')"
                                    fieldName="mbdate_of_birth[]" fieldRequired="true" :fieldPlaceholder="__('placeholders.date')" />
                            </div>
                            <div class="col-lg-3 col-md-6">
                            <x-forms.tel fieldId="mbmobile1" :fieldLabel="__('app.mobile')" fieldName="mbmobile[]" fieldRequired="true"
                           :fieldPlaceholder="__('placeholders.mobile')"></x-forms.tel>
                            </div>     
                        </div>
                    
                </div>
                
                <button type="button" class="btn btn-secondary m-3" id="addMore">@lang('app.AddMore')</button>

                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                    @lang('modules.client.clientOtherDetails')</h4>
                <div class="row p-20">
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100"
                                for="usr">@lang('modules.client.clientCanLogin')</label>
                            <div class="d-flex">
                                <x-forms.radio fieldId="login-yes" :fieldLabel="__('app.yes')" fieldName="login"
                                    fieldValue="enable" checked="true">
                                </x-forms.radio>
                                <x-forms.radio fieldId="login-no" :fieldLabel="__('app.no')" fieldValue="disable"
                                    fieldName="login"></x-forms.radio>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100"
                                for="usr">@lang('modules.emailSettings.emailNotifications')</label>
                            <div class="d-flex">
                                <x-forms.radio fieldId="notification-yes" :fieldLabel="__('app.yes')" fieldValue="yes"
                                    fieldName="email_notifications" checked="true">
                                </x-forms.radio>
                                <x-forms.radio fieldId="notification-no" :fieldLabel="__('app.no')" fieldValue="no"
                                    fieldName="email_notifications">
                                </x-forms.radio>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.label class="my-3" fieldId="hourly_rate"
                            :fieldLabel="__('modules.employees.hourlyRate')"></x-forms.label>
                        <x-forms.input-group>
                            <x-slot name="prepend">
                                <span
                                    class="input-group-text f-14 bg-white-shade">{{ company()->currency->currency_symbol }}</span>
                            </x-slot>

                            <input type="number" step=".01" min="0" class="form-control height-35 f-14"
                                name="hourly_rate" id="hourly_rate">
                        </x-forms.input-group>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.label class="my-3" fieldId="slack_username"
                            :fieldLabel="__('modules.employees.slackUsername')"></x-forms.label>
                        <x-forms.input-group>
                            <x-slot name="prepend">
                                <span class="input-group-text f-14 bg-white-shade">@</span>
                            </x-slot>

                            <input type="text" class="form-control height-35 f-14" name="slack_username"
                                id="slack_username">
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-12">
                        <x-forms.text fieldId="tags" :fieldLabel="__('app.skills')" fieldName="tags"
                            :fieldPlaceholder="__('placeholders.skills')" />
                    </div>

                    @if (function_exists('sms_setting') && sms_setting()->telegram_status)
                        <div class="col-md-4">
                            <x-forms.number fieldName="telegram_user_id" fieldId="telegram_user_id"
                                fieldLabel="<i class='fab fa-telegram'></i> {{ __('sms::modules.telegramUserId') }}"
                                :popover="__('sms::modules.userIdInfo')" />
                        </div>
                    @endif
                    <div class="col-lg-3 col-md-6">
                        <x-forms.datepicker fieldId="probation_end_date" :fieldLabel="__('modules.employees.probationEndDate')"
                            fieldName="probation_end_date" :fieldPlaceholder="__('placeholders.date')"
                            :popover="__('messages.probationEndDate')"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.datepicker fieldId="notice_period_start_date" :fieldLabel="__('modules.employees.noticePeriodStartDate')"
                            fieldName="notice_period_start_date" :fieldPlaceholder="__('placeholders.date')"
                            :popover="__('messages.noticePeriodStartDate')"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.datepicker fieldId="notice_period_end_date" :fieldLabel="__('modules.employees.noticePeriodEndDate')"
                            fieldName="notice_period_end_date" :fieldPlaceholder="__('placeholders.date')"
                            :popover="__('messages.noticePeriodEndDate')"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="employment_type" :fieldLabel="__('modules.employees.employmentType')"
                            fieldName="employment_type" :fieldPlaceholder="__('placeholders.date')">
                            <option value="">--</option>
                            <option value="full_time">@lang('app.fullTime')</option>
                            <option value="part_time">@lang('app.partTime')</option>
                            <option value="on_contract">@lang('app.onContract')</option>
                            <option value="internship">@lang('app.internship')</option>
                            <option value="trainee">@lang('app.trainee')</option>
                        </x-forms.select>
                    </div>

                    <div class="col-lg-3 col-md-6 d-none internship-date">
                        <x-forms.datepicker fieldId="internship_end_date" :fieldLabel="__('modules.employees.internshipEndDate')"
                            fieldName="internship_end_date" :fieldPlaceholder="__('placeholders.date')"/>
                    </div>
                    <div class="col-lg-3 col-md-6 d-none contract-date">
                        <x-forms.datepicker fieldId="contract_end_date" :fieldLabel="__('modules.employees.contractEndDate')"
                            fieldName="contract_end_date" :fieldPlaceholder="__('placeholders.date')"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="marital_status" :fieldLabel="__('modules.employees.maritalStatus')"
                            fieldName="marital_status" :fieldPlaceholder="__('placeholders.date')">
                            <option value="unmarried">Unmarried</option>
                            <option value="married">Married</option>
                        </x-forms.select>
                    </div>

                    <div class="col-lg-3 col-md-6 d-none marriage_date">
                        <x-forms.datepicker fieldId="marriage_anniversary_date" :fieldLabel="__('modules.employees.marriageAnniversaryDate')"
                            fieldName="marriage_anniversary_date" :fieldPlaceholder="__('placeholders.date')"/>
                    </div>

                    <input type ="hidden" name="add_more" value="false" id="add_more" />

                </div>
                <x-forms.custom-field :fields="$fields"></x-forms.custom-field>

                <x-form-actions>
                    <x-forms.button-primary id="save-employee-form" class="mr-3" icon="check">
                        @lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-secondary class="mr-3" id="save-more-employee-form" icon="check-double">@lang('app.saveAddMore')
                    </x-forms.button-secondary>
                    <x-forms.button-cancel class="border-0 " data-dismiss="modal">@lang('app.cancel')
                    </x-forms.button-cancel>

                </x-form-actions>
            </div>
        </x-form>

    </div>
</div>

<script src="{{ asset('vendor/jquery/tagify.min.js') }}"></script>
<script>
    $(document).ready(function() {

        var rowCount=1;

        $('#addMore').on('click', function () {

            rowCount++; // Increment the row count

            // Clone the first row and update attributes
            var newRow = $('.member-row[data-row-id="1"]').clone();
            newRow.attr('data-row-id', rowCount);

            // Update IDs and names to ensure uniqueness
            newRow.find('[id^="member"]').attr('id', 'member' + rowCount).attr('name', 'member[' + rowCount + ']');
            newRow.find('[id^="mbrelation"]').attr('id', 'mbrelation' + rowCount).attr('name', 'mbrelation[' + rowCount + ']');
            newRow.find('[id^="date_of_birth"]').attr('id', 'date_of_birth' + rowCount).attr('name', 'mbdate_of_birth[' + rowCount + ']');
            newRow.find('[id^="mbmobile"]').attr('id', 'mbmobile' + rowCount).attr('name', 'mbmobile[' + rowCount + ']');

            // Append the new row to the memberDetails div
            $('#memberDetails').append(newRow);


            datepicker('#date_of_birth'+rowCount, {
            position: 'bl',
            ...datepickerConfig
            });

        });


        if ($('.custom-date-picker').length > 0) {
            datepicker('.custom-date-picker', {
                position: 'bl',
                ...datepickerConfig
            });
        }

        datepicker('#joining_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#probation_end_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#notice_period_start_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#notice_period_end_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#marriage_anniversary_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#date_of_birth1', {
            position: 'bl',
            maxDate: new Date(),
            ...datepickerConfig
        });

        datepicker('#date_of_birth', {
            position: 'bl',
            maxDate: new Date(),
            ...datepickerConfig
        });

        datepicker('#internship_end_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#contract_end_date', {
            position: 'bl',
            ...datepickerConfig
        });

        $('#marital_status').change(function(){
            var value = $(this).val();
            if(value == 'married') {
                $('.marriage_date').removeClass('d-none');
            }
            else {
                $('.marriage_date').addClass('d-none');
            }
        })

        $('#employment_type').change(function(){
            var value = $(this).val();
            if(value == 'on_contract') {
                $('.contract-date').removeClass('d-none');
            }
            else {
                $('.contract-date').addClass('d-none');
            }

            if(value == 'internship') {
                $('.internship-date').removeClass('d-none');
            }
            else {
                $('.internship-date').addClass('d-none');
            }
        })
        var input = document.querySelector('input[name=tags]'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input);

        $('#save-more-employee-form').click(function() {

            $('#add_more').val(true);

            const url = "{{ route('employees.store') }}";
            var data = $('#save-employee-data-form').serialize();
            saveEmployee(data, url, "#save-more-employee-form");


        });

        $('#save-employee-form').click(function() {

            const url = "{{ route('employees.store') }}";
            var data = $('#save-employee-data-form').serialize();
            saveEmployee(data, url, "#save-employee-form");

        });

        function saveEmployee(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-employee-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: buttonSelector,
                file: true,
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        }
                        else if(response.add_more == true) {

                            var right_modal_content = $.trim($(RIGHT_MODAL_CONTENT).html());

                            if(right_modal_content.length) {

                                $(RIGHT_MODAL_CONTENT).html(response.html.html);
                                $('#add_more').val(false);
                            }
                            else {

                                $('.content-wrapper').html(response.html.html);
                                init('.content-wrapper');
                                $('#add_more').val(false);
                            }

                        }
                        else {

                            window.location.href = response.redirectUrl;

                        }

                        if (typeof showTable !== 'undefined' && typeof showTable === 'function') {
                            showTable();
                        }

                    }

                }
            });
        }

        $('#random_password').click(function() {
            const randPassword = Math.random().toString(36).substr(2, 8);

            $('#password').val(randPassword);
        });

        $('#designation-setting-add').click(function() {
            const url = "{{ route('designations.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        })

        $('.department-setting').click(function() {
            const url = "{{ route('departments.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        init(RIGHT_MODAL);
    });

    function checkboxChange(parentClass, id) {
        var checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }

    $('.cropper').on('dropify.fileReady', function(e) {
        var inputId = $(this).find('input').attr('id');
        var url = "{{ route('cropper', ':element') }}";
        url = url.replace(':element', inputId);
        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });
</script>
