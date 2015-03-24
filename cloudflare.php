<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
  <head>
 	<meta charset="utf-8"></meta>
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"></meta>
	<meta content="initial-scale=1" name="viewport"></meta>
	<meta content="DreamPress is DreamHost's managed WordPress Offering. Having problems? Come check if it's working." name="description"></meta>

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
    <link rel="stylesheet" href="assets/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <div id="container">
		<div id="content">
	    	<div id="title">What's the deal with CloudFlare?</div>
	    	
	    	<p>First up, CloudFlare and DreamPress work just fine together. If you're using the DreamHost Panel to activate CloudFlare, we even take care of all the hard work for you (except for one thing...). You need to make sure that you've set WordPress to use the www-prefix in your domain.</p>
	    	
	    	<h2>Make WordPress use WWW</h2>
	    	
	    	<p>1. Go to WP Admin -> Settings -> General
		    <br />2. Change both the home and site URLs to http://www.example.com/
		    <br />3. Do a search/replace of all your post content to change everything to use www</p>
	    	
	    	<p>That last one may be hard. If you use SSH, we make it easy on DreamPress. Just log in and type this:</p>
	    	<pre>
		    	wp search-replace http://example.com http://www.example.com --dry-run
	    	</pre>
	    	
	    	<p>Check the output. If everything looks okay run it again without <code>--dry-run</code> at the end.</p>
	    	<p>If you need a plugin, we suggest <a href="https://wordpress.org/plugins/better-search-replace/">Better Search Replace</a>.</p>
	    	
	    	<p>Then check to make sure that the Varnish IP is set properly. If you've chosen to use our panel to configure CloudFlare then this will be handled automatically:</p>
		    	
		    <pre>
			    wp option get vhp_varnish_ip
		    </pre>
	    	
	    	<h2>If you're NOT using Panel...</h2>
	    	
	    	<p>If you're not using our panel, there's one extra step you need to do. Go into Panel and get your DNS information. You need the IP address for your domain.</p>
	    	
	    	<p>Edit your wp-config.php file and add this:</p>
	    	
	    	<pre>
		    	define('VHP_VARNISH_IP','123.45.67.89');
	    	</pre>
	    	
	    	<p>Replace "123.45.67.89" with the IP of your Varnish Server (not CloudFlare, Varnish). DO NOT put in http in this define statement. </p>
		    	
		    
		    <p>For more information, read <a href="http://wiki.dreamhost.com/DreamPress#Can_I_use_CloudFlare_and_Varnish_together.3F">Using CloudFlare and Varnish</a></p>
	    	
	    	
      </div><!-- end content -->
      <div id="footer">
        <div>Brought to you by:</div>
        <div><a href="https://www.dreamhost.com" target="_new" ><img src="assets/images/logo.dreamhost.svg" width="200px" alt="DreamHost" title="DreamHost" /></a><div>
      </div><!-- end footer -->
    </div><!-- end container -->
  </body>
</html>