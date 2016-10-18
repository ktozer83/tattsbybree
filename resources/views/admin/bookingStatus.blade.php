@extends('layouts.app')

@section('page_title') Admin - Editing Booking Status @endsection

@section('additional_css')
<link rel="stylesheet" href="/css/jquery-ui.structure.css" type="text/css">
<link rel="stylesheet" href="/css/jquery-ui.theme.css" type="text/css">
@endsection

@section('content')
<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xs-offset-2 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default" href="/members" role="button">Home</a>
        </div>
    </div>
    @include('common.validationErrors')
    <form method="POST" action="">
        {!! csrf_field() !!}
        <div class="form-group radio-buttons">
            <label for="colourInput">
                Bookings Available:
            </label>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary @if ((old('can_book') == '1') || ($bookingStatus->can_book == '1')) active @endif">
                    <input type="radio" name="can_book" value="1" autocomplete="off" @if ((old('can_book') == '1') || ($bookingStatus->can_book == '1')) checked @endif> Yes
                </label>
                <label class="btn btn-primary @if ((old('can_book') == '0') || ($bookingStatus->can_book == '0')) active @endif">
                    <input type="radio" name="can_book" value="0" autocomplete="off" @if ((old('can_book') == '0') || ($bookingStatus->can_book == '0')) checked @endif> No
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="slotsInput">Slots Available:</label>
            <input type="text" class="form-control" id="slotsInput" name="slots_available" value="@if (old('slots_available')){{ old('slots_available') }}@else{{ $bookingStatus->slots_available }}@endif" placeholder="No Bookings Until Date" required>
        </div>
        <div class="form-group">
            <label for="dateInput">No Bookings Until:</label>
            <input type="text" class="form-control" id="dateInput" name="no_bookings_until" value="@if (old('message')){{ old('no_bookings_date') }}@else{{ $bookingStatus->no_bookings_until }}@endif" placeholder="No Bookings Until Date" readonly required>
        </div>
        <div class="form-group">
            <label for="descriptionInput">Message:</label>
            <textarea class="form-control" id="messageInput" name="message" rows="3" placeholder="Booking Message" required>@if (old('message')){{ old('message') }}@else{{ $bookingStatus->message }}@endif
            </textarea>
        </div>                
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <button type="submit" class="btn btn-default">Save</button>
        </div>
    </form>
</div>
@endsection

@section('additional_js')
<script src='https://code.jquery.com/ui/1.11.4/jquery-ui.min.js' type='text/javascript'></script>
<script type="text/javascript">
$(function () {
    $("#dateInput").datepicker({minDate: 0, maxDate: "+2Y", dateFormat: 'yy-mm-dd'});
});
</script>
@endsection