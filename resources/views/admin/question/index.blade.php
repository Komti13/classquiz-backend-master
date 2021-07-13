@extends('admin.layout.layout',['title'=>'Questions'])
@section('css')
    <!-- BEGIN PLUGIN CSS -->
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="{{ asset('assets/plugins/jquery-datatable/css/jquery.dataTables.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet"
          type="text/css" media="screen"/>
    <!-- END PLUGIN CSS -->
    <style type="text/css">
        .dataTable {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="tabbable tabs-left">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#pane-levels" role="tab" data-toggle="tab">Levels</a>
            </li>
            <li>
                <a href="#pane-subjects" role="tab" data-toggle="tab">Subjects</a>
            </li>
            <li>
                <a href="#pane-chapters" role="tab" data-toggle="tab">Chapters</a>
            </li>
            <li>
                <a href="#pane-question-groups" role="tab" data-toggle="tab">Question groups</a>
            </li>
            <li>
                <a href="#pane-questions" role="tab" data-toggle="tab">Questions</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="pane-levels">
                <table class="table" id="levels">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="pane-subjects">
                <table class="table" id="subjects">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="pane-chapters">
                <table class="table" id="chapters">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="pane-question-groups">
                <table class="table" id="question-groups">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="pane-questions">
                <table class="table" id="questions">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
@section('js')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ asset('assets/plugins/jquery-datatable/js/jquery.dataTables.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-datatable/extra/js/dataTables.tableTools.min.js') }}"
            type="text/javascript"></script>
    <script type="text/javascript"
            src="{{ asset('assets/plugins/datatables-responsive/js/datatables.responsive.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/datatables-responsive/js/lodash.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/datatables.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
    <!-- END JAVASCRIPTS -->
    <script type="text/javascript">
        var level = 0;
        var subject = 0;
        var chapter = 0;
        var questionGroup = 0;
        var levels = $('#levels').dataTable({
            'ajax': {
                "type": "GET",
                "url": '{{url('/questions/get-levels')}}',
            },
            'columns': [{
                "data": "name"
            }, {
                "data": "id",
                render: function (dataField) {
                    return '<a class="btn btn-mini btn-white" href="{{url('/')}}/levels/' + dataField + '/edit">Details</a>&nbsp;<a data-id="' + dataField + '" id="getSubjects" class="btn btn-mini btn-white" href="#">Subjects</a>';
                }
            }],
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "oLanguage": {
                "sLengthMenu": "_MENU_ ",
                "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
            },
        });
        var subjects = $('#subjects').dataTable({
            'ajax': {
                "type": "GET",
                "url": '{{url('/questions/get-subjects')}}/' + level,
            },
            'columns': [{
                "data": "name"
            }, {
                "data": "id",
                render: function (dataField) {
                    return '<a class="btn btn-mini btn-white" href="{{url('/')}}/subjects/' + dataField + '/edit">Details</a>&nbsp;<a data-id="' + dataField + '" id="getChapters" class="btn btn-mini btn-white" href="#">Chapters</a>';
                }
            }],
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "oLanguage": {
                "sLengthMenu": "_MENU_ ",
                "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
            },
        });
        var chapters = $('#chapters').dataTable({
            'ajax': {
                "type": "GET",
                "url": '{{url('/questions/get-chapters')}}/' + level + '/' + subject,
            },
            'columns': [{
                "data": "name"
            }, {
                "data": "id",
                render: function (dataField) {
                    return '<a class="btn btn-mini btn-white" href="{{url('/')}}/chapters/' + dataField + '/edit">Details</a>&nbsp;<a data-id="' + dataField + '" id="getQuestionGroups" class="btn btn-mini btn-white" href="#">Question Groups</a>';
                }
            }],
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "oLanguage": {
                "sLengthMenu": "_MENU_ ",
                "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
            },
        });
        var questionGroups = $('#question-groups').dataTable({
            'ajax': {
                "type": "GET",
                "url": '{{url('/questions/get-question-groups')}}/' + chapter,
            },
            'columns': [{
                "data": "name"
            }, {
                "data": "id",
                render: function (dataField) {
                    return '<a data-id="' + dataField + '" id="getQuestions" class="btn btn-mini btn-white" href="#">Questions</a>';
                }
            }],
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "oLanguage": {
                "sLengthMenu": "_MENU_ ",
                "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
            },
        });
        var questions = $('#questions').dataTable({
            'ajax': {
                "type": "GET",
                "url": '{{url('/questions/get-questions')}}/' + questionGroup,
            },
            'columns': [{
                "data": "title"
            }, {
                "data": "id",
                render: function (dataField) {
                    return '<a class="btn btn-mini btn-white" href="{{url('/')}}/questions/' + dataField + '">Details</a>';
                }
            }],
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "oLanguage": {
                "sLengthMenu": "_MENU_ ",
                "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
            },
        });
        $(document).on('click', '#getSubjects', function () {
            level = $(this).data("id");
            $(this).parents('tbody').children('tr').removeClass('info');
            $(this).parents('tr').addClass('info');
            show_tab("pane-subjects");
            subjects.api().ajax.url('{{url('/questions/get-subjects')}}/' + level).load();
        });
        $(document).on('click', '#getChapters', function () {
            subject = $(this).data("id");
            $(this).parents('tbody').children('tr').removeClass('info');
            $(this).parents('tr').addClass('info');
            show_tab("pane-chapters");
            chapters.api().ajax.url('{{url('/questions/get-chapters')}}/' + level + '/' + subject).load();
        });
        $(document).on('click', '#getQuestionGroups', function () {
            chapter = $(this).data("id");
            $(this).parents('tbody').children('tr').removeClass('info');
            $(this).parents('tr').addClass('info');
            show_tab("pane-question-groups");
            questionGroups.api().ajax.url('{{url('/questions/get-question-groups')}}/' + chapter).load();
        });
        $(document).on('click', '#getQuestions', function () {
            questionGroup = $(this).data("id");
            $(this).parents('tbody').children('tr').removeClass('info');
            $(this).parents('tr').addClass('info');
            show_tab("pane-questions");
            questions.api().ajax.url('{{url('/questions/get-questions')}}/' + questionGroup).load();
        });

        function show_tab(tabID) {
            $('.nav-tabs a[href="#' + tabID + '"]').tab('show');
            $('.nav-tabs li').addClass('disabled');
            $('a[href="#' + tabID + '"]').parent('li').removeClass('disabled').prevAll().removeClass('disabled');
        }

        $('.nav-tabs li').addClass('disabled');
        $(".nav-tabs a[data-toggle=tab]").on("click", function (e) {
            if ($(this).parents('li').hasClass("disabled")) {
                e.preventDefault();
                return false;
            }
        });

        $('.dataTables_filter input').addClass("input-medium ");
        $('.dataTables_length select').addClass("select2-wrapper span12");
    </script>
@endsection
