@extends('admin.layout.layout',['title'=>'Import - Export Schools'])
@section('css')
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Import - Export <span class="semi-bold">Schools</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <a href="{{ route('schools.export',['xls']) }}">
                        <button class="btn btn-success">Export to Excel xls</button>
                    </a>

                    <a href="{{ route('schools.export',['xlsx']) }}">
                        <button class="btn btn-success">Export to Excel xlsx</button>
                    </a>

                    <a href="{{ route('schools.export',['csv']) }}">
                        <button class="btn btn-success">Export to CSV</button>
                    </a>

                    {!! Form::open(['route' => ['schools.import'],'files'=>true]) !!}
                    <div class="form-group">
                        <br>
                        {!! Form::label('import_file', 'File to import (csv,xls)') !!}
                        {!! Form::File('import_file',null,['class'=>'form-control']) !!}
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
