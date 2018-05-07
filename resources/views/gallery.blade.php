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
            
            <div class="row">
                <div class="col-sm-12 portfolio-masonry">
                       

                       @foreach($galleries as $name=>$gallery)
                            @foreach ( $gallery as $gall)

                           <div class="portfolio-box tedbir">
                                <div class="portfolio-box-container">
                                    <img src="{{asset('upload/albums/'.$name.'/'.$gall)}}" alt="" data-at2x="{{asset('upload/albums/'.$name.'/'.$gall)}}">
                                    <!-- <div class="portfolio-box-text">
                                        <h3>Lorem Website</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.</p>
                                    </div> -->
                                </div>
                            </div>


                            @endforeach
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