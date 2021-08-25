@extends('admin.layout.layout',['title'=>''])
@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"> --}}
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <style>
        .page-content {
            background-color: #f7f7fc !important;
        }

        .switch {
            display: inline-flex;
            margin: 0 10px;
        }

        .switch input[type=checkbox] {
            height: 0;
            width: 0;
            visibility: hidden;
        }

        .switch input[type=checkbox]:checked+label {
            background: green;
        }

        .switch input[type=checkbox]:checked+label::after {
            left: calc(100% - 4px);
            transform: translateX(-100%);
        }

        .switch label {
            cursor: pointer;
            width: 48px;
            height: 24px;
            background: grey;
            display: block;
            border-radius: 24px;
            position: relative;
        }

        .switch label::after {
            content: "";
            position: absolute;
            top: 4px;
            left: 4px;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 16px;
            transition: 0.3s;
        }

        .form-row h4 {
            color: red;
        }

    </style>
@endsection

@section('content')

    <div>
        <div class="alert alert-danger mt-3 " id="diverr">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            </button>
            <p id="erreur"></p>

        </div>
        {{-- About User --}}
        {!! Form::open(['route' => ['logistics.store', 'source' => 'first', 'class' => 'form', 'id' => 'form']]) !!}
        <div class="form-row">
            <h4>Parent</h4>
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('phone', 'Phone') !!}
                    {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('phone2', 'Phone 2') !!}
                    {!! Form::text('phone2', null, ['class' => 'form-control', 'placeholder' => 'Phone 2']) !!}
                </div>
            </div>
        </div>
        <br><br>
        <div class="form-row">
            <div class="col-sm-7">
                <div class="form-group">
                    {!! Form::label('address', 'Address') !!}
                    {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address']) !!}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('city_id', 'City') !!}
                    {!! Form::select('city_id', $cities, null, ['placeholder' => 'Choose City', 'id' => 'city_id']) !!}
                </div>
            </div>
            {{-- <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('country_id', 'Country') !!}
                    {!! Form::select('country_id', $countries, null, ['placeholder' => 'Choose Country', 'id' => 'country_id']) !!}
                </div>
            </div> --}}
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('children', 'Number of Children') !!}
                    {!! Form::number('nb_children', 0, ['class' => 'form-control', 'placeholder' => 'number of children', 'id' => 'nbchildren','min' => 0]) !!}
                </div>
            </div>
        </div>
        <br><br>


        @for ($i = 0; $i < 7; $i++)
            <div class="form-row" id="child<?php echo $i; ?>">
                <h4>Child <?php echo $i + 1; ?></h4>
                <div class="col-sm-4">
                    <div class="form-group ">
                        {!! Form::label('childname', 'Child name') !!}
                        {!! Form::text('childname' . $i, null, ['class' => 'form-control', 'placeholder' => 'Child name', 'id' => 'childname' . $i]) !!}
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">

                        {!! Form::label('level_id', 'Level') !!}
                        {!! Form::select('level_id' . $i, $levels, null, ['placeholder' => 'Choose Level', 'class' => 'levels', 'id' => 'level_id' . $i, 'data-level-id' => '152']) !!}
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        {!! Form::label('pack', 'Pack Name') !!}
                        <select name='pack<?php echo $i; ?>' class='packs' id='pack<?php echo $i; ?>'>
                            <option value="---">Choose Pack</option>

                            @foreach ($packs as $id => $name)

                                <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        {!! Form::label('price', 'Price') !!}
                        {!! Form::number('price' . $i, 0, ['class' => 'form-control price', 'placeholder' => 'price $', 'value' => '0', 'id' => 'price' . $i]) !!}
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        {!! Form::label('promo_price', 'Price After Promotion') !!}
                        {!! Form::number('promo_price' . $i, 0, ['class' => 'form-control price', 'placeholder' => 'promotion $', 'value' => '0', 'id' => 'promo_price' . $i]) !!}
                    </div>
                </div>
                {{-- <div class="form-row"> --}}
                <div class="col-sm-6">
                    <div class="form-group ">
                        {!! Form::label('label', 'Current Year Token ') !!}
                        {!! Form::text('current' . $i, '', ['class' => 'form-control', 'placeholder' => 'token for this year', 'name' => 'current' . $i, 'id' => 'current' . $i, 'readonly']) !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('label', 'Next Year Token ') !!}
                        {!! Form::text('next' . $i, '', ['class' => 'form-control', 'placeholder' => 'token for next year', 'name' => 'next' . $i, 'id' => 'Next' . $i, 'readonly']) !!}
                    </div>
                </div>
                {{-- </div> --}}
            </div>

        @endfor
        <br><br>
        <div class="form-row">
            <h4>Informrations</h4>
            <div class="col-sm-3">
                <div class="form-group ">
                    {!! Form::label('source', 'Source') !!}
                    {!! Form::select('source', $sources, null, ['id' => 'source', 'placeholder' => 'Choose Source']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('ad', 'AD') !!}
                    {!! Form::select('ad', $ads, null, ['id' => 'ad', 'placeholder' => 'Choose AD']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('type', 'SMS Type') !!}
                    {!! Form::select('type', ['type1', 'type2', 'type3'], null, ['id' => 'sms']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('sms_text', 'Text') !!}
                    {!! Form::textarea('sms_text', null, ['class' => 'form-control', 'placeholder' => 'SMS Text...', 'rows' => 1, 'cols' => 40]) !!}
                </div>
            </div>
        </div>
        <br><br>
        <div class="form-row">
            <div class="col-sm-3">
                <div class="form-group ">
                    {!! Form::label('call_date', 'Call Date') !!}
                    {!! Form::input('call_date', 'call_date', null, ['class' => 'form-control', 'placeholder' => 'Call Date', 'id' => 'datepick']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', $status, null, ['placeholder' => 'Choose status', 'id' => 'status']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">

                    {!! Form::label('notes', 'Notes') !!}
                    {!! Form::textarea('notes', null, ['class' => 'form-control', 'placeholder' => 'Notes...', 'rows' => 3, 'cols' => 40]) !!}
                </div>
            </div>
        </div>
        <div class="form-row" id="payment">
            <div class="col-sm-3">
                <div class="form-group ">
                    {!! Form::label('payment', 'Payment Methode') !!}
                    {!! Form::select('paymethod', $methodes, null, ['placeholder' => 'Choose payment methode', 'id' => 'methode']) !!}
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    {!! Form::label('amount', 'Amount') !!}
                    {!! Form::number('amount', 0, ['class' => 'form-control', 'placeholder' => 'Amount to pay', 'id' => 'amount']) !!}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('fees', 'Delivery Fees') !!}

                    <div class="switch">
                        <input type="checkbox" id="fees" name="fees" />
                        <label for="fees" style="margin-top:-9px "></label>
                    </div>
                </div>

            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('dd', 'Double Delivery') !!}

                    <div class="switch">
                        <input type="checkbox" id="double" name="double" />
                        <label for="double" style="margin-top:-9px "></label>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-row" id="dateform">
            <div class="col-sm-12">
                <div class="form-group ">
                    {!! Form::label('delivery_date', '', ['id' => 'datelabel']) !!}
                    {!! Form::input('datetime-local', 'delivery_date', null, ['class' => 'form-control', 'placeholder' => 'Delivery Date', 'id' => 'dateinput']) !!}
                </div>
            </div>


        </div>

        <div style="margin-bottom: 50px">
            <br><br>
            <input type="submit" id="submit3" name="make_payment" class="btn btn-success btn-block" value="Confirm" />
        </div>
    </div>


    </div>

@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#diverr').hide();

            var packs = JSON.parse(`<?php echo $allpacks; ?>`),
                promo = JSON.parse(`<?php echo $promos; ?>`),
                promo_exist = false;
            for (let i = 0; i < 7; i++) {
                $('#child' + i).hide();
            }
            $('#payment').hide();
            $('#dateform').hide();
            $("#country_id,#city_id,#status,#calltype,.levels,#sms,#ad,#source,#methode")
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
            $('#fees').on('change', function() {
                if ($('#double').is(":checked") == false) {
                    if ($('#fees').is(":checked")) {
                        $("#amount").val(parseInt($("#amount").val()) + 7);
                    } else {
                        $("#amount").val(parseInt($("#amount").val()) - 7);
                    }
                }
            });
            $('#double').on('change', function() {
                let nb;
                if ($('#fees').is(":checked")) {
                    nb = 3;
                } else nb = 10
                if ($('#double').is(":checked")) {
                    $("#amount").val(parseInt($("#amount").val()) + nb);
                } else {
                    $("#amount").val(parseInt($("#amount").val()) - nb);
                }
            });
            $('#status').on('change', function() {
                st = $(this).val();
                if (st != 2 && st != 4 && st != 10) {
                    $('#dateform').show();
                    if (st == 1 || st == 3 || st == 5 || st == 6) {
                        $('#datelabel').text('Next Call Date');
                    } else {
                        $('#datelabel').text('Conversion Date');
                    }
                } else {
                    $('#dateform').hide();

                }
                if (st == 7 || st == 8 || st == 9) {
                    $("#amount").val(0);
                    $('#fees').prop('checked', false);
                    $('#double').prop('checked', false);
                    $('#payment').show();
                    for (let i = 0; i < 7; i++) {
                        if ($("#promo_price" + i).val() > 0) {
                            $("#amount").val(parseInt($("#amount").val()) + parseInt($("#promo_price" + i)
                                .val()));
                        } else {
                            $("#amount").val(parseInt($("#amount").val()) + parseInt($("#price" + i)
                                .val()));
                        }
                    }
                } else {
                    $('#payment').hide();
                }
            });
            $('#nbchildren').on('change', function() {
                for (let i = 0; i < 7; i++) {
                    $('#child' + i).hide();
                }
                for (let i = 0; i < $(this).val(); i++) {
                    $('#child' + i).show();
                    $.ajax({
                        type: 'GET',
                        url: '{{ Route('tokengen') }}',
                        success: function(data) {
                            $('#current' + i).val(data);
                        }
                    });
                }
                $('#children').show();
            });
            $(".levels")
                .on('change', function() {
                    var id = $(this).attr('id');
                    var z = id.charAt(id.length - 1);
                    $('#tokeen' + z).hide();
                    $("#pack" + z + " option").remove().end();
                    $("#pack" + z).val(null).trigger('change');

                    for (let i = 0; i < packs.length; i++) {
                        if (packs[i].level_id == $(this).val()) {
                            var option = new Option(packs[i].name, packs[i].id, false, false);
                            $packs.append(option).trigger('change');
                        }

                    }
                    var option = new Option("---", null, false, false);
                    $packs.append(option);
                    st = $("#status").val();
                    if (st == 7 || st == 8 || st == 9) {
                        $("#amount").val(0);
                        $('#fees').prop('checked', false);
                        $('#double').prop('checked', false);
                        $('#payment').show();
                        s = 0;
                        for (let i = 0; i < 7; i++) {
                            if ($("#promo_price" + i).val() > 0) {
                                s += parseInt($("#promo_price" + i).val());
                            } else {
                                s += parseInt($("#price" + i).val());
                            }
                        }
                        $("#amount").val(s);
                    }
                })
            $(".packs")
                .on('change', function() {
                    var id = $(this).attr('id');
                    var z = id.charAt(id.length - 1);
                    $("#pack" + z)
                        .on('change', function() {
                            for (let i = 0; i < promo.length; i++) {
                                if (promo[i].pack_id == $(this).val()) {
                                    $("#promo_price" + z).val(promo[i].value);
                                    promo_exist = true;
                                }
                            }

                            for (let i = 0; i < packs.length; i++) {
                                if (packs[i].id == $(this).val()) {
                                    $("#price" + z).val(packs[i].price);
                                    if (!promo_exist) {
                                        $("#promo_price" + z).val(packs[i].price);
                                    }
                                }
                            }

                        })
                    $("#pack" + z)
                        .one('change', function() {
                            st = $("#status").val();
                            if (st == 7 || st == 8 || st == 9) {
                                $("#amount").val(0);
                                $('#fees').prop('checked', false);
                                $('#double').prop('checked', false);
                                $('#payment').show();
                                s = 0;
                                for (let i = 0; i < 7; i++) {
                                    if ($("#promo_price" + i).val() > 0) {
                                        s += parseInt($("#promo_price" + i).val());
                                    } else {
                                        s += parseInt($("#price" + i).val());
                                    }
                                }
                                $("#amount").val(s);
                            }
                            var selectVal = $("#pack" + z + " option:selected").html();
                            if (selectVal === 'اشتراك السنة الحالية و السنة المقبلة') {
                                $('#tokeen' + z).show();

                                $.ajax({
                                    type: 'GET',
                                    url: '{{ Route('tokengen') }}',
                                    success: function(data) {
                                        $('#Next' + z).val(data)
                                    }
                                });

                            } else {
                                $('#Next' + z).val('')

                            }
                        })


                });

            $('form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: form.serialize(),
                    success: function(data) {
                        // console.log("controller", data)
                    },
                    error: function(error) {
                        res = error.responseJSON.errors;
                        $.each(res, function(index, value) {
                            $("#erreur").append("<li>" + index + " : " + value +
                                "</li>");
                        });
                        $('#diverr').show();

                    }
                });
                form[0].reset();
                $('select').each(
                    function(index) {
                        var input = $(this);
                        input.prop("selectedIndex", 0).change();
                    }
                );
                $('#dateform').hide();
                for (let i = 0; i < 7; i++) {
                    $('#child' + i).hide();
                }

            })
        });
    </script>
@endsection
