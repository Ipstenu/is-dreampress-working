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

    <title>What's the deal with PHPSESSID?</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="assets/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <div id="container">
		<div id="content">
	    	<div id="title">What's the deal with PHPSESSID?</div>
	    	
	    	<p>Any time a plugin or theme makes use of PHP Sessions and gives a new session to every user, it will cause performance issues with Varnish. For certain plugins (like ecommerce ones), we have configured our servers to be more forgiving, and any well-coded plugin will be okay. If used correctly, Sessions isn't that evil, as it's similar to using a cookie when a user needs one. It's telling Varnish not to cache information that shouldn't be cached. It would be BETTER if the plugin used cookies, of course, but those too tell the site not to be cached!</p>

			<p>You can scan for sessions by a simple Grep:</p>

			<pre>
				grep -Ri "PHPSESSID" ./wp-content/plugins ; grep -Ri "session_start" ./wp-content/plugins ; grep -Ri "start_session" ./wp-content/plugins
				grep -Ri "PHPSESSID" ./wp-content/themes ; grep -Ri "session_start" ./wp-content/themes ; grep -Ri "start_session" ./wp-content/themes
			</pre>

			<p>NOTE: DreamObjects has this code, but does not use it. It's part of the SDK so don't worry about that.</p>

			<p>If you want to check in a specific theme, it's fastest to just CD into that theme folder (wp theme status, pick the green one ;) ) and do this:</p>

			<pre>grep -Ri "PHPSESSID" . ; grep -Ri "session_start" . ; grep -Ri "start_session" .</pre>

			<p>Sometimes it's okay to use PHPSESSID or PHP Sessions, and sometimes it's not. This is really confusing and is best explained via examples. Keep in mind, even if we DO track it down to a point where we're reasonable sure the plugin or theme is the problem, you will still want to verify by turning off that plugin or switching themes. This is, in fact, why we always test WP that way. Once you narrow down what might be the cause, you can turn off plugins selectively, for greater results.</p>
	    	
	    	
      </div><!-- end content -->
      <div id="footer">
        <div>Brought to you by:</div>
        <div><a href="https://www.dreamhost.com" target="_new" ><img src="assets/images/logo.dreamhost.svg" width="200px" alt="DreamHost" title="DreamHost" /></a><div>
      </div><!-- end footer -->
    </div><!-- end container -->
  </body>
</html>