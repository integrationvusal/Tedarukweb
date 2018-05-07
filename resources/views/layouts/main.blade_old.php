<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ $settings['site_name'] }} - @yield('title')</title>

		<!-- CSS -->
		<link rel="stylesheet" href="{{asset('js/jquery-3.1.1.min.js')}}http://fonts.googleapis.com/css?family=Open+Sans:400italic,400">
		<link rel="stylesheet" href="http://fonts.goo{{asset('css/animate.css')}}gleapis.com/css?family=Droid+Sans">
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster">
		<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
		<link rel="stylesheet" href="">
		<link rel="stylesheet" href="{{asset('css/magnific-popup.css')}}">
		<link rel="stylesheet" href="{{asset('css/flexslider.css')}}">
		<link rel="stylesheet" href="{{asset('css/form-elements.css')}}">
		<link rel="stylesheet" href="{{asset('css/style.css')}}">
		<link rel="stylesheet" href="{{asset('css/tedaruk.css')}}">
		<link rel="stylesheet" href="{{asset('css/pgwslider.css')}}">

		<link rel="stylesheet" href="{{asset('css/media-queries.css')}}">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">												

	</head>

	<body>

		<div class="headtop">
			<div class="topbar">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-2">
						<div class="left-text">
						<h3>@lang('custom.slogan')</h3>
						</div>							
						</div>

						<div class="col-md-2 logo">
							<div class="text-center">
								<a href="/"><img src="{{asset('img/logo5.png')}}" alt=""></a>
							</div>	
							<div class="name">
								@lang('custom.main_slogan')
							</div>
						</div>

						<div class="col-md-8">
							<div class="right-text">
								@lang('custom.president_slogan')
							</div>						
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<table class="table-condensed pull-right">
								<tr>
									<td>
										<div class="sosial-icons">
											<a href="{{ $settings['facebook'] }}" target="_blank" class="fa fa-facebook"></a>
											<a href="{{ $settings['instagram'] }}" target="_blank" class="fa fa-instagram"></a>
											<a href="{{ $settings['twitter'] }}" target="_blank" class="fa fa-twitter"></a>
										</div>
									</td>
									<td>
										<ul class="lang list-inline">
											@foreach(trans('custom.langs') as $lang=>$k)
												<li><a href="{{'/'.$lang.'/'.$baseurl}}" @if($_SESSION['lang'] == $lang) class="active" @endif><img src="{{asset('img/lang_'.$lang.'.png')}}" alt="">@lang('custom.langs.'.$lang)</a></li>
												<!--<li><a @if(session('lang') == $lang) class="active" @endif><img src="{{asset('img/lang_'.$lang.'.png')}}" alt="">@lang('custom.langs.'.$lang)</a></li>-->
											@endforeach
										</ul>
									</td>
									<td>
										<div class="input-group">
											<input type="text" class="form-control" placeholder="@lang('custom.search')">
											<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
											</span>
										</div>
									</td>
								</tr>	
							</table>
						</div>
					</div>
				</div>
			</div>

		<!-- Top menu -->
			<nav class="navbar navbar-static-top" role="navigation" id="header">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
							<span class="sr-only"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

					</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="top-navbar-1">
					@widget('MenuWidget', ['class'=>'nav navbar-nav navbar-right', 'submenu'=>true, 'brand'=>false])
				</div>
				</div>
			</nav>
		</div>

		@yield('content')


<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-5 footer-box wow fadeInUp">
                <div class="footer-box-text">
                    <p><iframe width="100%" height="170" style="border: 0;" src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d5114.269784686163!2d49.784638023655255!3d40.3431666075414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x40307e585c7e1597%3A0xefc4882da4b3953f!2zxo9yemFxIG3JmWhzdWxsYXLEsW7EsW4gdMmZZGFyw7xrw7wgdsmZIHTJmWNoaXphdMSxIEFTQywgxZ_JmWguLCBRYXJhZGHEnyByYXlvbnUgQVoxMDYzLCBxyZlzLCBYb2Nhc8mZbiB5b2x1IDU3LCBMb2tiYXRhbiwg0JDQt9C10YDQsdCw0LnQtNC20LDQvQ!3m2!1d40.342844199999995!2d49.7847466!5e0!3m2!1sru!2s!4v1482571345421" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>
                </div>
            </div>

            <div class="col-md-7 footer-box wow fadeInDown">
                <div class="footer-box-text footer-box-text-contact">
                    <p><i class="fa fa-home"></i>@lang('custom.name_company')</p>
					<p><i class="fa fa-map-marker"></i>@lang('custom.address')</p>
					<p><i class="fa fa-phone"></i>{{ $settings['telefon']}}</p>
					<p><i class="fa fa-phone-square"></i> @lang('custom.call_center'):{{ $settings['call_center'] }} </p>
					<p><i class="fa fa-envelope"></i>{{ $settings['email'] }}</p>
					<p><i class="fa fa-globe"></i>{{ $settings['site_adress'] }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 wow fadeIn">
                <div class="footer-border"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 footer-copyright wow fadeIn">
                <p class="text-center">© <a href="http://integration.az/">INTEGRATION</a>, 2017. @lang('custom.allright')</p>
            </div>
        </div>
    </div>
</footer>

<!-- Javascript -->
<script src="{{asset('js/jquery.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/bootstrap-hover-dropdown.min.js')}}"></script>
<script src="{{asset('js/jquery.backstretch.js')}}"></script>
<script src="{{asset('js/wow.min.js')}}"></script>
<script src="{{asset('js/retina-1.1.0.min.js')}}"></script>
<script src="{{asset('js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('js/jquery.flexslider-min.js')}}"></script>
<script src="{{asset('js/jflickrfeed.min.js')}}"></script>
<script src="{{asset('js/masonry.pkgd.min.js')}}"></script>
<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script src="{{asset('js/jquery.ui.map.min.js')}}"></script>
<script src="{{asset('js/scripts.js')}}"></script>
<script src="{{asset('js/pgwslider.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>


    <script>
			
			$(window).on("backstretch.after", function (e, instance, index) {
				$('.slider-2-text').fadeOut(500);
				$('.slider-2-text:eq('+index+')').fadeIn(1000);
			});

        $(function(){
		if($('[data-menu]').length) $('a[href$="'+$('[data-menu]').data('menu')+'"]').parent().addClass('active')

			if($('.pgwSlider li').length > 0) $('.pgwSlider').pgwSlider();
			
			$('.pgwSlider > .ps-list > li a').click(function(e){
 				e.stopPropagation();
			});
			
        });
    </script>

<script>
    $(document).ready(function(){
    	$('.portfolio-filters a').unbind().click(function(event) {
                event.preventDefault();

                var goTo = $(event.target).attr('href');
                location.href = goTo;
        });

    });
</script>
</body>

</html>