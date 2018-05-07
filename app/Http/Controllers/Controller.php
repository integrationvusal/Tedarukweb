<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App;
use App\Slider;
use App\Settings;
use App\Http\Helper;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected $_settings;

	public function __construct(Request $request)
	{
		if (session_status() == PHP_SESSION_NONE)
			session_start();
		
		$hasLang = $request->route()->hasParameter('lang');

        $this->_settings = Settings::all();
		
        if($hasLang){
			$_SESSION['lang'] = $request->route()->getParameter('lang');
            $request->route()->forgetParameter('lang');
        }
        elseif(!isset($_SESSION['lang'])){
			$_SESSION['lang'] = $this->_settings['site_default_lang_dir'];
        }
		
        App::setLocale($_SESSION['lang']);
		
		View::share('slider', Slider::all());
		View::share('baseurl', Helper::parseurl($hasLang));
		
	}
	
	protected function url_get_contents($url) {
		if (!function_exists('curl_init'))
			die('CURL is not installed!');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}
