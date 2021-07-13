@extends('admin.layout.layout',['title'=>'Schools'])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Create <span class="semi-bold">School</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::open(['route' => 'schools.store','files'=>true]) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('type', 'Type') !!}
                        {!! Form::select('type',['Private'=>'Private','Public'=>'Public'],null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', 'Address') !!}
                        {!! Form::text('address',null,['class'=>'form-control','placeholder'=>'Address']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('icon', 'Icon') !!}
                        {!! Form::File('icon',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('country_id', 'Country') !!}
                        {!! Form::select('country_id',$countries,null) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('governorate_id', 'Governorate') !!}
                        {!! Form::select('governorate_id',$governorates,null) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('delegation_id', 'Delegation') !!}
                        {!! Form::select('delegation_id',$delegations,null) !!}
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
    <script type="text/javascript">
        $(document).ready(function () {
            $("#country_id,#governorate_id,#delegation_id").select2({
                width: '100%'
            });
        });
    </script>
@endsection
