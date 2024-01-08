<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">Result</h5>
    <button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">

    <x-form id="edit-result-data-form" method="PUT">
        <div class="row">
            <div class="col-md-12 p-20 ">
                <div class="media">
                    <img src="{{ $result->user->image_url }}" class="align-self-start mr-3 taskEmployeeImg rounded"
                        alt="{{ mb_ucwords($result->user->name) }}">
                    <div class="media-body bg-white">
                        <div class="form-group">
                            <div id="task-edit-result">{!! $result->result !!}</div>
                            <textarea name="result" class="form-control invisible d-none"
                                id="task-edit-result-text">{!! $result->result !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-edit-result" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>
    var edit_task_results = "{{ user()->permission('edit_task_result') }}";

    $(document).ready(function() {
        if (edit_task_results == "all" || edit_task_results == "added") {
            quillImageLoad('#task-edit-result');
        }

        $('#save-edit-result').click(function() {
            var result = document.getElementById('task-edit-result').children[0].innerHTML;
            document.getElementById('task-edit-result-text').value = result;

            var token = '{{ csrf_token() }}';

            const url = "{{ route('task-result.update', $result->id) }}";

            $.easyAjax({
                url: url,
                container: '#edit-result-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-edit-result",
                data: {
                    '_token': token,
                    result: result,
                    '_method': 'PUT',
                    taskId: '{{ $result->task->id }}'
                },
                success: function(response) {
                    if (response.status == "success") {
                        document.getElementById('result-list').innerHTML = response.view;
                        $(MODAL_LG).modal('hide');
                    }

                }
            });
        });

    });

</script>
