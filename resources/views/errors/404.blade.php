@extends('layouts.main')

@section('title', trans('custom.not_found'))

@section('content')

    <section class="single padding50">
        <div class="container">
            <div class="row">       

                <div class="col-md-12">
                    <img class="center-block" src="{{asset('img/404.png')}}"/>
                    <div class="main-title">
                        <span class="main-error">@lang('custom.not_found')</span>
                    </div>
                    <div class="clear"></div>              
                </div>

            </div>
        </div>
    </section>

@endsection