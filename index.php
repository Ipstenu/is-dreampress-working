<?php

/* Is DreamPress Working?
 *
 * This tool checks if your DreamPress site is running properly, with varnish
 * and all those cool doo-dads. It also checks your pagespeed scores via 
 * GTMetrix's API but only has 20 checks a day so ... er ... be nice?
 *
 */

	// Sessions
	@ob_start();
	session_start();

	define( 'ISDREAMPRESSWORKING', TRUE );

	include_once( 'template/header.php' );
	include_once( 'code/functions.php' );
	include_once( 'code/StrictUrlValidator.php' );
?>

<div id="dreampress" class="main-content">
	<section class="section-intro maxwidth-700">
		<!-- Placeholder -->
	</section>

	<section id="managed-wordpress-hosting" class="section-centered dreampress-pros">
		<div class="section-wrap">
			
			<div class="clearfix spacing">
				<h1>Is DreamPress Working? <strong>BETA!!!</strong></h1>
				<p><a href="/" class="btn btn--large js-scroll-to">Updated: March 15, 2018</a></p>
			</div>

		<?php
		// Define the filename so I can move this around.
		$filename = $_SERVER["PHP_SELF"];

		/*
		 * The Form
		 *
		 * We're doing a very basic check. Is it a post?
		 */

		if ( !$_POST ) {
			// If this is NOT a post, we should show the basic welcome.
			?>

			<div class="clearfix spacing">
				<p class="section__subhead">So you have a site hosted on <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a> and you're not sure if it's working right or caching fully? Let us help!</p>
				<p>This site will actually check any site and give you results, so other hosts will also show up as working if they happen to use Varnish. But you should check out <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a>. We're pretty cool.</p>
			</div>
			<div class="section-cta box">
				<h2><strong>Check A Site!</strong></h2>
				<?php include_once( 'template/button.php' ); ?>
			</div>

			<?php
		} elseif (!$_POST['url']) {
			// This IS a post, but you forgot a URL. Can't scan nothing.
			?>

			<div class="clearfix spacing">
				<h2 class="spacing">We can't tell what site you're trying to check.</h2>
				<p>Did you forget to put in a URL?</p>
			</div>
			<div class="section-cta box">
				<h2><strong>Try Again!</strong></h2>
				<?php include_once( 'template/button.php' ); ?>
			</div>
		<?php
		} else {

			// We are a post, we're checking for a URL, let's do the magic!

			if ( isset($_SESSION['last_submit']) && time()-$_SESSION['last_submit'] < 30 ) {
				?>
	
				<div class="clearfix spacing">
					<h2 class="spacing">Hold on there!</h2>
					<p>You're checking too many sites too fast.</p>
					<p>We get it, though. You want to make sure you fix everything on your site and that it's working perfectly. Before you re-run a test, make sure you've changed everything, uploaded it, <em>and</em> flush Varnish on your server.</p>
					<p>You did all that? Cool!</p>
					<p>Please wait at least 60 seconds and try again.</p>
				</div>
				<div class="section-cta box">
					<h2><strong>Ready? Give it another go!</strong></h2>
					<?php include_once( 'template/button.php' ); ?>
				</div>

				<?php
			} else {
				$_SESSION['last_submit'] = time();
			
				// Sanitize the URL
				$varnish_url  = (string) rtrim( filter_var( $_POST['url'], FILTER_SANITIZE_URL), '/' );
				$varnish_url  = (string) $_POST['url'];
			
				// Set Varnish Host for reasons
				$varnish_host = (string) preg_replace( '#^https?://#', '', $varnish_url );
			
				if ( preg_match("~^https://~i", $varnish_url ) ) {
					$varnish_host = $varnish_url;
					$varnish_url  = $varnish_url;
				} elseif ( !preg_match( "~^(?:f|ht)tp?://~i", $varnish_url ) ) {
					$varnish_host = "http://" . $varnish_url;
					$varnish_url  = "http://" . $varnish_url;
				}

				// Is it a real URL?			
				if ( StrictUrlValidator::validate( $varnish_url, true, true ) === false ) {
					?>
					<div class="clearfix spacing">
						<h2 class="spacing">Egad!</h2>
						<p><?php echo $varnish_url; ?> is not a valid URL.</p>
						<p>URL validation failed: <strong><?php echo StrictUrlValidator::getError(); ?></strong></p>
					</div>
					<div class="section-cta box">
						<h2><strong>Double check your typing and try again.</strong></h2>
						<?php include_once( 'template/button.php' ); ?>
					</div>	
					<?php
				} else {
					// Good, we're a real URL.
					// Pull the lever, Kronk!
					$CurlConnection  = curl_headers( $varnish_url );	
					$varnish_headers = curl_response( $CurlConnection );

					// If there's a 302 redirect then the get_headers 1 param breaks, so we'll compensate.
					while ( strpos( $varnish_headers[0] , '200') === false ) {
						$varnish_headers = curl_response( curl_headers( $varnish_headers['Location'] ) );
					}

					?><div class="clearfix spacing"><?php
					// Check if the headers are set AND if the values are valid
					$cacheheaders_set = ( isset( $varnish_headers['X-Cacheable'] ) || isset( $varnish_headers['X-Varnish'] ) || isset( $varnish_headers['X-Cache'] ) || is_numeric( strpos( $varnish_headers['Via'], 'arnish' ) ) )? true : false;
					$cacheheaders_val = ( strpos( $varnish_headers['X-Cacheable'], 'yes' ) !== false || strpos( $varnish_headers['X-Cacheable'], 'YES' ) !== false && isset( $varnish_headers[ 'Age' ] ) && $varnish_headers[ 'Age' ] > 0 )?  true : false;

					if ( !$cacheheaders_set ) {
						?>
						<h2 class="spacing">Alas, no!</h2>
						<p>Our robots were not able to find the "X-Varnish" header in the response from the server. That means Varnish is probably not running, which in turn means you actually may not be on DreamPress!</p>
						<p>If you're sure you <em>are</em> on DreamPress, take the information below and send it in a support ticket to our awesome techs. That will help us debug things even faster!</p>
						<?php
					} elseif ( $cacheheaders_val ) {
						?>
						<h2>Woot! YES!!</h2>
						<p>Well, congratulations to you!</p>
						<p>Looks like your site is running with a Varnish cache.</p>
						<p>Want to know more about the site? Check the results below.</p>
						<?php
					} else { 
					?>
						<h2>Not Exactly...</h2>
						<p>Varnish is running, but it can't serve up the cache properly. Why? Check out the red-bombs and yellow-warnings below.</p>
					<?php
					} 
				?>

					<h2>Scanner Results</h2>
					<p>Our happy Robot Scanners found the following interesting information about your site.</p>
					
					<?php include_once( 'code/robotscan.php' ); ?>

			<?php
			// No matter what, we're going to show the headers etc. right? Wrong! If it wasn't a valid URL, we shouldn't
			if ( StrictUrlValidator::validate( $varnish_url, true, true ) === true ) {
				?>

				<p>&nbsp;</p>

				<h2>Technical Details</h2>

				<p>Here are some more gory details on the domain.</p>
	
				<table class="table-standard wordpress-comparison">
					<tr><td width="200px" style="text-align:right;">The url we checked:</td><td><a href="<?php echo $varnish_host; ?>"><?php echo $varnish_host; ?></a></td></tr>
					<tr><td width="200px">&nbsp;</td><td><?php echo $varnish_headers[0]; ?></td></tr>
					<?php
					foreach ( $varnish_headers as $header => $key ) {
						if ( $header != '0' ) {
							echo '<tr><td style="text-align:right;">' . $header . ':</td><td>' . htmlspecialchars( $key ) . '</td></tr>';
						}
					}
					?>
				</table>

				<!--
				// Currently we're disabling GTMetrix - it's not really that useful. Also it's not working
				<p>&nbsp;</p>

				<h2>GTMetrix Scan</h2>

				<?php //include( 'code/gtmetrix.php' ); ?>
				-->
			</div>

			<div class="section-cta box">
				<h2><strong>Check a different URL</strong></h2>
				<?php include_once( 'template/button.php' ); ?>
			</div>
			<?php
			}
		}
	}
}
?>
		</div>
	</section>

<?php
	include_once( 'template/footer.php' );