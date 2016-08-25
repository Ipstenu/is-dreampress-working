<?php
	include_once( "template/header.php" );
	include_once( "template/intro.php" );
?>

	<section id="managed-wordpress-hosting" class="section-centered dreampress-pros">
		<div class="section-wrap">

			<div class="clearfix spacing">
				<h2 class="spacing">What's the deal with max-age headers?</h2>
				    	
			    	<p>'max-age' is a header call that's sent via cache-control that determines how old the page can be before cache needs to delete it's copy and generate a new one. If max-age is 0 (or set to some date like 1981), then it will tell Varnish and your browser cache to not cache a page, and always spin up a new copy.</p>
	    		    	
					<p>The basic grep to find these is as follows:</p>
		    	
					<pre>grep -R "max-age=0" .</pre>
			
					<p>Sadly, many javascript libraries put this in as well and they are <em>not</em> detectable in a sane way.</p>
			
					<p>The best way to debug an issue like this is to disable plugins, rerun the test, and then start reenabling them one at a time until it happens again.</p>
			</div>

			<div class="section-cta box">
				<h2><strong>Ready to Scan?</strong></h2>
				<p>Now that you know all about Max-Age, why not check your site again.</p>
				<a href="index.php" class="btn-signup">Check A Site</a>
			</div>

		</div>
	</section>

<?php
	include_once( "template/footer.php" );