@extends('layouts.app')

@section('page_title') Edit Portfolio Categories @endsection

@section('additional_css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/dt-1.10.11/datatables.min.css"/>
@endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    @include('common.validationErrors')
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default dropdown-toggle" href="/members/admin/images" role="button">Portfolio Images</a>
            <a class="btn btn-default" href="javascript:void()" role="button" data-toggle="modal" data-target="#addCategoryModal">Add Category</a>
            <a class="btn btn-default" href="/members" role="button">Home</a>
        </div>
    </div>
    @if (count($categories) < 0)
    <p>No categories found.</p>
    @else
    <table id="categoriesTable" class="table table-hover table-condensed text-center">
        <thead>
            <tr>
                <th>Category</th>
                <th>Hidden</th>
                <th># of Images</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>
                    <a href="/members/admin/images?category={{ $category->id }}">
                        {{ $category->category_name }}
                    </a>
                </td>
                <td>
                    {{ readableEntry($category->hidden) }}
                </td>
                <td>
                    {{ count($category->images) }}
                </td>
                <td>
                    <small>
                        <a data-toggle="modal" data-target="#editCategoryModal" data-category-name="{{ $category->category_name }}" data-category-visibility="{{ $category->hidden }}" href="javascript:void();">Edit</a>
                        <br>
                        <a data-toggle="modal" data-target="#deleteCategoryModal" data-category-name="{{ $category->category_name }}" href="javascript:void();">Delete</a>
                    </small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addCategoryModalLabel">Add New Category</h4>
            </div>
            <div class="modal-body text-center">
                <form method="POST" action="" id="addCategoryForm" class="form-inline">
                    {!! csrf_field() !!}
                    <input type="hidden" name="formName" value="newForm">
                    <div class="form-group">
                        <label for="categoryNameInput">Category Name:</label>
                        <input type="text" class="form-control" id="categoryNameInput" name="categoryName" value="{{ old('categoryName') }}" placeholder="Category Name">
                    </div>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary @if (old('oldVisibility') == 'hidden') active @endif">
                            <input type="radio" name="visibility" value="hidden" autocomplete="off"> Hidden
                        </label>
                        <label class="btn btn-primary @if (old('oldVisibility') == 'no') active @endif">
                            <input type="radio" name="visibility" value="visible" autocomplete="off"> Visible
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" name="addCategorySubmit" id="addCategorySubmit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editCategoryModalLabel">Edit Category</h4>
            </div>
            <div class="modal-body text-center">
                <form method="POST" action="" id="editCategoryForm" class="form-inline">
                    {!! csrf_field() !!}
                    <input type="hidden" name="formName" value="editForm">
                    <input type="hidden" id="originalCategoryName" name="originalCategoryName" value="">
                    <div class="form-group">
                        <label for="newCategoryName" class="control-label">Category Name:</label>
                        <input type="text" class="form-control" id="newCategoryName" name="newCategoryName">
                    </div>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary @if (old('newVisibility') == 'hidden') active @endif">
                            <input type="radio" id="hiddenRadio" name="editVisibility" value="hidden" autocomplete="off"> Hidden
                        </label>
                        <label class="btn btn-primary @if (old('newVisibility') == 'no') active @endif">
                            <input type="radio" id="visibleRadio" name="editVisibility" value="visible" autocomplete="off"> Visible
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="editCategorySubmit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deleteModalLabel">Delete Category Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete '<span id="delete-span"></span>'?</p>
                <p class="text-info">Any photos in this category will be moved to uncategorized.</p>
                <form method="POST" action="/members/admin/categories/delete" id="deleteCategoryForm">
                    {!! csrf_field() !!}
                    <input type="hidden" id="deleteCategoryName" name="deleteCategoryName" value="">
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
<script type="text/javascript" src="https://cdn.datatables.net/t/bs/dt-1.10.11/datatables.min.js"></script>
<script type="text/javascript">

    $(document).ready(function(){
        $('#categoriesTable').DataTable( {
            searching: false
        });
    });

    $('#addCategorySubmit').click(function () {
        $('#addCategoryForm').submit();
    });

    $('#editCategorySubmit').click(function () {
        $('#editCategoryForm').submit();
    });

    $('#deleteConfirmSubmit').click(function () {
        $('#deleteCategoryForm').submit();
    });

    $('#editCategoryModal').on('show.bs.modal', function (event) {
        var link = $(event.relatedTarget);
        var categoryName = link.data('category-name');
        var visibility = link.data('category-visibility');

        var modal = $(this);
        modal.find('#newCategoryName').val(categoryName);
        modal.find('#originalCategoryName').val(categoryName);
        if (visibility == '1') {
            var radio = document.getElementById('hiddenRadio');
            (radio).checked = true;
            $(radio).parent('label').addClass('active');

        } else if (visibility == '0') {
            var radio = document.getElementById('visibleRadio');
            (radio).checked = true;
            $(radio).parent('label').addClass('active');
        }
    });

    // clear all 'active' classes
    $('#editCategoryModal').on('hide.bs.modal', function () {
        $("input:radio").each(function () {
            $(this).parent('label').removeClass('active');
        });
    });

    $('#deleteCategoryModal').on('show.bs.modal', function (event) {
        var link = $(event.relatedTarget);
        var categoryName = link.data('category-name');

        var modal = $(this);
        modal.find('#delete-span').append(categoryName);
        modal.find('#deleteCategoryName').val(categoryName);
    });

</script>
@endsection