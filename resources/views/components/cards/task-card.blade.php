@php
$moveClass = '';
@endphp
@if ($draggable == 'false')
    @php
        $moveClass = 'move-disable';
    @endphp
@endif

<div class="card rounded bg-white border-grey b-shadow-4 m-1 mb-2 {{ $moveClass }} task-card"
    data-task-id="{{ $task->id }}" id="drag-task-{{ $task->id }}" style="border-left: 3px solid {{ $task->priority == 'high' ? '#dd0000' : ($task->priority == 'medium' ? '#ffc202' : '#0a8a1f') }}">
    <div class="card-body p-2">
        <div class="d-flex justify-content-between mb-1">
            <a href="{{ route('tasks.show', [$task->id]) }}"
                class="f-12 f-w-500 text-dark mb-0 text-wrap openRightModal">{{ ucfirst($task->heading) }}</a>
            <p class="f-12 font-weight-bold text-dark-grey mb-0">
                @if ($task->is_private)
                    <span class='badge badge-secondary mr-1'><i class='fa fa-lock'></i>
                        @lang('app.private')</span>
                @endif
                #{{ $task->task_short_code }}
            </p>
        </div>

        @if (count($task->labels) > 0)
            <div class="mb-1 d-flex">
                @foreach ($task->labels as $key => $label)
                    <span class='badge badge-secondary mr-1'
                        style="background:{{ $label->label_color }}">{{ mb_ucfirst($label->label_name) }}
                    </span>
                @endforeach
            </div>
        @endif

        <div class="d-flex mb-1 justify-content-between">
            @if ($task->project_id)
                <div>
                    <i class="fa fa-layer-group f-11 text-lightest"></i><span
                        class="ml-2 f-11 text-lightest">{{ ucfirst($task->project->project_name) }}</span>
                </div>
            @endif

            @if ($task->estimate_hours > 0 || $task->estimate_minutes > 0)
                <div  data-toggle="tooltip" data-original-title="@lang('app.estimate'): {{ $task->estimate_hours }} @lang('app.hrs') {{ $task->estimate_minutes }} @lang('app.mins')">
                    <i class="fa fa-hourglass-half f-11 text-lightest"></i><span
                        class="ml-2 f-11 text-lightest">{{ $task->estimate_hours }}:{{ $task->estimate_minutes }}</span>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex flex-wrap">
                @foreach ($task->users as $item)
                    <div class="avatar-img mr-1 rounded-circle">
                        <a href="{{ route('employees.show', $item->id) }}" alt="{{ mb_ucwords($item->name) }}"
                            data-toggle="tooltip" data-original-title="{{ mb_ucwords($item->name) }}"
                            data-placement="right"><img src="{{ $item->image_url }}"></a>
                    </div>
                @endforeach
            </div>
            @if ($task->subtasks_count > 0)
                <i class="fa fa-check-square-o"></i> {{ $task->completed_subtasks_count .'/' . $task->subtasks_count }}
            @endif
            <!-- added by chandu on 19/09/2023 for viewing completion date from 65 to 71 and 89 -->
            @if(!is_null($task->completed_on)) 

                <div class="d-flex text-dark-green">
                        <i class="fa fa-calendar-alt f-11 align-self-center"></i><span class="f-12 ml-1">{{ $task->completed_on->translatedFormat(company()->date_format) }}</span>
                </div>

            @else
                @if (!is_null($task->due_date))
                    @if ($task->due_date->endOfDay()->isPast())
                        <div class="d-flex text-red">
                            <i class="f-11 bi bi-calendar align-self-center"></i><span
                                class="f-12 ml-1">{{ $task->due_date->translatedFormat(company()->date_format) }}</span>
                        </div>
                    @elseif($task->due_date->setTimezone(company()->timezone)->isToday())
                        <div class="d-flex text-dark-green">
                            <i class="fa fa-calendar-alt f-11 align-self-center"></i><span class="f-12 ml-1">@lang('app.today')</span>
                        </div>
                    @else
                        <div class="d-flex text-lightest">
                            <i class="fa fa-calendar-alt f-11 align-self-center"></i><span
                                class="f-12 ml-1">{{ $task->due_date->translatedFormat(company()->date_format) }}</span>
                        </div>
                    @endif
                @endif
            @endif

        </div>
    </div>
</div><!-- div end -->
