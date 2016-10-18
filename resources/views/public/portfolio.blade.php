@extends('layouts.app')

@section('page_title') Portfolio @if (isset($category)) - {{ $category->category_name }} @endif @endsection

@section('additional_css')
<link href="/css/lightbox.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    @if (count($images) == 0)
    <p class="text-center">No images found.</p>
    @else
    @if (isset($category))    
    <a href="/portfolio" class="btn btn-default center-block" role="button">Back</a>
    <div class="grid">
        <div class="grid-sizer"></div>
        @foreach ($images as $image)
        <div class="grid-item">
            <div class="panel panel-default imagePanel">
                <div class="panel-body">
                    <a href="/img/portfolio/{{ $image->filename }}" data-lightbox="user-images"><img src="/img/portfolio/{{ $image->filename }}"></a>
                </div>
                @if (($image->image_title) || ($image->image_caption))
                <div class="panel-footer">
                    @if ($image->image_title) <h4>{{ $image->image_title }}</h4> @endif
                    @if ($image->image_caption) <p>{{ $image->image_caption }}</p> @endif
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="grid">
        <div class="category-grid-sizer"></div>
        @for ($i = 0; $i <= (count($categories) - 1); $i++)
        <div class="category-grid-item">
            <div class="panel panel-default cover-image">
                <div class="panel-heading category-heading">
                    <h3>{{ $categories[$i]->category_name }}</h3>
                </div>
                <div class="panel-body">
                    @foreach($images as $image)
                    @if ($image->category->category_name == $categories[$i]->category_name)
                    <a href="/portfolio?category={{ $image->category->id }}">
                        <img src="/img/portfolio/{{ $image->filename }}">
                    <a class="cover-link" href="/portfolio?category={{ $image->category->id }}">View All</a>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endfor
    </div>
    @endif
    @endif
</div>
@endsection

@section('additional_js')
<script type="text/javascript" src="/js/lightbox.js"></script>
<script src="/js/masonry.pkgd.min.js" type="text/javascript"></script>
<script src="/js/imagesloaded.pkgd.min.js"></script>
<script type="text/javascript">
    @if (isset($category))
    var $grid = $('.grid').masonry({
        // options
        columnWidth: '.grid-sizer',
        itemSelector: '.grid-item',
        gutter: 0,
        fitWidth: true
    });
    @else
    var $grid = $('.grid').masonry({
        // options
        columnWidth: '.category-grid-sizer',
        itemSelector: '.category-grid-item',
        percentPosition: true
    });
    @endif
    
    $grid.imagesLoaded().progress( function() {
        $grid.masonry('layout');
    });
    
    lightbox.option({
        'wrapAround': true
    });
</script>
@endsection