@php
    $addTaskResultPermission = user()->permission('add_task_result');
    $editTaskResultPermission = user()->permission('edit_task_result');
    $deleteTaskResultPermission = user()->permission('delete_task_result');

@endphp

<!-- TAB CONTENT START -->
<div class="tab-pane fade show active" role="tabpanel" aria-labelledby="nav-email-tab">
    @if ($addTaskResultPermission == 'all'
    || ($addTaskResultPermission == 'added' && $task->added_by == user()->id)
    || ($addTaskResultPermission == 'owned' && in_array(user()->id, $taskUsers))
    || ($addTaskResultPermission == 'both' && (in_array(user()->id, $taskUsers) || $task->added_by == user()->id))
    )

        <div class="row p-20">
            <div class="col-md-12">
                <a class="f-15 f-w-500" href="javascript:;" id="add-results"><i
                        class="icons icon-plus font-weight-bold mr-1"></i>@lang('app.add')
                    @lang('app.result')</a>
            </div>
        </div>

        <x-form id="save-result-data-form" class="d-none">
            <div class="col-md-12 p-20 ">
                <div class="media">
                    <img src="{{ user()->image_url }}" class="align-self-start mr-3 taskEmployeeImg rounded"
                         alt="{{ mb_ucfirst(user()->name) }}">
                    <div class="media-body bg-white">
                        <div class="form-group">
                            <div id="task-result"></div>
                            <textarea name="result" class="form-control invisible d-none" id="task-result-text"></textarea>
                        </div>
                    </div>
                </div>
                <div class="w-100 justify-content-end d-flex mt-2">
                    <x-forms.button-cancel id="cancel-result" class="border-0 mr-3">@lang('app.cancel')
                    </x-forms.button-cancel>
                    <x-forms.button-primary id="submit-result" icon="location-arrow">@lang('app.submit')
                        </x-button-primary>
                </div>

            </div>
        </x-form>
    @endif


    <div class="d-flex flex-wrap justify-content-between p-20" id="result-list">
        @forelse($task->results as $result)
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
                                <button
                                    class="btn btn-lg f-14 p-0 text-lightest text-capitalize rounded  dropdown-toggle"
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
                                           data-row-id="{{ $result->id }}"
                                           href="javascript:;">@lang('app.delete')</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-text f-14 text-dark-grey text-justify ql-editor">{!! ucfirst($result->result) !!}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <x-cards.no-record :message="__('messages.noResultFound')" icon="clipboard"/>
        @endforelse
    </div>


</div>
<!-- TAB CONTENT END -->

<script>



    var add_task_result = "{{ $addTaskResultPermission }}";

    $('#add-results').click(function () {
        $(this).closest('.row').addClass('d-none');
        $('#save-result-data-form').removeClass('d-none');
    });

    $('#cancel-result').click(function () {
        $('#save-result-data-form').addClass('d-none');
        $('#add-results').closest('.row').removeClass('d-none');
    });


    $(document).ready(function () {

        if (add_task_result == "all" || add_task_result == "added") {
            quillImageLoad('#task-result');
        }


        $('#submit-result').click(function () {

            
            var result = document.getElementById('task-result').children[0].innerHTML;


            const urlRegex = /https?:\/\/[^\s]+/;

            const urls = result.match(urlRegex);

            if (urls) {

                var link = document.createElement('a');
            
            urls.forEach((url) => {
                console.log('Found URL:', url);
                if (url.endsWith('</p>')) {
                    url = url.replace(/<\/p>$/, ''); // Remove </p> from the end
                }
                link.href = url;
                link.target = '_blank';

            });

            link.innerHTML = result;

            result=link.outerHTML;

            }

    
            document.getElementById('task-result-text').value = result;

            console.log(result);


            var token = '{{ csrf_token() }}';

            const url = "{{ route('task-result.store') }}";

            $.easyAjax({
                url: url,
                container: '#save-result-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#submit-result",
                data: {
                    '_token': token,
                    result: result,
                    taskId: '{{ $task->id }}'
                },
                success: function (response) {
                    if (response.status == "success") {
                        $('#result-list').html(response.view);
                        document.getElementById('task-result').children[0].innerHTML = "";
                        $('#task-result-text').val('');
                    }

                }
            });


        });

    });
</script>
