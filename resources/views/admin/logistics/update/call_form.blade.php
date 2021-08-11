<div class="row-fluid" id="call">
    <div class="span12">
        {{-- About Call --}}
        <div class="grid simple">
            <div class="grid-title">
                <h4>About <span class="semi-bold">Call</span></h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="grid-body ">
                {!! Form::model($user_call,['route' => ['logistics.update','id'=>$id, 'source' => 'second']]) !!}
                
                <div class="form-group">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', $status, null) !!}
                </div>
                <div class="input-append success form-group" style="width: 96%;">
                    {!! Form::label('call_date', 'Call Date') !!}
                    {!! Form::input('conversation_date', 'conversation_date', null, ['class' => 'form-control', 'placeholder' => 'Call Date', 'id' => 'datepick']) !!}
                </div>
                {{-- <div class="form-group">
                    {!! Form::label('calltype', 'Call Type') !!}
                    {!! Form::select('calltype', ['Sale'=>'Sale','Feedback'=>'Feedback', 'Technical Support'=>'Technical Support'], null, ['placeholder' => 'Choose Status']) !!}
                </div> --}}
                <div class="form-group">
                    {!! Form::label('notes', 'Notes') !!}
                    {!! Form::textarea('notes', null, ['class' => 'form-control', 'placeholder' => 'Notes...']) !!}
                </div>
                <div class="text-right" class="d-flex justify-content-around">
                    <br>
                    {{-- <div > --}}
                    <input type="submit" name="previous" class="previous action-button-previous" value="Previous"
                        id="previous2" />
                    <button type="submit" name="next" class="next action-button" value="Next Step" id="submit2">
                        Next</i></button>
                    {{-- </div> --}}

                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#status,#calltype")
                .select2({
                    width: '100%'
                })
            $('#datepick').datetimepicker({
                inline: true,
                format: 'YYYY-MM-DD HH:mm'
            });
            $('form').submit(function(e) {
                e.preventDefault();
                source = 'second';
                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: form.serialize(),
                    success: function(data) {
                        // console.log(data.notes)
                        $('#next2').html(data);
                    }
                });
            })
            $("#submit2").click(function() {

                current_fs = $(this).parents("fieldset");
                next_fs = $(this).parents("fieldset").next();

                //Add Class Active
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            });
            $("#previous2").click(function() {

                current_fs = $(this).parents("fieldset");
                previous_fs = $(this).parents("fieldset").prev();

                //Remove class active
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset
                previous_fs.show();

                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        previous_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            });
        });
    </script>
</div>
