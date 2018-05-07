@extends('layouts.main')

@section('title', $title )

@section('content')
    <div class="page-title-container">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 wow fadeIn">
                    <i class="fa fa-camera"></i>
                    <h1>{{$title}}</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- gallery -->
    <div class="portfolio-container">
        <div class="container">
            <!--<div class="row">
                <div class="col-sm-12 portfolio-filters wow fadeInLeft">
                    <a href="#" class="filter-all active">@lang('custom.all_cats')</a>
                        @foreach($categories as $cat)
                            / <a href="#" class="filter-{{ $cat['id'] }}">{{ $cat['name'] }}</a>
                        @endforeach
                                             
                                    </div>
            </div>-->
            <div class="row">
                <div class="col-sm-12 portfolio-masonry">
                    @foreach($videos as $video)
                         <div class="portfolio-box {{ Helper::video_category($video['category']) }}">
                            <div class="portfolio-box-container">
                                <img class="portfolio-video" src="{{ Helper::youtube_img($video['link']) }}" alt="" data-at2x="{{ Helper::youtube_img($video['link']) }}"
                                     data-portfolio-video="{{ Helper::video_link($video['link']) }}">
                                <i class="portfolio-box-icon fa fa-play"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!--div class="row text-center">
        <ul class="pagination pagination-sm">
                    </ul>
    </div-->


@endsection
