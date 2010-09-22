<?

class User {
	
	public static function load_active_user() {
		$db = DB::connection();
		$result = $db->query("SELECT * FROM `".DTP."tweetusers` WHERE `screenname` = '" . $db->s($config['twitter_screenname']) . "' LIMIT 1");
		$author = $db->fetch($result);
		$author['extra'] = unserialize($author['extra']);
		
		return new User($author);
	}
	
	private __construct($user_info) {
		foreach($user_info as $key=>$val) {
			$this->$key = $val;
		}
	}
	
}