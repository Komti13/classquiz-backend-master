@extends('admin.layout.layout',['title'=>'Pack Promotions'])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet"
          type="text/css"/>

@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Edit <span class="semi-bold">Pack promotion</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::model($packPromotion,['method'=>'patch','route' => ['pack-promotions.update',$packPromotion->id]]) !!}
                    <div class="input-append success date form-group" style="width: 96%;">
                        {!! Form::label('start', 'Start') !!}
                        {!! Form::text('start',null,['class'=>'form-control','placeholder'=>'Start', 'autocomplete' => 'off']) !!}
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                    </div>
                    <div class="input-append success date form-group" style="width: 96%;">
                        {!! Form::label('end', 'End') !!}
                        {!! Form::text('end',null,['class'=>'form-control','placeholder'=>'End', 'autocomplete' => 'off']) !!}
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                    </div>
                    <div class="form-group">
                        {!! Form::label('pack_id', 'Pack') !!}
                        {!! Form::select('pack_id',$packs,null, ['placeholder' => 'Choose Pack']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        {!! Form::text('value',null,['class'=>'form-control','placeholder'=>'Value']) !!}
                    </div>

                    <div class="checkbox checkbox check-success">
                        {!! Form::checkbox('early_bird',1,null,['id'=>'early_bird']) !!}
                        {!! Form::label('early_bird', 'Early bird') !!}
                    </div>
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
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"
            type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#pack_id").select2({
                width: '100%'
            })

            $('.input-append.date').datepicker({
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endsection
