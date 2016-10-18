@extends('layouts.app')

@section('page_title') Register @endsection

@section('additional_css')
<script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
<div class="col-xs-8 col-sm-6 col-md-4 col-lg-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
    @include('common.validationErrors')
    <form method="POST" action="">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="emailInput">Email Address</label>
            <input type="email" class="form-control" id="emailInput" name="email" value="{{ old('email') }}" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label for="nameInput">Name</label>
            <input type="text" class="form-control" id="nameInput" name="name" value="{{ old('name') }}" placeholder="First and Last Name" required>
        </div>
        <div class="form-group">
            <label for="passwordInput">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmationInput">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmationInput" name="password_confirmation" placeholder="Confirm Password" required>
        </div>
        <div class="form-group text-center">
            <div class="g-recaptcha" data-sitekey="6LfzMBoTAAAAAF3LTAZ9VPZUtTmGNp7fbuin9exE"></div>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <button type="submit" class="btn btn-default">Register</button>
        </div>
    </form>
</div>
@stop