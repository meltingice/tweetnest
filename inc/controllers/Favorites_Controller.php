<?

class Favorites_Controller extends Controller { 
	
	public function load() {
		
		$tweets = Tweet::load_favorite_tweets();
		$user = Tweetnest::$user;
		
		$months = visual::months($total_tweets);
		$sidebar_data = array(
			'months' => $months,
			'total_tweets' => $total_tweets,
			'active' => array(
				'year' => $_GET['y'],
				'month' => $_GET['m']
			)
		);
		
		$sidebar = $this->render('sidebar', $sidebar_data);
		
		if(isset($_GET['y']) && isset($_GET['m'])) {
			$title = "Favorite tweets from " . Util::current_date();
		} else {
			$title = 'Favorite tweets';
		}
		
		$page_data = array(
			'tweets' => $tweets,
			'user' => $user,
			'sidebar' => $sidebar,
			'title' => $title
		);
		
		$page = $this->render('favorites', $page_data);
		
		$template_data = array(
			'page_title' => "Favorite Tweets by @{$user->screenname}",
			'user' => $user, 
			'content' => $page
		);
		
		echo $this->render('template', $template_data);
	}
}