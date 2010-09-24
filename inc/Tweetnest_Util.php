<?

class Util {
	private static $css_i = 0;
	
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
	
	// CSS functions ------------------------------------
	
	public static function css($var, $canBeEmpty = false){ // Display CSS value found in given config variable
		$config = Tweetnest::$config;
		$author = Tweetnest::$user;
		
		if(self::$css_i >= 10){ self::$css_i = 0; return false; } // Too much recursion
		$var     = trim(strtolower($var));
		$profile = array(
			"profile_background_color", "profile_text_color", "profile_link_color",
			"profile_sidebar_fill_color", "profile_sidebar_border_color"
		);
		if(isset($config['style'][$var])){
			$val = $config['style'][$var];
			if($val == "profile"){
				self::$css_i = 0;
				$pv = array(
					"text_color" => "profile_text_color",
					"link_color" => "profile_link_color",
					"content_background_color" => "#fff",
					"top_background_color" => "profile_background_color",
					"top_background_image" => "profile_background_image_url",
					"top_background_image_tile" => "profile_background_tile",
					"top_bar_background_color" => "profile_sidebar_fill_color",
					// Tweet
					"tweet_border_color" => "#eee",
					"tweet_meta_text_color" => "#999",
				);
				if(preg_match("/#[0-9a-f]+/", $pv[$var])){
					return self::cssHex($pv[$var]);
				} else {
					if($author->extra[$pv[$var]]){
						return self::profileCss($author->extra[$pv[$var]]);
					}
					return $canBeEmpty ? "" : self::standardCss($var);
				}
			}
			if(in_array($val, $profile)){
				self::$css_i = 0;
				return self::cssHex($author->extra[$val]); // They're only ever color vars, no need to run self::profileCss()
			}
			if(preg_match("/^[a-z_]+$/", $val) && isset($config['style'][$val])){
				self::$css_i++;
				return self::css($val, $canBeEmpty); // Recursion
			}
			if(preg_match("/^https?:\/\//", $val) || preg_match("/_image$/", $var)){
				self::$css_i = 0;
				return "url(" . $val . ")";
			}
			if(is_bool($val)){
				self::$css_i = 0;
				if(substr_count($var, "tile") > 0){
					return (!self::sBool($val) ? "no-" : "") . "repeat";
				}
				return $val ? 1 : 0;
			}
			if(preg_match("/^[a-zA-Z0-9-_ #\"'\(\),.]+$/i", $val)){ // Legit
				self::$css_i = 0;
				return preg_match("/#[0-9a-f]+/", $val) ? self::cssHex($val) : $val;
			}
			self::$css_i = 0;
			return $canBeEmpty ? "" : self::standardCss($var); // Empty or weird
		}
		self::$css_i = 0;
		return false;
	}
	
	public static function standardCss($var){
		$var = trim(strtolower($var));
		if(substr_count($var, "color") > 0){
			if(substr_count($var, "text_color") > 0 || substr_count($var, "link_color") > 0){
				return "#ccc";
			}
			return "transparent";
		} elseif(substr_count($var, "tile") > 0){
			return "repeat";
		} elseif(substr_count($var, "position") > 0){
			return "0 0";
		} elseif(substr_count($var, "image") > 0){
			return "none";
		} else {
			return false; // for the WTF case
		}
	}
	
	public static function profileCss($val){
		if(preg_match("/^https?:\/\//", $val)){
			return "url(" . $val . ")";
		}
		if(is_bool(self::sBool($val))){
			return (!self::sBool($val) ? "no-" : "") . "repeat";
		}
		return self::cssHex($val);
	}
	
	public static function cssHex($val){
		return "#" . preg_replace('/^([0-9a-f])\1([0-9a-f])\2([0-9a-f])\3$/i', '\1\2\3', ltrim($val, "#"));
	}
	
	public static function sBool($val){
		if(is_bool($val)){ return $val; }
		if(strtolower(trim($val)) == "false" || $val === 0){ return false; }
		if(strtolower(trim($val)) == "true"  || $val === 1){ return true;  }
		return $val;
	}
	
}