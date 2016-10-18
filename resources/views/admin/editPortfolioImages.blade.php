@extends('layouts.app')

@section('page_title') Edit Portfolio Images @if (isset($category_name)) - {{ $category_name }} @endif @endsection

@section('additional_css')
<link href="/css/lightbox.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/dt-1.10.11/datatables.min.css"/>
@endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    @include('common.validationErrors')
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories <span class="caret"></span></a>
            <a class="btn btn-default" href="/members/admin/images" role="button">All Images</a>
            <a class="btn btn-default" href="/members/admin/images/upload" role="button">Image Upload</a>
            <a class="btn btn-default" href="/members" role="button">Home</a>
            <ul class="dropdown-menu">
                @foreach($categories as $category)
                <li><a href="?category={{ $category->id }}">{{ $category->category_name }}</a></li>
                @endforeach
                <li role="separator" class="divider"></li>
                <li><a href="/members/admin/categories">Edit Categories</a></li>
            </ul>
        </div>
    </div>
    @if (isset($images))
    @if (count($images) == 0)
    <p class="text-center">No images found</p>
    @else
    <p class="bg-info text-info text-center">Make sure a cover image is set for each category! If one is not set, the area will appear blank on your portfolio page!</p>
    <table id="imagesTable" class="table table-hover table-condensed text-center">
        <thead>
            <tr>
                <th>Image</th>
                <th>Featured</th>
                <th>Category</th>
                <th>Cover</th>
                <th>Hidden</th>
                <th>Title</th>
                <th>Caption</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($images as $img)
            <tr>
                <td>
                    <a href="/img/portfolio/{{ $img->filename }}" data-lightbox="portfolio-images">
                        <img class="portfolio-images" src="/img/portfolio/{{ $img->filename }}">
                    </a>
                </td>
                <td>
                    {{ readableEntry($img->featured) }}
                </td>
                <td>
                    {{ $img->category->category_name }}
                </td>
                <td>
                    {{ readableEntry($img->cover) }}
                </td>
                <td>
                    {{ readableEntry($img->hidden) }}
                </td>
                <td>
                    {{ $img->image_title or 'None' }}
                </td>
                <td>
                    {{ $img->image_caption or 'None' }}
                </td>
                <td>
                    <small>
                        <a data-toggle="modal" data-target="#editImageModal" href="javascript:void()" data-filename="{{ $img->filename }}" data-image-id="{{ $img->id }}" data-featured="{{ $img->featured }}" data-category="{{ $img->category_id }}" data-cover="{{ $img->cover }}" data-hidden="{{ $img->hidden }}" data-image-title="{{ $img->image_title }}" data-image-caption="{{ $img->image_caption }}">Edit</a>
                        <br>
                        <a data-toggle="modal" data-target="#deleteImageModal" data-image-name="{{ $img->filename }}" data-image-id="{{ $img->id }}" href="javascript:void();">Delete</a>
                    </small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endif
</div>

