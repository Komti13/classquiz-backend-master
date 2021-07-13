@extends('admin.layout.layout',['title'=>'Chapters'])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Edit <span class="semi-bold">Chapter</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::model($chapter,['method'=>'patch','route' => ['chapters.update',$chapter->id],'files'=>true]) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('short_name', 'Short name') !!}
                        {!! Form::text('short_name',null,['class'=>'form-control','placeholder'=>'Short name']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('time', 'Time') !!}
                        {!! Form::text('time',null,['class'=>'form-control','placeholder'=>'time']) !!}
                    </div>
                    <div class="form-group">
                        <img src="{{ asset('uploads/chapter/'.$chapter->icon) }}" width="50">
                        {!! Form::label('icon', 'Icon') !!}
                        {!! Form::File('icon',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="checkbox checkbox check-success">
                        {!! Form::checkbox('related', 1, null,['id'=>'related']) !!}
                        {!! Form::label('related', 'Related to other chapter') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('subject_id', 'Subject') !!}
                        {!! Form::select('subject_id',$subjects,null) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('chapter_type_id', 'Type') !!}
                        {!! Form::select('chapter_type_id',$chapterTypes,null) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('levels', 'Levels') !!}
                        {!! Form::select('levels[]',$levels,null, ['multiple' => 'multiple', 'id' => 'levels']) !!}
                    </div>
                    <p class="title">Orders by level</p>
                    <div class="row" id="orders">
                        @foreach($chapter->levels as $level)
                            <div class="col-md-{{ intdiv(12, $chapter->levels->count())}} order"
                                 data-level-id="{{ $level->id }}">
                                <div class="form-group">
                                    <label>{{$level->name}}</label>
                                    <input type="number" name="orders[{{$level->id}}]order" class="form-control"
                                           value="{{ $chapter->levels->where('id', $level->id)->first()->pivot->order }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::textarea('description',null,['class'=>'form-control','placeholder'=>'description']) !!}
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
            $("#subject_id,#levels,#chapter_type_id").select2({
                width: '100%'
            });

            // $("#levels").on('change', function(){
            //   var orders = $('.order').map(function() {
            //       return [$(this).data('level-id')];
            //   }).get();

            //   var levels = $("#levels").val();

            //   orders.forEach(function(k, v) {
            //     if(!levels.includes(v)){
            //       remove the input
            //     }
            //   });
            //   levels.forEach(function(k, v) {
            //     if(!orders.includes(v)){
            //         '<div class="col-md-3 order" data-level-id="'+v+'">'+
            //         '  <div class="form-group">'+
            //         '    <label></label>'+
            //         '    <input type="number" name="orders['+v+']" class="form-control">'+
            //         '  </div>'+
            //         '</div>'
            //     }
            //   });
            // })
        });
    </script>
@endsection
