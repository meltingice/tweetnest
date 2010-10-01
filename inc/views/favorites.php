<h1><?=$title?></h1>
<form id="search" action="/search" method="get"><div><input type="text" name="q" value="" /></div></form>

<div id="c">
    <div id="primary">
    <? foreach($tweets as $tweet): ?>
    	<div id="tweet-<?=$tweet->tweetid?>" class="tweet <?= $tweet->is_rt ? 'retweet' : '' ?> <?= $tweet->is_reply ? 'reply' : '' ?>">
	    	<div class="fav" title="A personal favorite"><span>(A personal favorite)</span></div>
    		<p class="text"><?=$tweet->tweet?></p>
    
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
    </div>
    <div id="secondary">
    	<?=$sidebar?>
    </div>
</div>