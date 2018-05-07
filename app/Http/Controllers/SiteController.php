<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\News;
use App\Menu;
use App\Gallery;
use App\Wholesale;
use App\Photoday;
use App\Videos;
use App\Banners;
use App\Search;
use Session;

use PHPMailer\PHPMailer\PHPMailer;

class SiteController extends Controller
{

	
	public function home(){
		
		$category_id = Menu::getContent('media-xeberler')['id'];
		
		$galleries=[];

		foreach (Gallery::all() as $name=>$album) foreach ($album as $photo) $galleries[]=['name'=>$name, 'photo'=>$photo];

		$galleries = array_slice($galleries, 0, 4);

		$banners = Banners::all();

		return view('home',[
			'title'=>'Ana səhifə',
			'news'=>News::all('news', 4, null, 'd.m.Y', $category_id),
		    'gall' => $galleries,
		    'banners' => $banners,
		]);
	}


	public function category($slug){
		Article::setCounter($slug);
		$data = Article::getContent($slug);

		if(!$data)	abort(404);


		return view('category', [
			'title'=>$data[0]['text'],
			'time'=>date('d.m.Y, H:i', strtotime($data[0]['add_datetime']) ),
			'content'=>$data[1]['text'],
			'counter'=>$data[0]['counter'],
			'news' => News::all('news,clauses,interview', null, null, 'd.m.Y, H:i', $data[0]['id']),
		]);
	}

	

	public function news($slug, $read_OR_page=false){

		if($_SESSION['lang'] != 'az' && $slug == 'media-bizden-yazirlar')
			return	redirect()->to('/');
	
	    $arxiv = Menu::getContent($slug);

	    $limit = 2;
	    $total = ceil(News::total('news', $arxiv['id'])/$limit);

	    $page = 0;

	    if(is_numeric($read_OR_page)){
	    	$read_OR_page = (int)$read_OR_page;
	    	if($read_OR_page>=$total) $read_OR_page = $total;
	    	$read_OR_page--;
	    	if($read_OR_page>0) $page = $read_OR_page;
	    }

	    $all_news = News::all('news', $limit, $page, 'd.m.Y, H:i',$arxiv['id']);

	    if(!$all_news)	abort(404);

	    $read_news = $all_news[0];

	    if($read_OR_page && !is_numeric($read_OR_page)){
	    	$data = News::get('news', 'd.m.Y, H:i', null, null, $read_OR_page);
	    	if($data) $read_news = $data;
	    }

		return view('news', [
			'title'=>$arxiv['text'],
			'slug'=>$slug,
			'news'=>$all_news,
			'total'=>$total,
			'page'=>$page+1,
			'read'=>$read_news
		]);
	}
	

	public function rehberlik($slug){

		$arxiv= Menu::getContent($slug);

		$result = [];
		foreach(Menu::getMenu($arxiv['id'], false) as $menu){
			$article = Article::getContent($menu['sef']);
			$result[] = [
				'name' => $menu['name'],
				'position' => $article[0]['text'],
				'content' => $article[1]['text']
			];
		}
	       
		return view('rehberlik',['title' => $arxiv['text'], 'result' => $result]);
	}
	

	
	public function videos($slug){
	    $arxiv = Menu::getContent($slug);

	    $categories = Menu::getMenu($arxiv['id'], false);

		
		return view('videos', [
			'title'=>$arxiv['text'],
			'categories'=>$categories,
			'videos'=>Videos::all()
		]);
	}

	public function search(Request $request){

		$query='';
	
		if($request->isMethod('post')){
			$query = mb_strtolower(trim($request->input('query')));
			$_SESSSION['query'] = $query;
		}elseif(isset($_SESSION['query']))
			$query = $_SESSION['query'];
		
		if(!$query) redirect('/');
		
	 	return view('search', [
			'title'=>trans('custom.searching'),
			'search_text'=> $query,
			'results'=>Search::all($query)
		]); 
		
	}

	public function contact(Request $request, $slug){

        $data = Article::getContent($slug);

		if(!$data)	abort(404);
		
		if($request->isMethod('post')){
			
			$_p = $request->all();
            
            $recaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Lf_u0cUAAAAAAriLASEAxUYxSdFc3cwbCop7XpC&response='.$_p['g-recaptcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']));
  
			
            if($recaptcha->success == 1){
                $_html = '<table cellspacing="0" cellpadding="10" border="1" width="100%">';

    			foreach ($_p['contact'] as $k=>$_c) {
    				$_html.='
    					<tr>
    						<td>'.$_p['fields'][$k].'</td>
    						<td>'.$_c.'</td>
    					</tr>
    				';
    			}
    
    			$_html.='</table>';
				
				
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = 'smtp.mail.gov.az';
				$mail->Port = 587;
				$mail->SMTPSecure = 'tls';    
				$mail->SMTPAuth = true;
				$mail->CharSet = 'UTF-8';
				$mail->IsHTML(true);
				$mail->Username = 'mail@tedaruk.gov.az';
				$mail->Password = 'Baku@2017';
				$mail->setFrom('mail@tedaruk.gov.az','Mail Tədarük');

				$mail->addAddress($this->_settings['email'], 'Mail Tədarük');
				$mail->Subject = 'Tədarük - Əlaqə';

				$mail->msgHTML($_html);
				
				if (!$mail->send()) {
					flash(trans('custom.contact_send_success'))->success();
				} else {
					flash(trans('custom.contact_send_error'))->error(); 
				}

            } 
            else
                flash(trans('custom.contact_send_robot'))->error();

            return redirect()->refresh();
		}

		return view('contact', [ 'title'=>$data[0]['text'], 'content'=>$data[1]['text']]);
	}


	public function gallery($slug){
		$data = Menu::getContent($slug);

		if(!$data)	abort(404);

		return view('gallery', ['title'=>$data['text'], 'galleries'=>Gallery::all() ]);
	}

	public function error(){
		return view('error');
	}
}
