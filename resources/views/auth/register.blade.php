<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('css')
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | Register</title>
</head>
<body>

  <div class="alert alert-primary text-center" role="alert">
    Register in {{ config('app.name') }}
  </div>

  <form class="auth-fοrm mt-5" method="post" action="{{ route('process-register') }}">

    {{ csrf_field() }}

    @if ( $errors->any() )
        @foreach ($errors->all() as $error)
          <div class="alert alert-danger text-center" role="alert">{{ $error }}</div>
        @endforeach
    @endif

    @if ( session('success_msg') ) 
      <div class="alert alert-success text-center" role="alert">{!! session('success_msg') !!}</div>
    @endif

    <div class="form-group">
      <label>{{ __('Username') }}</label>
      <input type="email" name="username" class="form-control" required />
    </div>

    <div class="form-group">
      <label>{{ __('Password') }}</label>
      <input type="password" name="password" class="form-control" required />
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-primary submit-btn">{{ __('Register') }}</button>
    </div>

    <div class="text-center bottom-frm-txt">Already have an account?
      <a href="{{ route('login') }}">Login</a>
    </div>
  </form>
   
</body>
</html>
