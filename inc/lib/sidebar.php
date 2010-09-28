<?

class sidebar {
	
	public static function months(&$total_tweets) {
		$db = DB::connection();
		
		$query = "
			SELECT
				MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) AS month,
				YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) AS year,
				COUNT(*) AS tweets
			FROM `".DTP."tweets`
			GROUP BY year, month
			ORDER BY year DESC, month DESC";
			
		$q = $db->query($query);
		
		// Gather monthly data
		$months = array(); $max = 0; $total = 0;
		$amount = $db->num_rows;
		while($r = $db->fetch($q)){
			$months[] = $r;
			if($r['tweets'] > $max){ $max = $r['tweets']; }
			$total += $r['tweets'];
		}
		
		// Calculate percentages
		foreach($months as $i=>$month) {
			$month['percent'] = round(($month['tweets'] / $max) * 100, 2);
			$month['date'] = date("F Y", mktime(1,0,0,$month['month'],1,$month['year']));
			$months[$i] = $month;
		}
		
		$total_tweets = $total;
		
		return $months;
	}
	
}