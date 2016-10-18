@extends('layouts.app')

@section('page_title') Home @endsection

@section('additional_css')
<link href="css/slider/ideal-image-slider.min.css" rel='stylesheet' type='text/css'>
<link href="css/slider/themes/custom.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('content')
<div class="panel panel-default featured-panel">
    <div id="imgSlider" class="panel-body text-center">
        @foreach($featuredImages as $image)
        <img class="featured-image" src="/img/portfolio/{{ $image->filename }}" @if (!empty($image->image_title)) title="{{ $image->image_title }}" @endif @if (!empty($image->image_caption)) alt="{{ $image->image_caption }}"@endif>
        @endforeach
    </div>
</div>


<div class="row homeContent">
    <div id="introText" class="col-xs-6 col-sm-6 col-md-7 col-lg-8">
        <p class="lead text-center">Welcome to the online portfolio for Tatts by Bree! If you're interested in <a href="/portfolio">her work</a> or looking to get <a href="contact">in contact</a> with Bree then you've come to the right place. Feel free to create an account and get a <a href="/members/quote">quote</a> if you're interested in getting work done!</p>
        <p class="text-center">
            {{ $bookingStatus->message }}
        </p>
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 text-center">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="ctba-text sverige">Custom Tattooing by Appointment</h3>
                    </div>
                </div>
                <div class="row">
                    <h1 class="get-quote-text">
                        @if ($bookingStatus->can_book == '1')
                        <a href="/members/quote">Request Your Free Quote Now</a>
                        @else 
                        <a href="/members/quote">Get on Bree's Waiting List</a> @endif
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div id="instafeed" class="col-xs-6 col-md-5 col-lg-4">
        <h3 class="text-center"><img id="instagramLogo" src="img/inst_logo.png"><strong>Newest Instagram Pictures</strong></h3>
    </div>
</div>
@endsection

@section('additional_js')
<script type="text/javascript" src="js/instafeed.min.js"></script>
<script src="js/slider/ideal-image-slider.min.js"></script>
<script src="js/slider/extensions/iis-captions.js"></script>
<script src="js/slider/extensions/iis-bullet-nav.js"></script>
<script type="text/javascript">
    // instagram feed
    var userFeed = new Instafeed({
        get: 'user',
        userId: '232310854',
        accessToken: env('INSTA_ACCESS_TOKEN');
        limit: '9',
        template: '<a target="_blank" href="~~link~~"><img src="~~image~~" /></a>'
    });
    userFeed.run();
    // image slider
    var slider = new IdealImageSlider.Slider({
        selector: '#imgSlider',
        height: '4:3',
        interval: 5000,
        effect: 'slide'
    });
    slider.addBulletNav();
    slider.addCaptions();
    slider.start();
</script>
@endsection