@extends('layouts.main')

@section('title', $title )

@section('search', $search_text )

@section('content')

    <div class="page-title-container">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 wow fadeIn">
                    <i class="fa fa-home"></i>
                    <h1>{{ $title }}</h1>
                </div>
            </div>
        </div>
    </div>


    <section>
        <div class="container">
            <div class="row">
				<div class="col-sm-12 single">
					<ul class="search-result">
						@foreach($results as $k=>$res)
							<li>
								<a href="@if($res['is_news']) {{route('news_read', ['read'=>$res['sef'], 'slug'=>'media-xeberler' ])}} @else {{route('category', $res['sef'])}} @endif">
									{{$k+1}}) 
									{!!$res['str']!!}
								</a>
							</li>
						@endforeach
					</ul>
				</div>
            </div>
        </div>
    </section>


@endsection
