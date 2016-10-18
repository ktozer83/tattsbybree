@extends('layouts.app')

@section('page_title') Login @endsection

@section('content')
<div class="col-xs-8 col-sm-6 col-md-4 col-lg-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
    @include('common.validationErrors')
    <form method="POST" action="">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="emailInput">Email</label>
            <input type="email" class="form-control" id="emailInput" name="email" value="{{ old('email') }}" placeholder="Email">
        </div>
        <div class="form-group">
            <label for="passwordInput">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password">
        </div>
        <div class="col-sm-12 text-center">
            <div class="checkbox">
                <label>
                    <input id="rememberInput" name="remember" type="checkbox"> Remember Me
                </label>
            </div>
            <button type="submit" class="btn btn-default">Login</button>
        </div>
    </form>
    <p class="text-center"><a href="/forgot">Forgot Your Password?</a></p>
</div>
@stop