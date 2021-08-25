@extends('admin.layout.layout',['title'=>''])
@section('css')
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
        {!! Form::model($s, ['route' => ['logistics.update', 'source' => 'first', 'class' => 'form', 'id' => $id]]) !!}
        <div class="form-row">
            <h4>Parent</h4>
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', $s->user->name, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('phone', 'Phone') !!}
                    {!! Form::text('phone', $s->user->phone, ['class' => 'form-control', 'placeholder' => 'Phone']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    {!! Form::label('phone2', 'Phone 2') !!}
                    {!! Form::text('phone2', $s->user->phone2, ['class' => 'form-control', 'placeholder' => 'Phone 2']) !!}
                </div>
            </div>
        </div>
        <br><br>
        <?php
        $nbchild = 1;
        if ($s->user->nb_children > 0) {
            $nbchild = $s->user->nb_children;
        }
        ?>
        <div class="form-row">
            <div class="col-sm-7">
                <div class="form-group">
                    {!! Form::label('address', 'Address') !!}
                    {!! Form::text('address', $s->user->address, ['class' => 'form-control', 'placeholder' => 'Address']) !!}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('city_id', 'City') !!}
                    {!! Form::select('city_id', $cities, $s->user->city_id, ['placeholder' => 'Choose City', 'id' => 'city_id']) !!}
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
                    {!! Form::number('nb_children', $s->user->nb_children, ['class' => 'form-control', 'placeholder' => 'number of children', 'id' => 'nbchildren', 'min' => $s->user->nb_children]) !!}
                </div>
            </div>
        </div>
        <br><br>
        <?php
        $child_name = null;
        $level_id = null;
        if ($s->child != null) {
            $child_name = $s->child->name;
            $level_id = $s->child->level_id;
        }
        ?>

        <div class="form-row">
            <h4>Child Info</h4>
            <div class="col-sm-4">
                <div class="form-group ">
                    {!! Form::label('childname', 'Child name') !!}
                    {!! Form::text('childname', $child_name, ['class' => 'form-control', 'placeholder' => 'Child name', 'id' => 'childname']) !!}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">

                    {!! Form::label('level_id', 'Level') !!}
                    {!! Form::select('level_id', $levels, $level_id, ['placeholder' => 'Choose Level', 'class' => 'levels', 'id' => 'level_id0', 'data-level-id' => '152']) !!}
                </div>
            </div>
            <?php
            
            $pack_id = null;
            $pack_price = 0;
            $selected = 'selected';
            if ($s->pack != null) {
                $pack_id = $s->pack->id;
                $pack_price = (int) $s->pack->price;
            }
            $promo_price = 0;
            foreach ($promos as $promo) {
                if ($promo->pack_id == $pack_id) {
                    $promo_price = (int) $promo->value;
                }
            }
            $current = null;
            $next = null;
            if ($s->token != null) {
                if ($s->token->token[strlen($s->token->token) - 1] == '*') {
                    $current = null;
                    $next = substr($s->token->token, 0, 9);
                } else {
                    $next = null;
                    $current = $s->token->token;
                }
            }
            ?>
            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('pack', 'Pack Name') !!}
                    <select name='pack' class='packs' id='pack0'>
                        <option value="---">Choose Pack</option>

                            @foreach ($packs as $id => $name)
                                @if ("{{ $id }}"=="{{ $pack_id }}" )
                                    <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}" selected>
                                        {{ $name }}
                                    </option>
                                @else
                                    <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}">
                                        {{ $name }}
                                    </option>
                                @endif

                            @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('price', 'Price') !!}
                    {!! Form::number('price', $pack_price, ['class' => 'form-control price', 'placeholder' => 'price $', 'value' => '0', 'id' => 'price0']) !!}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('promo_price', 'Price After Promotion') !!}
                    {!! Form::number('promo_price', $promo_price, ['class' => 'form-control price', 'placeholder' => 'promotion $', 'value' => '0', 'id' => 'promo_price0']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group ">
                    {!! Form::label('label', 'Current Year Token ') !!}
                    {!! Form::text('current', $current, ['class' => 'form-control', 'placeholder' => 'token for this year', 'name' => 'current', 'id' => 'current0', 'readonly']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('label', 'Next Year Token ') !!}
                    {!! Form::text('next', $next, ['class' => 'form-control', 'placeholder' => 'token for next year', 'name' => 'next', 'id' => 'Next0', 'readonly']) !!}
                </div>
            </div>
        </div>
        {{-- @if ($s->child != null) --}}
        {{-- @endif --}}
        @for ($i = $nbchild; $i < 7; $i++)
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
                                @if (" {{ $id }}"=="{{ $pack_id }}" )
                                    <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}"
                                        selected>
                                        {{ $name }}
                                    </option>
                                @else
                                    <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}">
                                        {{ $name }}
                                    </option>
                                @endif

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
        <?php
        $source = null;
        $date = null;
        $type = null;
        $ad = null;
        $notes = null;
        $stat = null;
        $txt = null;
        if ($s->user->usercalls != null) {
            if ($s->user->usercalls->salesInfo != null) {
                if ($s->user->usercalls->salesInfo->source != null) {
                    $source = $s->user->usercalls->salesInfo->source->id;
                }
            }
        
            if ($s->user->usercalls->salesInfo != null) {
                if ($s->user->usercalls->salesInfo->ad != null) {
                    $ad = $s->user->usercalls->salesInfo->ad->id;
                }
            }
        
            if ($s->user->usercalls->sms != null) {
                $type = $s->user->usercalls->sms->type;
                $txt = $s->user->usercalls->sms->text;
            }
            if ($s->user->usercalls->conversation_date != null) {
                $date = $s->user->usercalls->conversation_date;
            }
            if ($s->user->usercalls->notes != null) {
                $notes = $s->user->usercalls->notes;
            }
            if ($s->user->usercalls->userStatus != null) {
                $stat = $s->user->usercalls->userStatus->id;
            }
        }
        
        ?>
        <div class="form-row">
            <h4>Informrations</h4>
            <div class="col-sm-3">
                <div class="form-group ">
                    {!! Form::label('source', 'Source') !!}
                    {!! Form::select('source', $sources, $source, ['id' => 'source', 'placeholder' => 'Choose Source']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('ad', 'AD') !!}
                    {!! Form::select('ad', $ads, $ad, ['id' => 'ad', 'placeholder' => 'Choose AD']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('type', 'SMS Type') !!}
                    {!! Form::select('type', ['type1', 'type2', 'type3'], $type, ['id' => 'sms']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('sms_text', 'Text') !!}
                    {!! Form::textarea('sms_text', $txt, ['class' => 'form-control', 'placeholder' => 'SMS Text...', 'rows' => 1, 'cols' => 40]) !!}
                </div>
            </div>
        </div>
        <br><br>
        <div class="form-row">
            <div class="col-sm-3">
                <div class="form-group ">
                    {!! Form::label('call_date', 'Call Date') !!}
                    {!! Form::input('call_date', 'call_date', $date, ['class' => 'form-control', 'placeholder' => 'Call Date', 'id' => 'datepick']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', $status, $stat, ['placeholder' => 'Choose status', 'id' => 'status']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">

                    {!! Form::label('notes', 'Notes') !!}
                    {!! Form::textarea('notes', $notes, ['class' => 'form-control', 'placeholder' => 'Notes...', 'rows' => 3, 'cols' => 40]) !!}
                </div>
            </div>
        </div>
        <?php
        $payment = $s->payment;
        $method = null;
        $amount = 0;
        $delstatus = null;
        $deldate = null;
        if ($r != null) {
            $deldate = date('Y-m-d\TH:i', strtotime($r));
        }
        if ($s->delivery != null) {
            $delstatus = $s->delivery->delivery_status;
        }
        
        $payment_exist = 'false';
        if ($payment != null) {
            $payment_exist = 'true';
            $method = $payment->payment_method_id;
            $amount = (int) $payment->amount;
        }
        $check = '';
        if ($s->delivery != null) {
            if ($s->delivery->double_delivery == true) {
                $check = 'checked';
            }
        }
        $feescheck = '';
        if ($s->delivery != null) {
            if ($s->delivery->delivery_fees == true) {
                $feescheck = 'checked';
            }
        }
        // echo $amount;
        ?>
        <div class="form-row" id="payment">
            <div class="col-sm-3">
                <div class="form-group ">
                    {!! Form::label('payment', 'Payment Methode') !!}
                    {!! Form::select('paymethod', $methodes, $method, ['placeholder' => 'Choose payment methode', 'id' => 'methode']) !!}
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    {!! Form::label('amount', 'Amount') !!}
                    {!! Form::number('amount', $amount, ['class' => 'form-control', 'placeholder' => 'Amount to pay', 'id' => 'amount']) !!}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('fees', 'Delivery Fees') !!}

                    <div class="switch">
                        <input type="checkbox" id="fees" name="fees" <?php echo $feescheck; ?> />
                        <label for="fees" style="margin-top:-9px "></label>
                    </div>
                </div>

            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {!! Form::label('dd', 'Double Delivery') !!}

                    <div class="switch">
                        <input type="checkbox" id="double" name="double" <?php echo $check; ?> />
                        <label for="double" style="margin-top:-9px "></label>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-row" id="dateform">
            <div class="col-sm-12">
                <div class="form-group ">
                    {!! Form::label('delivery_date', '', ['id' => 'datelabel']) !!}
                    {!! Form::input('datetime-local', 'delivery_date', $deldate, ['class' => 'form-control', 'placeholder' => 'Delivery Date', 'id' => 'dateinput']) !!}
                </div>
            </div>
        </div>
        <div class="form-row" id="delivery">
            <div class="col-sm-12">
                <div class="form-group ">
                    {!! Form::label('delstatus', 'Delivery Status') !!}
                    {!! Form::select('delstatus', ['Delivery launched' => 'Delivery launched', 'To be confirmed' => 'To be confirmed', 'Cancelled before launch' => 'Cancelled before launch', 'payed (credit card)' => 'payed (credit card)', 'payed(tranfer)' => 'payed(tranfer)', 'payed(Physical C)' => 'payed(Physical C)'], $delstatus, ['id' => 'delstatus', 'placeholder' => 'Choose Delivery Status']) !!}
                </div>
            </div>

        </div>

        <div style="margin-bottom: 50px">
            <br><br>
            <input type="submit" id="submit3" name="make_payment" class="btn btn-success btn-block" value="Update" />
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
            var nbchild = JSON.parse(`<?php echo $nbchild; ?>`),
                packs = JSON.parse(`<?php echo $allpacks; ?>`),
                promo = JSON.parse(`<?php echo $promos; ?>`),
                pay_exist = JSON.parse(`<?php echo $payment_exist; ?>`),
                promo_exist = false;
            for (let i = nbchild; i < 7; i++) {
                $('#child' + i).hide();
            }
            if (pay_exist == false) {
                $('#payment').hide();
                $('#delivery').hide();
                $('#dateform').hide();
            }
            st = $("#status").val();
            if (st == 7 || st == 8 || st == 9) {
                $('#dateform').show();
                $('#datelabel').text('Conversion Date');
            } else if (st == 1 || st == 3 || st == 5 || st == 6) {
                $('#dateform').show();
                $('#datelabel').text('Next Call Date');
            }
            $("#country_id,#city_id,#status,#calltype,.levels,#sms,#ad,#source,#methode,#delstatus")
                .select2({
                    width: '100%'
                })
            const $packs = $(".packs")
                .select2({
                    width: '100%'
                })
            $("#datepick").focus(function() {
                $('#datepick').datetimepicker({
                    inline: true,
                    format: 'DD-MM-YYYY HH:mm',
                });
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
                    $('#delivery').show();
                    s = 0;
                    for (let i = 0; i < 7; i++) {
                        if ($("#promo_price" + i).val() > 0) {
                            if (!isNaN(parseInt($("#promo_price" + i).val()))) {
                                s += parseInt($("#promo_price" + i).val());
                            }
                        } else {
                            if (!isNaN(parseInt($("#price" + i).val()))) {
                                s += parseInt($("#price" + i).val());
                            }
                        }
                    }
                    $("#amount").val(s);
                } else {
                    $('#payment').hide();
                }
            });
            $('#nbchildren').on('change', function() {
                for (let i = nbchild; i < 7; i++) {
                    $('#child' + i).hide();
                }
                for (let i = nbchild; i < $(this).val(); i++) {
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
                    // $('#tokeen' + z).hide();
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
                                if (!isNaN(parseInt($("#promo_price" + i).val()))) {
                                    s += parseInt($("#promo_price" + i).val());
                                }
                            } else {
                                if (!isNaN(parseInt($("#price" + i).val()))) {
                                    s += parseInt($("#price" + i).val());
                                }
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
                                // $("#amount").val(0);
                                $('#fees').prop('checked', false);
                                $('#double').prop('checked', false);
                                $('#payment').show();
                                s = 0;
                                for (let i = 0; i < 7; i++) {
                                    if ($("#promo_price" + i).val() > 0) {
                                        if (!isNaN(parseInt($("#promo_price" + i).val()))) {
                                            s += parseInt($("#promo_price" + i).val());
                                        }
                                    } else {
                                        if (!isNaN(parseInt($("#price" + i).val()))) {
                                            s += parseInt($("#price" + i).val());
                                        }
                                    }
                                }
                                $("#amount").val(s);
                            }
                            var selectVal = $("#pack" + z + " option:selected").html();
                            if (selectVal === 'اشتراك السنة الحالية و السنة المقبلة') {
                                if ($('#Next' + z).val() == '') {
                                    $.ajax({
                                        type: 'GET',
                                        url: '{{ Route('tokengen') }}',
                                        success: function(data) {
                                            $('#Next' + z).val(data)
                                        }
                                    });

                                }
                            } else {
                                if ($('#Next' + z).val() != '') {
                                    $('#current' + z).val($('#Next' + z).val());
                                    $('#Next' + z).val('');
                                }
                                if ($('#current' + z).val() == '') {
                                    $.ajax({
                                        type: 'GET',
                                        url: '{{ Route('tokengen') }}',
                                        success: function(data) {
                                            $('#current' + z).val(data)
                                        }
                                    });
                                }

                            }

                        });
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
                        alert("Subscription updated successfully"); 
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
                
                // form[0].reset();
                // $('select').each(
                //     function(index) {
                //         var input = $(this);
                //         input.prop("selectedIndex", 0).change();
                //     }
                // );
                // $('#dateform').hide();
                // for (let i = nbchild; i < 7; i++) {
                //     $('#child' + i).hide();
                // }

            })
        });
    </script>
@endsection
