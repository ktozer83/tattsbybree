@extends('layouts.app')

@section('page_title') Contact @endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">    
    @include('common.validationErrors')
    <div class="row">
        <div id="contactInfo" class="col-xs-4 col-sm-4">
            <p>
                <br>
                <a href="mailto:inquiries@tattsbybree.com">inquiries@tattsbybree.com</a>
                <br>
                (705)874-1520
            </p>
            <p>
                480 King George St.<br>
                Peterborough, ON
            </p>
            <p>
                <strong><u>Hours</u></strong>
                <br>
                Monday:
                <br>
                <strong><span class="text-warning">Closed</span></strong>
                <br>
                Tuesday - Saturday:
                <br>
                <span class="text-success">11:00am - 7:00pm</span>
                <br>
                Sunday:
                <br>
                <strong><span class="text-warning">Closed</span></strong>
            </p>
            <p class="text-danger text-center">
                <strong>By appointment only! No walk-ins!</strong>
            </p>
        </div>
        <div class="col-xs-8 col-sm-8">
            <p class="text-center">Fill out the form below, email, or call, and we will get back to you as soon as possible.</p>
            <form method="POST" action="">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="emailInput">Email address</label>
                    <input type="email" class="form-control" id="emailInput" name="email" value="{{ old('email') }}" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="nameInput">Name</label>
                    <input type="text" class="form-control" id="nameInput" name="name" value="{{ old('name') }}" placeholder="Name">
                </div>
                <div class="form-group">
                    <label for="messageInput">Message</label>
                    <textarea class="form-control" id="messageInput" name="message" rows="3"value="{{ old('message') }}" placeholder="Your message or question"></textarea>
                </div>
                <div class="form-group text-center">
                        <button type="submit" class="btn btn-default">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection