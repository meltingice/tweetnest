<?

class Tweet {

	public static function load_index_tweets() {
		return self::load_tweets(
			"SELECT 
				`".DTP."tweets`.*
			FROM `".DTP."tweets`
			ORDER BY `".DTP."tweets`.`time`
			DESC LIMIT 25"
		);
	}
	
	public static function load_month_tweets() {
		// This data must be numeric because of the router regex
		// so no need to escape it.
		$year = Router::$PARAMS[0];
		$month = Router::$PARAMS[1];
		
		return self::load_tweets(
			"SELECT
				`".DTP."tweets`.*
			FROM `".DTP."tweets`
			WHERE
				YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '" . $year . "' AND
				MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '" . $month . "'
			ORDER BY `".DTP."tweets`.`time` DESC");
	}
	
	public static function load_day_tweets() {
		// This data must be numeric because of the router regex
		// so no need to escape it.
		$year = Router::$PARAMS[0];
		$month = Router::$PARAMS[1];
		$day = Router::$PARAMS[2];
		
		return self::load_tweets(
			"SELECT
				`".DTP."tweets`.*
			FROM `".DTP."tweets`
			WHERE
				YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$year' AND
				MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$month'
				AND DAY(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$day'
				ORDER BY `".DTP."tweets`.`time` DESC");
	}
	
	private static function load_tweets($query) {
		$db = DB::connection();
		$q = $db->query($query);
		
		$tweets = array();
		while($data = $db->fetch($q)) {
			$tweets[] = new Tweet(Util::parse_tweet($data));
		}
		
		return $tweets;
	}
	
	public function __construct($tweet_data) {
		foreach($tweet_data as $key=>$var) {
			$this->$key = $var;
		}
	}
	
	private function link() {
		$link = 'http://twitter.com/';
		if($this->is_rt) {
			$link .= $this->extra['rt']['screenname'];
		} else {
			$link .= Tweetnest::$user->screenname;
		}
		$link .= '/statuses/';
		if($this->is_rt) {
			$link .= $this->extra['rt']['tweetid'];
		} else {
			$link .= $this->tweetid;
		}
		
		return $link;
	}
	
	private function tweet_date($rt) {
		if($rt) {
			return date("g:i A, M jS, Y", $this->time);
		} else {
			return date("g:i A, M jS, Y", ($this->is_rt ? $this->extra['rt']['time'] : $this->time));
		}
	}
	
	public function tweet_source($rt) {
		if($rt) {
			return $this->source;
		} else {
			if($this->is_rt) {
				return $this->extra['rt']['source'];
			} else {
				return $this->source;
			}
		}
	}
	
	private function format_tweet() {
		return $this->linkifyTweet(htmlspecialchars($this->text));
	}
	
	private function _linkifyTweet_link($a, $b, $c, $d){
		$url = stripslashes($a);
		$end = stripslashes($d);
		return "<a class=\"link\" href=\"" . ($b[0] == "w" ? "http://" : "") . str_replace("\"", "&quot;", $url) . "\">" . (strlen($url) > 25 ? substr($url, 0, 24) . "..." : $url) . "</a>" . $end;
	}
	
	private function _linkifyTweet_at($a, $b){
		return "<span class=\"at\">@</span><a class=\"user\" href=\"http://twitter.com/" . $a . "\">" . $a . "</a>";
	}
	
	private function _linkifyTweet_hashtag($a, $b){
		return "<a class=\"hashtag\" href=\"http://twitter.com/search?q=%23" . $a . "\">#" . $a . "</a>";
	}
	
	private function linkifyTweet($str){
		$link = function($matches) {
			$url = $matches[0];
			return "<a class=\"link\" target=\"_blank\" href=\"" . ($url[0] == "w" ? "http://" : "") . 
				str_replace("\"", "&quot;", $url) . "\">" . 
				(strlen($url) > 25 ? substr($url, 0, 24) . "..." : $url) . 
				"</a>";
		};
		
		$at = function($matches) {
			return "<span class=\"at\">@</span><a target=\"_blank\" class=\"user\" href=\"http://twitter.com/" . $matches[1] . "\">" . $matches[1] . "</a>";
		};
		
		$hashtag = function($matches) {
			return "<a target=\"_blank\" class=\"hashtag\" href=\"http://twitter.com/search?q=%23" . $matches[1] . "\">#" . $matches[1] . "</a>";
		};
		
		$html = preg_replace_callback("/\b(((https*:\/\/)|www\.).+?)(([!?,.\"\)]+)?(\s|$))/", $link, $str);
		$html = preg_replace_callback("/\B\@([a-zA-Z0-9_]{1,20}(\/\w+)?)/", $at, $html);
		$html = preg_replace_callback("/\B\#(\w+)/", $hashtag, $html);
		
		return $html;
	}

	
	public function __get($key) {
		switch($key) {
			case 'is_rt' : return array_key_exists('rt', $this->extra);
			case 'tweet' : return $this->format_tweet();
			case 'link' : return $this->link();
			case 'tweet_date' : return $this->tweet_date(false);
			case 'retweet_date' : return $this->tweet_date(true);
			case 'tweet_source' : return $this->tweet_source(false);
			case 'retweet_source' : return $this->tweet_source(true);
		}
	}
	
}