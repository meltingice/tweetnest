<?

Extension::name('Image Thumbnails');
Extension::desc('Enable thumbnail display for various image services');
Extension::action('before_tweet', function($data) {
	$imgs  = array();
	$links = Util::findURLs($data);
	foreach($links as $link => $l){
	    if(is_array($l) && array_key_exists("host", $l) && array_key_exists("path", $l)){
	    	$domain = Util::domain($l['host']);
	    	$imgid  = imgid($l['path']);
	    	if($imgid){
	    		if($domain == "twitpic.com"){
	    			$imgs[$link] = "http://twitpic.com/show/thumb/" . $imgid;
	    		}
	    		if($domain == "yfrog.com"){
	    			$imgs[$link] = "http://yfrog.com/" . $imgid . ".th.jpg";
	    		}
	    		if($domain == "tweetphoto.com" || $domain == "pic.gd"){
	    			$imgs[$link] = "http://tweetphotoapi.com/api/TPAPI.svc/imagefromurl?size=thumbnail&url=" . $link;
	    		}
	    		if($domain == "twitgoo.com"){
	    			$values = simplexml_load_string(getURL("http://twitgoo.com/api/message/info/" . $imgid));
	    			$imgs[$link] = (string) $values->thumburl;
	    		}
	    		if($domain == "img.ly"){
	    			$imgs[$link] = "http://img.ly/show/thumb/" . $imgid;
	    		}
	    		if($domain == "imgur.com"){
	    			$imgs[$link] = "http://i.imgur.com/" . $imgid . "s.jpg";
	    		}
	    		if($domain == "twitvid.com"){
	    			$imgs[$link] = "http://images.twitvid.com/" . $imgid . ".jpg";
	    		}
	    	}
	    }
	}
	
	if(count($imgs) > 0){
		$HTML = "";
	    foreach($imgs as $link=>$img) {
	    	$HTML .= '<a target="_blank" class="pic pic-1 hoverin" href="'.$link.'"><img src="'.$img.'" alt=""></a> ';
	    }
	    
	    return $HTML;
	}
	
	return "";
});

function imgid($path){
	$m = array();
	preg_match("@/([a-z0-9]+).*@i", $path, $m);
	if(count($m) > 0){
	    return $m[1];
	}
	return false;
}