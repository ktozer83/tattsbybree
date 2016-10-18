@extends('layouts.app')

@section('page_title') Forgot Password @endsection

@section('content')
<div class="col-xs-8 col-sm-6 col-md-4 col-lg-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
    @include('common.validationErrors')
    <p class="text-center">Enter the email address used to create your account and instructions for resetting your password will be sent to that address.</p>
    <form method="POST" action="">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="emailInput">Email</label>
            <input type="email" class="form-control" id="emailInput" name="email" value="{{ old('email') }}" placeholder="Email">
        </div>
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </form>
</div>
@endsection