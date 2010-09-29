<div id="days" class="days-<?=$days_in_month?>">
	<div class="dr">
		<? for($i = 1; $i <= $days_in_month; $i++): ?>
	    <div class="d">
	    	<? if(array_key_exists($i, $days)): ?>
	    	<? $day = $days[$i]; ?>
	    	<a title="<?=$day['total']?> tweets, <?=$day['types'][1]['count']?> replies, <?=$day['types'][2]['count']?> retweets" href="<?=PATH?>/<?=$year?>/<?=$month?>/<?=$i?>">
	    		
	    		<span class="p" style="height:<?=($day['percent']*250)?>px">
	    			<span class="n">
	    				<?=($day['total'] != 1 ? number_format($day['total']) : "")?>
	    			</span>
	    			<? if($day['types'][1]['count'] > 0): ?>
	    			<span class="r" style="height:<?=($day['percent'] * 250 * $day['types'][1]['percent'])?>px"></span>
	    			<? endif; ?>
	    			<? if($day['types'][2]['count'] > 0): ?>
	    			<span class="rt" style="height:<?=($day['percent'] * 250 * $day['types'][2]['percent'])?>px"></span>
	    			<? endif; ?>
	    		</span>
	    		
	    		<? if(isset($active) && $active == $i): ?>
	    		<span class="m mm ms">
	    			<strong><?=$i?></strong>
	    		</span>
	    		<? else: ?>
	    		<span class="m"><?=$i?></span>
	    		<? endif; ?>
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