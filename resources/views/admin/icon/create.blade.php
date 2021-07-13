@extends('admin.layout.layout',['title'=>'Icons'])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Create <span class="semi-bold">Icon</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::open(['route' => 'icons.store','files'=>true]) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('category', 'Category') !!}
                        {!! Form::number('category',null,['class'=>'form-control','placeholder'=>'Category']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('atlas_id', 'Atlas') !!}
                        {!! Form::select('atlas_id',$atlases,null) !!}
                    </div>
                    <div class="checkbox checkbox check-success">
                        {!! Form::checkbox('is_default',1,false,['id'=>'is_default']) !!}
                        {!! Form::label('is_default', 'Is default') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('direct_url', 'Direct url') !!}
                        {!! Form::file('direct_url') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('index', 'Index') !!}
                        {!! Form::number('index',null,['class'=>'form-control','placeholder'=>'Index']) !!}
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
        if ($('#is_default').is(':checked')) {
            $('#direct_url').addClass('form-control');
            $('#direct_url').attr('type', 'text');
        } else {
            $('#direct_url').removeClass('form-control');
            $('#direct_url').attr('type', 'file');
        }
        $('#is_default').on('change', function () {
            $('#direct_url').toggleClass('form-control');
            if ($('#is_default').is(':checked')) {
                $('#direct_url').attr('type', 'text');
            } else {
                $('#direct_url').attr('type', 'file');
            }
        })

        $("#atlas_id").select2({
            width: '100%'
        });
    </script>
@endsection
