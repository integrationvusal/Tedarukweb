<?php

Route::match(['get', 'post'],'/{lang}/contact/{slug}', 'SiteController@contact')->name('contact')->where('lang', '[A-Za-z]{2}');
Route::match(['get', 'post'],'/contact/{slug}', 'SiteController@contact')->name('contact');

Route::get('/{lang}/category/{slug}', 'SiteController@category')->name('category')->where('lang', '[A-Za-z]{2}');
Route::get('/category/{slug}', 'SiteController@category')->name('category');

Route::get('/{lang}/gallery/{slug}', 'SiteController@gallery')->name('gallery')->where('lang', '[A-Za-z]{2}');
Route::get('/gallery/{slug}', 'SiteController@gallery')->name('gallery');

Route::get('/{lang}/news/{slug}/{read}', 'SiteController@news')->name('news_read')->where('lang', '[A-Za-z]{2}');
Route::get('/news/{slug}/{read}', 'SiteController@news')->name('news_read');

Route::get('/{lang}/news/{slug}/view/{page}', 'SiteController@news')->name('news_view')->where('lang', '[A-Za-z]{2}');
Route::get('/news/{slug}/view/{page}', 'SiteController@news')->name('news_view');

Route::get('/{lang}/news/{slug}', 'SiteController@news')->name('news')->where('lang', '[A-Za-z]{2}');
Route::get('/news/{slug}', 'SiteController@news')->name('news');

Route::get('/{lang}/rehberlik/{slug}', 'SiteController@rehberlik')->name('rehberlik')->where('lang', '[A-Za-z]{2}');
Route::get('/rehberlik/{slug}', 'SiteController@rehberlik')->name('rehberlik');

Route::get('/{lang}/video/{slug}', 'SiteController@videos')->name('video')->where('lang', '[A-Za-z]{2}');
Route::get('/video/{slug}', 'SiteController@videos')->name('video');

Route::match(['get', 'post'], '/{lang}/search', 'SiteController@search')->name('search')->where('lang', '[A-Za-z]{2}');
Route::match(['get', 'post'], '/search', 'SiteController@search')->name('search');

Route::get('/{lang}/', 'SiteController@home')->name('home')->where('lang', '[A-Za-z]{2}');
Route::get('/', 'SiteController@home')->name('home');



