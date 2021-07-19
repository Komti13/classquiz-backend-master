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
                    <div class="form-group">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', $status, null) !!}
                    </div>
                    <div class="input-append success form-group" style="width: 96%;">
                        {!! Form::label('call_date', 'Call Date') !!}
                        {!! Form::input('call_date', 'call_date', null, ['class' => 'form-control', 'placeholder' => 'Call Date', 'id' => 'datepick']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes', 'Notes') !!}
                        {!! Form::textarea('notes', null, ['class' => 'form-control', 'placeholder' => 'Notes...']) !!}
                    </div>

                </div>
            </div>
            {{-- About SMS --}}
            <div class="grid simple">
                <div class="grid-title">
                    <h4>About <span class="semi-bold">SMS</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <div class="form-group">
                        {!! Form::label('type', 'SMS Type') !!}
                        {!! Form::select('type', ['type1', 'type2', 'type3'], null) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('sms_text', 'Text') !!}
                        {!! Form::textarea('sms_text', null, ['class' => 'form-control', 'placeholder' => 'SMS Text...']) !!}
                    </div>


                </div>
            </div>
            {{-- About Sales --}}
            <div class="grid simple">
                <div class="grid-subtitle">
                    <h4>Sale Operation Informations</h4>
                </div>
            </div>

            {{-- About source --}}
            <div class="grid simple">

                <div class="grid-title">
                    <h4>About <span class="semi-bold">Source</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <div class="form-group">
                        {!! Form::label('source', 'Source') !!}
                        {!! Form::select('source', ['---', $sources], null) !!}
                    </div>
                </div>
            </div>
            {{-- About AD --}}

            <div class="grid simple">
                <div class="grid-title">
                    <h4>About <span class="semi-bold">AD</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <div class="form-group">
                        {!! Form::label('ad', 'AD') !!}
                        {!! Form::select('ad', ['---', $ads], null) !!}
                    </div>

                </div>
            </div>
            {{-- About Pack --}}

            <div class="grid simple">
                <div class="grid-title">
                    <h4>About <span class="semi-bold">Pack</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <div class="form-group">
                        {!! Form::label('pack', 'Pack Name') !!}
                        {!! Form::select('pack', ['---', $packs], null) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('price', 'Price') !!}
                        {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'Pack Price']) !!}
                    </div>
                </div>
            </div>
            {{-- About Payment --}}

            <div class="grid simple">
                <div class="grid-title">
                    <h4>About <span class="semi-bold">Payment</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <div class="form-group">
                        {!! Form::label('payment', 'Payment Methode') !!}
                        {!! Form::select('payment', ['---', $methodes], null) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('amount', 'Amount') !!}
                        {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => 'Amount to pay']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('paystatus', 'Payment Status') !!}
                        {!! Form::select('paystatus', ['---', $payStatus], null) !!}
                    </div>
                </div>
            </div>
            {{-- About Delivery --}}

            <div class="grid simple">
                <div class="grid-title">
                    <h4>About <span class="semi-bold">Delivery</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <div class="input-append success form-group" style="width: 96%;">
                        {!! Form::label('delivery_date', 'Delivery Date') !!}
                        {!! Form::input('date', 'delivery_date', null, ['class' => 'form-control', 'placeholder' => 'Delivery Date']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('delstatus', 'Delivery Status') !!}
                        {!! Form::select('delstatus', ['---', 'Delivery launched'=>'Delivery launched', 'To be confirmed'=>'To be confirmed', 'Cacelled before launch'=>'Cacelled before launch', 'payed (credit card)'=>'payed (credit card)', 'payed(tranfer)'=>'payed(tranfer)', 'payed(Physical C)'=>'payed(Physical C)'], null) !!}
                    </div>
                    <div class="form-group">
                        <label for="fees" class="col-sm-4 col-md-4 control-label text-left ">Delivery Fees</label>
                        <div class="col-sm-7 col-md-7">
                            <div class="input-group">
                                <div id="radioBtn" class="btn-group">
                                    <a class="btn btn-primary btn-sm active" data-toggle="fees" data-title="true">YES</a>
                                    <a class="btn btn-primary btn-sm notActive" data-toggle="fees" data-title="false">NO</a>
                                </div>
                                <input type="hidden" name="fees" id="fees" value="true">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="grid simple">
                <div class="grid-title">
                    <h4>Token <span class="semi-bold"></span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <div class="form-group">
                        {!! Form::label('token', 'Token') !!}
                        {!! Form::text('token', 'NHDNCNUGWDZ485D', ['class' => 'form-control', 'placeholder' => 'Amount to pay']) !!}
                    </div>

                    <div class="text-right">
                        <br>
                        <button type="submit" class="btn btn-primary">Save <i
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
            $("#level_id, #subject_id, #pack_type_id ,#country_id,#status,#type,#source,#ad,#payment,#paystatus,#pack,#delstatus")
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
