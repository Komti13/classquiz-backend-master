@extends('admin.layout.layout',['title'=>$role->description])
@section('css')
    <!-- BEGIN PLUGIN CSS -->
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/jquery-datatable/css/jquery.dataTables.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet"
          type="text/css" media="screen"/>
    <!-- END PLUGIN CSS -->
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Filters</h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Created at</label>
                        <div class="col-sm-5">
                            <div class="input-append success date form-group" style="width: 96%;">

                                <input type="text" class="form-control" placeholder="From" id="created-at-from">
                                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-append success date form-group" style="width: 96%;">

                                <input type="text" class="form-control" placeholder="To" id="created-at-to">
                                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                            </div>
                        </div>
                    </div>
                    @if($role->name=='STUDENT')
                    <div class="form-group">
                        <label for="subscription" class="col-sm-2 control-label">Subscription</label>
                        <div class="col-sm-10">
                            <select id="subscription">
                                <option value=""></option>
                                @foreach($packs as $pack)
                                    <option value="{{$pack->name}}">{{$pack->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="level" class="col-sm-2 control-label m-t-10">Level</label>
                        <div class="col-sm-10">
                            <select id="level" class="m-t-10">
                                <option value=""></option>
                                @foreach($levels as $level)
                                    <option value="{{$level->id}}">{{$level->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>List <span class="semi-bold">{{ $role->description }}</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <table class="table" id="example3">
                        <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Created at</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Phone</th>
                            <th>Parent</th>
                            <th>Achievements</th>
                            <th>Subscriptions</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"
            type="text/javascript"></script>
    <!-- END JAVASCRIPTS -->
    <script type="text/javascript">
        const role = "{{$role->name}}";
        var oTable3 = $('#example3').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('users.index',['role'=>$role->name]) }}",
                data: function (d) {
                    d.subscription = $("#subscription").val();
                    d.created_at_from = $("#created-at-from").val();
                    d.created_at_to = $("#created-at-to").val();
                    d.level = $("#level").val();
                }
            },
            'fnDrawCallback': function() {
                initPopover();
            },
            columns: [
                {data: 'id'},
                {data: 'created_at'},
                {data: 'name', orderable: false},
                {data: 'email', orderable: false},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    visible: role === 'STUDENT',
                    render: function(data, type, row, meta) {
                        if(role !== 'STUDENT'){
                            return '';
                        }
                        return row.level.name;
                    }
                },
                {data: 'phone', orderable: false},
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    visible: role === 'STUDENT',
                    render: function(data, type, row, meta) {
                        const parent = row.parent[0];
                        if (parent) {
                            let content = '';
                            content += parent.phone ?
                                `<p class="badge badge-primary"><i class="fa fa-phone"></i> ${parent.phone}</p>` :
                                '';
                            content += parent.email ?
                                `<p class="badge badge-primary"><i class="fa fa-envelope"></i> ${parent.email}</p>` :
                                '';
                            return `
                            <a href="/users/PARENT/${parent.id}/">
                             ${content}
                            </a>
                        `;
                        }
                        return '';
                    },
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    visible: role === 'STUDENT',
                    render: function(data, type, row, meta) {
                        const achievement = row.achivement || {};
                        return `<button type="button" class="btn btn-small btn-primary" data-toggle="popover"
                            title="Achievements"
                            data-html="true"
                            data-content='
                                Total achievements: ${(Number(achievement.donuts) + Number(achievement.candy)) || 0}<br>
                                Donuts: <p class="badge badge-primary">${Number(achievement.donuts) || 0}</p><br>
                                Candies: <p class="badge badge-primary" >${Number(achievement.candy) || 0}</p><br>
                                Completed Chapters: <p class="badge badge-primary" >${Number(
                            achievement.nb_completed_chapter) || 0}</p><br>
                                Total time: <p class="badge badge-primary" >${Number(achievement.total_time) || 0}</p><br>
                            '>
                              ${(Number(achievement.donuts) + Number(achievement.candy)) || 0}
                            <i class="fa fa-question-circle"></i>
                        </button>`;
                    },
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    visible: role === 'STUDENT',
                    render: function(data, type, row, meta) {
                        let html = '';
                        for (let subscription of row.valid_subscriptions) {
                            html += `<p class="badge badge-primary">${subscription.pack.name}</p>`;
                        }
                        return html;
                    },
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    visible: role === 'PARENT',
                    render: function(data, type, row, meta) {
                        const students = row.students;
                        let html = '';
                        for (let student of students) {
                            html += `<a href="/users/STUDENT/${student.id}" class="badge badge-primary">#${student.id} ${student.name ||
                            ''}</a>`;
                        }
                        return html;
                    },
                },
                {
                    data: 'enabled',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        if (row.enabled) {
                            return `<span class="label label-success">enabled</span>`;
                        } else {
                            return `<span class="label label-danger">disabled</span>`;
                        }
                    },
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return `<div class="btn-group" style="display:flex">
                        <a class="btn btn-mini btn-white" href="/users/${role}/${row.id}">Details</a>
                        <button class="btn btn-mini btn-white dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="/users/${role}/${row.id}/edit">Edit</a>
                            </li>
                            <li>
                                <a href="javascript:;" data-id="${row.id}" class="delete">Delete</a>
                            </li>
                        </ul>
                    </div>`;
                    },
                },
            ],
            'aaSorting': [[0, 'desc']],
            'sDom': '<\'row\'<\'col-md-6\'l <\'toolbar\'>><\'col-md-6\'f>r>t<\'row\'<\'col-md-12\'p i>>',
            'oLanguage': {
                'sLengthMenu': '_MENU_ ',
                'sInfo': 'Showing <b>_START_ to _END_</b> of _TOTAL_ entries',
            },
        });
        $('div.toolbar').
            html(
                '<div class="table-tools-actions"><a class="btn btn-primary" href="{{ route("users.create",["role"=>$role->name]) }}" style="margin-left:12px" id="test2">Add</a></div>');

        $(document).on('click', '.delete', function(e) {
            const id = $(this).data('id');
            if (confirm('Are you sure?')) {
                $.post('/users/' + role + '/' + id,
                    {_method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content')}, function() {
                        location.reload();
                    });
            }
        });

        function initPopover() {
            oTable3.$('[data-toggle="popover"]').popover().click(function(e) {
                e.preventDefault();
            });

            $('#subscription, #level').select2({
                width: '100%',
                placeholder: "Choose an option",
                allowClear: true
            });

            $('.input-append.date').datepicker({
                autoclose: true,
                todayHighlight: true,
            });
        }

        $("#created-at-to, #created-at-from, #subscription, #level").on('change', function () {
            oTable3.DataTable().draw();
        });
    </script>
@endsection
