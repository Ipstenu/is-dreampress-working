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

    <title>What's the deal with no-cache?</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="assets/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <div id="container">
		<div id="content">
	    	<div id="title">What's the deal with no-cache?</div>
	    	
	    	<p>'no-cache' is a header that's sent by a plugin or theme that is, literally, telling browsers (and Varnish) <em>not</em> to cache your site.</p>
	    	
	    	<p>I bet you just figured out why that was a bad thing, eh?</p>
	    	
	    	<p>The problem here is that <em>finding</em> what's calling that, because it's not always obvious. Thankfully there are two main ways that no-cache is set: pragma and cache-control. You can run a basic grep on the files like this:</p>
		    	
		    <pre>
grep -R pragma . 
grep -R cache-control .
grep -R Cache-Control .
			</pre>
			
			<p>This will net you a lot of false positives, though, because many plugins and themes legitimately use no-cache for admin features (which aren't really cached anyway). It's possible to force those things by using the WordPress function <code>send_headers()</code>, however that level of debugging is outside the realm of what DreamHost can offer.</p>
			<p>The other serious issue is that many javascript libraries put this in as well and they are <em>not</em> detectable in a sane way.</p>
			
			<p>The best way to debug an issue like this is to disable plugins, rerun the test, and then start reenabling them one at a time until it happens again.</p>
	    	
      </div><!-- end content -->
      <div id="footer">
        <div>Brought to you by:</div>
        <div><a href="https://www.dreamhost.com" target="_new" ><img src="assets/images/logo.dreamhost.svg" width="200px" alt="DreamHost" title="DreamHost" /></a><div>
      </div><!-- end footer -->
    </div><!-- end container -->
  </body>
</html>