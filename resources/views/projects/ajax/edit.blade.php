@php
$addProjectCategoryPermission = user()->permission('manage_project_category');
$addClientPermission = user()->permission('add_clients');
$editProjectMemberPermission = user()->permission('edit_project_members');
$addEmployeePermission = user()->permission('add_employees');
$addProjectMemberPermission = user()->permission('add_project_members');
$addProjectMemberPermission = user()->permission('add_project_members');
$createPublicProjectPermission = user()->permission('create_public_project');

@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-project-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.projects.projectInfo')</h4>
                <div class="row p-20">
                    <div class="col-lg-6 col-md-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('modules.taskShortCode')"
                            fieldName="project_code" fieldRequired="true" fieldId="project_code"
                            :fieldPlaceholder="__('placeholders.writeshortcode')" :fieldValue="$project->project_short_code" :fieldReadOnly="true"/>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('modules.projects.projectName')"
                            fieldName="project_name" fieldRequired="true" fieldId="project_name"
                            :fieldValue="$project->project_name" :fieldPlaceholder="__('placeholders.project')" />
                    </div>
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="col-md-6 col-lg-4">
                        <x-forms.datepicker fieldId="start_date" fieldRequired="true"
                            :fieldLabel="__('modules.projects.startDate')" fieldName="start_date"
                            :fieldValue="$project->start_date->format(company()->date_format)"
                            :fieldPlaceholder="__('placeholders.date')" />
                    </div>

                    <div class="col-md-6 col-lg-4" id="deadlineBox">
                        <x-forms.datepicker fieldId="deadline" fieldRequired="true"
                            :fieldLabel="__('modules.projects.deadline')" fieldName="deadline"
                            :fieldValue="($project->deadline ? $project->deadline->format(company()->date_format) : '')"
                            :fieldPlaceholder="__('placeholders.date')" />
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="form-group">
                            <div class="d-flex mt-5">
                                <x-forms.checkbox fieldId="without_deadline"
                                    :checked="($project->deadline == null) ? true : false"
                                    :fieldLabel="__('modules.projects.withoutDeadline')" fieldName="without_deadline" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="category_id"
                            :fieldLabel="__('modules.projects.projectCategory')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="category_id" id="project_category_id"
                                data-live-search="true">
                                <option value="">--</option>
                                @foreach ($categories as $category)
                                    <option @if ($project->category_id == $category->id) selected @endif value="{{ $category->id }}">
                                        {{ mb_ucwords($category->category_name) }}</option>
                                @endforeach
                            </select>

                            @if ($addProjectCategoryPermission == 'all' || $addProjectCategoryPermission == 'added')
                                <x-slot name="append">
                                    <button id="addProjectCategory" type="button"
                                        class="btn btn-outline-secondary border-grey"
                                        data-toggle="tooltip" data-original-title="{{__('modules.projectCategory.addProjectCategory') }}">@lang('app.add')</button>
                                </x-slot>
                            @endif
                        </x-forms.input-group>
                    </div>

                    @if (!in_array('client', user_roles()))
                        <div class="col-md-4">
                            <x-forms.label class="my-3" fieldId="department" :fieldLabel="__('app.department')">
                            </x-forms.label>
                            <x-forms.input-group>
                                <select class="form-control multiple-users" multiple name="team_id[]" id="employee_department"
                                    data-live-search="true">
                                    @foreach ($teams as $team)
                                            @php
                                                $selected = '';
                                            @endphp

                                            @foreach ($projectteams as $prjteam)
                                                @if ($prjteam->team_id == $team->id)
                                                    @php
                                                        $selected = 'selected';
                                                    @endphp
                                                @endif
                                                
                                            @endforeach
                                           
                                             <option value="{{$team->id}}" {{$selected}}>{{$team->team_name}}</option>
                                          
                                        @endforeach
                                </select>
                            </x-forms.input-group>
                        </div>
                    @endif

                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="client_id" :fieldLabel="__('app.client')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="client_id" id="client_id"
                                data-live-search="true" data-size="8">
                                <option value="">--</option>
                                @foreach ($clients as $client)
                                    <x-user-option :user="$client" :selected="$project->client_id == $client->id"/>
                                @endforeach
                            </select>

                            @if ($addClientPermission == 'all' || $addClientPermission == 'added')
                                <x-slot name="append">
                                    <button id="add-client" type="button"
                                        class="btn btn-outline-secondary border-grey"
                                        data-toggle="tooltip" data-original-title="{{__('modules.client.addNewClient') }}">@lang('app.add')</button>
                                </x-slot>
                            @endif
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="form-group my-3">
                            <x-forms.label class="my-3" fieldId="project_summary"
                                :fieldLabel="__('modules.projects.projectSummary')">
                            </x-forms.label>
                            <div id="project_summary">{!! $project->project_summary !!}</div>
                            <textarea name="project_summary" id="project_summary-text"
                                class="d-none">{!! $project->project_summary !!}</textarea>
                        </div>
                    </div>

                    @if ($project->public == 1 && $createPublicProjectPermission == 'all')
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="d-flex mt-2">
                                    <x-forms.checkbox fieldId="is_private"
                                        :fieldLabel="__('modules.projects.createPrivateProject')" fieldName="private" />
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($project->public == 0 && $createPublicProjectPermission == 'all')
                        <div class="col-sm-12 d-none">
                            <div class="form-group">
                                <div class="d-flex mt-2">
                                    <x-forms.checkbox fieldId="is_public"
                                        :fieldLabel="__('modules.projects.changeToPublicProject')" fieldName="public" />
                                </div>
                            </div>
                        </div>
                    @endif


                    @if ($editProjectMembersPermission == 'all' || $editPermission == 'all')
                        <div class="col-md-12 @if ($project->public == 1) d-none @endif d-none" id="edit_members">
                           <div class="form-group my-3">
                                <x-forms.label fieldId="selectAssignee" :fieldLabel="__('modules.tasks.assignTo')">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control multiple-users" multiple name="member_id[]"
                                        id="selectEmployee" data-live-search="true" data-size="8">
                                        @foreach ($employees as $item)
                                            @php
                                                $selected = '';
                                            @endphp

                                            @foreach ($project->members as $member)
                                                @if ($member->user->id == $item->id)
                                                    @php
                                                        $selected = 'selected';
                                                    @endphp
                                                @endif
                                            @endforeach
                                            <x-user-option :user="$item" :selected="$selected" :pill="true"/>
                                        @endforeach
                                    </select>
                                    @if ($addEmployeePermission == 'all' || $addEmployeePermission == 'added')
                                        <x-slot name="append">
                                            <button id="add-employee" type="button"
                                                class="btn btn-outline-secondary border-grey">@lang('app.add')</button>
                                        </x-slot>
                                    @endif
                                </x-forms.input-group>
                            </div>
                        </div>
                    @endif

                    @if ($project->public == 1 && $editProjectMembersPermission || $editPermission == 'all')
                        <div class="col-md-12 d-none" id="add_members">
                            <div class="form-group my-3">
                                <x-forms.label class="my-3" fieldId="selectEmployee" fieldRequired="true"
                                    :fieldLabel="__('modules.projects.addMemberTitle')">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control multiple-users" multiple name="user_id[]"
                                        id="selectEmployee" data-live-search="true" data-size="8">
                                        @if ($employees != '')

                                            @foreach ($employees as $item)
                                                <x-user-option
                                                    :user="$item"
                                                    :pill="true"
                                                    :selected="request()->has('default_assign') && request('default_assign') == $item->id ||(isset($projectTemplateMembers) && in_array($item->id, $projectTemplateMembers))"
                                                />

                                            @endforeach
                                        @endif
                                    </select>

                                    @if ($addEmployeePermission == 'all' || $addEmployeePermission == 'added')
                                        <x-slot name="append">
                                            <button id="add-employee" type="button"
                                                class="btn btn-outline-secondary border-grey">@lang('app.add')</button>
                                        </x-slot>
                                    @endif
                                </x-forms.input-group>
                            </div>
                        </div>
                    @elseif(in_array('employee', user_roles()))
                        <input type="hidden" name="user_id[]" value="{{ user()->id }}">
                    @endif

                    <div class="col-md-12 col-lg-4">
                        <x-forms.select fieldId="project_status"
                            :fieldLabel="__('app.project') . ' ' . __('app.status')" fieldName="status" search="true">
                            @foreach ($projectStatus as $status)
                                <option
                                data-content="<i class='fa fa-circle mr-1 f-15' style='color:{{$status->color}}'></i>{{ ucfirst($status->status_name) }}"
                                @if ($project->status == $status->status_name)
                                selected @endif
                                value="{{$status->status_name}}">$status->status_name
                                </option>

                            @endforeach


                        </x-forms.select>
                    </div>

                    <div class="col-md-12 col-lg-4">
                        <x-forms.range class="mr-0 mr-lg-2 mr-md-2"
                            :disabled="($project->calculate_task_progress == 'true' ? 'true' : 'false')"
                            :fieldLabel="__('modules.projects.projectCompletionStatus')" fieldName="completion_percent"
                            fieldId="completion_percent" :fieldValue="$project->completion_percent" />
                    </div>

                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            <div class="d-flex mt-5">
                                <x-forms.checkbox fieldId="calculate-task-progress"
                                    :checked="($project->calculate_task_progress == 'true') ? true : false"
                                    :fieldLabel="__('modules.projects.calculateTasksProgress')"
                                    fieldName="calculate_task_progress" />
                            </div>
                        </div>
                    </div>


                </div>

                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                    @lang('modules.client.clientOtherDetails')</h4>

                <div class="row p-20">
                    <div class="col-lg-4">
                        <x-forms.select fieldId="currency_id" :fieldLabel="__('modules.invoices.currency')"
                            fieldName="currency_id" search="true">
                            @foreach ($currencies as $currency)
                                <option @if ($currency->id == $project->currency_id) selected @endif value="{{ $currency->id }}">
                                    {{ $currency->currency_symbol . ' (' . $currency->currency_code . ')' }}
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <x-forms.number class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('modules.projects.projectBudget')"
                            fieldName="project_budget" fieldId="project_budget" :fieldValue="$project->project_budget"
                            :fieldPlaceholder="__('placeholders.price')" />
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <x-forms.number class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('modules.projects.hours_allocated')"
                            fieldName="hours_allocated" fieldId="hours_allocated"
                            :fieldValue="$project->hours_allocated" :fieldPlaceholder="__('placeholders.hourEstimate')" />
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <div class="d-flex mt-5">
                                <x-forms.checkbox fieldId="manual_timelog"
                                    :fieldLabel="__('modules.projects.manualTimelog')" :checked="($project->manual_timelog
                                    == 'enable')" fieldName="manual_timelog" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4" id="clientNotification">
                        <div class="form-group">
                            <div class="d-flex mt-5">
                                <x-forms.checkbox fieldId="client_task_notification" :checked="($project->allow_client_notification
                                == 'enable')"
                                    :fieldLabel="__('modules.projects.clientTaskNotification')"
                                    fieldName="client_task_notification" />
                            </div>
                        </div>
                    </div>

                    @if ($editPermission == 'all')
                        <div class="col-lg-3 col-md-6">
                            <x-forms.select fieldId="added_by" :fieldLabel="__('app.added').' '.__('app.by')"
                                fieldName="added_by">
                                <option value="">--</option>
                                @foreach ($employees as $item)
                                    <x-user-option :user="$item" :selected="$project->added_by == $item->id" />
                                @endforeach
                            </x-forms.select>
                        </div>
                    @endif



                </div>

                <div class="row p-20">
                    <div class="col-md-6 col-lg-3">
                        <div class="form-group">
                            <div class="d-flex mt-5">
                                <x-forms.checkbox fieldId="miroboard_checkbox"
                                    :fieldLabel="__('modules.projects.enableMiroboard')" fieldName="miroboard_checkbox"
                                    :checked="$project ? $project->enable_miroboard : ''"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 {{!is_null($project) && $project->enable_miroboard ? '' : 'd-none'}}" id="miroboard_detail">
                        <div class="form-group my-3">
                            <div class="row">
                                <div class="col-md-6 mt-6">
                                    <x-forms.text class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('modules.projects.miroBoardId')"
                                        fieldName="miro_board_id" fieldRequired="true" fieldId="miro_board_id" :fieldValue="$project->miro_board_id"/>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div class="d-flex mt-5">
                                    <x-forms.checkbox fieldId="client_access"
                                        :fieldLabel="__('modules.projects.clientMiroAccess')" fieldName="client_access"
                                        :checked="$project ? $project->client_access : ''"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <x-forms.custom-field :fields="$fields" :model="$project"></x-forms.custom-field>


                <x-form-actions>
                    <x-forms.button-primary id="save-project-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('projects.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>


