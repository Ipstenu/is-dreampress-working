<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
  <head>
 	<meta charset="utf-8"></meta>
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"></meta>
	<meta content="initial-scale=1" name="viewport"></meta>
	<meta content="DreamPress is DreamHost's managed WordPress Offering. Having problems? Come check if it's working." name="description"></meta>

	<link href='//fonts.googleapis.com/css?family=Ubuntu:400,300italic,500,700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- Holy shit. So many icons. -->
	<link href="assets/images/favicons/apple-touch-icon-57x57.png" sizes="57x57" rel="apple-touch-icon"></link>
	<link href="assets/images/favicons/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon"></link>
	<link href="assets/images/favicons/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon"></link>
	<link href="assets/images/favicons/apple-touch-icon-144x144.png" sizes="144x144" rel="apple-touch-icon"></link>
	<link href="assets/images/favicons/apple-touch-icon-60x60.png" sizes="60x60" rel="apple-touch-icon"></link>
	<link href="assets/images/favicons/apple-touch-icon-120x120.png" sizes="120x120" rel="apple-touch-icon"></link>
	<link href="assets/images/favicons/apple-touch-icon-76x76.png" sizes="76x76" rel="apple-touch-icon"></link>
	<link href="assets/images/favicons/apple-touch-icon-152x152.png" sizes="152x152" rel="apple-touch-icon"></link>
	<link rel="icon" href="assets/images/favicons/favicon.ico">

    <title>What's the deal with max-age headers?</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="assets/site.css" type="text/css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="assets/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
  </head>
  <body id="top" style="margin-bottom: 597px;">

	<header id="page-header">
	<div class="button-area">
		<a href="https://panel.dreamhost.com" class="btn-login">LOGIN</a>
		<a href="http://webmail.dreamhost.com" class="btn-login">WEBMAIL</a>
	</div>
	<a href="/" id="logo"><span>DreamHost</span></a>
	<button class="activate-menu">Menu</button>
	<nav class="header-nav">
		<button class="deactivate-menu">&times;</button>
		<ul>
			<li><a href="https://dreamhost.com/hosting/">Hosting</a></li>
			<li><a href="https://dreamhost.com/cloud/">Cloud</a></li>
			<li><a href="https://dreamhost.com/wordpress">WordPress</a></li>
			<li><a href="https://dreamhost.com/domains/">Domains</a></li>
			<li><a href="https://dreamhost.com/support/">Contact</a></li>
		</ul>
	</nav>

</header>

<div id="dreampress" class="main-content">
	<section class="section-intro maxwidth-700">
		<div class="section-wrap">
			<h1><a href="index.php">Is DreamPress Working?</a></h1>
			<p class="section-intro__lead-in">Please don't give this URL to customers yet! It's a work in progress!</p>
			<p>This site is a work in progress. Please contact Mika with issues.</p>
			<span class="btn-signup">Last Updated: May 20, 2016</span>
		</div>
	</section>

<section class="section-light">
		<div class="section-wrap">
			<h2 class="spacing">What's the deal with max-age headers?</h2>
				    	
	    	<p>'max-age' is a header call that's sent via cache-control that determines how old the page can be before cache needs to delete it's copy and generate a new one. If max-age is 0 (or set to some date like 1981), then it will tell Varnish and your browser cache to not cache a page, and always spin up a new copy.</p>
	    		    	
	    	<p>The basic grep to find these is as follows:</p>
		    	
		    <pre>grep -R "max-age=0" .</pre>
			
			<p>Sadly, many javascript libraries put this in as well and they are <em>not</em> detectable in a sane way.</p>
			
			<p>The best way to debug an issue like this is to disable plugins, rerun the test, and then start reenabling them one at a time until it happens again.</p>
		</div>
</section>

<p>&nbsp;</p>

</div>

<footer id="page-footer">
	<div class="top-bar">
		<h2>Proudly hosting over <span>1,500,000</span> dreams since 1997.</h2>
	</div>

	<div class="section-wrap">
		<ul class="get-started">
			<li><h3><a href="https://dreamhost.com/hosting/shared/">Get Started</a></h3></li>
			<li><a href="https://dreamhost.com/hosting/shared/" class="sign-up">Sign up</a></li>
			<li><a href="https://panel.dreamhost.com">Log in</a></li>
		</ul>
		<ul class="products">
			<li><h3><a href="https://dreamhost.com/hosting/">Services</a></h3></li>
			<li></li>
			<li><a href="https://dreamhost.com/domains/">Domains</a></li>
			<li><a href="https://dreamhost.com/hosting/wordpress/">WordPress Hosting</a></li>
			<li><a href="https://dreamhost.com/hosting/shared/">Web Hosting</a></li>
			<li><a href="https://dreamhost.com/cloud/storage/">Cloud Storage</a></li>
			<li><a href="https://dreamhost.com/hosting/vps/">VPS Hosting</a></li>
			<li><a href="https://dreamhost.com/cloud/computing/">Cloud Computing</a></li>
			<li><a href="https://dreamhost.com/hosting/dedicated/">Dedicated Servers</a></li>
			<li><a href="https://dreamhost.com/cloud/cdn/">CDN</a></li>
		</ul>
		<ul class="company">
			<li><h3><a href="https://dreamhost.com/company/">Company</a></h3></li>
			<li></li>
			<li><a href="https://dreamhost.com/company/">About</a></li>
			<li><a href="https://dreamhost.com/affiliates/">Affiliates</a></li>
			<li><a href="//www.dreamhost.com/blog">Blog</a></li>
			<li><a href="https://dreamhost.com/partners/">Partners</a></li>
			<li><a href="https://dreamhost.com/careers/">Careers</a></li>
			<li><a href="https://dreamhost.com/company/were-green/">Green Hosting</a></li>
			<li><a href="https://dreamhost.com/press/">Press &amp; News</a></li>
			<li><a href="https://dreamhost.com/legal/">Legal</a></li>
		</ul>
		<ul class="support">
			<li><h3><a href="https://dreamhost.com/support/">Support</a></h3></li>
			<li><a href="https://dreamhost.com/support/">Contact</a></li>
			<li><a href="http://wiki.dreamhost.com">Wiki</a></li>
			<li><a href="//discussion.dreamhost.com">Forums</a></li>
			<li><a href="https://dreamhost.com/legal/abuse/">Report Abuse</a></li>
		</ul>
		<div class="boring-stuff">
			<a href="https://dreamhost.com/legal/terms-of-service/">Terms of Service</a><a href="https://dreamhost.com/legal/privacy-policy/">Privacy Policy</a><a href="http://whoisweb.dreamhost.com/">Whois</a>
		</div>
	</div>

	<ul class="social">
		<li><a href="//twitter.com/dreamhost"><span>Twitter</span><i aria-hidden="true">l</i></a></li>
		<li><a href="//facebook.com/dreamhost"><span>Facebook</span><i aria-hidden="true">f</i></a></li>
		<li><a href="//instagram.com/dreamhost"><span>Instagram</span><i aria-hidden="true">i</i></a></li>
		<li><a href="//youtube.com/user/dreamhostusa"><span>YouTube</span><i aria-hidden="true">x</i></a></li>
	</ul>

	<div class="made-in-la">
		Dreamed up with <span class="love"></span> in Los Angeles.
	</div>

</footer>

</body>
</html>
