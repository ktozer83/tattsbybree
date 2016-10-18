@extends('layouts.app')

@section('page_title') View Details @endsection

@section('additional_css')
<link href="/css/lightbox.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    @if (Auth::user()->account_type_id <= '2')
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default" href="{{ $quote->id }}/edit" role="button">Edit</a>
            <a class="btn btn-default" role="button" data-toggle="modal" data-target="#deleteQuoteModal" data-quote-id="{{ $quote->id }}" href="javascript:void();">Delete</a>
            <a class="btn btn-default" href="/members" role="button">Home</a>
        </div>
    </div>
    @endif
    <div class="row">
        @if ($quote->appointment_status_id == '1')
        <div class="col-xs-8 col-sm-8 col-md-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2">
        @else
        <div class="col-sm-6">
        @endif
                <h4 class="text-center"><u>Details</u></h4>
                @if ((Auth::user()->account_type_id == '1') || (Auth::user()->account_type_id == '2'))
                <p class="text-center"><strong>Client Name:</strong> {{ $quote->client_name }}</p>
                <p class="text-center"><strong>Email:</strong> {{ $quote->email }}</p>            
                @endif
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
        @if ($quote->appointment_status_id != '1')
        </div>
        <div id="appointmentDetails" class="col-sm-6">
                <h4 class="text-center"><u>Appointment Details</u></h4>
                <p class="text-center"><strong>Quote Price:</strong> ${{ ($quote->quote_price) }}</p>
                <p class="text-center"><strong>Consultation Needed:</strong> {{ readableEntry($quote->consultation_needed) }}@if ($quote->consultation_needed == '1') <a href="javscript:void()" id="consultTooltip" data-toggle="tooltip" data-placement="right" title="If a consultation is needed, you will need to sit with Bree to discuss furthur details of your tattoo."><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a> @endif</p>
                @if (!empty($quote->consultation_date))
                <p class="text-center"><strong>Consultation Date:</strong> {{ formatDate($quote->consultation_date . " " . $quote->consultation_time) }}</p>
                <p class="text-center"><strong>Consultation Confirmed:</strong> {{ readableEntry($quote->consultation_confirmed) }}</p>
                @endif
                <p class="text-center"><strong>Appointment Made:</strong> {{ readableEntry($quote->appointment_made) }}</p>
                @if ($quote->appointment_made == '1')
                <p class="text-center"><strong>Appointment Date:</strong> {{ formatDate($quote->appointment_date . " " . $quote->appointment_time) }}</p>
                @endif
                @if (isset($quote->down_payment_cost))
                <p class="text-center"><strong>Down Payment Amount:</strong> ${{ ($quote->down_payment_cost) }}</p>
                <p class="text-center"><strong>Down Payment Paid:</strong> {{ readableEntry($quote->down_payment_paid) }}</p>
                @endif
        </div>
            @endif
            
        @if ($quote->appointment_status_id == '1')
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-xs-8 col-sm-8 col-md-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2">
            <span class="line-break text-center"><img src="/img/linebr.png"></span>
            @if (count($quote->comments) > 0)
            <div class="quote-comments">
                <h4 class="text-center"><u>Comments</u></h4>
                @foreach($quote->comments as $comment)
                <blockquote>
                    <p>
                        {{ $comment->comment }}
                        <span class="text-right"><small>Posted by {{ $comment->user_name }} on {{ formatDate($comment->created_at) }}</small></span>
                    </p>
                </blockquote>
                @endforeach
            </div>
            @endif
            <form method="POST" action="">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="commentInput">Add a Comment:</label>
                    <textarea class="form-control" id="commentInput" name="comment" rows="3" placeholder="Your comments">{{ old('comments') }}</textarea>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-default">Send</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div id="quote-user-images" class="col-xs-8 col-sm-8 col-md-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2">
            <span class="line-break text-center"><img src="/img/linebr.png"></span>
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
</div>

@if ((Auth::user()->account_type_id == '1') || (Auth::user()->account_type_id == '1'))
<!-- Delete Modal -->
<div class="modal fade" id="deleteQuoteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deleteModalLabel">Delete Quote Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this quote?</p>
                <p class="text-danger">The person who made this quote will <strong><u>not</u></strong> be notified if their quote has been deleted.</p>
                <form method="POST" action="/members/admin/quote/delete" id="deleteQuoteForm">
                    {!! csrf_field() !!}
                    <input type="hidden" id="deleteQuoteId" name="deleteQuoteId" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="deleteConfirmSubmit" class="btn btn-primary">Delete</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('additional_js')
<script type="text/javascript" src="/js/lightbox.js"></script>
<script type="text/javascript">
lightbox.option({
'wrapAround': true
});
$(function() {
    $("#consultTooltip").tooltip();
});
@if ((Auth::user()->account_type_id == '1') || (Auth::user()->account_type_id == '1'))
$('#deleteConfirmSubmit').click(function () {
   $('#deleteQuoteForm').submit();
});

$('#deleteQuoteModal').on('show.bs.modal', function (event) {
    var link = $(event.relatedTarget);
    var quoteId = link.data('quote-id');

    var modal = $(this);
    modal.find('#deleteQuoteId').val(quoteId);
});
@endif
</script>
@endsection