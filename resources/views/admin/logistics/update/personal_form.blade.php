<div class="row-fluid" id="home">
    <div class="span12">
        {{-- About children --}}
        {!! Form::model($s, ['route' => ['logistics.update', 'id' => $id, 'source' => 'third']]) !!}

        {{-- @for ($i = 0; $i < $nbchild; $i++) --}}
        <?php
        $child_id = null;
        $child_name = null;
        $child_lvl = null;
        if ($s->child != null) {
            $child_id = $s->child->id;
            $child_name = $s->child->name;
            $child_lvl = $s->child->level_id;
        }
        
        ?>
        <div class="grid simple">

            <div class="grid-title">
                <h4>About <span class="semi-bold">child</span></h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="grid-body ">

                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('namechild', $child_name, ['class' => 'form-control', 'placeholder' => 'Name', 'id' => `name`]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('level_id', 'Level') !!}
                    {!! Form::select('level_id', ['---', $levels], $child_lvl, ['class' => 'levels', 'id' => 'level_id', 'data-level-id' => '152']) !!}
                </div>
            </div>

        </div>

        {{-- About Pack --}}
        <?php
        $pack_id = null;
        $pack_price = null;
        $selected='selected';
        if ($s->pack != null) {
            $pack_id = $s->pack->id;
            $pack_price = $s->pack->price;
        }
        $promo_price = null;
        foreach ($promos as $promo) {
            if ($promo->pack_id == $pack_id) {
                $promo_price = $promo->value;
            }
        }
        
        ?>
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
                    <select name='pack' class='packs' id='pack' >
                            <option value="---">---</option>

                            @foreach ($packs as $id => $name)
                        
                                @if ("{{ $id }}" == "{{ $pack_id }}")
                                <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}"  selected >
                                    {{ $name }}
                                </option>
                                @else
                                <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}"   >
                                    {{ $name }}
                                </option>
                                @endif
                              
                    </div>
        @endforeach
        </select>
                </div>
                <div class="form-group">
                    {!! Form::label('price', 'Price') !!}
                    {!! Form::text('price', $pack_price, ['class' => 'form-control price', 'value' => '0', 'id' => 'price']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('promo_price', 'Price After Promotion') !!}
                    {!! Form::text('promo_price', $promo_price, ['class' => 'form-control price', 'value' => '0', 'id' => 'promo_price']) !!}
                </div>
            </div>
        </div>
        {{-- @endfor --}}

        {{-- About Sales --}}
        <div class="grid simple">
            <div class="grid-title" style="text-align: center; padding-top: 20px">
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
            <?php
            $src = null;
            if ($s->user->usercalls->salesInfo->source != null) {
                $src = $s->user->usercalls->salesInfo->source->id;
            } else {
                $src = null;
            }
            ?>
            <div class="grid-body ">
                <div class="form-group">
                    {!! Form::label('source', 'Source') !!}
                    {!! Form::select('source', $sources, $src, ['id' => 'source', 'placeholder' => 'Choose Source']) !!}
                </div>
            </div>
        </div>
        {{-- About AD --}}
        <?php
        $ad = null;
        if ($s->user->usercalls->salesInfo->ad != null) {
            $ad = $s->user->usercalls->salesInfo->ad->id;
        } else {
            $ad = null;
        }
        ?>
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
                    {!! Form::select('ad', ['---', $ads], $ad, ['id' => 'ad', 'placeholder' => 'Choose Ad']) !!}
                </div>

            </div>
        </div>

        {{-- About SMS --}}
        <div class="grid simple">
            <div class="grid-title">
                <h4>Send <span class="semi-bold">SMS</span></h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <?php
            $type = null;
            $txt = null;
            if ($s->user->usercalls->sms != null) {
                $type = $s->user->usercalls->sms->type;
                $txt = $s->user->usercalls->sms->text;
            } else {
                $type = null;
                $txt = null;
            }
            ?>
            <div class="grid-body ">
                <div class="form-group">
                    {!! Form::label('type', 'SMS Type') !!}
                    {!! Form::select('type', ['type1', 'type2', 'type3'], $type, ['id' => 'sms', 'placeholder' => 'Choose SMS type']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('sms_text', 'Text') !!}
                    {!! Form::textarea('sms_text', $txt, ['class' => 'form-control', 'placeholder' => 'SMS Text...']) !!}
                </div>


            </div>
        </div>
        {{-- About Payment --}}
        <?php
        $method = null;
        $amount = null;
        $status = null;
        if ($payment != null) {
            $method = $s->payment->payment_method_id;
            $amount = $s->payment->amount;
            $status = $s->payment->current_status->id;
        } else {
            $method = null;
            $amount = null;
            $status = null;
        }
        ?>
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
                    {!! Form::select('paymethod', $methodes, $method, ['id' => 'methode', 'placeholder' => 'Choose Payment Methode']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('amount', 'Amount') !!}
                    {!! Form::text('amount', $amount, ['class' => 'form-control', 'placeholder' => 'Amount to pay']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('paystatus', 'Payment Status') !!}
                    {!! Form::select('paystatus', $payStatus, $status, ['id' => 'paystatus', 'placeholder' => 'Choose Payment Status']) !!}
                </div>
            </div>
        </div>
        {{-- About Delivery --}}
        <?php
        $deldate = null;
        $delstatus = null;
        $fees = true;
        if ($payment != null) {
            if ($s->payment->delivery != null) {
                $deldate = $s->payment->delivery->delivery_date;
                $delstatus = $s->payment->delivery->delivery_status;
                $fees = $s->payment->delivery->delivery_fees;
            } else {
                $deldate = null;
                $delstatus = null;
                $fees = true;
            }
        }
        
        ?>

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
                    {!! Form::input('date', 'delivery_date', $deldate, ['class' => 'form-control', 'placeholder' => 'Delivery Date']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('delstatus', 'Delivery Status') !!}
                    {!! Form::select('delstatus', ['Delivery launched' => 'Delivery launched', 'To be confirmed' => 'To be confirmed', 'Cacelled before launch' => 'Cacelled before launch', 'payed (credit card)' => 'payed (credit card)', 'payed(tranfer)' => 'payed(tranfer)', 'payed(Physical C)' => 'payed(Physical C)'], $delstatus, ['id' => 'delstatus', 'placeholder' => 'Choose Delivery Status']) !!}
                </div>
                <div class="form-group">
                    <label for="fees" class="col-sm-4 col-md-4 control-label text-left ">Delivery Fees</label>
                    <div class="col-sm-7 col-md-7">
                        <div class="input-group">
                            <div id="radioBtn" class="btn-group">
                                <a class="btn btn-primary btn-sm active" data-toggle="fees" data-title="true">YES</a>
                                <a class="btn btn-primary btn-sm notActive" data-toggle="fees" data-title="false">NO</a>
                            </div>
                            <input type="hidden" name="fees" id="fees" value=<?php echo $fees; ?>>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="grid simple">
            <div class="grid-title">
                <h4>Tokens<span class="semi-bold"></span></h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <?php
            $token = null;
            if ($s->token != null) {
                $token = $s->token->token;
            } else {
                $token = null;
            }
            ?>
            <div class="grid-body ">
                {{-- @for ($i = 0; $i < $nbchild; $i++) --}}
                <div class="form-group">
                    <h4>Tokens For Child</h4>
                    {!! Form::label('label', 'Current Year Token ') !!}
                    {!! Form::text('current', $token, ['class' => 'form-control', 'placeholder' => 'token for this year', 'name' => 'current', 'id' => 'current']) !!}
                    <div id="tokeen" class="tokeen">
                        {!! Form::label('label', 'Next Year Token ') !!}
                        {!! Form::text('next', '', ['class' => 'form-control', 'placeholder' => 'token for next year', 'name' => 'next', 'id' => 'Next']) !!}
                    </div>
                </div>
                {{-- @endfor --}}

                <div class="text-right">
                    <br>
                    <input type="submit" name="previous" class="previous action-button-previous" value="Previous"
                        id="previous3" />
                    <input type="submit" id="submit3" name="make_payment" class="next action-button" value="Confirm" />
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var packs = JSON.parse(`<?php echo $allpacks; ?>`),
                promo = JSON.parse(`<?php echo $promos; ?>`),
                promo_exist = false;
            $("#sms,.levels,#source,#ad,#delstatus,#paystatus,#methode")
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


            $(".levels")
                .on('change', function() {
                    // var id = $(this).attr('id');
                    // var z = id.charAt(id.length - 1);
                    $('#tokeen').hide();
                    $("#pack" + " option").remove().end();
                    $("#pack").val(null).trigger('change');
                    for (let i = 0; i < packs.length; i++) {
                        if (packs[i].level_id == $(this).val()) {
                            var option = new Option(packs[i].name, packs[i].id, false, false);
                            $packs.append(option).trigger('change');
                        }

                    }
                    var option = new Option("---",null, false, false);
                        $packs.append(option);

                    // $('.level > div > input').prop("disabled", true);
                    // $('.level').hide();
                    // $('.level[data-level-id="' + $(this).val() + '"]  > div > input').prop("disabled", false);
                    // $('.level[data-level-id="' + $(this).val() + '"]').show();
                })
            $(".packs")
                .on('change', function() {
                    // var id = $(this).attr('id');
                    // var z = id.charAt(id.length - 1);
                    $("#pack")
                        .on('change', function() {
                            for (let i = 0; i < promo.length; i++) {
                                if (promo[i].pack_id == $(this).val()) {
                                    $("#promo_price").val(promo[i].value);
                                    promo_exist = true;
                                }
                            }

                            for (let i = 0; i < packs.length; i++) {
                                if (packs[i].id == $(this).val()) {
                                    $("#price").val(packs[i].price);
                                    if (!promo_exist) {
                                        $("#promo_price").val(packs[i].price);
                                    }
                                }
                            }
                        })
                    $("#pack")
                        .one('change', function() {
                            var selectVal = $("#pack" + " option:selected").html();
                            if (selectVal === 'اشتراك السنة الحالية و السنة المقبلة') {
                                $('#tokeen').show();

                                $.ajax({
                                    type: 'GET',
                                    url: '{{ Route('tokengen') }}',
                                    success: function(data) {

                                        $('#Next').val(data)
                                    }
                                });

                            } else {
                                $('#tokeen').hide();
                            }
                        })
                })
            $('form').submit(function(e) {
                e.preventDefault();
                source = 'third';
                var form = $(this);
                var url = form.attr('action');
                var $inputs = $('form :input');
                var personal = {};
                $inputs.each(function() {
                    personal[this.name] = $(this).val();
                });

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: personal,
                    success: function(data) {
                        // console.log("data from controller", data)
                    }
                });
            })
            $("#submit3").click(function() {

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
            $("#previous3").click(function() {

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



        // $(".subject > div > input").on("change", function() {
        //     if ($(this).attr("type") == "radio") {
        //         $(".chapter input").prop('checked', false);
        //         $(".chapter input").prop("disabled", true);
        //         $(this).parents(".subject").find("input").prop("disabled", false);
        //     }
        // })

        // $('.expand').click(function() {
        //     $(this).parents('.element').first().children('.expandable').slideToggle('slow');
        //     var icon = $('.material-icons', this);
        //     if (icon.text() == 'keyboard_arrow_right') {
        //         icon.text('keyboard_arrow_down')
        //     } else {
        //         icon.text('keyboard_arrow_right')
        //     }
        // });

        // $('input[type="checkbox"][class!="chapter-input"]').change(function() {
        //     if (this.checked) {
        //         $(this).parents('.element').first().find('input[type="checkbox"]').prop('checked', true);
        //     } else {
        //         $(this).parents('.expandable').first().find('input[type="checkbox"]').prop('checked', false);
        //     }
        // });
    </script>
</div>
