@extends('layouts.app')

@push('datatable-styles')
    <script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
    <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
    @include('sections.datatable_css')
@endpush

@section('filter-section')

    <x-filters.filter-box>
        <!-- DATE START -->
        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.duration')</p>
            <div class="select-status d-flex">
                <input type="text" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"
                    id="datatableRange" placeholder="@lang('placeholders.dateRange')">
            </div>
        </div>
        <!-- DATE END -->

        <!-- STATUS START -->
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.status')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="status" id="status">
                    <option value="all">@lang('app.all')</option>
                    <option value="not finished">@lang('modules.tasks.hideCompletedTask')</option>
                    @foreach ($taskBoardStatus as $status)
                        <option value="{{ $status->id }}">{{ $status->slug == 'completed' || $status->slug == 'incomplete' ? __('app.' . $status->slug) : mb_ucwords($status->column_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- STATUS END -->

        <!-- PROJECT START -->
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.project')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="project_id" id="project_id" data-live-search="true"
                    data-size="8">
                    <option value="all">@lang('app.all')</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ mb_ucwords($project->project_name) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- PROJECT END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->

    </x-filters.filter-box>

@endsection

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-flex flex-column">
            <!-- TASK STATUS START -->
            <!-- <x-cards.data id="task-chart-card" :title="__('app.menu.tasks')" padding="false">
            </x-cards.data> -->
            <!-- TASK STATUS END -->
            <div
                class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center mb-4 mb-md-0 mb-lg-0">
                <div class="d-block text-capitalize">
                    <h5 class="f-15 f-w-500 mb-20 text-darkest-grey">@lang('app.menu.tasks')</h5>
                    <div class="d-flex">
                        <a href="{{ route('tasks.index') . '?assignee=me&empreportid='.$employeeid }}">
                            <p class="mb-0 f-21 font-weight-bold text-blue d-grid mr-5">
                                {{ $inProcessTasks }}<span class="f-12 font-weight-normal text-lightest">
                                    @lang('app.pending') </span>
                            </p>
                        </a>
                        <a href="{{ route('tasks.index') . '?assignee=me&overdue=yes&empreportid='.$employeeid }}">
                            <p class="mb-0 f-21 font-weight-bold text-red d-grid mr-5">{{ $dueTasks }}<span
                                    class="f-12 font-weight-normal text-lightest">@lang('app.overdue')</span>
                            </p>
                        </a>
                        <a href="{{ route('tasks.index') . '?assignee=me&priority=yes&empreportid='.$employeeid }}">
                            <p class="mb-0 f-21 font-weight-bold text-yellow d-grid mr-5">{{ $prtaskcount }}<span
                                    class="f-12 font-weight-normal text-lightest">@lang('app.priority')</span>
                            </p>
                        </a>
                        <a href="{{ route('tasks.index') . '?assignee=me&completed=yes&empreportid='.$employeeid }}">
                            <p class="mb-0 f-21 font-weight-bold text-success d-grid mr-5">{{ $completedTasks }}<span
                                    class="f-12 font-weight-normal text-lightest">@lang('app.completed')</span>
                            </p>
                        </a>
                    </div>
                </div>
                <div class="d-block">
                    <i class="fa fa-list text-lightest f-27"></i>
                </div>
            </div>

            <div id="table-actions" class="flex-grow-1 align-items-center mt-4">
            </div>

        </div>

        <!-- Add Task Export Buttons End -->
        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-4 bg-white">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}


        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->

@endsection

@push('scripts')
    @include('sections.datatable_js')

    <script>
        $('#allTasks-table').on('preXhr.dt', function(e, settings, data) {

            var dateRangePicker = $('#datatableRange').data('daterangepicker');
            var startDate = $('#datatableRange').val();

            if (startDate == '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
            }

            var projectID = $('#project_id').val();
            if (!projectID) {
                projectID = 0;
            }
            var clientID = $('#clientID').val();
            var assignedBY = $('#assignedBY').val();
            var assignedTo = @json($employeeid);
            var status = $('#status').val();
            var label = $('#label').val();
            var category_id = $('#category_id').val();
            var billable = $('#billable_task').val();
            var searchText = $('#search-text-field').val();

            data['clientID'] = clientID;
            data['assignedBY'] = assignedBY;
            data['assignedTo'] = assignedTo;
            data['status'] = status;
            data['label'] = label;
            data['category_id'] = category_id;
            data['billable'] = billable;
            data['projectId'] = projectID;
            data['startDate'] = startDate;
            data['endDate'] = endDate;
            data['searchText'] = searchText;
        });
        const showTable = () => {
            window.LaravelDataTables["allTasks-table"].draw(false);
            pieChart();
        }

        $('#billable_task, #status, #field, #clientID, #category_id, #assignedBY, #assignedTo, #label, #project_id')
            .on('change keyup',
                function() {
                    if ($('#status').val() != "all") {
                        $('#reset-filters').removeClass('d-none');
                        showTable();
                    } else if ($('#project_id').val() != "all") {
                        $('#reset-filters').removeClass('d-none');
                        showTable();
                    } else if ($('#clientID').val() != "all") {
                        $('#reset-filters').removeClass('d-none');
                        showTable();
                    } else if ($('#category_id').val() != "all") {
                        $('#reset-filters').removeClass('d-none');
                        showTable();
                    } else if ($('#assignedBY').val() != "all") {
                        $('#reset-filters').removeClass('d-none');
                        showTable();
                    } else if ($('#assignedTo').val() != "all") {
                        $('#reset-filters').removeClass('d-none');
                        showTable();
                    } else if ($('#label').val() != "all") {
                        $('#reset-filters').removeClass('d-none');
                        showTable();
                    } else if ($('#billable_task').val() != "all") {
                        $('#reset-filters').removeClass('d-none');
                        showTable();
                    } else {
                        $('#reset-filters').addClass('d-none');
                        showTable();
                    }
                });

        $('#search-text-field').on('keyup', function() {
            if ($('#search-text-field').val() != "") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            }
        });

        $('#reset-filters,#reset-filters-2').click(function() {
            $('#filter-form')[0].reset();

            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });


        function pieChart() {
            var data = new Array();
            var dateRangePicker = $('#datatableRange').data('daterangepicker');
            var startDate = $('#datatableRange').val();

            if (startDate == '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
            }

            var projectID = $('#project_id').val();
            if (!projectID) {
                projectID = 0;
            }
            var clientID = $('#clientID').val();
            var assignedBY = $('#assignedBY').val();
            var assignedTo = @json($employeeid);
            var status = $('#status').val();
            var label = $('#label').val();
            var category_id = $('#category_id').val();
            var billable = $('#billable_task').val();
            var searchText = $('#search-text-field').val();

            var url = "{{ route('employee-report.chart') }}";

            $.easyAjax({
                url: url,
                container: '#task-chart-card',
                blockUI: true,
                type: "POST",
                data: {
                    clientID: clientID,
                    assignedBY: assignedBY,
                    assignedTo: assignedTo,
                    status: status,
                    label: label,
                    category_id: category_id,
                    billable: billable,
                    projectId: projectID,
                    startDate: startDate,
                    endDate: endDate,
                    searchText: searchText,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#task-chart-card').html(response.html);
                }
            });
        }
        pieChart();

    </script>
@endpush