<script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>
<script>
    $(document).ready(function() {

        if ($('.custom-date-picker').length > 0) {
            datepicker('.custom-date-picker', {
                position: 'bl',
                ...datepickerConfig
            });
        }

        $(".multiple-users").selectpicker({
            actionsBox: true,
            selectAllText: "{{ __('modules.permission.selectAll') }}",
            deselectAllText: "{{ __('modules.permission.deselectAll') }}",
            multipleSeparator: " ",
            selectedTextFormat: "count > 8",
            countSelectedText: function(selected, total) {
                return selected + " {{ __('app.membersSelected') }} ";
            }
        });

        quillImageLoad('#project_summary');

        const dp1 = datepicker('#start_date', {
            position: 'bl',
            dateSelected: new Date("{{ str_replace('-', '/', $project->start_date) }}"),
            onSelect: (instance, date) => {
                dp2.setMin(date);
            },
            ...datepickerConfig
        });

        const dp2 = datepicker('#deadline', {
            position: 'bl',
            dateSelected: new Date("{{ $project->deadline ? str_replace('-', '/', $project->deadline) : str_replace('-', '/', now(company()->timezone)) }}"),
            onSelect: (instance, date) => {
                dp1.setMax(date);
            },
            ...datepickerConfig
        });

        @if ($project->deadline == null)
            $('#deadlineBox').hide();
        @endif

        $('#without_deadline').click(function() {
            var check = $('#without_deadline').is(":checked") ? true : false;
            if (check == true) {
                $('#deadlineBox').hide();
            } else {
                $('#deadlineBox').show();
            }
        });

        $('#save-project-form').click(function() {
            var note = document.getElementById('project_summary').children[0].innerHTML;
            document.getElementById('project_summary-text').value = note;

            const url = "{{ route('projects.update', $project->id) }}";

            $.easyAjax({
                url: url,
                container: '#save-project-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-project-form",
                data: $('#save-project-data-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        $('#addProjectCategory').click(function() {
            const url = "{{ route('projectCategory.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#department-setting').click(function() {
            const url = "{{ route('departments.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#client_view_task').change(function() {
            $('#clientNotification').toggleClass('d-none');
        });

        $('#is_private').change(function() {
            $('#add_members').toggleClass('d-none');
            $('#edit_members').addClass('d-none');
        });

        $('#is_public').change(function() {
            $('#edit_members').toggleClass('d-none');
            $('#add_members').addClass('d-none');
        });

        $('#miroboard_checkbox').change(function() {
            $('#miroboard_detail').toggleClass('d-none');
        });

        $('#add-client').click(function() {
            $(MODAL_XL).modal('show');

            const url = "{{ route('clients.create') }}";

            $.easyAjax({
                url: url,
                blockUI: true,
                container: MODAL_XL,
                success: function(response) {
                    if (response.status == "success") {
                        $(MODAL_XL + ' .modal-body').html(response.html);
                        $(MODAL_XL + ' .modal-title').html(response.title);
                        init(MODAL_XL);
                    }
                }
            });
        });

        $('#add-employee').click(function() {
            $(MODAL_XL).modal('show');

            const url = "{{ route('employees.create') }}";

            $.easyAjax({
                url: url,
                blockUI: true,
                container: MODAL_XL,
                success: function(response) {
                    if (response.status == "success") {
                        $(MODAL_XL + ' .modal-body').html(response.html);
                        $(MODAL_XL + ' .modal-title').html(response.title);
                        init(MODAL_XL);
                    }
                }
            });
        });

        $('#calculate-task-progress').change(function() {
            if ($(this).is(':checked')) {
                $('#completion_percent').attr('disabled', 'true');
            } else {
                $('#completion_percent').removeAttr('disabled');
            }
        });

        init(RIGHT_MODAL);
    });

    function checkboxChange(parentClass, id){
        var checkedData = '';
        $('.'+parentClass).find("input[type= 'checkbox']:checked").each(function () {
            checkedData = (checkedData !== '') ? checkedData+', '+$(this).val() : $(this).val();
        });
        $('#'+id).val(checkedData);
    }

    $('#save-project-data-form').on('change', '#employee_department', function () {
        var selectElement = document.getElementById("employee_department");
        var id = [];
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].selected) {
            id.push(selectElement.options[i].value);
            }
        }
        

        if(id.length===0){

             id = ['0'];

        }

        console.log(id);
        

        let url = "{{ route('departments.members', ':id') }}";
        url = url.replace(':id', id);

        console.log(url);

        $.easyAjax({
            url: url,
            type: "GET",
            container: '#save-project-data-form',
            blockUI: true,
            redirect: true,
            success: function (data) {
                $('#selectEmployee').html(data.data);
                $('#selectEmployee').selectpicker('refresh');
                $("#selectEmployee").selectpicker('selectAll');
            }
        })
    });

</script>
