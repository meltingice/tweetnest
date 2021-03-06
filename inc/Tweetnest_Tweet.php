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
			ORDER BY `".DTP."tweets`.`time` DESC"
		);
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
				ORDER BY `".DTP."tweets`.`time` DESC"
		);
	}
	
	public static function load_favorite_tweets() {
		$db = DB::connection();
		
		$month = false;
		if(!empty($_GET['m']) && !empty($_GET['y'])){
			$m = $db->s(ltrim($_GET['m'], "0"));
			$y = $db->s($_GET['y']);
			if(is_numeric($m) && $m >= 1 && $m <= 12 && is_numeric($_GET['y']) && $_GET['y'] >= 2000){
				$month = true;
				$selectedDate = array("y" => $y, "m" => $m, "d" => 0);
			}
		}
		
		return self::load_tweets(
			"SELECT
				`".DTP."tweets`.*
			FROM `".DTP."tweets`
			WHERE
				`".DTP."tweets`.`favorite` > 0 " .
				($month ? " AND YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$y' AND MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$m'" : "") .
			"ORDER BY `".DTP."tweets`.`time` DESC"
		);
	}
	
	public static function load_search_tweets() {
		$db = DB::connection();
		$search = new Search();
		
		$month = false;
		if(!empty($_GET['m']) && !empty($_GET['y'])){
			$m = $db->s(ltrim($_GET['m'], "0"));
			$y = $db->s($_GET['y']);
			if(is_numeric($m) && $m >= 1 && $m <= 12 && is_numeric($_GET['y']) && $_GET['y'] >= 2000){
				$month = true;
				$selectedDate = array("y" => $y, "m" => $m, "d" => 0);
			}
		}
		
		$sort = $_COOKIE['tweet_sort_order'] == "time" ? "time" : "relevance"; // Sorting by time or default order (relevance)
	
		$tooShort = (strlen($_GET['q']) < $search->minWordLength || $search->minWordLength > 1 && strlen(trim($_GET['q'], "*")) <= 1);
		
		if(!$tooShort){
			$results = $search->query(
				$_GET['q'],
				$sort,
				($month 
					? " AND YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$y' AND MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$m'"
					: ""
				)
			);
			
			$tweets = array();
			foreach($results as $tweet) {
				$tweet = new Tweet(Util::parse_tweet($tweet));
				$tweet->text = Util::highlightQuery($tweet->text, $tweet);
				$tweets[] = $tweet;
			}

			return $tweets;
		}
		
		return false;
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
		$this->text = Extensions::execute_hook('tweet', $this->text);
		return $this->linkifyTweet($this->text);
	}
	
	private function linkifyTweet($str){
		$link = function($matches) {
			$url = $matches[0];
			return "<a class=\"link\" target=\"_blank\" href=\"" . ($url[0] == "w" ? "http://" : "") . 
				str_replace("\"", "&quot;", $url) . "\">" . 
				(strlen($url) > 25 ? substr($url, 0, 24) . "..." : $url) . 
				"</a> ";
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
			case 'is_reply' : return ($this->type == 1 && $this->extra['in_reply_to_status_id'] != null);
			case 'is_rt' : return $this->type == 2;
			case 'tweet' : return $this->format_tweet();
			case 'link' : return $this->link();
			case 'tweet_date' : return $this->tweet_date(false);
			case 'retweet_date' : return $this->tweet_date(true);
			case 'tweet_source' : return $this->tweet_source(false);
			case 'retweet_source' : return $this->tweet_source(true);
			case 'reply_source' : return "http://twitter.com/{$this->extra['in_reply_to_screen_name']}/statuses/{$this->extra['in_reply_to_status_id']}";
		}
	}
	
}