<?

class Index_Controller extends Controller { 
	
	public function load() {
		$db = DB::connection();
		$q = $db->query(
			"SELECT 
				`".DTP."tweets`.*
			FROM `".DTP."tweets`
			ORDER BY `".DTP."tweets`.`time`
			DESC LIMIT 25"
		);
		
		$tweet_data = array();
		while($data = $db->fetch($q)) {
			$tweet_data[] = Util::parse_tweet($data);
		}
		
		$user = Tweetnest::$user;
		
		$page_data = array(
			'tweets' => $tweet_data,
			'user' => $user
		);
		
		$page = $this->render('index', $page_data);
		
		$template_data = array(
			'page_title' => "Tweets by @{$user->username}",
			'user' => Tweetnest::$user, 
			'content' => $page
		);
		
		echo $this->render('template', $template_data);
	}
	
}