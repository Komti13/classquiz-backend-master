@extends('admin.layout.layout',['title'=>'Templates'])
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Create <span class="semi-bold">Template</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::open(['route' => 'templates.store']) !!}

                    <div class="form-group">
                        {!! Form::label('id', 'Id') !!}
                        {!! Form::text('id',null,['class'=>'form-control','placeholder'=>'Id']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('selected_template', 'Selected template') !!}
                        {!! Form::text('selected_template',null,['class'=>'form-control','placeholder'=>'Selected template']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('selected_category', 'Selected category') !!}
                        {!! Form::text('selected_category',null,['class'=>'form-control','placeholder'=>'Selected category']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('selected_icon', 'Selected icon') !!}
                        {!! Form::text('selected_icon',null,['class'=>'form-control','placeholder'=>'Selected icon']) !!}
                    </div>
                    <div class="checkbox checkbox check-success">
                        {!! Form::checkbox('with_last_field',1,true,['id'=>'with_last_field']) !!}
                        {!! Form::label('with_last_field', 'With last field') !!}
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
