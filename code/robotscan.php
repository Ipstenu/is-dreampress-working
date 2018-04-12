<?php

/* Is DreamPress Working?
 */

if( !defined( 'ISDREAMPRESSWORKING' ) ) die( 'Direct access not permitted' );

// Include the debugger!
include_once( 'debug.php' );

// We are a post, we're checking for a URL, let's do the magic!
$_SESSION['last_submit'] = time();

// Set icons
$icons = array (
	'awesome' => '<i class="fas fa-heartbeat" style="color:#008000;"></i>',
	'good'    => '<i class="fas fa-beer" style="color:#008000;"></i>',
	'warning' => '<i class="fas fa-exclamation-triangle" style="color:#FF9933"></i>',
	'awkward' => '<i class="fas fa-meh" style="color:#FF9933;"></i>',
	'bad'     => '<i class="fas fa-bomb" style="color:#990000;"></i>',
);

// Sanitize the URL
$varnish_url  = (string) rtrim( filter_var( $_POST['url'], FILTER_SANITIZE_URL), '/' );

// Set Varnish Host for reasons
$varnish_host = (string) preg_replace( '#^https?://#', '', $varnish_url );

if ( preg_match("~^https://~i", $varnish_url ) ) {
	$varnish_host = $varnish_url;
} elseif ( !preg_match( "~^(?:f|ht)tp?://~i", $varnish_url ) ) {
	$varnish_host = $varnish_url  = "http://" . $varnish_url;
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
	// Get the response and headers
	$headers    = VarnishDebug::remote_get_headers( $varnish_url );

	// Preflight checklist
	$preflight  = VarnishDebug::preflight( $headers );
	?>

	<h2>Scanner Results</h2>
	<p>Our happy Robot Scanners found the following interesting information about your site.</p>

	<div class="clearfix spacing">
		<table class="table-standard wordpress-comparison">
		<?php
			// If we failed the preflight checks, we fail.
			if ( $preflight['preflight'] == false ) {
				?><tr>
					<td width="40px"><?php echo $icons['bad']; ?></td>
					<td><?php echo $preflight['message']; ?></td>
				</tr><?php
			} else {
				// We passed the checks, let's get the data!
				$output = VarnishDebug::get_all_the_results( $headers );
				foreach ( $output as $item ) {
					if ( $item !== false && is_array( $item ) ) {
						?><tr>
							<td width="40px"><?php echo $icons[ $item['icon'] ]; ?></td>
							<td><?php echo $item['message'] ?></td>
						</tr><?php
					}
				}
			}
		?>
		</table>

		<p>&nbsp;</p>

		<h2>Technical Details</h2>

		<p>Here are some more gory details on the domain.</p>

		<table class="table-standard wordpress-comparison">
			<tr><td width="200px" style="text-align:right;">The url we checked:</td><td><a href="<?php echo $varnish_host; ?>"><?php echo $varnish_host; ?></a></td></tr>
			<tr><td width="200px">&nbsp;</td><td><?php echo $headers[0]; ?></td></tr>
			<?php
			foreach ( $headers as $header => $key ) {
				if ( $header != '0' ) {
					echo '<tr><td style="text-align:right;">' . $header . ':</td><td>' . htmlspecialchars( $key ) . '</td></tr>';
				}
			}
			?>
		</table>
	</div>
	<?php
}
?>

<div class="section-cta box">
	<h2><strong>Check a different URL</strong></h2>
	<?php include_once( 'template/button.php' ); ?>
</div>