<div class="row-fluid" id="home">
    <div class="span12">
        {{-- About User --}}
        <div class="grid simple ">
            <div class="grid-title">
                <h4>About <span class="semi-bold">User</span></h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="grid-body ">
                {{-- if you put next line validation work --}}
                {{-- <form action="{{ route('logistics.store', ['source' => 'first']) }}" id="form" class="form"> ' --}}
                {{-- if you put next line submission work --}}

                {!! Form::open(['route' => ['logistics.store', 'source' => 'first', 'class' => 'form', 'id' => 'form']]) !!}

                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name' ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('phone', 'Phone') !!}
                    {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone' ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('phone2', 'Phone 2') !!}
                    {!! Form::text('phone2', null, ['class' => 'form-control', 'placeholder' => 'Phone 2']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('address', 'Address') !!}
                    {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('city_id', 'City') !!}
                    {!! Form::select('city_id', $cities, null) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('country_id', 'Country') !!}
                    {!! Form::select('country_id', $countries, null) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('children', 'Number of Children') !!}
                    {!! Form::number('children', null, ['class' => 'form-control', 'placeholder' => 0]) !!}
                </div>
                <div class="text-right">
                    <br>

                    <button type="submit" name="next" class="next action-button" value="Next Step" id="submit">
                        Next</i></button>
                </div>
                </form>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {
            $("#country_id,#city_id")
                .select2({
                    width: '100%'
                })
            $('form').submit(function(e) {
                e.preventDefault();
                // source = 'first';
                var form = $(this);
                var url = form.attr('action');
                // if (!$validator) {

                // if ($("#form").valid()) {

                    // $('#form').validate();   
                    // console.log(form)

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: form.serialize(),
                        success: function(data) {
                            // console.log("controller", data)
                            $('#next1').html(data);
                        },
                        // error: function(error) {
                            // request - > session() - > put('err', error.responseJSON.errors);
                            //    console.log(error.responseJSON.errors);
                        // }
                    });
                // }
            })
            $("#submit").click(function() {


                // if ($("form").valid()) {
                    // console.log($("#form").valid())
                    current_fs = $(this).parents("fieldset");
                    next_fs = $(this).parents("fieldset").next();
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

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
                // }
            });
        });
    </script>
</div>
