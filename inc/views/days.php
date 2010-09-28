<div id="days" class="days-<?=$days_in_month?>">
	<div class="dr">
		<? for($i = 1; $i <= $days_in_month; $i++): ?>
	    <div class="d">
	    	<? if(array_key_exists($i, $days)): ?>
	    	<? $day = $days[$i]; ?>
	    	<a title="<?=$day['total']?> tweets, <?=$day['types'][1]?> replies, <?=$day['types'][2]?> retweets" href="/<?=$year?>/<?=$month?>/<?=$i?>">
	    		
	    		<span class="p" style="height:<?=($day['percent']*250)?>px">
	    			<span class="n">
	    				<?=($day['total'] != 1 ? number_format($day['total']) : "")?>
	    			</span>
	    			<span class="r" style="height:11.63px"></span>
	    			<span class="rt" style="height:14.53px"></span>
	    		</span>
	    		<span class="m"><?=$i?></span>
	    		
	    	</a>
	    	<? else: ?>
	    		<a href="/<?=$year?>/<?=$month?>/<?=$i?>">
	    			<span class="z">0</span>
	    			<span class="m"><?=$i?></span>
	    		</a>
	
	    	<? endif; ?>
	    </div>
	    <? endfor; ?>
    	
	</div>
</div> 