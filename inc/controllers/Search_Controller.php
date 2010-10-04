<?

class Search_Controller extends Controller { 
	
	public function load() {
		
		$tweets = Tweet::load_search_tweets();
		$user = Tweetnest::$user;
		
		$search = trim($_GET['q']);
		if(strlen($search) == 0) {
			header('Location: '.PATH);
		}
		
		if(isset($_COOKIE['tweet_sort_order'])) {
			$mode = $_COOKIE['tweet_sort_order'];
		} else {
			$mode = 'relevance';
		}
		
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
			$title = "Searching for \"$search\" in " . Util::current_date();
		} else {
			$title = "Searching for \"$search\"";
		}
		
		$page_data = array(
			'tweets' => $tweets,
			'user' => $user,
			'sidebar' => $sidebar,
			'title' => $title,
			'mode' => $mode
		);
		
		$page = $this->render('search', $page_data);
		
		$template_data = array(
			'page_title' => "Tweets by @{$user->screenname} / $title",
			'user' => $user, 
			'content' => $page
		);
		
		echo $this->render('template', $template_data);
	}
}