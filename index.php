<?php

/* Is DreamPress Working?
 *
 * This tool checks if your DreamPress site is running properly, with varnish
 * and all those cool doo-dads.
 *
 */

	// Sessions
	@ob_start();
	session_start();

	define( 'ISDREAMPRESSWORKING', TRUE );

	include_once( 'template/header.php' );
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
				<p><a href="/" class="btn btn--large js-scroll-to">Updated: April 12, 2018</a></p>
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
		} elseif ( !$_POST['url'] ) {
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
		} elseif ( isset( $_SESSION['last_submit'] ) && ( time() - $_SESSION['last_submit'] < 30 ) ) {
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
			include_once( 'code/robotscan.php' );
		}
		?>
		</div>
	</section>

<?php
	include_once( 'template/footer.php' );