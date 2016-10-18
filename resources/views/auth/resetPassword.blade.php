@extends('layouts.app')

@section('page_title') Reset Password @endsection

@section('content')
<div class="col-xs-8 col-sm-6 col-md-4 col-lg-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
    <p class="text-center">Please enter your new password below. Upon completion you will be asked to log in.</p>
    @include('common.validationErrors')
    <form method="POST" action="">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="passwordInput">New Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="New Password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmationInput"> Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmationInput" name="password_confirmation" placeholder="Confirm Password" required>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <button type="submit" class="btn btn-default">Change Password</button>
        </div>
    </form>
</div>
@endsection