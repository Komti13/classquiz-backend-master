@extends('admin.layout.layout',['title'=>'Subscriptions'])
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
                    <h4>List <span class="semi-bold">Subscriptions</span></h4>
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
                            <th>Payment status</th>
                            <th>Created at</th>
                            <th>User</th>
                            <th>Pack</th>
                            <th>Validity</th>
                            <th>Price</th>
                            <th>Token used</th>
                            <th>Promotion used</th>
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
            ajax: "{{ route('subscriptions.index') }}",
            'fnDrawCallback': function() {
                initPopover();
            },
            columns: [
                {data: 'id'},
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        const paymentStatus = row.payment ? row.payment.current_status : null;
                        if (paymentStatus.id == 1) {
                            return `<span class="badge badge-warning">${paymentStatus.name}</span>`;
                        } else if (paymentStatus.id == 2) {
                            return `<span class="badge badge-success">${paymentStatus.name}</span>`;
                        } else if (paymentStatus.id == 3) {
                            return `<span class="badge badge-danger">${paymentStatus.name}</span>`;
                        } else {
                            return '';
                        }
                    },
                },
                {data: 'created_at'},
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        const user = row.user;
                        if(!user){
                            return 'NA';
                        }
                        return `<a href="/users/STUDENT/${user.id}" class="badge badge-primary">#${user.id} ${user.name ||
                        ''}`;
                    },
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        const pack = row.pack;
                        return `<a href="/packs/${pack.id}/edit" class="badge badge-primary">${pack.name || ''}`;
                    },
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        const pack = row.pack;
                        if (pack.is_valid) {
                            return `<p class="badge badge-success">
                            ${pack.validity_start} - ${pack.validity_end}
                            </p>`;
                        } else {
                            return `<p class="badge badge-danger">
                            ${pack.validity_start} - ${pack.validity_end}
                             </p>`;
                        }

                    },
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button
                                    type="button"
                                    class="btn btn-small btn-primary"
                                    data-toggle="popover"
                                    title="Pricing"
                                    data-html="true"
                                    data-content='
                                    Pack price: ${row.pack.price}<br>
                                    Applied promotion: <p class="badge badge-primary">${row.packPromotion ?
                            row.packPromotion.value :
                            'None'}</p><br>
                                    Applied token: <p class="badge badge-primary" >${row.token ?
                            row.token.value :
                            'None'}</p><br>
                                    Final price: ${row.payment ? row.payment.amount : 0}'>
                                        ${row.payment ? row.payment.amount : 0} <i class="fa fa-question-circle"></i>
                                </button>`;
                    },
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        if (row.token) {
                            return `<a href="/tokens/${row.token.id}/edit" class="badge badge-primary">#${row.token.token}`;
                        } else {
                            return 'None';
                        }
                    },
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        if (row.packPromotion) {
                            return `<a href="/pack-promotions/${row.packPromotion.id}/edit" class="badge badge-primary">#${row.packPromotion.id}`;
                        } else {
                            return 'None';
                        }
                    },
                },
            ],
            'sDom': '<\'row\'<\'col-md-6\'l <\'toolbar\'>><\'col-md-6\'f>r>t<\'row\'<\'col-md-12\'p i>>',
            'aaSorting': [[0, 'desc']],
            'oLanguage': {
                'sLengthMenu': '_MENU_ ',
                'sInfo': 'Showing <b>_START_ to _END_</b> of _TOTAL_ entries',
            },
        });
        $('div.toolbar').
            html(
                '<div class="table-tools-actions"><a class="btn btn-primary" onclick=\'alert("Under Construction")\' href="#" style="margin-left:12px" id="test2">Buy for student</a></div>');

        $(document).on('click', '.delete', function(e) {
            confirm = confirm('Are you sure?');
            if (confirm) {
                $(this).next('form').submit();
            }
        });

        function initPopover() {
            oTable3.$('[data-toggle="popover"]').popover().click(function(e) {
                e.preventDefault();
            });
        }

    </script>
@endsection
