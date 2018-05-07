@extends('layouts.main')

@section('title', $title )

@section('content')
<div class="page-title-container">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 wow fadeIn">
                    <i class="fa fa-home"></i>
                    <h1>{{$title}}</h1>
                </div>
            </div>
        </div>
    </div>


    <section id="leaders">
        <div class="container">
            <!-- <div class="row">
                <div class="col-sm-12 single text-left">
                    <p><span style="color: #ffffff;">d</span></p>
                </div>
            </div> -->
            <div class="row">

                @foreach( $result as $k=>$res)
                    <div class="col-sm-4">
                        <a data-toggle="modal" data-target="#partnerModeal{{ $loop->iteration }}" href="javascript:void(0)">
                            <div class="team-box @if($k!=1) little @endif wow fadeInUp">
								<img src="{{asset(Helper::getImgFromContent($res['content']))}}"/>
                                <h3>{{ $res['name'] }}</h3>
                                <p>{{ $res['position'] }}</p>
                            </div>
                        </a>
                    </div>
                    <div class="modal fade" id="partnerModeal{{ $loop->iteration }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <img src="{{asset('img/gerb.png')}}" alt="">
                                </div>
                                <div class="modal-body">
                                    {!! $res['content'] !!}
                                </div>
                            </div>
                        </div>
                    </div>


                @endforeach
                        
            </div>
        </div>
    </section>
@endsection