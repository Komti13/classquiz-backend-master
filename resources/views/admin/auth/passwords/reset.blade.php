@extends('admin.layout.auth',['title'=>'Reset Password'])
@section('content')
    <h2>
        Reset Password
    </h2>
    <form class="login-form validate" role="form" method="POST" action="{{ route('password.reset')}}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">
        <div class="row">
            <div class="form-group col-md-10{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="form-label">E-Mail Address</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}"
                       autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-10{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control" name="password">
                @if ($errors->has('password'))
                    <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-10{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <button class="btn btn-primary btn-cons pull-right" type="submit">Reset Password</button>
            </div>
        </div>
    </form>
@endsection
