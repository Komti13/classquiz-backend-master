@extends('admin.layout.layout',['title'=>'Payments'])
@section('css')
    <!-- BEGIN PLUGIN CSS -->
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="{{ asset('assets/plugins/jquery-datatable/css/jquery.dataTables.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet"
          type="text/css" media="screen"/>
    <!-- END PLUGIN CSS -->
    <style>
        .status {
            border: 0;
            border-radius: 8px;
            min-height: 25px;
            height: 25px !important;
            padding: 0;
            width: 100px;
        }

        .bg-yellow:focus {
            color: #fff;
            background-color: #337ab7;
        }

        .bg-success:focus {
            background-color: #dff0d8;
        }

        .bg-danger:focus {
            background-color: #f2dede;
        }
    </style>
@endsection
@section('content')
    <div class="alert alert-success mt-3" id="success-alert" style="display: none;">
        <p>Payment status changed successfully</p>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>List <span class="semi-bold">Payments</span></h4>
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
                            <th>User</th>
                            <th>Amount</th>
                            <th>Payment method</th>
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
    <!-- END JAVASCRIPTS -->
    <script type="text/javascript">
        var oTable3 = $('#example3').dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('payments.index') }}",
            'fnDrawCallback': function() {
                initStatusSelect();
            },
            columns: [
                {data: 'id'},
                {data: 'created_at'},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        const user = row.user;
                        if(!user){
                            return 'NA';
                        }
                        return `<a href="/users/${user.roles[0].name}/${user.id}" class="badge badge-primary">#${user.id} ${user.name || ''}`;
                    },
                },
                {data: 'amount',orderable: false},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return row.payment_method.name;
                    },
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        const statuses = {!! $paymentStatuses !!};
                        let options = '';
                        for (let status of statuses){
                            options+=`<option value="${status.id}" ${status.id == row.current_status.id ? 'selected' : ''}>${status.name}</option>`;
                        }
                        const classes = row.current_status.id == 1 ? 'bg-yellow' : (row.current_status.id == 2 ? 'bg-success' : 'bg-danger');
                        const disabled = row.payment_method_id == 2 || !row.user ? 'selected' : '';
                        return `
                        <select data-payment-id="${row.id}" class="status ${classes}" ${disabled}>
                            ${options}
                        </select>`;
                    },
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return `<a class="btn btn-mini" href="/payments/${row.id}">Details</a>`;
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
        $("div.toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary" href="#" onclick=\'alert("Under Construction")\' style="margin-left:12px" id="test2">Manual payment</a></div>');

        function initStatusSelect() {
            $(".status").on('focus', function() {
                $.data(this, 'current', $(this).val());
            }).change(function() {
                if (!confirm('Change status to ' + $(':selected', this).text() + ' ?')) {
                    $(this).val($.data(this, 'current'));
                    return false;
                }
                $.post("/payments/payment-status/" + $(this).data('payment-id'), {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "payment_status_id": $(this).val()
                }, function(data) {
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                        $("#success-alert").slideUp(500);
                    });
                });
                if ($(this).val() == 1) {
                    $(this).removeClass().addClass('status bg-yellow');
                } else if ($(this).val() == 2) {
                    $(this).removeClass().addClass('status bg-success');
                } else if ($(this).val() == 3) {
                    $(this).removeClass().addClass('status bg-danger');
                }

            });
        }

    </script>
@endsection
