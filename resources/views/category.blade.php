@extends('layouts.main')

@section('title', $title )

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


    <section 
    @if($title == 'QANUNVERCİLİK')
  
     id="law"

    @else

       id="leaders"

    @endif
    >
        <div class="container">
            <div class="row">
                <div class="col-sm-12 single
  @if($title == 'QANUNVERCİLİK')
     
  @else
  
  text-left

  @endif   

                ">
                     {!! $content !!}
                </div>
            </div>
                    </div>
    </section>


@endsection