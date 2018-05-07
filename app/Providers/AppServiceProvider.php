<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Settings;
use App\Slider;
use App\Http\Helper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   

		View::composer('*', function($view)
		{

            $slider = Slider::all();

            $img = $_SERVER['APP_URL'].'/upload/slider/'.$slider[0]['img'];

            if($view->offsetExists('content')){
                preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $view->offsetGet('content'), $matches);
                if(isset($matches['src']))    $img = $matches['src'];
            }


            $view->with('page_img', $img);
            $view->with('page_url', $_SERVER['APP_URL'].(isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:null));
            $view->with('settings', Settings::all());
            $view->with('baseurl', Helper::parseurl(false));
            $view->with('slider', $slider);
            
		});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
