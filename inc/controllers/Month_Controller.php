<?

class Month_Controller extends Controller {
	// # of tweets to show per page
	private $tpp = 200;
	
	public function load() {
		
		$tweets = Tweet::load_month_tweets();
		$user = Tweetnest::$user;
		
		$months = visual::months($total_tweets);
		$days = visual::days();
		
		$sidebar_data = array(
			'months' => $months,
			'total_tweets' => $total_tweets
		);
		
		$sidebar = $this->render('sidebar', $sidebar_data);
		
		$days_data = array(
			'days' => $days,
			'days_in_month' => date::get_days_in_month(Router::$PARAMS[1], Router::$PARAMS[0]),
			'year' => Router::$PARAMS[0],
			'month' => Router::$PARAMS[1]
		);
		
		$days_page = $this->render('days', $days_data);
		
		$show_more = false;
		if(count($tweets) > $this->tpp) {
			$show_more = true;
			$tweets = array_slice($tweets, 0, $this->tpp);
		}
		
		$page_data = array(
			'tweets' => $tweets,
			'show_more' => $show_more,
			'user' => $user,
			'sidebar' => $sidebar,
			'current_date' => $this->current_date(),
			'days' => $days_page
		);
		
		$page = $this->render('month', $page_data);
		
		$template_data = array(
			'page_title' => "Tweets by @{$user->screenname} / ".$this->current_date(),
			'user' => $user, 
			'content' => $page
		);
		
		echo $this->render('template', $template_data);
		
	}
	
	private function current_date() {
		$year = Router::$PARAMS[0];
		$month = Router::$PARAMS[1];
		
		return date("F Y", mktime(1,0,0,$month,1,$year));
	}
	
}