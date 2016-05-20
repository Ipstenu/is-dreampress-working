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

    <title>What's the deal with CloudFlare?</title>
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
			<h2 class="spacing">What's the deal with CloudFlare?</h2>

				<p>First up, CloudFlare and DreamPress work just fine together. If you're using the DreamHost Panel to activate CloudFlare, we even take care of all the hard work for you (except for one thing...). You need to make sure that you've set WordPress to use the www-prefix in your domain.</p>
	    			<h3>Make WordPress use WWW</h3>
	    	
			    	<ol>
				    	<li>Go to WP Admin -> Settings -> General</li>
				    <li>Change both the home and site URLs to http://www.example.com/</li>
				    <li>Do a search/replace of all your post content to change everything to use www</li>
			    	</ol>
			    	
				<p>That last one may be hard. If you use SSH, we make it easy on DreamPress. Just log in and type this:</p>
			    	<pre>wp search-replace http://example.com http://www.example.com --dry-run</pre>
	    	
			    	<p>Check the output. If everything looks okay run it again without <code>--dry-run</code> at the end.</p>
			    	<p>If you need a plugin, we suggest <a href="https://wordpress.org/plugins/better-search-replace/">Better Search Replace</a>.</p>
				<p>Then check to make sure that the Varnish IP is set properly. If you've chosen to use our panel to configure CloudFlare then this will be handled automatically:</p>
				<pre>wp option get vhp_varnish_ip</pre>
	    	
				<h3>If you're NOT using Panel...</h3>
	    	
				<p>If you're not using our panel, there's one extra step you need to do. Go into Panel and get your DNS information. You need the IP address for your domain.</p>
				<p>Edit your wp-config.php file and add this:</p>
	    	
				<pre>define('VHP_VARNISH_IP','123.45.67.89');</pre>
	    	
				<p>Replace "123.45.67.89" with the IP of your Varnish Server (not CloudFlare, Varnish). DO NOT put in http in this define statement. </p>
				<p>For more information, read <a href="http://wiki.dreamhost.com/DreamPress#Can_I_use_CloudFlare_and_Varnish_together.3F">Using CloudFlare and Varnish</a></p>
		</div>
<p>&nbsp;</p>

</section>

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
