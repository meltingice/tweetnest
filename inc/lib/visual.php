<?

class visual {
	
	public static function months(&$total_tweets) {
		$db = DB::connection();
		
		if(Router::is_search()) {
			$search = new Search();
			$mq = $search->monthsQuery($_GET['q']);
			while($d = $db->fetch($mq)){
				$highlightedMonths[$d['y'] . "-" . $d['m']] = $d['c'];
			}
		}
		
		$query = "
			SELECT
				MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) AS month,
				YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) AS year,
				COUNT(*) AS tweets,
				favorite
			FROM `".DTP."tweets`
			GROUP BY year, month
			ORDER BY year DESC, month DESC";
			
		$q = $db->query($query);
		
		// Gather monthly data
		$months = array(); $max = 0; $total = 0;
		$amount = $db->num_rows;
		while($r = $db->fetch($q)){
			if(Router::is_favorites()) {
				$query2 = "
					SELECT
						MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) AS month,
						YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) AS year,
						COUNT(*) as count
					FROM `".DTP."tweets`
					WHERE
						favorite = 1
					GROUP BY year, month
					HAVING
						month = '{$r['month']}' AND
						year = '{$r['year']}'";
				$result2 = $db->query($query2);
				if($result2) {
					$data = $db->fetch($result2);
					$r['favorites'] = $data['count'];
				}
			} elseif(Router::is_search()) {
				if(isset($highlightedMonths[$r['year']."-".$r['month']])) {
					$r['found'] = $highlightedMonths[$r['year']."-".$r['month']];
				}
			}
			
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
	
	public static function days() {
		$db = DB::connection();
		
		// guaranteed numeric by router regex
		$year = Router::$PARAMS[0];
		$month = Router::$PARAMS[1];
		
		$query = "
			SELECT
				DAY(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) as d,
				MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) AS m,
				YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) AS y,
				`type`,
				COUNT(*) AS c
			FROM `".DTP."tweets`
			WHERE
				YEAR(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$year' AND
				MONTH(FROM_UNIXTIME(`time`" . DB_OFFSET . ")) = '$month'
			GROUP BY y, m, d, `type`
			ORDER BY y ASC, m ASC, d ASC, `type` ASC";
		
		$q = $db->query($query);
		
		$days   = array(); $max = 0; $total = 0;
		while($r = $db->fetch($q)){
			if(!array_key_exists($r['d'], $days)){
				$days[$r['d']] = array(
					"total" => 0,
					"types" => array(
						1 => array(
							'count' => 0
						),
						2 => array(
							'count' => 0
						)
					)
				);
			}
			$days[$r['d']]['total'] += $r['c'];
			$days[$r['d']]['types'][$r['type']]['count'] = $r['c'];
			
			if($days[$r['d']]['total'] > $max){ $max = $days[$r['d']]['total']; }
			$total += $r['c'];
		}
		
		foreach($days as $i=>$day) {
			$day['percent'] = round(($day['total'] / $max), 2);
			$day['types'][1]['percent'] = ($day['types'][1]['count'] / $day['total']);
			$day['types'][2]['percent'] = ($day['types'][2]['count'] / $day['total']);
			
			$days[$i] = $day;
		}
		
		return $days;
	}
	
	public static function is_active_date($month, $year) {
		if(Router::$PAGE != 'month' && Router::$PAGE != 'day') {
			return false;
		}
		
		return (Router::$PARAMS[0] == $year && Router::$PARAMS[1] == $month);
	}
	
}