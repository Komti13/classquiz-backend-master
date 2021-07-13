@extends('admin.layout.auth',['title'=>'Login'])
@section('content')
    <h2>
        Sign in to ClassQuiz
    </h2>
    <form class="login-form validate" role="form" method="POST" action="{{ route('login') }}">
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
            <div class="control-group col-md-10">
                <div class="checkbox checkbox check-success">
                    <a href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>&nbsp;&nbsp;
                    <input id="remember" name="remember" type="checkbox">
                    <label for="remember">Remember Me</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <button class="btn btn-primary btn-cons pull-right" type="submit">Login</button>
            </div>
        </div>
    </form>
@endsection
