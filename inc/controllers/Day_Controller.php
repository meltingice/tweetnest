<?

class Day_Controller extends Controller {
	
	public function load() {
		
		$tweets = Tweet::load_day_tweets();
		$user = Tweetnest::$user;
		
		$months = visual::months($total_tweets);
		$days = visual::days();
		
		$sidebar_data = array(
			'months' => $months,
			'total_tweets' => $total_tweets,
			'active' => array(
				'year' => Router::$PARAMS[0],
				'month' => Router::$PARAMS[1]
			)
		);
		
		$sidebar = $this->render('sidebar', $sidebar_data);
		
		$days_data = array(
			'days' => $days,
			'days_in_month' => date::get_days_in_month(Router::$PARAMS[1], Router::$PARAMS[0]),
			'year' => Router::$PARAMS[0],
			'month' => Router::$PARAMS[1],
			'active' => Router::$PARAMS[2]
		);
		
		$days_page = $this->render('days', $days_data);
		
		$page_data = array(
			'tweets' => $tweets,
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
		$day = Router::$PARAMS[2];
		
		return date("F j\\t\h, Y", mktime(1,0,0,$month,$day,$year));
	}
	
}