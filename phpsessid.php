<?php
	include_once( "template/header.php" );
	include_once( "template/intro.php" );
?>

	<section id="managed-wordpress-hosting" class="section-centered dreampress-pros">
		<div class="section-wrap">

			<div class="clearfix spacing">
				<h2 class="spacing">What's the deal with PHPSESSID?</h2>	
				<p>Any time a plugin or theme makes use of PHP Sessions and gives a new session to every user, it will cause performance issues with Varnish. For certain plugins (like ecommerce ones), we have configured our servers to be more forgiving, and any well-coded plugin will be okay. If used correctly, Sessions isn't that evil, as it's similar to using a cookie when a user needs one. It's telling Varnish not to cache information that shouldn't be cached. It would be BETTER if the plugin used cookies, of course, but those too tell the site not to be cached!</p>
				<p>You can scan for sessions via Grep on the command line:</p>

				<pre>
grep -Ri "PHPSESSID" ./wp-content/plugins ; grep -Ri "session_start" ./wp-content/plugins ; grep -Ri "start_session" ./wp-content/plugins
grep -Ri "PHPSESSID" ./wp-content/themes ; grep -Ri "session_start" ./wp-content/themes ; grep -Ri "start_session" ./wp-content/themes
				</pre>

				<p>NOTE: The DreamObjects Backup Plugin has this code, but does not use it. It's part of the SDK so don't worry about that.</p>

				<p>If you want to check in a specific theme, it's fastest to just CD into that theme folder (wp theme status, pick the green one ;) ) and do this:</p>

				<pre>grep -Ri "PHPSESSID" . ; grep -Ri "session_start" . ; grep -Ri "start_session" .</pre>

				<p>Sometimes it's okay to use PHPSESSID or PHP Sessions, and sometimes it's not. This is really confusing and is best explained via examples. Keep in mind, even if we DO track it down to a point where we're reasonable sure the plugin or theme is the problem, you will still want to verify by turning off that plugin or switching themes. This is, in fact, why we always test WP that way. Once you narrow down what might be the cause, you can turn off plugins selectively, for greater results.</p>
			</div>

			<div class="section-cta box">
				<h2><strong>Ready to Scan?</strong></h2>
				<p>Now that you know all about PHP Sessions, why not check your site again.</p>
				<a href="index.php" class="btn-signup">Check A Site</a>
			</div>

		</div>
	</section>

<?php
	include_once( "template/footer.php" );