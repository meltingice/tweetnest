<?

Extension::name('bitly');
Extension::desc('Enables expansion of bit.ly links in tweets');

Extension::action('tweet', function($data) {
	$url_regex = '/(http\:\/\/(?:www.)?(bit\.ly|j\.mp)\/(\S+))/';
	
	$callback = function($matches) {
		return Bitly::expand($matches[1]);
	};
	
	return preg_replace_callback($url_regex, $callback, $data);
});

class Bitly {
	public static function expand($url) {
		$req = "http://api.bit.ly/v3/expand?";
		$req .= "shortUrl=".urlencode($url);
		$req .= "&login=tweetnestbitly";
		$req .= "&apiKey=R_f05696a4e6b30788bb52beaacfc48fe6";
		$req .= "&format=json";
		
		$ctx = stream_context_create(array( 
			'http' => array( 
				'timeout' => 2
			) 
		)); 
		
		$json = file_get_contents($req, 0, $ctx);
		if($json === false) {
			return $url;
		}
		
		$resp = json_decode($json);

		if($resp->status_code == 200 && !isset($resp->data->expand[0]->error)) {
			return $resp->data->expand[0]->long_url;
		} else {
			return $url;
		}
	}
}