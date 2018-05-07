@extends('layouts.main')

@section('title', $title )

@section('content')

<div class="page-title-container" data-menu="media">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 wow fadeIn">
                <i class="fa fa-newspaper-o"></i>
                <h1>{{$title}}</h1>
            </div>
        </div>
    </div>
</div>
<section id="news">
    <div class="container">
        <div class="row">
            <div class="col-md-4 wow fadeIn">
                <ul class="list-unstyled news-list">

                    @foreach( $news as $new)
                
                    <li>
                        <p class="text-left">{{ $new['time'] }}</p>
						@if($slug == 'media-bizden-yazirlar') 
							<img width="100" class="to-left margin" src="{{asset('upload/news/originals/'.$new['img'])}}"/>
						@endif
                        <h4 class="text-left">{{ $new['title'] }}</h4>
                        <a class="big-link-1 big-link-1-active" href="{{route('news_read',['slug'=>$slug,'read'=>$new['sef']])}}">@lang('custom.more')</a>
                    </li>

                    @endforeach
                   
                </ul>

                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm">
                        <li {{$total}} ><a @if($page-1>0) href="{{ route('news_view', ['slug'=>$slug, 'page'=>$page-1]) }}" @else class="disabled" @endif>&laquo;</a></li>
                        @for($i=($page>2?$page-2:1); $i<=($page+2>$total?$total:$page+2); $i++)
                            <li @if($i == $page) class="active" @endif><a href="{{ route('news_view', ['slug'=>$slug, 'page'=>$i]) }}">{{$i}}</a></li>
                        @endfor
                        <li><a @if($page+1<$total) href="{{ route('news_view', ['slug'=>$slug, 'page'=>$page+1]) }}" @else class="disabled" @endif>&raquo;</a></li>
                    </ul>
                </nav>
            </div>

            <div class="col-md-8 wow fadeIn news-info">
                <p class="text-left">{{$read['time']}}</p>
                <h3 class="text-left">{{$read['title']}} </h3>
                <img src="{{asset('upload/news/originals/'.$read['img'])}}">
                <p>{!!$read['content']!!}</p>
            </div>

        </div>
    </div>
</section>


@endsection