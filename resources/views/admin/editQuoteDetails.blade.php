@extends('layouts.app')

@section('page_title') Edit Quote Details @endsection

@section('additional_css')
<link rel="stylesheet" href="/css/jquery-ui.structure.css" type="text/css">
<link rel="stylesheet" href="/css/jquery-ui.theme.css" type="text/css">
<link rel="stylesheet" href="/css/jquery.timepicker.css" type="text/css">
<link href="/css/lightbox.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    @include('common.validationErrors')
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default" href="/members/details/{{ $quote->id }}" role="button">Back</a>
            <a class="btn btn-default" href="/members" role="button">Home</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <h4 class="text-center"><u>Details</u></h4>
            <p class="text-center"><strong>Client Name:</strong> {{ $quote->client_name }}</p>
            <p class="text-center"><strong>Email:</strong> {{ $quote->email }}</p> 
            <p class="text-center"><strong>Contact Number:</strong> {{ formatPhone($quote->phone_number) }}</p>
            <p class="text-center"><strong>Budget:</strong> {{ $quote->budget_range }}</p>
            <p class="text-center"><strong>Detail:</strong>
                @if ($quote->black_white == '1')
                Black and white
                @elseif ($quote->colour == '1')
                Colour
                @endif
            </p>
            <p class="text-center"><strong>Status:</strong> {{ $quote->appointment_status->status_name }}</p>
            <p>
                <strong>Description:</strong>
                <br>
                {{ $quote->description }}
            </p>
            <div id="quote-user-images">
                <h4 class="text-center"><u>Attached Images</u></h4>
                <ul class="list-inline text-center">
                    @foreach($quote->images as $image)
                    <li>
                        <a href="/img/user_img/{{ $image->filename }}" data-lightbox="user-images"><img class="user-images" src="/img/user_img/{{ $image->filename }}"></a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-sm-6 editDetailsDiv text-center">
            <h4 class="text-center"><u>Admin</u></h4>
            <form method="POST" action="">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="status">Category:</label>
                    <select id="status" name="status" class="form-control text-center">
                        @foreach($allStatus as $status)
                        <option value="{{ $status->id }}" @if ($quote->appointment_status_id == $status->id) selected="selected" @endif>{{ $status->status_name }}</option>
                        @endforeach
                    </select>
                </div>
                <hr>
                <div class="form-group">
                    <label for="quoteInput">Quote Price:</label>
                    <input type="text" class="form-control" id="quoteInput" name="quote_price" value="@if ((old('quote_price'))){{ old('quote_price') }}@else{{ $quote->quote_price }}@endif" placeholder="Quote">
                    <p class="help-block">Don't worry about adding a dollar sign.</p>
                </div>
                <hr>
                <label>Consultation Needed:</label>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-primary @if ($quote->consultation_needed == '1') active @endif">
                        <input type="radio" id="consultRadio" name="editNeedConsult" value="1" autocomplete="off" @if ($quote->consultation_needed == '1') checked @endif> Yes
                    </label>
                    <label class="btn btn-primary @if ($quote->consultation_needed == '0') active @endif">
                        <input type="radio" id="noConsultRadio" name="editNeedConsult" value="0" autocomplete="off" @if ($quote->consultation_needed == '0') checked @endif> No
                    </label>
                </div>
                <div class="form-group">
                    <label for="dateInput">Consultation Date and Time:</label><br>
                    <input type="text" class="form-control" id="consultationDateInput" name="consultation_date" value="@if ((old('consultation_date'))){{ old('consultation_date') }}@else{{ $quote->consultation_date }}@endif" placeholder="Consultation Date" readonly >
                    <input type="text" class="form-control time" id="consultationTimeInput" name="consultation_time" value="@if ((old('consultation_time'))){{ old('consultation_time') }}@else{{ $quote->consultation_time }}@endif" placeholder="Consultation Time">
                </div>
                <label>Consultation Confirmed:</label>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-primary @if ($quote->consultation_confirmed == '1') active @endif">
                        <input type="radio" id="consultConfirmRadio" name="editConsult" value="1" autocomplete="off" @if ($quote->consultation_confirmed == '1') checked @endif> Yes
                    </label>
                    <label class="btn btn-primary @if ($quote->consultation_confirmed == '0') active @endif">
                        <input type="radio" id="noConsultConfirmRadio" name="editConsult" value="0" autocomplete="off" @if ($quote->consultation_confirmed == '0') checked @endif> No
                    </label>
                </div>
                <hr>
                <label>Appointment Made:</label>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-primary @if ($quote->appointment_made == '1') active @endif">
                        <input type="radio" id="appointmentRadio" name="editAppointment" value="1" autocomplete="off" @if ($quote->appointment_made == '1') checked @endif> Yes
                    </label>
                    <label class="btn btn-primary @if ($quote->appointment_made == '0') active @endif">
                        <input type="radio" id="noAppointmentRadio" name="editAppointment" value="0" autocomplete="off" @if ($quote->appointment_made == '0') checked @endif> No
                    </label>
                </div>
                <div class="form-group">
                    <label for="dateInput">Appointment Date and Time:</label><br>
                    <input type="text" class="form-control" id="appointmentDateInput" name="appointment_date" value="@if ((old('appointment_date'))){{ old('appointment_date') }}@else{{ $quote->appointment_date }}@endif" placeholder="Appointment Date" readonly >
                    <input type="text" class="form-control time" id="appointmentTimeInput" name="appointment_time" value="@if ((old('appointment_time'))){{ old('appointment_time') }}@else{{ $quote->appointment_time }}@endif" placeholder="Appointment Time">
                </div>
                <hr>
                <div class="form-group">
                    <label for="quoteInput">Down Payment:</label>
                    <input type="text" class="form-control" id="downPaymentInput" name="down_payment_cost" value="@if ((old('down_payment_cost'))){{ old('down_payment_cost') }}@else{{ $quote->down_payment_cost }}@endif" placeholder="Down Payment">
                    <p class="help-block">Don't worry about adding a dollar sign.</p>
                </div>
                <label>Down Payment Paid:</label>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-primary @if ($quote->down_payment_paid == '1') active @endif">
                        <input type="radio" id="downPaymentRadio" name="editDownPayment" value="1" autocomplete="off" @if ($quote->down_payment_paid == '1') checked @endif> Yes
                    </label>
                    <label class="btn btn-primary @if ($quote->down_payment_paid == '0') active @endif">
                        <input type="radio" id="nodownPaymentRadio" name="editDownPayment" value="0" autocomplete="off" @if ($quote->down_payment_paid == '0') checked @endif> No
                    </label>
                </div>
                <hr>
                <div class="col-sm-8 col-sm-offset-2 text-center">
                    <button type="submit" class="btn btn-default">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('additional_js')
<script src='https://code.jquery.com/ui/1.11.4/jquery-ui.min.js' type='text/javascript'></script>
<script src='/js/jquery.timepicker.min.js' type='text/javascript'></script>
<script type="text/javascript" src="/js/lightbox.js"></script>
<script type="text/javascript">
    lightbox.option({
        'wrapAround': true
    });
</script>
<script type="text/javascript">
$(function () {
    $("#consultationDateInput").datepicker({minDate: 0, maxDate: "+2Y", dateFormat: 'yy-mm-dd'});
});
$(function () {
    $("#appointmentDateInput").datepicker({minDate: 0, maxDate: "+2Y", dateFormat: 'yy-mm-dd'});
});
$(function () {
    $('#consultationTimeInput').timepicker();
    $('#consultationTimeInput').timepicker('option', {'minTime': '11:00am', 'maxTime': '7:00pm', 'step': '15'});
});
$(function () {
    $('#appointmentTimeInput').timepicker();
    $('#appointmentTimeInput').timepicker('option', {'minTime': '11:00am', 'maxTime': '7:00pm', 'step': '15'});
});
</script>
@endsection