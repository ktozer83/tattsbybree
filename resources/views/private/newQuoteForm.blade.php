@extends('layouts.app')

@section('page_title') @if ($bookingStatus->can_book == '0') Waiting List Request @else Request a Quote @endif @endsection

@section('additional_css')
<link rel="stylesheet" href="/css/jquery-ui.structure.css" type="text/css">
<link rel="stylesheet" href="/css/jquery-ui.theme.css" type="text/css">
@endsection

@section('content')
<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xs-offset-2 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
    @include('common.validationErrors')
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default" href="/members" role="button">Home</a>
        </div>
    </div>
    @if ($bookingStatus->can_book == '0')
    <p class="text-center">Although Bree is not currently booking any appointments until {{ $bookingStatus->no_bookings_until }}, you can still submit a quote and get an estimate. You will then be placed on a waiting list for when bookings become available.</p>
    @else
    <p class="text-center">Bree currently has {{ $bookingStatus->slots_available }} appointmeent slots available. Fill out the form below to ensure you get an appointment.</p>
    @endif
    <hr>
    <form method="POST" action="" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <p class="text-center">
            Name: {{ Auth::user()->name }}
            <br>
            Email: {{ Auth::user()->email }}
        </p>
        <div class="form-group">
            <label for="phoneInput">Phone Number:</label>
            <input type="text" class="form-control" id="phoneInput" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number" required>
            <p class="help-block text-center">Numbers only.</p>
        </div>
        <hr>
        <div class="form-group radio-buttons">
            <label for="colourInput">
                Detail:
            </label>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary @if (old('detail') == 'black_white') active @endif">
                    <input type="radio" name="detail" value="black_white" autocomplete="off" @if (old('detail') == 'black_white') checked @endif> Black and White
                </label>
                <label class="btn btn-primary @if (old('detail') == 'colour') active @endif">
                    <input type="radio" name="detail" value="colour" autocomplete="off"@if (old('detail') == 'colour') checked @endif> Colour                </label>
            </div>
        </div>
        <hr>
        <div class="form-group text-center">
            <label for="quote-price-range">Price range:</label>
            <input type="text" class="text-center" id="quote-price-range" name="budget_range" readonly>
            <div id="slider-range"></div>
            <p class="help-block text-center">Click and drag the purple squares above to set the range for your budget.</p>
        </div>
        <hr>
        <div class="form-group">
            <label for="descriptionInput">Description:</label>
            <textarea class="form-control" id="descriptionInput" name="description" rows="3" placeholder="Description of Tattoo" required>{{ old('description') }}</textarea>
            <p class="help-block text-center">Please be as descriptive as possible when describing your tattoo. Please include placement, size, and location.</p>
        </div>
        <hr>
        <div class="form-group">
            <label for="imageFile">Tattoo image:</label>
            <input type="file" name="images[]" multiple="true" id="imageFile" class="form-control">
            <p class="help-block text-center">You are able to upload more than one image, but images must be less than 1MB.</p>
        </div>        
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
</div>
@endsection

@section('additional_js')
<script src='https://code.jquery.com/ui/1.11.4/jquery-ui.min.js' type='text/javascript'></script>
<script>
$(function () {
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 5000,
        step: 50,
        values: [250, 1000],
        slide: function (event, ui) {
            $("#quote-price-range").val("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
        }
    });
    $("#quote-price-range").val("$" + $("#slider-range").slider("values", 0) +
            " - $" + $("#slider-range").slider("values", 1));
});
</script>
@endsection