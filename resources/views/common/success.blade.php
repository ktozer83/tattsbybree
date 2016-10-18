@if (Session::has('success'))
<div class="alert alert-success text-center alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ Session::get('success') }}
</div>
@endif