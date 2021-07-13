@extends('admin.layout.layout',['title'=>'Admins'])
@section('css')
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Edit <span class="semi-bold">Admin</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::model($admin,['method'=>'patch','route' => ['admins.update',$admin->id]]) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('email','Email') !!}
                        {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Email']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password','Password') !!}
                        {!! Form::password('password',['class'=>'form-control','placeholder'=>'Password']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password_confirmation', 'Password confirmation') !!}
                        {!! Form::password('password_confirmation',['class'=>'form-control','placeholder'=>'Password confirmation']) !!}
                    </div>
                    <div class="checkbox checkbox check-success">

                        {!! Form::checkbox('enabled',$admin->enabled,($admin->enabled == 1 ? true : false), ['id' => 'enabled']) !!}
                        {!! Form::label('enabled', 'Enabled') !!}
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
@endsection
