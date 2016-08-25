<?php
	include_once( "template/header.php" );
	include_once( "template/intro.php" );
?>

	<section id="managed-wordpress-hosting" class="section-centered dreampress-pros">
		<div class="section-wrap">

			<div class="clearfix spacing">
				<h2 class="spacing">What's the deal with Pagespeed?</h2>			
				<p>PageSpeed is no longer offered with DreamPress so you shouldn't have this issue. But. If you're on some other host, you may have issues.</p>
				<p>PageSpeed does a lot of pre-caching for you, generally by minifying and compressing CSS, JS and HTML. In order for it to work properly with Varnish, you must add the following to your .htaccess:</p>
<pre><code>
&lt;IfModule pagespeed_module&gt;
	ModPagespeed on
	ModPagespeedModifyCachingHeaders off
&lt;/IfModule&gt;
</code></pre>
			</div>

			<div class="section-cta box">
				<h2><strong>Ready to Scan?</strong></h2>
				<p>Now that you know all about PageSpeed, why not check your site again.</p>
				<a href="index.php" class="btn-signup">Check A Site</a>
			</div>

		</div>
	</section>

<?php
	include_once( "template/footer.php" );