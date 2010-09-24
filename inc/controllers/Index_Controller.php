<?

class Index_Controller implements Controller { 
	
	public function load() {
		$db = DB::connection();
		$q = $db->query(
			"SELECT 
				`".DTP."tweets`.*,
				`".DTP."tweetusers`.`screenname`,
				`".DTP."tweetusers`.`realname`,
				`".DTP."tweetusers`.`profileimage`
			FROM `".DTP."tweets`
			LEFT JOIN `".DTP."tweetusers`
			ON `".DTP."tweets`.`userid` = `".DTP."tweetusers`.`userid`
			ORDER BY `".DTP."tweets`.`time`
			DESC LIMIT 25"
		);
		
		$tweet_data = array();
		while($data = $db->fetch($q)) {
			$tweet_data[] = Util::parse_tweet($data);
		}
		
		print_r($tweet_data);
	}
	
}