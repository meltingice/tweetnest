<h1>Recent tweets</h1>
<form id="search" action="/search" method="get"><div><input type="text" name="q" value="" /></div></form>

<div id="c">
    <div id="primary">
    <? foreach($tweets as $tweet): ?>
    	<div id="tweet-<?=$tweet->tweetid?>" class="tweet <?= $tweet->is_rt ? 'retweet' : '' ?> <?= $tweet->is_reply ? 'reply' : '' ?>">
    		<p class="text"><?=$tweet->tweet?></p>
    
    		<p class="meta">
    			<a href="<?=$tweet->link?>" class="permalink"><?=$tweet->tweet_date?></a>
    			<span class="via">via <?=$tweet->tweet_source?></span>
    			<? if($tweet->is_rt): ?>
    			<span class="rted">(retweeted on <?=$tweet->retweet_date?> <span class="via">via <?=$tweet->retweet_source?></span>)</span>
    			<? endif; ?>
    		</p>
    	</div>
    <? endforeach; ?>
    </div>
    <div id="secondary">
    	<ul id="months">
    		<li class="fav"><a href="/favorites"><span class="m">Favorites</span></a></li>
    		<li><a href="/2010/09"><span class="m">September 2010</span><span class="n"> 565</span><span class="p" style="width:75.43%"></span></a></li>
    
    		<li><a href="/2010/08"><span class="m">August 2010</span><span class="n"> 603</span><span class="p" style="width:80.51%"></span></a></li>
    		<li><a href="/2010/07"><span class="m">July 2010</span><span class="n"> 749</span><span class="p" style="width:100%"></span></a></li>
    		<li><a href="/2010/06"><span class="m">June 2010</span><span class="n"> 456</span><span class="p" style="width:60.88%"></span></a></li>
    		<li><a href="/2010/05"><span class="m">May 2010</span><span class="n"> 165</span><span class="p" style="width:22.03%"></span></a></li>
    
    		<li><a href="/2010/04"><span class="m">April 2010</span><span class="n"> 74</span><span class="p" style="width:9.88%"></span></a></li>
    		<li><a href="/2010/03"><span class="m">March 2010</span><span class="n"> 49</span><span class="p" style="width:6.54%"></span></a></li>
    		<li><a href="/2010/02"><span class="m">February 2010</span><span class="n"> 45</span><span class="p" style="width:6.01%"></span></a></li>
    		<li><a href="/2010/01"><span class="m">January 2010</span><span class="n"> 36</span><span class="p" style="width:4.81%"></span></a></li>
    
    		<li><a href="/2009/12"><span class="m">December 2009</span><span class="n"> 21</span><span class="p" style="width:2.8%"></span></a></li>
    		<li><a href="/2009/11"><span class="m">November 2009</span><span class="n"> 1</span><span class="p" style="width:0.13%"></span></a></li>
    		<li><a href="/2009/10"><span class="m">October 2009</span><span class="n"> 23</span><span class="p" style="width:3.07%"></span></a></li>
    		<li><a href="/2009/09"><span class="m">September 2009</span><span class="n"> 50</span><span class="p" style="width:6.68%"></span></a></li>
    
    		<li><a href="/2009/08"><span class="m">August 2009</span><span class="n"> 23</span><span class="p" style="width:3.07%"></span></a></li>
    		<li><a href="/2009/07"><span class="m">July 2009</span><span class="n"> 28</span><span class="p" style="width:3.74%"></span></a></li>
    		<li><a href="/2009/06"><span class="m">June 2009</span><span class="n"> 30</span><span class="p" style="width:4.01%"></span></a></li>
    		<li><a href="/2009/05"><span class="m">May 2009</span><span class="n"> 4</span><span class="p" style="width:0.53%"></span></a></li>
    
    		<li><a href="/2009/04"><span class="m">April 2009</span><span class="n"> 8</span><span class="p" style="width:1.07%"></span></a></li>
    		<li><a href="/2009/03"><span class="m">March 2009</span><span class="n"> 1</span><span class="p" style="width:0.13%"></span></a></li>
    		<li><a href="/2008/08"><span class="m">August 2008</span><span class="n"> 1</span><span class="p" style="width:0.13%"></span></a></li>
    		<li><a href="/2008/07"><span class="m">July 2008</span><span class="n"> 1</span><span class="p" style="width:0.13%"></span></a></li>
    
    		<li><a href="/2008/06"><span class="m">June 2008</span><span class="n"> 2</span><span class="p" style="width:0.27%"></span></a></li>
    		<li><a href="/2007/01"><span class="m">January 2007</span><span class="n"> 10</span><span class="p" style="width:1.34%"></span></a></li>
    		<li class="meta">2,945 total tweets <!-- approx. 128 monthly --></li>
    	</ul>
    </div>
</div>