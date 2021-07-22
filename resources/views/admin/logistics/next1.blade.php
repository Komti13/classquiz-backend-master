@extends('admin.layout.layout',['title'=>'Logistics'])
@section('css')
    <link href="{{ asset('assets/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
  

        .countries {
            display: none;
        }

        .input {
            background: #fff;
            border: solid 1px #e5e9ec;
            margin-bottom: 4px !important;
        }

        .simple {
            margin-bottom: 20px;
        }

    </style>
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            {{-- About Call --}}
            <div class="grid simple">
                <div class="grid-title">
                    <h4>About <span class="semi-bold">Call</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    {!! Form::open(['route' => ['logistics.store','source'=>'second']]) !!}
                    <div class="form-group">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', $status, null) !!}
                    </div>
                    <div class="input-append success form-group" style="width: 96%;">
                        {!! Form::label('call_date', 'Call Date') !!}
                        {!! Form::input('call_date', 'call_date', null, ['class' => 'form-control', 'placeholder' => 'Call Date', 'id' => 'datepick']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('calltype', 'Call Type') !!}
                        {!! Form::select('calltype', ['Sale','Feedback','Technical Support'], null) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes', 'Notes') !!}
                        {!! Form::textarea('notes', null, ['class' => 'form-control', 'placeholder' => 'Notes...']) !!}
                    </div>
                    <div class="text-right">
                        <br>
                        <button type="submit" class="btn btn-primary">Next <i
                                class="icon-arrow-left13 position-right"></i></button>
                    </div>
                </div>
            </div>
           
        </div>
    </div>



@endsection
@section('js')
    <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#status,#calltype")
                .select2({
                    width: '100%'
                })
            $('#datepick').datetimepicker({
                inline: true,
                format: 'DD-MM-YYYY HH:mm'
            });
           
        });

    </script>
@endsection
