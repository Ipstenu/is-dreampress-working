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

    <title>Is DreamPress working? Find out for sure!</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="assets/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <div id="container">
		<div id="content">
	    	<div id="title">Is DreamPress Working?</div>

<?php

// Define the filename so I can move this around.
$filename=$_SERVER["PHP_SELF"];

// Icons

$icon_good 		= '<i class="fa fa-beer" style="color:#008000;"></i>';
$icon_warning 	= '<i class="fa fa-exclamation-triangle" style="color:#FFD700"></i>';
$icon_bad		= '<i class="fa fa-bomb" style="color:#FF0000;"></i>';

/*
 * The Form
 *
 * We're doing a very basic check. Is it a post?
 */

if (!$_POST) {
	// If this is NOT a post, we should show the basic welcome.
	?>

	<p>So you have a site hosted on DreamPress and you're not sure if it's working right or caching fully? Let us help!</p>

	<?php
} elseif (!$_POST['url']) {
	// This IS a post, but you forgot a URL. Can't scan nothing.
	?>

	<div id="subtitle">Whoops!</div>

	<p>Did you forget to put in a URL?</p>

	<p>Try again!</p>

	<?php
} else {

	// We are a post, we're checking for a URL, let's do the magic!

	// Sanitize the URL
	$varnish_url  = (string) rtrim( filter_var($_POST['url'], FILTER_SANITIZE_URL), '/' );
	$varnish_url = (string) $_POST['url'];

	// Set Varnish Host for reasons
	$varnish_host = (string) preg_replace('#^https?://#', '', $varnish_url);

	if (preg_match("~^https://~i", $varnish_url)) {
		$varnish_url = "https://" . $varnish_url;
	} elseif (!preg_match("~^(?:f|ht)tp?://~i", $varnish_url)) {
	    $varnish_url = "http://" . $varnish_url;
	}

	// Is it a real URL?

	// Call StrictURLValidator becuase FILTER_VALIDATE_URL things http://foo is okay, even when you tell it you want the damn host.
	require_once 'StrictUrlValidator.php';

	// If we're SSL, bail early
	if ( preg_match("~^https://~i", $varnish_url) ) {
	?>
		<div id="subtitle">SSL Enabled URL</div>

		<p>Did you know Varnish can't cache SSL? This is <a href="https://www.varnish-cache.org/docs/trunk/phk/ssl.html">by design and is unlikely to change</a>.</p>

		<p>At this time, if you're using SSL, DreamPress will be running a little slower than it might. Sorry. We're working on that!</p>

	<?php
	} elseif ( StrictUrlValidator::validate( $varnish_url, true, true ) === false ) {
	?>

		<div id="subtitle">Egad!</div>

		<p><?php echo $varnish_url; ?> is not a valid URL.</p>

		<?php echo "<p>URL validation failed: " . StrictUrlValidator::getError() . "</p>"; ?>

		<p>Try again!</p>

	<?php
	} else {
		// Good, we're a real URL.
		$varnish_host = preg_replace('#^https?://#', '', $varnish_url);
	
		function curl_headers ( $url ) {
			$curl = curl_init();
			curl_setopt_array( $curl, array(
				CURLOPT_FAILONERROR => true,
				CURLOPT_CONNECTTIMEOUT => 30,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_FOLLOWLOCATION => false,
				CURLOPT_MAXREDIRS => 10,
			    CURLOPT_HEADER => true,
			    CURLOPT_NOBODY => true,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_URL => $url ) );
			
			return $curl;
		}
		
		function curl_response ( $connection ) {
			$varnish_result = curl_exec($connection);
			$varnish_headerinfo = curl_getinfo($connection);
			$varnish_headers = array();		
			$varnish_responseheader = explode( "\n" , trim( mb_substr($varnish_result, 0, $varnish_headerinfo['header_size'] ) ) );

			// Reformatting 0 entry for playback
			$varnish_headers[0] = $varnish_responseheader[0];
			unset($varnish_responseheader[0]);

			foreach( $varnish_responseheader as $line ) {
				list( $key, $val) = explode( ':' , $line , 2 );
				$varnish_headers[$key] = trim($val);
			}
	
			return $varnish_headers;		
		}
	
		$CurlConnection = curl_headers( $varnish_url );	
		$varnish_headers = curl_response( $CurlConnection );
	
		// If there's a 302 redirect then the get_headers 1 param breaks, so we'll compensate.
		
		do {
			$varnish_headers = curl_response( curl_headers( $varnish_headers['Location'] ) );
		} while ( strpos( $varnish_headers[0] , '200') === false );

		if ( !isset($varnish_headers['X-Cacheable']) ) {

			?>
			<div id="subtitle">Alas, no.</div>
			<p>Our robots were not find the "X-Varnish" header in the response from the server. That means Varnish is probably not running, which in turn means you actually may not be on DreamPress!</p>
			<p>If you're sure you <em>are</em> on DreamPress, take the information below and send it in a support ticket to our awesome techs. That will help us debug things even faster!</p>

			<?php

		} elseif ( strpos( $varnish_headers['X-Cacheable'], 'yes') !== false || strpos( $varnish_headers['X-Cacheable'], 'YES') !== false ) {
			?>
			<p><img src="assets/images/robot.presents.right.svg" style="float:left;margin:0 5px 0 0;" width="150" /></p>
			<div id="subtitle">Yes!</div>
			<p>Well, congratulations to you!</p>
			<p>Looks like DreamPress is running and so is our awesome Varnish cache.</p>
			<p>Want to know more about the site? Check the results below:</p><br style="clear:both;" />
			<?php

		} else {
			?>
			<div id="subtitle">Not Exactly</div>

			<p>Faaaail</p>

			<p>So here's the deal. Varnish is running, but it can't serve up the cache properly. Why? Check out the red-bombs and yellow-warnings below.</p>

			<?php
		}
		?>
		<table id="headers">
		<?php

		/*
		 * Pre Flight Notices!
		 *
		 * We're going to do some extra checks to make sure this is WordPress and that everything's above board.
		 */

		// WORDPRESS
		$tags = get_meta_tags($varnish_url);
		if ( isset($tags['generator']) && strpos( $tags['generator'] ,'WordPress') !== false ) {
			?><tr>
				<td width="10px"><?php echo $icon_good; ?></td>
				<td>This is a WordPress site!</td>
			</tr><?php
		} else {
			?><tr>
				<td width="10px"><?php echo $icon_warning; ?></td>
				<td>We're not sure if this is a WordPress site... Did you strip the meta tags?</td>
			</tr><?php
		}

		// DNS
		$nameservers = dns_get_record($varnish_host,DNS_NS);
		if ( isset( $nameservers ) && is_array( $nameservers )  ) {
			$nsrecords = '';
			foreach ($nameservers as $record) {
				$nsrecords .= $record['target'].' ';
			}
			if ( isset( $nsrecords ) && strpos( $nsrecords ,'dreamhost') !== false ) {
				?><tr>
					<td><?php echo $icon_good; ?></td>
					<td>DreamHost's nameservers are in use:<br /><?php echo $nsrecords; ?></td>
				</tr><?php
			} else {
				?><tr>
					<td><?php echo $icon_warning; ?></td>
					<td>Not using DreamHost's nameservers:<br /><?php echo $nsrecords; ?><br />(Ours are ns1.dreamhost.com, ns2.dreamhost.com, ns3.dreamhost.com)</td>
				</tr><?php
			}
		}

		// VARNISH
		if ( isset( $varnish_headers['X-Cacheable'] ) && strpos( $varnish_headers['X-Cacheable'] ,'YES') !== false ) {
			?><tr>
				<td><?php echo $icon_good; ?></td>
				<td>Varnish is running properly so caching is happening.</td>
			</tr><?php
		} elseif (isset( $varnish_headers['X-Cacheable'] ) && strpos( $varnish_headers['X-Cacheable'] ,'NO') !== false ) {
			?><tr>
				<td><?php echo $icon_bad; ?></td>
				<td>Varnish is running but can't cache.</td>
			</tr><?php
		} else {
			?><tr>
				<td><?php echo $icon_warning; ?></td>
				<td>We can't find Varnish on this server.</td>
			</tr><?php
		}

		// CLOUDFLARE
		if ( isset( $varnish_headers['Server'] ) && strpos( $varnish_headers['Server'] ,'cloudflare') !== false ) {
			?><tr>
				<td><?php echo $icon_warning; ?></td>
				<td>You're using Cloudflare and may experience some cache oddities.</td>
			</tr><?php
		}


		// PAGESPEED
		if ( isset( $varnish_headers['X-Mod-Pagespeed'] ) ) {
			?><tr>
				<td><?php echo $icon_warning; ?></td>
				<td>Mod Pagespeed is active. Make sure you've turned off caching headers! <a href="http://wiki.dreamhost.com/DreamPress#Can_I_use_PageSpeed_and_Varnish_together.3F">Don't know how? Read this!</a></td>
			</tr><?php
		}

		// SET COOKIE
		if ( isset( $varnish_headers['Set-Cookie'] ) ) {

			// If we're NOT an array...
			if ( !is_array( $varnish_headers['Set-Cookie'] ) ) {
				// Check for PHPSESSID
				if ( strpos( $varnish_headers['Set-Cookie'] , 'PHPSESSID') !== false ) {
					?><tr>
						<td><?php echo $icon_bad; ?></td>
						<td>You're setting a PHPSESSID cookie. This makes Varnish not deliver cached pages.</td>
					</tr><?php
				}
			} else {
				// Check all the cookies for known problems
				foreach ( $varnish_headers['Set-Cookie'] as $key => $cookie ) {
					// EDD
					if ( strpos( $cookie, 'edd_wp_session' ) !== false ) {
						?><tr>
							<td><?php echo $icon_warning; ?></td>
							<td>You're using Easy Digital Downloads. Currently it's setting a cookie on every page, which is busting the cache. Please set <code>define( 'EDD_USE_PHP_SESSIONS', true );</code> in your <code>wp-config.php</code> file.</td>
						</tr><?php
					}

					if ( strpos( $cookie, 'wfvt_' ) !== false ) {
						?><tr>
							<td><?php echo $icon_bad; ?></td>
							<td>WordFence is putting down cookies on every page load. Please disable that in your options (available from version 4.0.4 and up)</td>
						</tr><?php
					}
				}
			}
		}

		// CACHE-CONTROL
		if ( isset( $varnish_headers['Cache-Control'] ) && strpos( $varnish_headers['Cache-Control'] ,'no-cache') !== false ) {
			?><tr>
				<td><?php echo $icon_bad; ?></td>
				<td>Something is setting the header Cache-Control to 'no-cache' which means visitors will never get cached pages.</td>
			</tr><?php
		}

		// PRAGMA
		if ( isset( $varnish_headers['Pragma'] ) && strpos( $varnish_headers['Pragma'] ,'no-cache') !== false ) {
			?><tr>
				<td><?php echo $icon_bad; ?></td>
				<td>Something is setting the header Pragma to 'no-cache' which means visitors will never get cached pages.</td>
			</tr><?php
		}

		?>
		</table>

		<p>If you had any red-bomb warnings, you should review your plugins and themes to see if they're busting cache.</p>

		<p>Here are some more gory details about the site:</p>

		<?php
		// No matter what, we're going to show the headers etc. right? Wrong! If it wasn't a valid URL, we shouldn't

		if ( StrictUrlValidator::validate( $varnish_url, true, true ) === true ) {
			?>
			<table id="headers">
				<tr><td width="200px" style="text-align:right;">The url we checked:</td><td><?php echo $varnish_host; ?></td></tr>
				<tr><td width="200px">&nbsp;</td><td><?php echo $varnish_headers[0]; ?></td></tr>
				<?php
				foreach ($varnish_headers as $header => $key ) {
					if ( $header != '0' ) {

						if ( is_array($key) ) {
							$newkey = '';
							foreach ($key as $entry => $subkey ) {
								$newkey .= $subkey.'<br />';
							}
							$key = $newkey;
						}
						echo '<tr><td style="text-align:right;">'.$header.':</td><td>'.$key.'</td></tr>';
					}
				}
				?>
			</table>

	        <div style="margin: 30px;font-weight: bold;font-size: 18pt;">Check another site!</div>
	    <?php
		    }
	}
}
?>

	<form method="POST" action="<?php echo $filename; ?>" id="check_dreampress_form">
          <input name="url" id="url" value="" type="text">
          <input name="check_it" id="check_it" value="Check It!" type="submit">
    </form>

      </div><!-- end content -->
      <div id="footer">
        <div>Brought to you by:</div>
        <div><a href="https://www.dreamhost.com"><img src="assets/images/logo.dreamhost.svg" width="200px" /></a><div>
        <div><a href="https://www.dreamhost.com" target="_new" >DreamHost</a></div>
      </div><!-- end footer -->
    </div><!-- end container -->
  </body>
</html>