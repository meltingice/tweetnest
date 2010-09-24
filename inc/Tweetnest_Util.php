<?

class Util {

	public static function getURL($url, $auth = NULL){
		// HTTP grabbin' cURL options, also exsecror
		$httpOptions = array(
			CURLOPT_FORBID_REUSE   => true,
			CURLOPT_POST           => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_USERAGENT      => "Mozilla/5.0 (Compatible; libCURL)",
			CURLOPT_VERBOSE        => false,
			CURLOPT_SSL_VERIFYPEER => false // Insecurity?
		);
		$conn = curl_init($url);
		$o    = $httpOptions;
		if(is_array($auth) && count($auth) == 2){
			$o[CURLOPT_USERPWD] = $auth[0] . ":" . $auth[1];
		}
		curl_setopt_array($conn, $o);
		$file = curl_exec($conn);
		if(!curl_errno($conn)){
			curl_close($conn);
			return $file;
		} else {
			$a = array(false, curl_errno($conn), curl_error($conn));
			curl_close($conn);
			return $a;
		}
	}
	
	public static function findURLs($str){
		$urls = array();
		preg_match_all("/\b(((https*:\/\/)|www\.).+?)(([!?,.\"\)]+)?(\s|$))/", $str, $m);
		foreach($m[1] as $url){
			$u = ($url[0] == "w") ? "http://" . $url : $url;
			$urls[$u] = parse_url($u);
		}
		return $urls;
	}
	
	public static function domain($host){
		if(empty($host) || !is_string($host)){ return false; }
		if(preg_match("/^[0-9\.]+$/", $host)){ return $host; } // IP
		if(substr_count($host, ".") <= 1){
			return $host;
		} else {
			$h = explode(".", $host, 2);
			return $h[1];
		}
	}
	
	// STUPE STUPE STUPEFY
	public static function stupefyRaw($str, $force = false){
		global $config;
		return ($config['smartypants'] || $force) ? str_replace(
			array("Ð", "Ñ", "Ô", "Õ", "Ò", "Ó", "É"),
			array("---", "--", "'", "'", "\"", "\"", "..."),
			$str) : $str;
	}
	
	public static function parse_tweet($tweet) {
		$serialized = array(
			'extra',
			'coordinates',
			'geo',
			'place'
		);
		
		foreach($serialized as $key) {
			if(isset($tweet[$key])) {
				$tweet[$key] = unserialize($tweet[$key]);
			}
		}
		
		return $tweet;
	}
	
}