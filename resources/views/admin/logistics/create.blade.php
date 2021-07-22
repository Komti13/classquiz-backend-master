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

        .countries {
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

        #radioBtn .notActive {
            color: #0aa699;
            background-color: #fff;
        }

    </style>
@endsection
@section('content')
    <div class="row-fluid">
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
                    {!! Form::open(['route' => ['logistics.store','source'=>'first']]) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone', 'Phone') !!}
                        {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone2', 'Phone 2') !!}
                        {!! Form::text('phone2', null, ['class' => 'form-control', 'placeholder' => 'Phone 2']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', 'Address') !!}
                        {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address']) !!}
                    </div>
                    {{-- <div class="form-group">
                        {!! Form::label('level_id', 'Level') !!}
                        {!! Form::select('level_id', $levels, null) !!}
                    </div> --}}

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
                        <button type="submit" class="btn btn-primary">Next <i
                                class="icon-arrow-left13 position-right"></i></button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>



@endsection
@section('js')
    <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"> --}}
    {{-- </script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#country_id")
                .select2({
                    width: '100%'
                })
            $('#datepick').datetimepicker({
                inline: true,
                format: 'DD-MM-YYYY HH:mm'
            });
            $('#radioBtn a').on('click', function() {
                var sel = $(this).data('title');
                var tog = $(this).data('toggle');
                $('#' + tog).prop('value', sel);

                $('a[data-toggle="' + tog + '"]').not('[data-title="' + sel + '"]').removeClass('active')
                    .addClass('notActive');
                $('a[data-toggle="' + tog + '"][data-title="' + sel + '"]').removeClass('notActive')
                    .addClass('active');
            })
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
