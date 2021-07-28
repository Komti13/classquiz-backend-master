@extends('admin.layout.layout',['title'=>'Logistics Managment'])
@section('css')
    <!-- BEGIN PLUGIN CSS -->
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="{{ asset('assets/plugins/jquery-datatable/css/jquery.dataTables.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-bootstrap-checkbox/css/bootstrap-checkbox.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet"
        type="text/css" media="screen" />

    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css"/>
    
    <!-- END PLUGIN CSS -->
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4><span class="semi-bold">Logistics</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body" style="overflow:auto;">
                 
                    <table class="table" id="example5">
                        <thead>
                            <tr>
                                <th></th>
                                <th id="th1" >#ID</th>
                                <th id="th1">Name</th>
                                <th id="th1">Phone</th>
                                <th id="th1">Level</th>
                                <th id="th1" class="date">Creation Date</th>
                                <th id="th1">Source</th>
                                <th id="th1">Status</th>
                                <th id="th1">Notes</th>
                                <th id="th1">SMS sent</th>
                                <th id="th1">Adrerss</th>
                                <th id="th1" class="date">Conversion Date</th>
                                <th id="th1" class="th2">Payment</th>
                                <th id="th1">Delivery Status</th>
                                <th id="th1" class="th2">Pack</th>
                                <th id="th1" class="th2">Pack Type</th>
                                <th id="th1" class="th2">Original Price</th>
                                <th id="th1" class="th2">Current Price</th>
                                <th id="th1" class="th2">Activation Code</th>
                                <th id="th1" class="th2">Code Used</th>
                                <th id="th1" class="th2">Final Status</th>
                                <th id="th1" class="th2">Action</th>
                                {{-- <th>Print</th> --}}
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
    <script src="{{ asset('assets/plugins/jquery-datatable/js/jquery.dataTables.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/plugins/jquery-datatable/extra/js/dataTables.tableTools.min.js') }}"
        type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/datatables-responsive/js/datatables.responsive.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/plugins/datatables-responsive/js/lodash.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/datatables.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>

    <!-- END JAVASCRIPTS -->
    <script type="text/javascript">
       
        $(document).ready(function() {
            $('#example5 thead tr ').clone(true).appendTo('#example5 thead');
            
            $('#example5 thead tr:eq(1) #th1').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');

                $('input', this).on('keyup change', function() {
                   
                    if (table.column(i+1).search() !== this.value ) {
                        table
                            .column(i+1)
                            .search(this.value)
                            .draw();
                    }
                });
            });
            $('#example5 thead tr:eq(1) .th2').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search here is disabled" disabled style="background-color: #d1d1d1"/>');

                
            });
            $('#example5 thead tr:eq(1) .date').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="date" placeholder="Search ' + title + '" />');

                $('input', this).on('keyup change', function() {
                 

                    if (table.column(i+1).search() !== this.value ) {
                        table
                            .column(i+1)
                            .search(this.value)
                            .draw();
                    }
                });
            });
            var table = $('#example5').DataTable({
                processing: true,
                serverSide: true,
                order: [[ 1, "desc" ]],
                //if there is a problem in search datatable comment next line
                orderCellsTop: true,
                ajax: "{{ route('logistics') }}",
                columns: [{
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0,
                        defaultContent: ''
                    },
                    {
                        data: 'user.id',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'user.name',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'user.phone',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        orderable: false,
                        data: 'user.level.name',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                         
                    },
                    {
                        data: 'user.usercalls.created_at',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            }
                        },
                        "searchable": false
                        
                    },
                    {
                        searchable: false,
                        orderable: false,
                        data: 'user.usercalls.sales_info.source.type',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'user.usercalls.user_status.name',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'user.usercalls.notes',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'user.usercalls.SMS_sent',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'user.address',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        
                        data: 'user.usercalls.conversation_date',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            }
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        data: 'payment.payment_method.name',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        
                        data: 'payment.delivery.delivery_status',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        data: 'pack.name'
                    },
                    {
                        searchable: false,
                        orderable: false,
                        data: 'pack.pack_type.name'
                    },
                    {  
                        searchable: false,
                        orderable: false,
                        data: 'pack.price'
                    },
                    {
                        searchable: false,
                        data: 'payment.amount',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {searchable: false,
                        orderable: false,
                        data: 'token.token',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        data: 'token.used',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        searchable: false,
                        orderable: false,
                        data: 'payment.current_status.name',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `<div class="btn-group">
                                    <a class="btn btn-mini btn-white"
                                       href="/packs/${row.id}/edit">Edit</a>
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
                select: {
                    style: 'multi',
                    selector: 'tr'
                },
                "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
                'aaSorting': [
                    [0, 'desc']
                ],
                "oLanguage": {
                    "sLengthMenu": "_MENU_ ",
                    "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
                },
            });
            $("div.toolbar").html(
                '<div class="table-tools-actions"><a class="btn btn-primary" href="{{ route('form') }}" style="margin-left:12px" id="test2">Add</a></div>'
            );
            
            $(document).on('click', '.delete', function(e) {
                const id = $(this).data('id');
                if (confirm('Are you sure?')) {
                    $.post('/packs/' + id, {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }, function() {
                        location.reload();
                    });
                }
            });
        });
    </script>
@endsection
