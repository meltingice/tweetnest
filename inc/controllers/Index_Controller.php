<?

class Index_Controller extends Controller { 
	
	public function load() {
		
		$tweets = Tweet::load_index_tweets();
		$user = Tweetnest::$user;
		
		$months = sidebar::months($total_tweets);
		
		$sidebar_data = array(
			'months' => $months,
			'total_tweets' => $total_tweets
		);
		
		$sidebar = $this->render('sidebar', $sidebar_data);
		
		$page_data = array(
			'tweets' => $tweets,
			'user' => $user,
			'sidebar' => $sidebar
		);
		
		$page = $this->render('index', $page_data);
		$template_data = array(
			'page_title' => "Tweets by @{$user->screenname}",
			'user' => $user, 
			'content' => $page
		);
		
		echo $this->render('template', $template_data);
	}
	
}