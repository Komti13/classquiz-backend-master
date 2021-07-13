@extends('admin.layout.layout',['title'=>'Atlases'])
@section('css')
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Create <span class="semi-bold">Atlas</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::open(['route' => 'atlases.store','files'=>true]) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
                    </div>

                    <div class="checkbox checkbox check-success">
                        {!! Form::checkbox('is_default',1,false,['id'=>'is_default']) !!}
                        {!! Form::label('is_default', 'Is default') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('url', 'Url') !!}
                        {!! Form::file('url') !!}
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
    <script type="text/javascript">
        if ($('#is_default').is(':checked')) {
            $('#url').addClass('form-control');
            $('#url').attr('type', 'text');
        } else {
            $('#url').removeClass('form-control');
            $('#url').attr('type', 'file');
        }
        $('#is_default').on('change', function () {
            $('#url').toggleClass('form-control');
            if ($('#is_default').is(':checked')) {
                $('#url').attr('type', 'text');
            } else {
                $('#url').attr('type', 'file');
            }
        })
    </script>
@endsection
