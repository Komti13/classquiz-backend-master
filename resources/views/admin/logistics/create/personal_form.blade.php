<div class="row-fluid" id="home">
    <div class="span12">
        {{-- About children --}}
        {!! Form::open(['route' => ['logistics.store', 'source' => 'third']]) !!}
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

                    <div class="form-group">
                        {!! Form::label('name' . $i, 'Name') !!}
                        {!! Form::text('name' . $i, null, ['class' => 'form-control', 'placeholder' => 'Name', 'id' => `name$i`]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('level_id' . $i, 'Level') !!}
                        {!! Form::select('level_id' . $i, ['---', $levels], null, ['class' => 'levels', 'id' => 'level_id' . $i, 'data-level-id' => '152']) !!}
                    </div>
                </div>

            </div>

            {{-- </div> --}}
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
                        <select name='pack<?php echo $i; ?>' class='packs' id='pack<?php echo $i; ?>'>
                            <option value="---">---</option>

                            @foreach ($packs as $id => $name)

                                <option class="pack" value="{{ $id }}" data-pack-id="{{ $id }}">
                                    {{ $name }}
                                </option>
                    </div>
        @endforeach
        </select>
    </div>
    <div class="form-group">
        {!! Form::label('price' . $i, 'Price') !!}
        {!! Form::text('price' . $i, null, ['class' => 'form-control price', 'value' => '0', 'id' => 'price' . $i]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('promo_price' . $i, 'Price After Promotion') !!}
        {!! Form::text('promo_price' . $i, null, ['class' => 'form-control price', 'value' => '0', 'id' => 'promo_price' . $i]) !!}
    </div>
</div>
</div>
@endfor

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
    <div class="grid-body ">
        <div class="form-group">
            {!! Form::label('source', 'Source') !!}
            {!! Form::select('source', ['---', $sources], null, ['id' => 'source']) !!}
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
            {!! Form::select('ad', ['---', $ads], null, ['id' => 'ad']) !!}
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
    <div class="grid-body ">
        <div class="form-group">
            {!! Form::label('type', 'SMS Type') !!}
            {!! Form::select('type', ['type1', 'type2', 'type3'], null, ['id' => 'sms']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('sms_text', 'Text') !!}
            {!! Form::textarea('sms_text', null, ['class' => 'form-control', 'placeholder' => 'SMS Text...']) !!}
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
            {!! Form::select('paymethod', ['---', $methodes], null, ['id' => 'methode']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('amount', 'Amount') !!}
            {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => 'Amount to pay']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('paystatus', 'Payment Status') !!}
            {!! Form::select('paystatus', ['---', $payStatus], null, ['id' => 'paystatus']) !!}
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
            {!! Form::select('delstatus', ['---', 'Delivery launched' => 'Delivery launched', 'To be confirmed' => 'To be confirmed', 'Cacelled before launch' => 'Cacelled before launch', 'payed (credit card)' => 'payed (credit card)', 'payed(tranfer)' => 'payed(tranfer)', 'payed(Physical C)' => 'payed(Physical C)'], null, ['id' => 'delstatus']) !!}
        </div>
        <div class="form-group">
            <label for="fees" class="col-sm-4 col-md-4 control-label text-left ">Delivery Fees</label>
            <div class="col-sm-7 col-md-7">
                <div class="input-group">
                    <div id="radioBtn" class="btn-group">
                        <a class="btn btn-primary btn-sm active" data-toggle="fees" data-title="true">YES</a>
                        <a class="btn btn-primary btn-sm notActive" data-toggle="fees" data-title="false">NO</a>
                    </div>
                    <input type="hidden" name="fees" id="fees" value="false">
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
    <div class="grid-body ">
        @for ($i = 0; $i < $nbchild; $i++)

            <div class="form-group">
                <h4>Tokens For Child {{ $i+1 }}</h4>
                {!! Form::label('label', 'Current Year Token ') !!}
                {!! Form::text('current', '', ['class' => 'form-control', 'placeholder' => 'token for this year', 'name' => 'current' . $i, 'id' => 'current' . $i, 'disabled']) !!}
                <div id="tokeen<?php echo $i; ?>" class="tokeen">
                    {!! Form::label('label', 'Next Year Token ') !!}
                    {!! Form::text('next', '', ['class' => 'form-control', 'placeholder' => 'token for next year', 'name' => 'next' . $i, 'id' => 'Next' . $i, 'disabled']) !!}
                </div>
                <script>
                    $(document).ready(function() {
                        $.ajax({
                            type: 'GET',
                            url: '{{ Route('tokengen') }}',
                            success: function(data) {
                                $('#current' + `<?php echo $i; ?>`).val(data);
                                $('.tokeen').hide();


                            }
                        });
                    });
                </script>
            </div>
        @endfor

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
                var option = new Option("---",null, false, false);
                        $packs.append(option);

                // $('.level > div > input').prop("disabled", true);
                // $('.level').hide();
                // $('.level[data-level-id="' + $(this).val() + '"]  > div > input').prop("disabled", false);
                // $('.level[data-level-id="' + $(this).val() + '"]').show();
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
                        var selectVal = $("#pack" + z + " option:selected").html();
                        if (selectVal === 'اشتراك السنة الحالية و السنة المقبلة') {
                            $('#tokeen' + z).show();
                            
                            $.ajax({
                                type: 'GET',
                                url: '{{ Route('tokengen') }}',
                                success: function(data) {
                            // console.log(z, data)

                                    $('#Next' + z).val(data)
                                }
                            });

                        }
                        else{
                            $('#tokeen' + z).hide();
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
                    console.log("data from controller", data)
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
