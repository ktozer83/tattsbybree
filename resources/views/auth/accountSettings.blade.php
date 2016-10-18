@extends('layouts.app')

@section('page_title') Account Settings @endsection

@section('content')
<div class="col-xs-8 col-sm-6 col-md-4 col-lg-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
    @include('common.validationErrors')
    <form method="POST" action="">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="emailInput">Email Address</label>
            <input type="email" class="form-control" id="emailInput" name="email" value="{{ Auth::user()->email }}" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label for="nameInput">Name</label>
            <input type="text" class="form-control" id="nameInput" name="name" value="{{ Auth::user()->name }}" placeholder="First and Last Name" required>
        </div>
        <div class="form-group">
            <label for="passwordInput">New Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="New Password">
        </div>
        <div class="form-group">
            <label for="password_confirmationInput">Confirm New Password</label>
            <input type="password" class="form-control" id="password_confirmationInput" name="password_confirmation" placeholder="Confirm New Password">
        </div>
        <div class="form-group radio-buttons">
            <label for="emailNotifications">
                Email Notifications:
            </label>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary @if (Auth::user()->get_email == '1') active @endif">
                    <input type="radio" name="emailNotifications" value="1" autocomplete="off" @if (Auth::user()->get_email == '1') checked @endif> On
                </label>
                <label class="btn btn-primary @if (Auth::user()->get_email == '0') active @endif">
                    <input type="radio" name="emailNotifications" value="0" autocomplete="off" @if (Auth::user()->get_email == '0') checked @endif> Off
                </label>
            </div>
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <button type="submit" class="btn btn-default">Update Settings</button>
        </div>
    </form>
</div>
@stop

@section('additional_js')
<script type="text/javascript">
$().button('toggle');
</script>
@endsection