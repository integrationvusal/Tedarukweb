@extends('layouts.main')

@section('title', $title)



@section('content')

<section class="pt60">
    <div class="container">
        <ul class="pgwSlider">

           @foreach($news as $new)
            <li>
                <img src="{{asset('upload/news/originals/'.$new['img'])}}">
                <span>{{ $new['title'] }}</span>
                <a class="big-link-1" href="{{route('news_read', ['slug'=>'media-xeberler', 'read'=>$new['sef']])}}">@lang('custom.more')</a>
                <div class="date-time">
                    <i class="fa fa-clock-o" aria-hidden="true"></i> {{$new['time']}}
                </div>
            </li>
            @endforeach

          
        </ul>
    </div>
</section>

<!-- About -->
<div class="services-container">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 services-title wow fadeIn">
                <h2>@lang('custom.threeblock_title')</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="service wow fadeInUp">
                    <!-- <div class="service-icon"><i class="fa fa-eye"></i></div> -->
                    <h3>@lang('custom.threeblock.one')</h3>
                    <a target="_blank" href="{{ asset('upload/files/1APREL-1.pdf') }}"><img src="{{ asset('upload/images/ab.jpg') }}" alt=""></a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="service wow fadeInDown">
                    <!-- <div class="service-icon"><i class="fa fa-table"></i></div> -->
                    <h3>@lang('custom.threeblock.two')</h3>
                    <a target="_blank" href="http://tedaruk.az"><img src="{{ asset('upload/images/tedaruk_'.$_SESSION['lang'].'.jpg') }}" alt=""></a>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="service wow fadeInDown">
                    <!-- <div class="service-icon"><i class="fa fa-print"></i></div> -->
                    <h3>@lang('custom.threeblock.three')</h3>
                    <a target="_blank" href="{{ route('contact', 'elaqe-muraciet-formasi') }}"><img src="{{ asset('upload/images/qx_'.$_SESSION['lang'].'.jpg') }}" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="services-half-width-container">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 work-title wow fadeIn">
                <h2>@lang('custom.news')</h2>
            </div>

         @foreach($news as $new)
            <div class="col-sm-3 services-half-width-text wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                <div class="main-news-preview">
                    <img height="200" src="{{asset('upload/news/originals/'.$new['img'])}}">
                </div>
                <h3>{{ $new['title'] }}</h3>
                <p>{{ str_limit(strip_tags($new['content']),100,'...') }}</p>
                <a class="big-link-1" href="{{ route('news_read', ['slug'=>'media-xeberler', 'sef'=>$new['sef']]) }}">@lang('custom.more')</a>
            </div>
           @endforeach
           
           
        </div>
    </div>
</div>

<!-- Photogallery 
<div class="work-container">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 work-title wow fadeIn">
                <h2>@lang('custom.gallery')</h2>
            </div>
        </div>
        <div class="row">
            
            @foreach ($gall as $galleries)

            <div class="col-sm-6 col-md-3">
                <div class="work wow fadeInUp">
                    <div style="height: 130px; overflow-y: hidden;">
                        <img src="{{asset('/upload/albums/'.$galleries['name'].'/'.$galleries['photo'])}}">
                    </div>
                    <div class="work-bottom">
                        <a class="big-link-2 view-work" href="{{asset('/upload/albums/'.$galleries['name'].'/'.$galleries['photo'])}}"><i class="fa fa-search"></i></a>
                        <a class="big-link-2" href="{{ route('gallery', 'fotoqalereya') }}"><i class="fa fa-link"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
            
            
           
        </div>
    </div>
</div>
-->

<!-- Testimonials -->
<div class="testimonials-container">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 testimonials-title wow fadeIn">
                <h2>@lang('custom.links')</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 testimonial-list">
                <div role="tabpanel">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tab0">
                            <div class="testimonial-image">
                                <!-- <img src="http://tedaruk.gov.az/static/assets/img/testimonials/1.jpg" alt="" data-at2x="http://tedaruk.gov.az/static/assets/img/testimonials/1.jpg"> -->
                            </div>

                            <div class="testimonial-text">
                                <p>
                                    @foreach( $banners as $banner )

                                    <a href="{{ $banner['link'] }}" target="_blank" title="{{ $banner['link'] }}">
                                        <img src="{{asset('/upload/banners/'.$banner['img'])}}" alt="" width="180">
                                    </a>

                                    @endforeach

                                    
                                </p>
                            </div>

                        </div>
                        <div role="tabpanel" class="tab-pane fade " id="tab1">
                            <div class="testimonial-image">
                                <!-- <img src="http://tedaruk.gov.az/static/assets/img/testimonials/1.jpg" alt="" data-at2x="http://tedaruk.gov.az/static/assets/img/testimonials/1.jpg"> -->
                            </div>
                            <div class="testimonial-text">
                                <p>

                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Nav tabs -->
                    <!--<ul class="nav nav-tabs" role="tablist">
<li role="presentation" class="active">
<a href="#tab0" aria-controls="tab0" role="tab" data-toggle="tab"></a>
</li>
<li role="presentation" >
<a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab"></a>
</li>
</ul>-->
                </div>
            </div>
        </div>
    </div>
</div>


@endsection