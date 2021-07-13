@extends('admin.layout.layout',['title'=>'Questions'])
@section('css')

@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Show <span class="semi-bold">Question</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <p> title : {{ $question->title }} </p>
                    <p> main_question : {{ $question->main_question }} </p>
                    <p> sub_question : {{ $question->sub_question }} </p>
                    <p> score : {{ $question->score }} </p>
                    <p> is_confirmed : {{ $question->is_confirmed }} </p>
                    <p> has_warning : {{ $question->has_warning }} </p>
                    <p> generate_on_layout : {{ $question->generate_on_layout }} </p>
                    <p> is_new_question : {{ $question->is_new_question }} </p>
                    <p> situation : {{ $question->situation }} </p>
                    <p> hints : {{ $question->hints }} </p>
                    <p> bg_color : {{ $question->bg_color }} </p>
                    <p> chapter_id : {{ $question->chapter_id }} </p>
                    <p> template_id : {{ $question->template_id }} </p>
                    <p> index_in_group : {{ $question->index_in_group }} </p>
                    <p> question_group_id : {{ $question->question_group_id }} </p>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('js')
@endsection