<!-- Edit Image Modal -->
<div class="modal fade" id="editImageModal" tabindex="-1" role="dialog" aria-labelledby="editImageModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editImageModalLabel">Edit Image Details</h4>
            </div>
            <div class="modal-body text-center">
                <img src="" id="editingImg" class="edited-image">
                <form method="POST" action="" id="editImageForm">
                    {!! csrf_field() !!}
                    <input type="hidden" id="imageId" name="imageId" value="">
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" class="form-control text-center">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <label>Featured Image:</label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary">
                            <input type="radio" id="featuredRadio" name="editFeatured" value="1" autocomplete="off"> Yes
                        </label>
                        <label class="btn btn-primary">
                            <input type="radio" id="notFeaturedRadio" name="editFeatured" value="0" autocomplete="off"> No
                        </label>
                    </div>
                    <p class="help-block">If yes, this image will appear on the front page.</p>
                    <hr>
                    <label>Cover Image:</label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary">
                            <input type="radio" id="coverRadio" name="editCover" value="1" autocomplete="off"> Yes
                        </label>
                        <label class="btn btn-primary">
                            <input type="radio" id="notCoverRadio" name="editCover" value="0" autocomplete="off"> No
                        </label>
                    </div>
                    <p class="help-block">If there is a cover image already set for this category, choosing yes will replace it.</p>

                    <hr>
                    <label>Hidden:</label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary">
                            <input type="radio" id="hiddenRadio" name="editHidden" value="1" autocomplete="off"> Yes
                        </label>
                        <label class="btn btn-primary">
                            <input type="radio" id="visibleRadio" name="editHidden" value="0" autocomplete="off"> No
                        </label>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label for="titleInput">Image Title:</label>
                        <input type="text" class="form-control" id="titleInput" name="image_title" value="" placeholder="Image Title">
                        <p class="help-block text-center">Optional</p>
                    </div>
                    <hr>                    
                    <div class="form-group">
                        <label for="captionInput">Image Caption:</label>
                        <textarea class="form-control" id="captionInput" name="image_caption" rows="3" placeholder="Image Caption"></textarea>
                        <p class="help-block text-center">Optional</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="editImageSubmit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteImageModal" tabindex="-1" role="dialog" aria-labelledby="deleteImageLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deleteImageLabel">Delete Image Confirmation</h4>
            </div>
            <div class="modal-body">
                <img class="edited-image" id="deletingImage" src="">
                <p>Are you sure you want to delete the above image?</p>
                <p class="text-danger">You cannot undo this! This image will need to be re-uploaded if you want to use it again.</p>
                <form method="POST" action="/members/admin/images/delete" id="deleteImageForm">
                    {!! csrf_field() !!}
                    <input type="hidden" id="deleteImageId" name="deleteImageId" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="deleteConfirmSubmit" class="btn btn-primary">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_js')
<script type="text/javascript" src="/js/lightbox.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/t/bs/dt-1.10.11/datatables.min.js"></script>
<script type="text/javascript">
    
$(document).ready(function(){
    $('#imagesTable').DataTable( {
        searching: false
    });
});

$('#editImageSubmit').click(function () {
    $('#editImageForm').submit();
});

$('#deleteConfirmSubmit').click(function() {
    $('#deleteImageForm').submit();
});

$('#editImageModal').on('show.bs.modal', function (event) {
    var link = $(event.relatedTarget);
    var imageId = link.data('image-id');
    var filename = link.data('filename');
    var category = link.data('category');
    var featured = link.data('featured');
    var cover = link.data('cover');
    var hidden = link.data('hidden');
    var imageTitle = link.data('image-title');
    var imageCaption = link.data('image-caption');
    
    var modal = $(this);
    modal.find('#imageId').val(imageId);
    modal.find('#editingImg').attr("src", '/img/portfolio/' + filename);
    modal.find('#category').val(category);
    // featured image
    if (featured == '1') {
        var radio = document.getElementById('featuredRadio');
        (radio).checked = true;
        $(radio).parent('label').addClass('active');

    } else if (featured == '0') {
        var radio = document.getElementById('notFeaturedRadio');
        (radio).checked = true;
        $(radio).parent('label').addClass('active');
    }
    // cover image
    if (cover == '1') {
        var radio = document.getElementById('coverRadio');
        (radio).checked = true;
        $(radio).parent('label').addClass('active');

    } else if (cover == '0') {
        var radio = document.getElementById('notCoverRadio');
        (radio).checked = true;
        $(radio).parent('label').addClass('active');
    }
    // hidden image
    if (hidden == '1') {
        var radio = document.getElementById('hiddenRadio');
        (radio).checked = true;
        $(radio).parent('label').addClass('active');

    } else if (hidden == '0') {
        var radio = document.getElementById('visibleRadio');
        (radio).checked = true;
        $(radio).parent('label').addClass('active');
    }
    modal.find('#titleInput').val(imageTitle);
    modal.find('#captionInput').val(imageCaption);
});
// clear all 'active' classes
$('#editImageModal').on('hide.bs.modal', function () {
    $( "input:radio" ).each(function() {
        $(this).parent('label').removeClass('active');
    });
});

$('#deleteImageModal').on('show.bs.modal', function (event) {
    var link = $(event.relatedTarget);
    var imageId = link.data('image-id');
    var filename = link.data('image-name');
    
    var modal = $(this);
    modal.find('#deleteImageId').val(imageId);
    modal.find('#deletingImage').attr("src", '/img/portfolio/' + filename);
});
</script>
@endsection