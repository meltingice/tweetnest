<ul id="months">
	
	<? if(!Router::is_index()): ?>
	<li class="home"><a href="<?=PATH?>/"><span class="m">Recent tweets</span></a></li>
	<? endif; ?>
	
	<li class="fav"><a href="<?=PATH?>/favorites"><span class="m">Favorites</span></a></li>
	<? foreach($months as $month): ?>
	<li>
		<a href="/<?=$month['year']?>/<?=$month['month']?>">
		<span class="m"><?=$month['date']?></span>
		<span class="n"> <?=$month['tweets']?></span>
		<span class="p" style="width:<?=$month['percent']?>%"></span>
		</a>
	</li>
	<? endforeach; ?>

	<li class="meta"><?=$total_tweets?> total tweets</li>
</ul>