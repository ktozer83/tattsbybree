@if (Session::has('error'))
<div class="alert alert-danger text-center alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    @if (Session::get('error') === 'badToken')
        @include('common.badToken')
    @else
        {{ Session::get('error') }}
    @endif
</div>
@endif