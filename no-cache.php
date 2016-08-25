<?php
	include_once( "template/header.php" );
	include_once( "template/intro.php" );
?>

	<section id="managed-wordpress-hosting" class="section-centered dreampress-pros">
		<div class="section-wrap">

			<div class="clearfix spacing">
				<h2 class="spacing">What's the deal with no-cache?</h2>
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

			</div>

			<div class="section-cta box">
				<h2><strong>Ready to Scan?</strong></h2>
				<p>Now that you know all about No-Cache, why not check your site again.</p>
				<a href="index.php" class="btn-signup">Check A Site</a>
			</div>

		</div>
	</section>

<?php
	include_once( "template/footer.php" );