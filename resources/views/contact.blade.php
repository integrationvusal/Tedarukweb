@extends('layouts.main')

@section('title', $title )

@section('content')
	<script src="https://www.google.com/recaptcha/api.js?hl={{$_SESSION['lang']}}"></script>
    <section class="contact">

        <div class="page-title-container">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 wow fadeIn">
                        <i class="fa fa-envelope"></i>
                        <h1>{{$title}}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Us -->
		@include('flash::message')
        <div class="contact-us-container">
            <div class="container">
                <div class="row">
                    <div class="col-sm-7 contact-form wow fadeInLeft">
                        <form role="form" action="" method="post" class="auto-contact">
						{{csrf_field()}}
                            <div class="form-group">
                                <label for="contact-name">@lang('custom.contact.name')</label>
                                <input type="text" name="contact[name]" id="contact_name" required="required">
                            </div>
                            <div class="form-group">
                                <label for="contact-email">@lang('custom.contact.email')</label>
                                <input type="text" name="contact[email]" id="contact_email">
                            </div>
							<div class="form-group">
                                <label for="contact-phone">@lang('custom.contact.phone')</label>
                                <input type="text" name="contact[phone]" id="contact_phone" required="required">
                            </div>
							<div class="form-group">
                                <label for="contact-type">@lang('custom.contact.type')</label>
								<select name="contact[type]" id="contact_type" required="required">
									@foreach(trans('custom.contact.type_text') as $type)
										<option value="{{$type}}">{{$type}}</option>
									@endforeach
								</select>
                            </div>
                            <div class="form-group">
                                <label for="contact-subject">@lang('custom.contact.subject')</label>
                                <input type="text" name="contact[subject]" id="contact_subject" required="required">
                            </div>
                            <div class="form-group">
                                <label for="contact-message">@lang('custom.contact.message')</label>
                                <textarea name="contact[message]" id="contact_message" required="required"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6Lf_u0cUAAAAAH2OL1XAncN_8Fqfm1gNE_VfmsQD"></div>
                            </div>
                            <button type="submit" class="btn">@lang('custom.contact.send')</button>
                        </form>
                    </div>
                     <div class="col-sm-5 contact-form wow fadeInRight">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection