@extends('admin.layout.layout',['title'=>$role->description])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            {!! Form::model($user,['method'=>'patch','route' => ['users.update','role'=>$role->name,'id'=>$user->id],'files'=>true]) !!}
            <div class="row">
                <div class="col-md-8">
                    <div class="grid simple">
                        <div class="grid-title">
                            <h4>Information</h4>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"></a>
                                <a href="#grid-config" data-toggle="modal" class="config"></a>
                                <a href="javascript:;" class="reload"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                        </div>
                        <div class="grid-body">

                            <div class="form-group">
                                @if($user->image)
                                    <img src="{{ asset('uploads/user/'.$user->image) }}" width="100" class="center-block">
                                @else
                                    <img src="{{ asset('uploads/user/avatar.png') }}" width="100" class="center-block">
                                @endif
                                {!! Form::label('image', 'Image') !!}
                                {!! Form::File('image',['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('name', 'Name') !!}
                                {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('address', 'Address') !!}
                                {!! Form::text('address',null,['class'=>'form-control','placeholder'=>'Address']) !!}
                            </div>

                            @if($role->name != 'EDITOR')
                                <div class="form-group">
                                    {!! Form::label('school_id', 'School') !!}
                                    {!! Form::select('school_id',$schools,null) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('school', 'School') !!}
                                    {!! Form::text('school',null,['class'=>'form-control','placeholder'=>'School']) !!}
                                </div>
                            @endif
                            <div class="form-group">
                                {!! Form::label('country_id', 'Country') !!}
                                {!! Form::select('country_id',$countries,null) !!}
                            </div>


                            <div class="text-right">
                                <br>
                                <a href="{{route('users.show', [$role->name, $user->id])}}" class="btn btn-default">Show <i
                                        class="icon-arrow-left13 position-right"></i></a>
                                <button type="submit" class="btn btn-primary">Save <i
                                        class="icon-arrow-left13 position-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="grid simple ">
                        <div class="grid-title">
                            <h4>Access</h4>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"></a>
                                <a href="#grid-config" data-toggle="modal" class="config"></a>
                                <a href="javascript:;" class="reload"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                        </div>
                        <div class="grid-body ">
                            <div class="form-group">
                                {!! Form::label('phone', 'Phone') !!}
                                {!! Form::text('phone',null,['class'=>'form-control','placeholder'=>'Phone', 'readonly' => $role->name ==='STUDENT']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('email','Email') !!}
                                {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Email', 'readonly' => $role->name ==='STUDENT']) !!}
                            </div>

                            @if($role->name == 'STUDENT')
                                <div class="form-group">
                                    {!! Form::label('level_id', 'Level') !!}
                                    {!! Form::select('level_id',$levels,null) !!}
                                </div>
                            @endif
                            <div class="form-group">
                                {!! Form::label('password','Password') !!}
                                {!! Form::password('password',['class'=>'form-control','placeholder'=>'Password', 'readonly' => $role->name ==='STUDENT']) !!}
                            </div>

                            <div class="checkbox checkbox check-success">
                                {!! Form::checkbox('enabled', 1, null,['id'=>'enabled']) !!}
                                {!! Form::label('enabled', 'Enabled') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>


        @endsection
        @section('js')
            <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}"
                    type="text/javascript"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#country_id,#school_id,#level_id').select2({
                        width: '100%',
                    });
                });
            </script>
@endsection
