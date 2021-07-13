@extends('admin.layout.layout',['title'=>'Tokens'])
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
                    {!! Form::model($token,['method'=>'patch','route' => ['tokens.update',$token->id]]) !!}
                    <div class="input-append success date form-group" style="width: 96%;">
                        {!! Form::label('validity_start', 'Validity start') !!}
                        {!! Form::text('validity_start',null,['class'=>'form-control','placeholder'=>'Validity start', 'autocomplete' => 'off']) !!}
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                    </div>
                    <div class="input-append success date form-group" style="width: 96%;">
                        {!! Form::label('validity_end', 'Validity end') !!}
                        {!! Form::text('validity_end',null,['class'=>'form-control','placeholder'=>'Validity end', 'autocomplete' => 'off']) !!}
                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                    </div>
                    <div class="checkbox checkbox check-success">
                        {!! Form::checkbox('private', 1, null,['id'=>'private']) !!}
                        {!! Form::label('private', 'Private') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('user_id', 'User') !!}
                        {!! Form::select('user_id',$users,null, ['placeholder' => 'Choose User']) !!}
                        <small class="form-text text-muted">To make token for specific user (Optional).</small>
                    </div>

                    <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        {!! Form::text('value',null,['class'=>'form-control','placeholder'=>'Value']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('token', 'Token') !!}
                        {!! Form::text('token', null,['class'=>'form-control','placeholder'=>'Token']) !!}
                        <small class="form-text text-muted">The token to provide for user(s).</small>
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
            $("#user_id").select2({
                width: '100%'
            })

            $('.input-append.date').datepicker({
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endsection
