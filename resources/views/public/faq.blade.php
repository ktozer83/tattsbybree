@extends('layouts.app')

@section('page_title') Frequently Asked Questions @endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    <ul class="list-unstyled">
        <li><a href="#q1">Does Bree take walk-ins?</a></li>
        <li><a href="#q2">What is Bree’s waiting list?</a></li>
        <li><a href="#q3">What is the shop rate?</a></li>
        <li><a href="#q4">How do I book my appointment?</a></li>
        <li><a href="#q5">How much of a deposit do I require?</a></li>
        <li><a href="#q6">Will I lose my deposit if I need to reschedule my appointment?</a></li>
        <li><a href="#q7">When will I get to see my design?</a></li>
        <li><a href="#q8">What do I need to do once the tattoo is finished?</a></li>
    </ul>
    <span class="line-break text-center"><img src="/img/linebr.png"></span>
    <a name="q1"></a>
    <blockquote>
        <p>Does Bree take walk-ins?</p>
    </blockquote>
    <p>No, all bookings are by appointment only.</p>
    <span class="line-break text-center"><img src="/img/linebr.png"></span>
    <a name="q2"></a>
    <blockquote>
        <p>What is Bree’s waiting list?</p>
    </blockquote>
    @if ($bookingStatus->can_book == '1')
    <p>Bree currently has {{ $bookingStatus->slots_available }} spaces available for appointments.</p>
    @else
    <p>Bookings are currently closed. Bree will begin booking again on {{ $bookingStatus->no_bookings_until }}.</p>
    @endif
    <span class="line-break text-center"><img src="/img/linebr.png"></span>
    <a name="q3"></a>
    <blockquote>
        <p>What is the shop rate?</p>
    </blockquote>
    <p>Pricing varies depending on the project. The shop rate is $100 an hour, with a full day booking (11:00am-7:00pm) at $500. Pieces taking under an hour will be charged by the piece depending on size and detail. </p>
    <span class="line-break text-center"><img src="/img/linebr.png"></span>
    <a name="q4"></a>
    <blockquote>
        <p>How do I book my appointment?</p>
    </blockquote>
    <p>To book your appointment you can call (705)-874-1520, <a href="mailto:inquires@tattsbybree.com">email</a>, or <a href="/members/quote">request a free quote</a>. If you haven't already, you will need to create an account before you can request a quote. Please be very thorough when describing your ideas, body placement, and size and be sure to provide at least one photo reference of the design you are looking to get done.</p>
    <span class="line-break text-center"><img src="/img/linebr.png"></span>
    <a name="q5"></a>
    <blockquote>
        <p>How much of a deposit do I require?</p>
    </blockquote>
    <p>For bookings over an hour there is a minimum of a $50 deposit required and a minimum of $100 for a full day booking. Deposits are <u>non-refundable</u> and will be put towards the cost of your tattoo on your booked appointment date.</p>
    <p>Exceptions may apply in times of serious illness or pregnancy.</p>
    <span class="line-break text-center"><img src="/img/linebr.png"></span>
    <a name="q6"></a>
    <blockquote>
        <p>Will I lose my deposit if I need to reschedule my appointment?</p>
    </blockquote>
    <p>No, your deposit will be transferred over and used on your new appointment date. Please give a <u>minimum</u> of 24 hours notice when rescheduling your appointment.</p>
    <span class="line-break text-center"><img src="/img/linebr.png"></span>
    <a name="q7"></a>
    <blockquote>
        <p>When will I get to see my design?</p>
    </blockquote>
    <p>All drawings will be done for the day of your appointment or drawn directly on the skin.</p>    <span class="line-break text-center"><img src="/img/linebr.png"></span>
    <a name="q8"></a>
    <blockquote>
        <p>What do I need to do once the tattoo is finished?</p>
    </blockquote>
    <p>We provide an aftercare sheet which can be found <a href="/pdf/aftercare.pdf" target="_blank">here</a>.</p>
    <p class="text-right"><small><a href="#">Top of Page</a></small></p>
</div>
@endsection