@extends('admin.layout.auth',['title'=>'Reset Password'])
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <h2>
        Reset Password
    </h2>
    <form class="login-form validate" role="form" method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="form-group col-md-10{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="form-label">E-Mail Address</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
                @endif

            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <button class="btn btn-primary btn-cons pull-right" type="submit">Send Password Reset Link</button>
            </div>
        </div>
    </form>
@endsection
