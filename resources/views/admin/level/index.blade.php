@extends('admin.layout.layout',['title'=>'Levels'])
@section('css')
    <!-- BEGIN PLUGIN CSS -->
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
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
                    <h4>List <span class="semi-bold">Levels</span></h4>
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
                            <th>Name</th>
                            <th>Enabled</th>
                            <th>Created at</th>
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
    <!-- END JAVASCRIPTS -->
    <script type="text/javascript">
        var oTable3 = $('#example3').dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('levels.index') }}",
            columns: [
                {data: 'id'},
                {data: 'name'},
                {
                    data: 'enabled',
                    searchable: false,
                    render: function(data, type, row, meta) {
                        if (row.enabled) {
                            return `<span class="label label-success">enabled</span>`;
                        } else {
                            return `<span class="label label-danger">disabled</span>`;
                        }
                    },
                },
                {data: 'created_at'},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return `<div class="btn-group">
                                    <a class="btn btn-mini btn-white"
                                       href="/levels/${row.id}/edit">Edit</a>
                                    <button class="btn btn-mini btn-white dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="javascript:;" data-id="${row.id}" class="delete">Delete</a>
                                        </li>
                                    </ul>
                                </div>`;
                    },
                },
            ],
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            'aaSorting': [[0, 'desc']],
            "oLanguage": {
                "sLengthMenu": "_MENU_ ",
                "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
            },
        });
        $("div.toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary" href="{{ route('levels.create') }}" style="margin-left:12px" id="test2">Add</a></div>');

        $(document).on('click', '.delete', function(e) {
            const id = $(this).data('id');
            if (confirm('Are you sure?')) {
                $.post('/levels/'+id, {_method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content')}, function(){
                    location.reload();
                });
            }
        });
    </script>
@endsection
