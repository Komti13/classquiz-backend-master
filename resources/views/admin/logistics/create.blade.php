@extends('admin.layout.layout',['title'=>'Logistics'])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet" type="text/css" />
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
.countries{
      display: none;
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

        .simple {
            margin-bottom: 20px;
        }

    </style>
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
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
                    {!! Form::open(['route' => ['logistics.store'], 'files' => true]) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone', 'Phone') !!}
                        {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', 'Address') !!}
                        {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address']) !!}
                    </div>
                    {{-- <div class="form-group">
                        {!! Form::label('image', 'Image') !!}
                        {!! Form::File('image',null,['class'=>'form-control']) !!}
                    </div> --}}
                    {{-- @if ($role->name == 'STUDENT') --}}
                    <div class="form-group">
                        {!! Form::label('level_id', 'Level') !!}
                        {!! Form::select('level_id', $levels, null) !!}
                    </div>
                  
                    <div class="form-group">
                        {!! Form::label('country_id', 'Country') !!}
                        {!! Form::select('country_id', $countries, null) !!}
                    </div>
                    
                </div>
            </div>

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
                    {{-- <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'User Status']) !!}
                    </div> --}}
                    {{-- <div class="form-group">
                        {!! Form::label('phone', 'Phone') !!}
                        {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', 'Address') !!}
                        {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address']) !!}
                    </div> --}}
                 
                    <div class="form-group">
                        {!! Form::label('status', 'Status') !!}
                        {{-- to complete later --}}
                        {!! Form::select('status', ['a','b','a','b','a','b'], null) !!}
                    </div>
                 <div class="input-append success date form-group" style="width: 96%;">
                        {!! Form::label('call_date', 'Call Date') !!}
                        {!! Form::text('call_date',null,['class'=>'form-control','placeholder'=>'Call Date', 'autocomplete' => 'off']) !!}
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes', 'Notes') !!}
                        {!! Form::textarea('notes', null, ['class' => 'form-control', 'placeholder' => 'Notes...']) !!}
                    </div>
                    {{-- <div class="form-group">
                        {!! Form::label('country_id', 'Country') !!}
                        {!! Form::select('country_id', $countries, null) !!}
                    </div> --}}
                  

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
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript">
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#level_id, #subject_id, #pack_type_id ,#country_id,#status").select2({
                width: '100%'
            })

            $('.input-append.date').datepicker({
                autoclose: true,
                todayHighlight: true
            });

            $("#level_id")
                .on('change', function() {
                    $('.level > div > input').prop("disabled", true);
                    $('.level').hide();
                    $('.level[data-level-id="' + $(this).val() + '"]  > div > input').prop("disabled", false);
                    $('.level[data-level-id="' + $(this).val() + '"]').show();
                })
        });

        $("#pack_type_id").on('change', function() {
            if ($(this).val() == "3") {
                $(".subject > div > input").parents(".input").removeClass("checkbox check-success").addClass(
                    "radio radio-success");
                $(".subject > div > input").attr('type', 'radio');
                $(".chapter input").prop('checked', false);
            } else {
                $(".subject > div > input").parents(".input").addClass("checkbox check-success").removeClass(
                    "radio radio-success");
                $(".subject > div > input").attr('type', 'checkbox');
            }
        })

        $(".subject > div > input").on("change", function() {
            if ($(this).attr("type") == "radio") {
                $(".chapter input").prop('checked', false);
                $(".chapter input").prop("disabled", true);
                $(this).parents(".subject").find("input").prop("disabled", false);
            }
        })

        $('.expand').click(function() {
            $(this).parents('.element').first().children('.expandable').slideToggle('slow');
            var icon = $('.material-icons', this);
            if (icon.text() == 'keyboard_arrow_right') {
                icon.text('keyboard_arrow_down')
            } else {
                icon.text('keyboard_arrow_right')
            }
        });

        $('input[type="checkbox"][class!="chapter-input"]').change(function() {
            if (this.checked) {
                $(this).parents('.element').first().find('input[type="checkbox"]').prop('checked', true);
            } else {
                $(this).parents('.expandable').first().find('input[type="checkbox"]').prop('checked', false);
            }
        });
    </script>
@endsection
