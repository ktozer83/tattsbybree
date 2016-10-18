@extends('layouts.app')

@section('page_title') Admin - Image Upload @endsection

@section('content')
<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xs-offset-2 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default dropdown-toggle" href="/members/admin/images" role="button">All Images</a>
            <a class="btn btn-default" href="/members/admin/categories" role="button">Edit Categories</a>
            <a class="btn btn-default" href="/members" role="button">Home</a>
        </div>
    </div>
    @include('common.validationErrors')
    <p class="text-center">Images will be visible by default and will not be a featured or cover photo. Once uploaded you will be able to edit image details.</p>
    <form method="POST" action="" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="categorySelect">Category:</label>
            <select name="category" class="form-control text-center">
                @foreach($categories as $category)
                <option id="categorySelect" value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="imageFile">Images:</label>
            <input type="file" name="images[]" multiple="true" id="imageFile" class="form-control">
            <p class="help-block text-center">You are able to upload more than one image, but images must be less than 1MB.</p>
        </div>        
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </form>    
</div>
@endsection