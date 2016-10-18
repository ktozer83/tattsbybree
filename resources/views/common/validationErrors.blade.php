@if (count($errors) > 0)
<!-- Form Errors -->
<div class="bg-danger text-danger text-center">
    <strong>Please correct the following errors:</strong>
    <br>
    <ul class="list-unstyled">
        @foreach ($errors->all() as $errors)
        <li >{{ $errors }}</li>
        @endforeach
    </ul>
</div>
@endif