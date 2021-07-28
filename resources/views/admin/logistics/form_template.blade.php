@extends('admin.layout.layout',['title'=>''])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="{{ asset('assets/css/test.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        #field {
             margin-top: 110px; 
            /* //width: 100%;  */
        }

        #fixed {
            position: fixed;
            background: #e5e9ec;
           /* // width: 100%; */
            padding: 5px;
            z-index: 1;
            top: 60px;
            margin-bottom: 50px;
            overflow: hidden;
            
        }

        /* .flexbox {
            display: flex;
            justify-content: space-between;
        } */

    </style>
@endsection

@section('content')
    {{-- <div class="container"> --}}
    <!-- MultiStep Form -->
    <div class="container-fluid" id="">
        <div class="row justify-content-center mt-0">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center p-0 mt-3 mb-2">
                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <div id="msform" class="formm">
                                <!-- progressbar -->
                                <div id="fixed" class="col-xs-9"    >
                                    <div class="page-title text-left">
                                        <h3>Logistics</h3>
                                    </div>
                                    <ul id="progressbar">
                                        <a href="#" class="a">
                                            <li class="active" id="personal"><strong>User</strong></li>
                                        </a>
                                        <a href="#" class="a">
                                            <li id="account"><strong>Call</strong></li>
                                        </a>
                                        <a href="#" class="a">
                                            <li id="payment"><strong>Personal</strong></li>
                                        </a>
                                        <a href="#" class="a">
                                            <li id="confirm"><strong>Finish</strong></li>
                                        </a>
                                    </ul>
                                    {{-- <div class="flexbox">
                                        <button type="button" class="previous">
                                            <img
                                                src="https://img.icons8.com/ios-glyphs/30/000000/double-left--v2.png" /></button>

                                        <button type="button" class="next2">
                                            <img
                                                src="https://img.icons8.com/ios-glyphs/30/000000/double-right--v2.png" /></button>

                                    </div> --}}
                                </div>
                                <div id="field" >
                                    <fieldset style="width: 96.5%">
                                        <div id="create"></div>
                                    </fieldset>
                                    <fieldset style="width: 96.5%">
                                        <div id="next1">
                                        </div>
                                    </fieldset>
                                    <fieldset style="width: 96.5%">
                                        <div id="next2">
                                        </div>
                                    </fieldset>
                                    <fieldset style="width: 96.5%">
                                        <div id="success">
                                            <h2 class="fs-title text-center">Success !</h2> <br><br>
                                            <div class="row justify-content-center">
                                                <div class="col-3"> <img
                                                        src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                        class="fit-image"> </div>
                                            </div> <br><br>
                                            <div class="row justify-content-center">
                                                <div class="col-7 text-center">
                                                    <h5>You Have Successfully Completed the Operation</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="form">

    </div>


@endsection
@section('js')
    <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            // $("#country_id,#city_id")
            //     .select2({
            //         width: '100%'
            //     })
            $('#datepick').datetimepicker({
                inline: true,
                format: 'DD-MM-YYYY HH:mm'
            });
            $.ajax({
                type: 'GET',
                url: "{{ Route('logistics.create') }}",
                data: 'html',
                success: function(data) {
                    // console.log(data)
                    $('#create').html(data);
                }
            });
            // To complete *************************************************
            // $(".a li").click(function() {
            //     $(".a li").removeClass('active');
            //     var index = $(this).index('ul .a li') + 1;
            //     console.log(index)
            //     $("ul .a li:nth-child(-n+" + index + ")").addClass('active');
            // });
            // *********************************************
            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;
            $(".next").click(function() {

                current_fs = $(this).parent();
                console.log("parent  ", current_fs);
                next_fs = $(this).parent().next();

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
            // $(".next2").click(function() {

            //     current_fs = $(this).closest('form').find('fieldset');
            //     console.log("parent  ", current_fs);
            //     next_fs = current_fs.next();

            //     //Add Class Active
            //     $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //     //show the next fieldset
            //     next_fs.show();
            //     //hide the current fieldset with style
            //     current_fs.animate({
            //         opacity: 0
            //     }, {
            //         step: function(now) {
            //             // for making fielset appear animation
            //             opacity = 1 - now;

            //             current_fs.css({
            //                 'display': 'none',
            //                 'position': 'relative'
            //             });
            //             next_fs.css({
            //                 'opacity': opacity
            //             });
            //         },
            //         duration: 600
            //     });
            // });

            $(".previous").click(function() {

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

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

            $('.radio-group .radio').click(function() {
                $(this).parent().find('.radio').removeClass('selected');
                $(this).addClass('selected');
            });

            $(".submit").click(function() {
                return false;
            })

            // $.ajax({
            //     type: 'GET',
            //     url: "{{ Route('logistics.create') }}",
            //     data: 'html',
            //     success: function(data) {
            //         console.log(data)
            //         $('#next1').html(data);
            //     }
            // });
        });
        $(window).load(function() {
            $(".container").addClass("load");
        });
    </script>
@endsection
