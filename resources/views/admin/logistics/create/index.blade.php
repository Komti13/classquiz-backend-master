@extends('admin.layout.layout',['title'=>'Logistics Managment'])
@section('css')
    <!-- BEGIN PLUGIN CSS -->
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
        media="screen" />

    <link href="{{ asset('assets/plugins/jquery-datatable/css/jquery.dataTables.css') }}" rel="stylesheet"
        type="text/css" />
        <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrap-bootstrap-checkbox/css/bootstrap-checkbox.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet"
        type="text/css" media="screen" />
        
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js" />

    <style>
        table,
        #th1,
        #th2,
        #th3 {
            text-align: center
        }

        .form-inline input {
            margin-bottom:  !important;
        }

    </style>
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
                    {{-- @if($role->name=='STUDENT') --}}
                    <div class="form-group">
                        <label for="subscription" class="col-sm-2 control-label">Subscription</label>
                        <div class="col-sm-10">
                            <select id="subscription">
                                <option value=""></option>
                                @foreach($packs as $pack)
                                    <option value="{{$pack->id}}">{{$pack->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stauts" class="col-sm-2 control-label m-t-10">Status</label>
                        <div class="col-sm-10">
                            <select id="status" class="m-t-10">
                                <option value=""></option>
                                @foreach($status as $st)
                                    <option value="{{$st->id}}">{{$st->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <div class="d-flex justify-content-around">
                        <h4><span class="semi-bold col">Logistics</span></h4>
                        <button onclick="authenticate().then(loadClient)" class="btn btn-warning">load google sheet</button>
                        <button onclick="execute()" class="btn btn-danger"> execute</button>

                        {{-- <button class="btn btn-danger col" id="sheet" style="" onclick="sheet()">insert
                            sheet</button> --}}
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#grid-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                </div>
                <div class="grid-body">
                    <div class="col-md-12 text-center" id="pdf">
                        <a name='code' class="btn btn-primary print" target="_blank">Print Code</a>
                        <a name='ticket' class="btn btn-primary print" target="_blank">Print Ticket</a>

                    </div>
                    <table class="table  table-striped" id="example5">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th class="th line" >Parent #ID</th>
                                <th class="th line" >Parent Name</th>
                                <th class="th line" >Phone</th>
                                <th class="th line" >Child #ID</th>
                                <th class="th line" >Child Name</th>
                                <th class="th line" >Child Level</th>
                                <th  class="th line">Creation Date</th>
                                <th class="th line" >Source</th>
                                <th class="th line" >Status</th>
                                <th class="th line" >Notes</th>
                                <th class="th line" >SMS sent</th>
                                <th class="th line" >Adrerss</th>
                                <th  class="th line">Conversion Date</th>
                                <th class="th line" >Payment</th>
                                <th class="th line" >Delivery Status</th>
                                <th class="th line" >Pack</th>
                                <th class="th line" >Pack Type</th>
                                <th class="th line" >Original Price</th>
                                <th class="th line" >Current Price</th>
                                <th class="th line" >Activation Code</th>
                                <th class="th line" >Code Used</th>
                                <th class="th line" >Final Status</th>
                                <th class="th line">Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><input class="form-check-input" type="checkbox" id="selectall"></th>
                                <th class="th" >Parent #ID</th>
                                <th class="th" >Parent Name</th>
                                <th class="th" >Phone</th>
                                <th class="th" >Child #ID</th>
                                <th class="th" >Child Name</th>
                                <th class="th" >Child Level</th>
                                <th  class="date">Creation Date</th>
                                <th class="th" >Source</th>
                                <th class="th" >Status</th>
                                <th class="th" >Notes</th>
                                <th class="th" >SMS sent</th>
                                <th class="th" >Adrerss</th>
                                <th  class="date">Conversion Date</th>
                                <th class="th" id="th2">Payment</th>
                                <th class="th" id="th2">Delivery Status</th>
                                <th class="th" id="th2">Pack</th>
                                <th class="th" id="th2">Pack Type</th>
                                <th class="th" id="th2">Original Price</th>
                                <th class="th" id="th2">Current Price</th>
                                <th class="th" id="th2">Activation Code</th>
                                <th class="th" id="th2">Code Used</th>
                                <th class="th" id="th2">Final Status</th>
                                <th class="th" id="th3">Action</th>
                            </tr>
                        </tfoot>
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
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"
    type="text/javascript"></script>

    <!-- END JAVASCRIPTS -->
    <script src="https://apis.google.com/js/api.js"></script>
    <script>
        /**
         * Sample JavaScript code for sheets.spreadsheets.values.get
         * See instructions for running APIs Explorer code samples locally:
         * https://developers.google.com/explorer-help/guides/code_samples#javascript
         */

        function authenticate() {
            return gapi.auth2.getAuthInstance()
                .signIn({
                    scope: "https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/drive.readonly https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/spreadsheets.readonly"
                })
                .then(function() {
                        console.log("Sign-in successful");
                    },
                    function(err) {
                        console.error("Error signing in", err);
                    });
        }

        function loadClient() {
            gapi.client.setApiKey("AIzaSyDtQ8wRBDNKRM6abJUo319k8oFycGw87xo");
            return gapi.client.load("https://sheets.googleapis.com/$discovery/rest?version=v4")
                .then(function() {
                        console.log("GAPI client loaded for API");
                    },
                    function(err) {
                        console.error("Error loading GAPI client for API", err);
                    });
        }
        // Make sure the client is loaded and sign-in is complete before calling this method.
        function execute() {
            sheetName = prompt("what's the sheet name?");
            return gapi.client.sheets.spreadsheets.values.get({
                    "spreadsheetId": "13TTHFqLz_pwiDo_4Kw6UeqlQ9kHFvZcCdWPOJDVURwY",
                    "range": sheetName + "!A2:AD27",
                    "dateTimeRenderOption": "FORMATTED_STRING",
                    "majorDimension": "ROWS",
                    "valueRenderOption": "FORMATTED_VALUE",
                    "alt": "json",
                    "prettyPrint": true
                })
                .then(function(response) {
                        // Handle the results here (response.result has the parsed body).
                        console.log("Response", response);
                        $.ajax({
                            url: '/logistics/write',
                            type: "GET",
                            data: ({
                                response: response
                            }),
                            success: function(data) {
                                console.log(data);
                                $.ajax({
                                    url: '/logistics/loadata',
                                    type: "GET",
                                    success: function(data) {
                                        console.log('success')
                                    }
                                });

                            }
                        });


                    },
                    function(err) {
                        console.error("Execute error", err);
                    });
        }
        gapi.load("client:auth2", function() {
            gapi.auth2.init({
                client_id: "1071080155285-j31dj79gh2um4h3dnjnaciov4rgnmcke.apps.googleusercontent.com"
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            /////*******************Search Inputs*************/////////////////
            $('#example5 thead tr').clone(true).appendTo('#example5 thead');

            // $('#example5 thead tr:eq(1) .th').each(function(i) {
            //     var title = $(this).text();
                // console.log(title)
                   // if (table.column(i).search() !== this.value) {
                    //     // console.log(table.column(i).search());
                    //     // console.log(this.value);
                    //     table
                    //         .column(i)
                    //         .search(this.value)
                    //         .draw();
                    // }
                        // if (table.column(i).search() !== this.value) {
                    //     table.column(i).search(this.value).draw();
                    // }
                // $(this).html(
                //     '<input style="text-align:center;margin-bottom:36px !important" type="text" placeholder="Search ' +
                //     title + '" />');
                // $('input', this).on('keyup change', function() {
                 
                // });
            // });
            $('#example5 thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html(
                    '<div style="width :150px"></div>'
                );
            });
            // $('#example5 thead tr:eq(1) .date').each(function(i) {
            //     var title = $(this).text();
            //     $(this).html('from <input  type="date" placeholder="Search ' + title +
            //         '" /> To <input  type="date" placeholder="Search ' + title + '" />');

                // $('input', this).on('keyup change', function() {
                

                // });
            // });
            var table = $('#example5').dataTable({
                // searching: false,
                processing: true,
                serverSide: true,
                "scrollX": true,
                orderCellsTop: true,
		        fixedHeader: true,
                "scrollY": 600,
                responsive: true,
                // orderCellsTop: false,
                ajax: {
                url: "{{ route('logistics') }}",
                data: function (d) {
                    d.subscription = $("#subscription").val();
                    d.created_at_from = $("#created-at-from").val();
                    d.created_at_to = $("#created-at-to").val();
                    d.status = $("#status").val();
                }
            },
                'fnDrawCallback': function() {
                initPopover();
                  },
                columns: [{
                        // searchable: false,
                        // orderable: false,
                        className: 'select-checkbox',
                        defaultContent: '',
                        // targets: 0,
                    },
                    {
                        searchable: false,
                        data: 'user.id',
                        name: 'user.id',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        searchable: false,
                        data: 'user.name',
                        name: 'user.name',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        searchable: true,
                        data: 'user.phone',
                        name: 'user.phone',
                        "render": function(data, type, row) {
                            if (row.user.phone2 == null && data == null) {
                                return '----';
                            } else if (row.user.phone2 == null) {
                                return data;
                            } else {
                                return data + '/' + row.user.phone2;
                            }
                        }
                    },
                    {
                        searchable: false,
                        data: 'child.id',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }

                    }, {
                        searchable: false,
                        data: 'child.name',
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
                        data: 'pack.level.name',
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
                        data: 'created_at',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        },


                    },
                    {
                        searchable: false,
                        // orderable: false,
                        data: 'user.usercalls.sales_info.source.source',
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
                        data: 'user.usercalls.sms_sent',
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

                        data: 'conversion_date',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;

                            }
                        }
                    },
                    {
                        // searchable: false,
                        // orderable: false,
                        data: 'payment.payment_method.paymethod',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {

                        data: 'delivery.delivery_status',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        // searchable: false,
                        // orderable: false,
                        data: 'pack.name',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        // searchable: false,
                        // orderable: false,
                        data: 'pack.pack_type.name',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'pack.price',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        // searchable: false,
                        data: 'payment.amount',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        // searchable: false,
                        // orderable: false,
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
                        // searchable: false,
                        // orderable: false,
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
                        // searchable: false,
                        // orderable: false,
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
                        // orderable: false,
                        // searchable: false,
                        render: function(data, type, row, meta) {
                            return `<div class="btn-group"> <a class="btn btn-mini btn-white" href="/logistics/edit/${row.id}" style="margin-right:10px">Edit</a>
                                <a class="btn btn-mini btn-danger" href="/logistics/delete/${row.id}">Delete</a> </div>`;
                        },
                    },
                ],
                select: {
                    style: 'multi',
                    selector: "tr"
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

            $("#selectAll,#selectall").on("click", function(e) {
                if ($(this).is(":checked")) {
                    table.rows().select();
                } else {
                    table.rows().deselect();
                }
            });

            // Search bar
            // $("#example5_filter").hide();
          
        function initPopover() {
            table.$('[data-toggle="popover"]').popover().click(function(e) {
                e.preventDefault();
            });

            $('#subscription, #status').select2({
                width: '100%',
                placeholder: "Choose an option",
                allowClear: true
            });

            $('.input-append.date').datepicker({
                autoclose: true,
                todayHighlight: true,
            });
        }
        $("#created-at-to, #created-at-from, #subscription, #status").on('change', function () {
            table.DataTable().draw();
        });

            $('.print').click(function() {
                type = $(this).attr('name')
                if (table.rows({
                        selected: true
                    }).count() == 0) {
                    alert('please select a column or more')
                } else {
                    var len = table.rows({
                        selected: true
                    }).count();
                    var ids = [];
                    for (let i = 0; i < len; i++) {
                        ids[i] = table.rows({
                            selected: true
                        }).data()[i].id;
                    }
                    var url = "{{ route('pdf', [':type', ':id']) }}";
                    url = url.replace(':type', type);
                    url = url.replace(':id', ids);
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', url, true);
                    xhr.responseType = 'blob';

                    xhr.onload = function(e) {
                        if (this['status'] == 200) {
                            var blob = new Blob([this['response']], {
                                type: 'application/pdf'
                            });
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = type + ".pdf";
                            link.click();
                        }
                    };

                    xhr.send();

                    // }
                }


            });
            $("div.toolbar").html(
                '<div class="table-tools-actions"><a class="btn btn-primary" href="{{ route('logistics.create') }}" style="margin-left:12px" id="test2">Add</a></div>'
            );

        });
    </script>
@endsection
