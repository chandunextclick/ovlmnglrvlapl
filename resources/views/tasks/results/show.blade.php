@php
    $editTaskResultPermission = user()->permission('edit_task_result');
    $deleteTaskResultPermission = user()->permission('delete_task_result');
@endphp

@forelse($results as $result)
    <div class="card w-100 rounded-0 border-0 note">
        <div class="card-horizontal">
            <div class="card-img my-1 ml-0">
                <img src="{{ $result->user->image_url }}" alt="{{ mb_ucwords($result->user->name) }}">
            </div>
            <div class="card-body border-0 pl-0 py-1">
                <div class="d-flex flex-grow-1">
                    <h4 class="card-title f-15 f-w-500 text-dark mr-3">{{ mb_ucwords($result->user->name) }}</h4>
                    <p class="card-date f-11 text-lightest mb-0">
                        {{ ucfirst($result->created_at->diffForHumans()) }}
                    </p>
                    <div class="dropdown ml-auto note-action">
                        <button class="btn btn-lg f-14 p-0 text-lightest text-capitalize rounded  dropdown-toggle"
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                            aria-labelledby="dropdownMenuLink" tabindex="0">
                            @if ($editTaskResultPermission == 'all' || ($editTaskResultPermission == 'added' && $result->added_by == user()->id))
                                <a class="cursor-pointer d-block text-dark-grey f-13 py-3 px-3 edit-result"
                                    href="javascript:;" data-row-id="{{ $result->id }}">@lang('app.edit')</a>
                            @endif

                            @if ($deleteTaskResultPermission == 'all' || ($deleteTaskResultPermission == 'added' && $result->added_by == user()->id))
                                <a class="cursor-pointer d-block text-dark-grey f-13 pb-3 px-3 delete-result"
                                    data-row-id="{{ $result->id }}" href="javascript:;">@lang('app.delete')</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-text f-14 text-dark-grey text-justify">
                 
                {!! ucfirst($result->result) !!}
            
                </div>
            </div>
        </div>
    </div>
@empty
    <x-cards.no-record :message="__('messages.noResultFound')" icon="clipboard" />
@endforelse
