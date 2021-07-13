@extends('admin.layout.layout',['title'=>'Packs'])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet"
          type="text/css"/>

    <style type="text/css">
        .levels .level {
            display: none;
        }

        .subjects {
            margin-left: 20px;
        }

        .chapters {
            display: none;
            margin-left: 20px;
        }

        .expand {
            position: relative;
            top: 6px;
            margin: 0 5px;
        }

        .input {
            background: #fff;
            border: solid 1px #e5e9ec;
            margin-bottom: 4px !important;
        }
    </style>

@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Edit <span class="semi-bold">Pack</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::model($pack,['method'=>'patch','route' => ['packs.update',$pack->id]]) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::text('description',null,['class'=>'form-control','placeholder'=>'Description']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('price', 'Price') !!}
                        {!! Form::text('price',null,['class'=>'form-control','placeholder'=>'Price']) !!}
                    </div>
                    <div class="input-append success date form-group" style="width: 96%;">
                        {!! Form::label('validity_start', 'Validity start') !!}
                        {!! Form::text('validity_start',null,['class'=>'form-control','placeholder'=>'Validity start', 'autocomplete' => 'off']) !!}
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                    </div>
                    <div class="input-append success date form-group" style="width: 96%;">
                        {!! Form::label('validity_end', 'Validity end') !!}
                        {!! Form::text('validity_end',null,['class'=>'form-control','placeholder'=>'Validity end', 'autocomplete' => 'off']) !!}
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                    </div>
                    <div class="form-group">
                        {!! Form::label('order', 'Order') !!}
                        {!! Form::number('order',null,['class'=>'form-control','placeholder'=>'Order']) !!}
                    </div>
                    <div class="checkbox checkbox check-success">
                        {!! Form::checkbox('enabled', 1, null,['id'=>'enabled']) !!}
                        {!! Form::label('enabled', 'Enabled') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('pack_type_id', 'Pack Type') !!}
                        {!! Form::select('pack_type_id',$packTypes,null, ['placeholder' => 'Choose Pack Type']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('level_id', 'Level') !!}
                        {!! Form::select('level_id',$levels->pluck('name', 'id'),null, ['placeholder' => 'Choose Level']) !!}
                    </div>
                    <div class="levels expandable">
                        @foreach($levels as $level)
                            <div class="level element" data-level-id="{{$level->id}}">
                                <div class="input checkbox check-success">
                                    <a href="javascript:;" class="expand"><i
                                            class="material-icons">keyboard_arrow_right</i></a>
                                    {!! Form::checkbox('levels[]',$level->id,false,['id'=>'level'.$level->id]) !!}
                                    {!! Form::label('level'.$level->id,$level->name) !!}
                                </div>

                                <div class="subjects expandable">
                                    @foreach($level->subjects as $subject)
                                        <div class="subject element">
                                            <div class="input checkbox check-success">
                                                <a href="javascript:;" class="expand"><i class="material-icons">keyboard_arrow_right</i></a>
                                                <input type="checkbox" name="subjects[]" id="{{$level->id.'-subject-'.$subject->id}}">
                                                <label for="{{$level->id.'-subject-'.$subject->id}}"></label>
                                            </div>

                                            <div class="chapters expandable">
                                                @foreach($subject->levelChapters($level->id) as $chapter)
                                                    <div class="chapter element">
                                                        <div class="input checkbox check-success">
                                                            <a href="javascript:;" class="expand"
                                                               style="visibility: hidden"><i
                                                                    class="material-icons">keyboard_arrow_right</i></a>
                                                            {!! Form::checkbox('chapters[]',$chapter->id,$pack->chapters->contains('id',$chapter->id),['class'=>'chapter-input','id'=>$level->id.'-chapter-'.$chapter->id]) !!}
                                                            {!! Form::label($level->id.'-chapter-'.$chapter->id,$chapter->name) !!}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-right">
                        <br>
                        <button type="submit" class="btn btn-primary">Save <i
                                class="icon-arrow-left13 position-right"></i></button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>



@endsection
@section('js')
    <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"
            type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#level_id, #subject_id, #pack_type_id").select2({
                width: '100%'
            })

            $('.input-append.date').datepicker({
                autoclose: true,
                todayHighlight: true
            });

            $('.level[data-level-id="' + $("#level_id").val() + '"]  > div > input').prop("disabled", false);
            $('.level[data-level-id="' + $("#level_id").val() + '"]').show();
            $("#level_id")
                .on('change', function () {
                    $('.level > div > input').prop("disabled", true);
                    $('.level').hide();
                    $('.level[data-level-id="' + $(this).val() + '"]  > div > input').prop("disabled", false);
                    $('.level[data-level-id="' + $(this).val() + '"]').show();
                })

            $("#pack_type_id").on('change', function () {
                if ($(this).val() == "3") {
                    $(".subject > div > input").parents(".input").removeClass("checkbox check-success").addClass("radio radio-success");
                    $(".subject > div > input").attr('type', 'radio');
                    $(".chapter input").prop('checked', false);
                } else {
                    $(".subject > div > input").parents(".input").addClass("checkbox check-success").removeClass("radio radio-success");
                    $(".subject > div > input").attr('type', 'checkbox');
                }
            })

            $(".subject > div > input").on("change", function () {
                if ($(this).attr("type") == "radio") {
                    $(".chapter input").prop('checked', false);
                    $(".chapter input").prop("disabled", true);
                    $(this).parents(".subject").find("input").prop("disabled", false);
                }
            })

            $('.expand').click(function () {
                $(this).parents('.element').first().children('.expandable').slideToggle('slow');
                var icon = $('.material-icons', this);
                if (icon.text() == 'keyboard_arrow_right') {
                    icon.text('keyboard_arrow_down')
                } else {
                    icon.text('keyboard_arrow_right')
                }
            });

            $('input[type="checkbox"][class!="chapter-input"]').change(function () {
                if (this.checked) {
                    $(this).parents('.element').first().find('input[type="checkbox"]').prop('checked', true);
                } else {
                    $(this).parents('.expandable').first().find('input[type="checkbox"]').prop('checked', false);
                }
            });

            $('.chapter input').each(function () {
                if (this.checked) {
                    $(this).parents('.level').first().find('> div >input[type="checkbox"]').prop('checked', true);
                    $(this).parents('.subject').first().find('> div >input[type="checkbox"]').prop('checked', true);
                }
            });

            $('.expandable').each(function () {
                if ($(this).find('input[type="checkbox"]:checked').length > 0) {
                    $(this).show();
                }
            });
        });
    </script>
@endsection
