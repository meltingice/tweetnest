<!DOCTYPE html>
<html lang="en">
<head>
	<title><?=$page_title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta name="description" content="An archive of all tweets written by <?=$user->realname?>." /> 
	<meta name="author" content="<?=$user->realname?>" /> 
	<link rel="stylesheet" href="<?=PATH?>/styles/streamlined/styles.css.php" type="text/css" /> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
	<script type="text/javascript" src="http://platform.twitter.com/anywhere.js?id=J9CvKf1M1QfBxD7l4dbU9w&amp;v=1"></script> 
	<script type="text/javascript" src="<?=PATH?>/tweets.js"></script> 
</head>
<body>
	<div id="container">
		<div id="top">
			<div id="author">
				<h2><a href="<?=$user->profile_link?>"><strong><?=$user->realname?></strong> (@<?=$user->screenname?>)<img src="<?=$user->profileimage?>" width="48" height="48" alt="" /></a></h2>

				<p><?=$user->location?></p>
			</div>
			<div id="info">
				<p>The below is an off-site archive of <strong><a href="/">all tweets posted by @<?=$user->screenname?></a></strong> ever</p>
				<p class="follow"><a href="<?=$user->profile_link?>">Follow me on Twitter</a></p>
			</div>

		</div>
		<div id="content">
			<?=$content?>
		</div>
		<div id="footer">
			&copy; <?=date('Y')?> <a href="<?=$user->profile_link?>"><?=$user->realname?></a>, powered by <a href="http://pongsocket.com/tweetnest/">Tweet Nest</a>
		</div>
	</div>
</body>
</html>