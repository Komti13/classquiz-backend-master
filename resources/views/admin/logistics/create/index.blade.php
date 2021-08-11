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

    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" />
    <style>
        table,
        #th1,
        #th2 {
            text-align: center
        }

    </style>
    <!-- END PLUGIN CSS -->
@endsection
@section('content')
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
                    <table class="table" id="example5">
                        <thead>
                            <tr>
                                <th></th>
                                <th id="th1">Parent #ID</th>
                                <th id="th1">Parent Name</th>
                                <th id="th1">Phone</th>
                                <th id="th1">Child #ID</th>
                                <th id="th1">Child Name</th>
                                <th id="th1">Child Level</th>
                                <th id="th1" class="date">Creation Date</th>
                                <th id="th1">Source</th>
                                <th id="th1">Status</th>
                                <th id="th1">Notes</th>
                                <th id="th1">SMS sent</th>
                                <th id="th1">Adrerss</th>
                                <th id="th1" class="date">Conversion Date</th>
                                <th id="th2">Payment</th>
                                <th id="th2">Delivery Status</th>
                                <th id="th2">Pack</th>
                                <th id="th2">Pack Type</th>
                                <th id="th2">Original Price</th>
                                <th id="th2">Current Price</th>
                                <th id="th2">Activation Code</th>
                                <th id="th2">Code Used</th>
                                <th id="th2">Final Status</th>
                                <th id="th2">Action</th>
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
    <script src="https://apis.google.com/js/api.js"></script>
    <script>
      /**
       * Sample JavaScript code for sheets.spreadsheets.values.get
       * See instructions for running APIs Explorer code samples locally:
       * https://developers.google.com/explorer-help/guides/code_samples#javascript
       */
    
      function authenticate() {
        return gapi.auth2.getAuthInstance()
            .signIn({scope: "https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/drive.readonly https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/spreadsheets.readonly"})
            .then(function() { console.log("Sign-in successful"); },
                  function(err) { console.error("Error signing in", err); });
      }
      function loadClient() {
        gapi.client.setApiKey("AIzaSyDtQ8wRBDNKRM6abJUo319k8oFycGw87xo");
        return gapi.client.load("https://sheets.googleapis.com/$discovery/rest?version=v4")
            .then(function() { console.log("GAPI client loaded for API"); },
                  function(err) { console.error("Error loading GAPI client for API", err); });
      }
      // Make sure the client is loaded and sign-in is complete before calling this method.
      function execute() {
        return gapi.client.sheets.spreadsheets.values.get({
          "spreadsheetId": "13TTHFqLz_pwiDo_4Kw6UeqlQ9kHFvZcCdWPOJDVURwY",
          "range": "Calls!A2:AD27",
          "dateTimeRenderOption": "FORMATTED_STRING",
          "majorDimension": "ROWS",
          "valueRenderOption": "FORMATTED_VALUE",
          "alt": "json",
          "prettyPrint": true
        })
            .then(function(response) {
                    // Handle the results here (response.result has the parsed body).
                    console.log("Response", response);
                  },
                  function(err) { console.error("Execute error", err); });
      }
      gapi.load("client:auth2", function() {
        gapi.auth2.init({client_id: "1071080155285-j31dj79gh2um4h3dnjnaciov4rgnmcke.apps.googleusercontent.com"});
      });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example5 thead tr ').clone(true).appendTo('#example5 thead');
            $('#example5 thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html(
                    '<div style="width :150px"></div>'
                );
            });
            /////*******************Search Inputs*************/////////////////
            // $('#example5 thead tr ').clone(true).appendTo('#example5 thead');

            // $('#example5 thead tr:eq(1) #th1').each(function(i) {
            //     var title = $(this).text();
            //     $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            //     $('input', this).on('keyup change', function() {
            //         if (table.column(i + 1).search() !== this.value) {
            //             console.log(table.column(i + 1).search());
            //             console.log(this.value);

            //             table
            //                 .column(i + 1)
            //                 .search(this.value)
            //                 .draw();
            //         }
            //     });
            // });
            // $('#example5 thead tr:eq(1) #th2').each(function(i) {
            //     var title = $(this).text();
            //     $(this).html(
            //         '<div style="width :150px">----</div>'
            //     );


            // });
            // $('#example5 thead tr:eq(1) .date').each(function(i) {
            //     var title = $(this).text();
            //     $(this).html('<input type="date" placeholder="Search ' + title + '" />');

            //     $('input', this).on('keyup change', function() {
            //         if (table.column(i + 1).search() !== this.value) {
            //             table.column(i + 1).search(this.value).draw();
            //         }

            //     });
            // });
            var table = $('#example5').DataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
                orderCellsTop: true,
                ajax: "{{ route('logistics') }}",
                columns: [{
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 1,
                        defaultContent: ''
                    },
                    {
                        orderable: false,
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
                        data: 'child.id',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        }

                    }, {
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
                        data: 'user.usercalls.created_at',
                        "render": function(data, type, row) {
                            if (data == null) {
                                return '----';
                            } else {
                                return data;
                            }
                        },
                        // "searchable": false

                    },
                    {
                        // searchable: false,
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

                        data: 'user.usercalls.conversation_date',
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
                            return `<div class="btn-group"> <a class="btn btn-mini btn-white" href="/logistics/editform/${row.id}" style="margin-right:10px">Edit</a>
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



            // Search bar
            $("#example5_filter").hide();


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
                    // console.log(len);
                    for (let i = 0; i < len; i++) {
                        var id = table.rows({
                            selected: true
                        }).data()[i].id;
                        var userid = table.rows({
                            selected: true
                        }).data()[i].user.id;
                        var token = table.rows({
                            selected: true
                        }).data()[i].token.token;
                        var url = "{{ route('pdf', [':type', ':id']) }}";
                        url = url.replace(':type', type);
                        url = url.replace(':id', id);
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
                                link.download = type + table.rows({
                                    selected: true
                                }).data()[i].user.id + ".pdf";
                                link.click();
                            }
                        };

                        xhr.send();

                    }
                }


            });
            $("div.toolbar").html(
                '<div class="table-tools-actions"><a class="btn btn-primary" href="{{ route('form') }}" style="margin-left:12px" id="test2">Add</a></div>'
            );

        });
    </script>
@endsection
