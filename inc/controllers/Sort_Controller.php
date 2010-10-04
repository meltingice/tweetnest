<?

class Sort_Controller {
	
	public function load() {
		setcookie("tweet_sort_order", $_GET['order'] == "time" ? "time" : "relevance", time()+60*60*24*365);

		$from = $_SERVER['HTTP_REFERER'];

		header("Location: " . $from);
	}
	
}