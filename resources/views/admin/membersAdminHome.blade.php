@extends('layouts.app')

@section('page_title') Admin - Home @endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5 col-lg-offset-1">
            @if ($bookingStatus->can_book == '1')
            <p>You are currently accepting bookings. You have {{ $bookingStatus->slots_available }} slots available.</p>
            @elseif ($bookingStatus->can_book == '0')
            <p>You are not accepting any bookings at this time. There are no bookings available until {{ $bookingStatus->no_bookings_until }}.</p>
            @endif
            <hr>
            <p>
                The most recent registered user is {{ $recentUser->name }} who registered on {{ formatDate($recentUser->created_at) }}.
            </p>
            <hr>
            <p>You have {{ $imageCount }} images in your portfolio, separated into {{ $categoryCount }} categories.</p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5 col-lg-offset-1">
             <div class="list-group">
                <a class="list-group-item" href="/members/admin/pending"><span class="badge badge-info">{{ $pendingCount }}</span>Pending Quotes</a>
                <a class="list-group-item" href="/members/admin/appointments">All Appointments</a>
                <a class="list-group-item" href="/members/admin/booking">Edit Booking Status</a>
                <a class="list-group-item" href="/members/admin/categories">Edit Categories</a>
                <a class="list-group-item" href="/members/admin/images">Edit Portfolio Images</a>
                <a class="list-group-item" href="/members/admin/images/upload">Image Upload</a>
                <a class="list-group-item" href="/members/admin/users">User Accounts</a>
            </div>
        </div>
    </div>
</div>
@endsection