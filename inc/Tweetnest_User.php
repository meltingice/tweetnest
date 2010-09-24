<?

class User {
	
	/*
	 * Couldn't decide if I wanted to move this logic into the User class itself.
	 * Eh... works here for now.
	 */
	public static function load_active_user() {
		$db = DB::connection();
		$result = $db->query("SELECT * FROM `".DTP."tweetusers` WHERE `screenname` = '" . $db->s(Tweetnest::$config['twitter_screenname']) . "' LIMIT 1");
		$author = $db->fetch($result);
		$author['extra'] = unserialize($author['extra']);
		
		return new User($author);
	}
	
	private function __construct($user_info) {
		foreach($user_info as $key=>$val) {
			$this->$key = $val;
		}
	}
	
	public function __get($key) {
		switch ($key) {
			case 'profile_link' : return "http://twitter.com/{$this->screenname}";
		}
	}
	
}