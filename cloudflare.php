<?php
	include_once( "template/header.php" );
	include_once( "template/intro.php" );
?>

	<section id="managed-wordpress-hosting" class="section-centered dreampress-pros">
		<div class="section-wrap">

			<div class="clearfix spacing">
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
				<p>For more information, read <a href="https://help.dreamhost.com/hc/en-us/articles/214581728-DreamPress-FAQs">Using CloudFlare and Varnish</a></p>
			</div>

			<div class="section-cta box">
				<h2><strong>Ready to Scan?</strong></h2>
				<p>Now that you know all about CloudFlare, why not check your site again.</p>
				<a href="index.php" class="btn-signup">Check A Site</a>
			</div>

		</div>
	</section>

<?php
	include_once( "template/footer.php" );