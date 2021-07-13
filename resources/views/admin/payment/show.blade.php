@extends('admin.layout.layout',['title'=>'Payment history'])
@section('css')
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Show <span class="semi-bold">Payment History</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <div class="borderall">
                        <h2>User:</h2>
                        <p>Name: <a
                                href="{{route('users.edit', [$payment->user->roles->first()->name,$payment->user->id])}}">{{ $payment->user->name }}</a>
                        </p>
                        <p>Level: {{ $payment->user->level->name }}</p>
                    </div>
                    <div class="borderall">
                        <h2>Payment:</h2>
                        <p>Amount: {{ $payment->amount }}</p>
                        <p>Payment method: {{ $payment->paymentMethod->name }}</p>
                        <p>Current status: {{$payment->current_status->name}}</p>
                        <p>Created at: {{ $payment->created_at }}</p>
                    </div>
                    <ul class="list-group">
                        @foreach($payment->paymentHistories as $history)
                            <li class="list-group-item"
                                @if($history->paymentStatus->id == $payment->current_status->id) class="list-group-item bg-primary" @endif>
                                <strong>{{$history->paymentStatus->name}} </strong> at {{$history->created_at}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('js')
    <script type="text/javascript">
    </script>
@endsection
