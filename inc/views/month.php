<h1><?=$current_date?></h1> 

<form id="search" action="<?=PATH?>/search" method="get">
	<div><input type="text" name="q" value="" /></div>
</form> 

<?=$days?>
 
<div id="c">
    <div id="primary">
    <? foreach($tweets as $tweet): ?>
    	<div id="tweet-<?=$tweet->tweetid?>" class="tweet <?= $tweet->is_rt ? 'retweet' : '' ?> <?= $tweet->is_reply ? 'reply' : '' ?>">
    		<?=Extensions::execute_hook('before_tweet', $tweet->text)?>
    		<p class="text"><?=$tweet->tweet?></p>
    		<?=Extensions::execute_hook('after_tweet', $tweet->text)?>
    		
    		<p class="meta">
    			<a href="<?=$tweet->link?>" class="permalink"><?=$tweet->tweet_date?></a>
    			<span class="via">via <?=$tweet->tweet_source?></span>
    			<? if($tweet->is_rt): ?>
    			<span class="rted">(retweeted on <?=$tweet->retweet_date?> <span class="via">via <?=$tweet->retweet_source?></span>)</span>
    			<? elseif($tweet->is_reply): ?>
				<a href="<?=$tweet->reply_source?>" class="replyto">in reply to <?=$tweet->extra['in_reply_to_screen_name']?></a>
    			<? endif; ?>
    		</p>
    	</div>
    <? endforeach; ?>
    
    <? if($show_more): ?>
    <div class="truncated">
    	<strong>There’s more tweets in this month!</strong>
    	<span>Go up and <a href="#top">select a date</a> to see more ↑</span>
    </div>
    <? endif; ?>
    </div>
    <div id="secondary">
    	<?=$sidebar?>
    </div>
</div>