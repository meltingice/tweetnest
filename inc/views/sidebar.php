<ul id="months">
	
	<? if(!Router::is_index()): ?>
		<li class="home"><a href="<?=PATH?>/">
		<? if(Router::is_favorites() || Router::is_search()): ?>
			<span class="m ms">
			    <span class="a">Recent tweets</span>
			    <? if(Router::is_favorites()): ?>
			    <span class="b"> (exit favorites)</span>
			    <? elseif(Router::is_search()): ?>
			    <span class="b"> (exit search)</span>
			    <? endif; ?>
			</span>
		<? else: ?>
			<span class="m">Recent tweets</span>
		<? endif; ?>
		</li></a>
	<? endif; ?>
	
	<? if(Router::is_favorites() && isset($_GET['m']) && isset($_GET['y'])): ?>
	<li class="fav"><a href="<?=PATH?>/favorites"><span class="m">	All favorites</span></a></li>
	<? elseif(!Router::is_favorites()): ?>
	<li class="fav"><a href="<?=PATH?>/favorites"><span class="m">	Favorites</span></a></li>
	<? endif; ?>
	
	<? foreach($months as $month): ?>
	<?
		if($month['month'] == $active['month'] && $month['year'] == $active['year']) {
			$class = 'selected';
		} elseif(Router::is_favorites() && $month['favorites'] > 0) {
			$class = 'highlighted';
		} elseif(Router::is_search() && $month['found'] > 0) {
			$class = 'highlighted';
		} else {
			$class = '';
		}
	?>
	<li class="<?=$class?>">
		
		<? if(Router::is_favorites() && $month['favorites'] > 0): ?>
		<a href="<?=PATH?>/favorites?y=<?=$month['year']?>&m=<?=$month['month']?>">
		<? elseif(Router::is_search() && $month['found'] > 0): ?>
		<a href="<?=PATH?>/search?q=<?=$_GET['q']?>&y=<?=$month['year']?>&m=<?=$month['month']?>">
		<? else: ?>
		<a href="<?=PATH?>/<?=$month['year']?>/<?=$month['month']?>">
		<? endif; ?>
		<span class="m"><?=$month['date']?></span>
		<span class="n">
			<?=$month['tweets']?>
			<? if(Router::is_favorites() && $month['favorites'] > 0): ?>
			<strong>(<?=$month['favorites']?>)</strong>
			<? elseif(Router::is_search() && $month['found'] > 0): ?>
			<strong>(<?=$month['found']?>)</strong>
			<? endif; ?>
		</span>
		<span class="p" style="width:<?=$month['percent']?>%"></span>
		</a>
	</li>
	<? endforeach; ?>

	<li class="meta"><?=$total_tweets?> total tweets</li>
</ul>