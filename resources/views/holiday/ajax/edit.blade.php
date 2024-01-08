<div class="row">
    <div class="col-sm-12">
        <x-form id="save-holiday-data-form" method="put">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('app.edit') @lang('app.menu.holiday')</h4>
                <div class="row p-20">

                    <div class="col-lg-4">
                        <x-forms.text :fieldLabel="__('app.date')" fieldName="date" fieldId="date"
                            :fieldPlaceholder="__('app.date')"
                            :fieldValue="$holiday->date->translatedFormat(company()->date_format)" />
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group my-3">
                            <x-forms.text :fieldLabel="__('modules.holiday.occasion')" fieldName="occassion"
                                fieldId="occassion" :fieldPlaceholder="__('modules.holiday.occasion')"
                                :fieldValue="$holiday->occassion" />
                        </div>
                    </div>
                    <div class="col-lg-3 align-items-center justify-content-center">
                        <div class="form-group my-5">
                                <input type="checkbox" name="optional" id="optional1" class="checkbox" value="{{$holiday->optional}}" @if($holiday->optional==1) checked @endif>
                                <label for="optional1">Optional</label>
                        </div>  
                    </div>

                </div>

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


<script>
    $(document).ready(function() {

        const dp1 = datepicker('#date', {
            position: 'bl',
            dateSelected: new Date("{{ str_replace('-', '/', $holiday->date) }}"),
            ...datepickerConfig
        });

        $('#optional1').on('change', function() {
                var checkboxId = $(this).attr('id');
                // console.log(checkboxId);
                updateCheckboxValue(checkboxId);
        });


        $('#save-holiday-form').click(function() {

            optionalData=[];

            var formData = $('#save-holiday-data-form').serializeArray();

            const url = "{{ route('holidays.update', $holiday->id) }}";
            
            var checkboxValue = $("#optional1").val();
            
            formData.push({name:'optional',value:checkboxValue});


            var combinedData = $.param(formData)

            console.log('Combined Data:', formData);

            $.easyAjax({
                url: url,
                container: '#save-holiday-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-holiday-form",
                data: combinedData,
                success: function(response) {
                    window.location.href = response.redirectUrl;
                }
            });
        });

        function updateCheckboxValue(checkboxId) {
            var checkbox = $('#' + checkboxId);
            checkbox.val(checkbox.prop('checked') ? '1' : '0');
            console.log(checkbox.val())
        }  

        init(RIGHT_MODAL);
    });
</script>
