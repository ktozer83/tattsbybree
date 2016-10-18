@extends('layouts.app')

@section('page_title') Admin - Pending Quotes @endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default" href="/members" role="button">Home</a>
            <a class="btn btn-default" href="/members/admin/appointments" role="button">All Appointments</a>
        </div>
    </div>
    <p class="text-center">As long as a quote has a status of pending it will be displayed here. As soon as the status is changed from pending it will appear on your appointments page.</p>
    <hr>
    @if (count($pendingQuotes) == 0)
    <p class="text-center">No pending quotes found.</p>
    @else
    <table class="table table-hover table-condensed text-center">
        <thead>
            <tr>
                <th>Quote</th>
                <th>Client Name</th>
                <th>Email</th>
                <th>Budget</th>
                <th>Date Added</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingQuotes as $quote)
            <tr>
                <td>
                    {{ $quote->id }}
                </td>
                <td>
                    {{ $quote->client_name }}
                </td>
                <td>
                    {{ $quote->email }}
                </td>
                <td>
                    {{ $quote->budget_range }}
                </td>
                <td>
                    {{ stripDate($quote->created_at) }}
                </td>
                <td><a href="/members/details/{{ $quote->id }}"><small>Details</small></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection