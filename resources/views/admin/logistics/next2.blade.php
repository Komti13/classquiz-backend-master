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
            {{-- About children --}}
            @for ($i = 0; $i < $nbchild; $i++)

                <div class="grid simple">

                    <div class="grid-title">
                        <h4>About <span class="semi-bold">child <?php echo $i + 1; ?></span></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#grid-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        {!! Form::open(['route' => ['logistics.store', 'source' => 'third']]) !!}

                        <div class="form-group">
                            {!! Form::label('name' . $i, 'Name') !!}
                            {!! Form::text('name' . $i, null, ['class' => 'form-control', 'placeholder' => 'Name','id'=>`name$i`]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('level_id' . $i, 'Level') !!}
                            {!! Form::select('level_id' . $i, $levels, null, ['class' => 'levels','id'=>'level_id'.$i,'data-level-id'=>'152']) !!}
                        </div>
                    </div>

                </div>

        </div>
        {{-- About Pack --}}

        <div class="grid simple">
            <div class="grid-title">
                <h4>Pack for Child <span class="semi-bold"><?php echo $i + 1; ?></span></h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="grid-body ">
                <div class="form-group">
                    {!! Form::label('pack' . $i, 'Pack Name') !!}
                    <select name='pack<?php echo $i ; ?>' class='packs' id='pack<?php echo $i ; ?>'>
                        @foreach ($packs as $id => $name)

                            <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}">
                                {{ $name }}
                            </option>
                </div>
                @endforeach
                </select>
                {{-- {!! Form::select('pack' . $i, ['---', $packs], null, ['class' => 'packs']) !!} --}}
            </div>
            <div class="form-group">
                {!! Form::label('price' . $i, 'Price') !!}
                {!! Form::text('price'.$i, null, ['class' => 'form-control price', 'value'=>'0','id'=>`price$i`]) !!}
            </div>
        </div>
    </div>
    @endfor

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
                {!! Form::select('delstatus', ['---', 'Delivery launched' => 'Delivery launched', 'To be confirmed' => 'To be confirmed', 'Cacelled before launch' => 'Cacelled before launch', 'payed (credit card)' => 'payed (credit card)', 'payed(tranfer)' => 'payed(tranfer)', 'payed(Physical C)' => 'payed(Physical C)'], null) !!}
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
            <h4>Token for Child<span class="semi-bold"></span></h4>
            <div class="tools">
                <a href="javascript:;" class="collapse"></a>
                <a href="#grid-config" data-toggle="modal" class="config"></a>
                <a href="javascript:;" class="reload"></a>
                <a href="javascript:;" class="remove"></a>
            </div>
        </div>
        <div class="grid-body ">
    @for ($i = 0; $i < $nbchild; $i++)

            <div class="form-group">
                {!! Form::label('token', 'Token For Child '.$i) !!}
                {!! Form::text('token', 'NHDNCNUGWDZ485D', ['class' => 'form-control', 'placeholder' => 'Amount to pay']) !!}
            </div>
            @endfor

            <div class="text-right">
                <br>
                <button type="submit" class="btn btn-primary">Save <i class="icon-arrow-left13 position-right"></i></button>
            </div>
        </div>
    </div>
    </div>
    </div>



@endsection
@section('js')
    <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".levels, #subject_id, #pack_type_id ,#country_id,#status,#type,#source,#ad,#payment,#paystatus,#pack,#delstatus")
                .select2({
                    width: '100%'
                })
               const $packs = $(".packs")
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
            var packs;
            $.getJSON("http://api.classquiz.test/datal", function(data) {
                packs = data.packs;
            });
            $(".levels")
                .on('change', function() {
                    var id=$(this).attr('id');
                  var z=id.charAt(id.length-1);
                    $("#pack"+z+" option").remove().end();
                    $("#pack"+z).val(null).trigger('change');
                    for (let i = 0; i < packs.length; i++) {

                        if (packs[i].level_id == $(this).val()) {
                            var option = new Option(packs[i].name, packs[i].id, false, false);
                           $packs.append(option).trigger('change');
                        }

                    }
                    
                    $('.level > div > input').prop("disabled", true);
                    $('.level').hide();
                    $('.level[data-level-id="' + $(this).val() + '"]  > div > input').prop("disabled", false);
                    $('.level[data-level-id="' + $(this).val() + '"]').show();
                })
                $(".packs")
                .on('change', function() {
                    for (let i = 0; i < packs.length; i++) {
                        if (packs[i].id == $(this).val()) {
                            $(".price").val(packs[i].price);
                        }

                    }
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
