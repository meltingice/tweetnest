<h1><?=$title?></h1>
<form id="search" action="<?=PATH?>/search" method="get"><div><input type="text" name="q" value="<?=(isset($_GET['q'])) ? $_GET['q'] : '' ?>" /></div></form>

<div id="c">
    <div id="primary">
    
    <div id="sorter">
    	Sort by
    	<a class="first <?=($mode=='relevance') ? 'selected' : '' ?>" href="<?=PATH?>/sort?order=relevance">
    	<? if($mode == 'relevance'): ?>
    	<strong>Relevance</strong>
    	<? else: ?>
    	Relevance
    	<? endif; ?>
    	</a>
    	<span> </span>
    	<a class="last <?=($mode=='time') ? 'selected' : '' ?>" href="<?=PATH?>/sort?order=time">
    	<? if($mode == 'time'): ?>
    	<strong>Time</strong>
    	<? else: ?>
    	Time
    	<? endif; ?>
    	</a>
    </div>

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
    </div>
    <div id="secondary">
    	<?=$sidebar?>
    </div>
</div>