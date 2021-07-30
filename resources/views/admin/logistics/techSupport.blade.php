<div class="row-fluid">
    <div class="span12">
        {{-- TechSupport --}}
        <div class="grid simple">
            <div class="grid-title">
                <h4>Technical <span class="semi-bold">Support</span></h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="grid-body ">
                {!! Form::open(['route' => ['logistics.store', 'source' => 'support']]) !!}

                <div class="form-group">
                    {!! Form::textarea('problem', null, ['class' => 'form-control', 'placeholder' => 'write the problem...']) !!}
                </div>
                <div class="text-right">
                    <br>
                    <input type="submit" name="previous" class="previous action-button-previous" value="Previous"
                        id="previous4" />
                    <button type="submit" name="next" class="next action-button" value="Next Step" id="submit4">
                        Next</i></button>

                </div>
            </div>
        </div>

    </div>
    <script>
        $('form').submit(function(e) {
            e.preventDefault();
            source = 'second';
            var form = $(this);
            var url = form.attr('action');
            var $inputs = $('form :input');
            var support = {};
            $inputs.each(function() {
                support[this.name] = $(this).val();
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: support,
                success: function(data) {
                    console.log("data from controller", data)

                }
            });
        })
        $("#submit4").click(function() {

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
        $("#previous4").click(function() {

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
    </script>
</div>
