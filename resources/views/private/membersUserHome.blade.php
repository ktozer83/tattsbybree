@extends('layouts.app')

@section('page_title') Members - Home @endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    @if ($bookingStatus->can_book == '1')
    <p class="text-center">Bree currently has {{ $bookingStatus->slots_available }} slots available for booking. Request a quote now if you want to get in before they're gone.</p>
    <hr>
    @else
    <p class="text-center">{{ $bookingStatus->message }}</p>
    <hr>
    @endif
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default" href="/members/quote" role="button">@if ($bookingStatus->can_book == '1') Request a Quote @else Get on the Waiting List @endif</a>
            <a class="btn btn-default" href="/logout" role="button">Logout</a>
        </div>
    </div>
    <h3 class="text-center">Your Quotes</h3>
    @if (count($appointments) == 0)
    <p class="text-center">No listings found.</p>
    @else
    <table class="table table-hover table-condensed text-center">
        <thead>
            <tr>
                <th>Quote</th>
                <th>Appointment</th>
                <th>Date Added</th>
                <th>Last Update</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>
                    {{ $appointment->id }}
                </td>
                <td>
                    @if ($appointment->appointment_made == '0')
                    {{ readableEntry($appointment->appointment_made) }}
                    @else
                    {{ $appointment->appointment_date }}
                    @endif
                </td>
                <td>
                    {{ stripDate($appointment->created_at) }}
                </td>
                <td>
                    @if ($appointment->updated_at == '-0001-11-30 00:00:00')
                    Not Updated
                    @else
                    {{ $appointment->updated_at }}
                    @endif
                </td>
                <td>
                    <span class="label {{ colourLabel($appointment->appointment_status_id) }}">{{ $appointment->appointment_status->status_name }}</span>
                </td>
                <td><a href="/members/details/{{ $appointment->id }}"><small>Details</small></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection