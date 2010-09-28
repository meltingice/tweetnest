<?

class date {

	// Altered "Days in Month" function taken from:
	// PHP Calendar Class Version 1.4 (5th March 2001)
	//  
	// Copyright David Wilkinson 2000 - 2001. All Rights reserved.
	// 
	// This software may be used, modified and distributed freely
	// providing this copyright notice remains intact at the head 
	// of the file.
	//
	// This software is freeware. The author accepts no liability for
	// any loss or damages whatsoever incurred directly or indirectly 
	// from the use of this script. The author of this software makes 
	// no claims as to its fitness for any purpose whatsoever. If you 
	// wish to use this software you should first satisfy yourself that 
	// it meets your requirements.
	//
	// URL:   http://www.cascade.org.uk/software/php/calendar/
	// Email: davidw@cascade.org.uk
	public static function get_days_in_month($month, $year){
	    if($month < 1 || $month > 12){ return 0; }
	    
	    $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	    $d = $daysInMonth[$month - 1];
	    
	    if($month == 2){
	    	// Check for leap year
	    	// Forget the 4000 rule, I doubt I'll be around then...
	    	if($year%4 == 0){
	    		if($year%100 == 0){
	    			if($year%400 == 0){
	    				$d = 29;
	    			}
	    		} else {
	    			$d = 29;
	    		}
	    	}
	    }
	    return $d;
	}
	
}