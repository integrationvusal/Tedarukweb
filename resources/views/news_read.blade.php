@extends('layouts.main')

@section('title', $title )

@section('content')

     <section class="single padding50">
        <div class="container">
            <div class="row">               
                <div class="col-md-8">
                    <div class="main-title mb20">
                        <span>{{$title}}</span>
                    </div>
                    <div class="clear"></div>            
                    <div class="date mb20">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>{{$time}}
                        <div class="pull-right"><i class="fa fa-eye" aria-hidden="true"></i>{{$counter}}</div>
                    </div>
                    {!! $content !!}
                    <hr>
                    <a href="#" class="fa sosial-bg fa-facebook"></a>
                    <a href="#" class="fa sosial-bg fa-twitter"></a>
                    <a href="#" class="fa sosial-bg fa-linkedin"></a>
                    <a href="#" class="fa sosial-bg fa-google-plus"></a>
                    <a href="#" class="fa sosial-bg fa-whatsapp"></a>

                    <div id="share"></div>
                </div>
                <div class="col-md-4">
                    
                    <div class="other-news">@lang('custom.other_news')</div>
                
                    <div class="clear"></div>   
                    @foreach($news as $new)
                        <div class="media">
                            <div class="media-left media-top">
                                <a href="{{route($new['type'], $new['sef'])}}"><img class="media-object" src="{{asset('upload/news/thumbs/'.$new['img'])}}" class="img-responsive img-rounded" alt="{{$new['title']}}">
                                </a>
                            </div>
                            <div class="media-body">
                                <a href="{{route($new['type'], $new['sef'])}}"><p class="media-heading text-bold">{{$new['title']}}</p></a>
                                <div class="date mb20"><i class="fa fa-clock-o" aria-hidden="true"></i>{{$new['time']}}</div>              
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    
    
@endsection