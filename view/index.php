<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en" itemscope="" itemtype="http://schema.org/Product"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<!-- Use the .htaccess and remove these lines to avoid edge case issues.
			 More info: h5bp.com/b/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>LonnyLot - New Years Tweet</title>
	<meta name="description" content="Automatically send out a tweet on New Years">

	<!-- Google+ Metadata /-->
	<meta itemprop="name" content="New Years Tweet">
	<meta itemprop="description" content="Automatically send out a tweet on New Years">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

	<!-- We highly recommend you use SASS and write your custom styles in sass/_custom.scss.
		 However, there is a blank style.css in the css directory should you prefer -->
	<link rel="stylesheet" href="css/gumby.css">
	<!-- <link rel="stylesheet" href="css/style.css"> -->

	<script src="js/libs/modernizr-2.6.2.min.js"></script>
</head>

<body>
	<?php if(!LIVE): ?>
	<div class="modal active" id="modal1">
	  <div class="content">
	    <div class="row">
	      <div class="ten columns centered text-center">
	        <h2>Happy 2014!</h2>
	        <p>
	        	This was a fun quick experiment - thank you for participating!
	        </p>
	        <p>We successfully sent tweets on the New Year for 19 people in 8 timezones!</p>
	        <p>Hope to see you next year!</p>
	      </div>
	    </div>
	  </div>
	</div>
	<?php endif; ?>
	<div id="fb-root"></div>
	<header>
		<div class="row">
			<div class="twelve columns">
				<h1>
					New Years Tweet
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="two columns">
				<div class="fb-like" data-href="http://newyearstweet.lonnylot.com" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
			</div>
			<div class="two columns">
				<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-url="http://newyearstweet.lonnylot.com" data-related="lonnylot" data-text="Automatically send out a tweet on New Years">Tweet</a>
			</div>
		</div>
		<p>
			<h2>Automatically send out a tweet on New Years!</h2>
		</p>
	</header>
	<form id="signin" action="/signin/twitter" method="POST">
	</form>
	<?php if($flash['success']): ?>
		<div class="row">
			<div class="twelve columns">
				<p class="success alert active">
					<?=$flash['success'];?>
				</p>
			</div>
		</div>
	<?php endif; ?>
	<?php if($sent): ?>
		<div class="row">
			<div class="twelve columns">
				<p class="success alert active">
					Happy new year! Your tweet has been sent!
				</p>
			</div>
		</div>
	<?php endif; ?>
	<?php if($flash['error']): ?>
		<div class="row">
			<div class="twelve columns">
				<p class="danger alert active">
					<?=$flash['error'];?>
				</p>
			</div>
		</div>
	<?php endif; ?>
	<form id="tweet-form" action="/new-years-tweet" method="POST">
		<div class="row">
			<section class="four columns step">
				<?php if(LIVE && $id): ?>
				<div class="mask"></div>
				<?php endif; ?>
				<h3>
					Step 1:
				</h3>
				<p>
					<a href="#" id="signin-display">
						<img src="img/sign-in-with-twitter-gray.png" alt="Sign in with Twitter" />
					</a>
				</p>
			</section>
			<section id="step2" class="four columns step">
				<?php if(LIVE && (!$id || $sent)): ?>
				<div class="mask"></div>
				<?php endif; ?>
				<h3>
					Step 2:
				</h3>
				<p class="field">
	    			<textarea name="tweet" class="input textarea" placeholder="Enter your new years tweet."><?=$tweet;?></textarea>
				</p>
			</section>
			<section class="four columns step">
				<?php if(LIVE && (!$id || $sent)): ?>
				<div class="mask"></div>
				<?php endif; ?>
				<h3>
					Step 3:
				</h3>
				<p class="field">
					<label>
						<select name="timezone">
							<option value="">Select Timezone</option>
							<?php foreach(DateTimeZone::listIdentifiers() AS $tz): ?>
								<option value="<?=$tz;?>"<?php if($tz == $timezone):?> SELECTED<?php endif;?>><?=$tz;?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</p>
			</section>
		</div>
		<div class="row">
			<section class="centered four columns" id="submit">
				<?php if(LIVE && (!$id || $sent)): ?>
				<div class="mask"></div>
				<?php endif; ?>
				<p id="submit-danger" class="danger alert"></p>
				<button class="xlarge btn primary">Submit</button>
			</section>
		</div>
	</form>


	<!-- Grab Google CDN's jQuery, fall back to local if offline -->
	<!-- 2.0 for modern browsers, 1.10 for .oldie -->
	<script>
	var oldieCheck = Boolean(document.getElementsByTagName('html')[0].className.match(/\soldie\s/g));
	if(!oldieCheck) {
	document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"><\/script>');
	} else {
	document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"><\/script>');
	}
	</script>
	<script>
	if(!window.jQuery) {
	if(!oldieCheck) {
	  document.write('<script src="js/libs/jquery-2.0.2.min.js"><\/script>');
	} else {
	  document.write('<script src="js/libs/jquery-1.10.1.min.js"><\/script>');
	}
	}
	</script>

	<!--
	Include gumby.js followed by UI modules followed by gumby.init.js
	Or concatenate and minify into a single file -->
	<!--
	<script gumby-touch="js/libs" src="js/libs/gumby.js"></script>
	<script src="js/libs/ui/gumby.retina.js"></script>
	<script src="js/libs/ui/gumby.fixed.js"></script>
	<script src="js/libs/ui/gumby.skiplink.js"></script>
	<script src="js/libs/ui/gumby.toggleswitch.js"></script>
	<script src="js/libs/ui/gumby.checkbox.js"></script>
	<script src="js/libs/ui/gumby.radiobtn.js"></script>
	<script src="js/libs/ui/gumby.tabs.js"></script>
	<script src="js/libs/ui/gumby.navbar.js"></script>
	<script src="js/libs/ui/jquery.validation.js"></script>
	<script src="js/libs/gumby.init.js"></script>

	<script src="js/plugins.js"></script>
	<script src="js/main.js"></script> 
	-->
	<script gumby-touch="js/libs" src="js/libs/gumby.min.js"></script>
	<script src="js/newyearstweet.js"></script>

	<script>
	window._gaq = [['_setAccount','UA-46748877-1'],['_trackPageview'],['_trackPageLoadTime']];
	Modernizr.load({
	  load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
	});
	</script>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
	   chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
	<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->

  </body>
</html>
