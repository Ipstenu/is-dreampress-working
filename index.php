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

<?php

// Define the filename so I can move this around.
$filename=$_SERVER["PHP_SELF"];

/*
 * Get File Contents
 *
 * This will be used to parse headers later for WP
*/

function file_get_contents_curl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

/*
 * The Form
 *
 * We're doing a very basic check. Is it a post?
 */

if (!$_POST) {
	// If this is NOT a post, we should show the basic welcome.
	?>
	
	<div id="title">Is DreamPress Working?</div>
	
	<p>So you have a site hosted on DreamPress and you're not sure if it's working right or caching fully? Let us help!</p>
	
	<?php
} elseif (!$_POST['url']) {
	// This IS a post, but you forgot a URL. Can't scan nothing.
	?>

	<div id="title">Whoops!</div>
	
	<p>Did you forget to put in a URL?</p>
	
	<p>Try again!</p>

	<?php	
} else {

	// We are a post, we're checking for a URL, let's do the magic!

	// Sanitize the URL
	$varnish_url = (string) rtrim( filter_var($_POST['url'], FILTER_SANITIZE_URL), '/' );
	
	// Set Varnish Host for reasons
	$varnish_host = preg_replace('#^https?://#', '', $varnish_url);
	
	if (preg_match("~^https://~i", $varnish_url)) {
		$varnish_url = "https://" . $varnish_url;
	} elseif (!preg_match("~^(?:f|ht)tp?://~i", $varnish_url)) {
	    $varnish_url = "http://" . $varnish_url;
	}
		
	// Is it a real URL?

	// Call StrictURLValidator becuase FILTER_VALIDATE_URL things http://foo is okay, even when you tell it you want the damn host.
	require_once 'StrictUrlValidator.php';

	if ( preg_match("~^https://~i", $varnish_url) ) {
	?>
		<div id="title">SSL Enabled URL</div>
		
		<p>Did you know Varnish can't cache SSL? This is <a href="https://www.varnish-cache.org/docs/trunk/phk/ssl.html">by design and is unlikely to change</a>.</p>
		
		<p>At this time, if you're using SSL, DreamPress will be running a little slower than it might. Sorry. We're working on that!</p>
	
	<?php	
	} elseif ( StrictUrlValidator::validate( $varnish_url, true, true ) === false ) {
	?>

		<div id="title">Egad!</div>
		
		<p><?php echo $varnish_url; ?> is not a valid URL.</p>

		<?php echo "<p>URL validation failed: " . StrictUrlValidator::getError() . "</p>"; ?>
		
		<p>Try again!</p>

	<?php	
	} else {

	// Sanitize!
	

	// Good, we're a real URL.
	$varnish_host = preg_replace('#^https?://#', '', $varnish_url);

		$default_opts = array(
		  'http'=>array(
		    'method'=>"HEAD",
		    'header'=>
		    	"Host: $varnish_host\r\n" .
		    	"Accept-Encoding: gzip, deflate\r\n" .
		    	"Accept: */*",
		  )
		);
		
		$default = stream_context_set_default($default_opts);
		$varnish_headers = get_headers( $varnish_url, 1 );

		// If there's a 302 redirect then the get_headers 1 param breaks, so we'll compensate.
		if ( substr($varnish_headers[0], 9, 3) != '200'  ) {
			$varnish_headers = get_headers( $varnish_headers['Location'], 1 );
		}
		
		if ( !isset($varnish_headers['X-Cacheable']) ) {
			
			?>
			<div id="title">Alas, no.</div>
			
			<p>Our robots were not find the "X-Varnish" header in the response from the server. That means Varnish is probably not running, which in turn means you actually may not be on DreamPress!</p>
			
			<p>If you're sure you <em>are</em> on DreamPress, take the information below and send it in a support ticket to our awesome techs. That will help us debug things even faster!</p>
					
			<?php
			
		} elseif ( strpos( $varnish_headers['X-Cacheable'], 'yes') !== false || strpos( $varnish_headers['X-Cacheable'], 'YES') !== false ) {
			?>
			<p><img src="assets/images/robot.presents.right.svg" style="float:left;margin:0 5px 0 0;" width="150" /></p>
			<div id="title">Yes!</div>
			<p>Well, congratulations to you!</p>
			<p>Looks like DreamPress is running and so is our awesome Varnish cache.</p><br style="clear:both;" />
			<?php

		} else {
			?>
			<div id="title">Not Exactly</div>
			
			<p>Faaaail</p>
			
			<p>So here's the deal. Varnish is running, but it can't serve up the cache properly. Why? Check out the red-bombs and yellow-warnings below.</p> 
			
			<?php
		}

		?>
		
		<p>
		<?php

		/*
		 * Pre Flight Notices!
		 *
		 * We're going to do some extra checks to make sure this is WordPress and that everything's above board.
		 */
		
		// WordPress
		$tags = get_meta_tags($varnish_url);
		if ( isset($tags['generator']) && strpos( $tags['generator'] ,'WordPress') !== false ) {
			?><i class="fa fa-beer" style="color:#008000;"></i> We have detected this is a WordPress site!<?php
		} else {
			?><i class="fa fa-exclamation-triangle" style="color:#FFD700"></i> We're not sure if this is a WordPress site... Did you strip the meta tags?<?php
		}
		
		// DNS
		$nameservers = dns_get_record($varnish_host,DNS_NS);
		foreach ($nameservers as $record) {
					echo '<li>'.$record['target'].'</li>';
				}

		// PAGESPEED
		
		if ( isset( $varnish_headers['X-Mod-Pagespeed'] ) ) {
			?><br /><i class="fa fa-exclamation-triangle" style="color:#FFD700"></i> Mod Pagespeed is active. Make sure you've turned off caching headers! <a href="http://wiki.dreamhost.com/DreamPress#Can_I_use_PageSpeed_and_Varnish_together.3F">Don't know how? Read this!</a><?php
		}
		
		// SET COOKIE
		if ( isset( $varnish_headers['Set-Cookie'] ) ) {
			
			// If we're NOT an array...
			if ( !is_array( $varnish_headers['Set-Cookie'] ) ) {
				// Check for PHPSESSID
				if ( strpos( $varnish_headers['Set-Cookie'] , 'PHPSESSID') !== false ) {
					?><br /><i class="fa fa-bomb" style="color:#FF0000;"></i> You're setting a PHPSESSID cookie. This makes Varnish not deliver cached pages.<?php
				}
			} else {
				// Check all the cookies for known problems
				foreach ( $varnish_headers['Set-Cookie'] as $key => $cookie ) {
					// EDD
					if ( strpos( $cookie, 'edd_wp_session' ) !== false ) {
						?><br /><i class="fa fa-exclamation-triangle" style="color:#FFD700"></i> You're using Easy Digital Downloads. Currently it's setting a cookie on every page, which is busting the cache. Please set <code>define( 'EDD_USE_PHP_SESSIONS', true );</code> in your <code>wp-config.php</code> file.<?php
					}
					
					if ( strpos( $cookie, 'wfvt_' ) !== false ) {
						?><br /><i class="fa fa-bomb" style="color:#FF0000;"></i> WordFence is putting down cookies on every page load. Please disable that in your options (available from version 4.0.4 and up)<?php
					}
				}
			}
		}
		
		// CACHE-CONTROL
		if ( isset( $varnish_headers['Cache-Control'] ) && strpos( $varnish_headers['Cache-Control'] ,'no-cache') !== false ) {
			?><br /><i class="fa fa-bomb" style="color:#FF0000;"></i> Something is setting the header Cache-Control to 'no-cache' which means visitors will never get cached pages.<?php
		}

		// PRAGMA
		if ( isset( $varnish_headers['Pragma'] ) && strpos( $varnish_headers['Pragma'] ,'no-cache') !== false ) {
			?><br /><i class="fa fa-bomb" style="color:#FF0000;"></i> Something is setting the header Pragma to 'no-cache' which means visitors will never get cached pages.<?php
		}
		?>
		</p>
		
		<p>If you had any red-bomb warnings, you should review your plugins and themes to see if they're busting cache.</p>

		<p>Here are some more gory details about the site:</p>

		<?php
		// No matter what, we're going to show the headers etc. right? Wrong! If it wasn't a valid URL, we shouldn't
		
		if ( StrictUrlValidator::validate( $varnish_url, true, true ) === true ) {
			?>				
			<table id="headers">
				<tr class="even"><td width="200px" style="text-align:right;">The url we checked:</td><td><?php echo $varnish_host; ?></td></tr>
				<tr class="odd"><td width="200px">&nbsp;</td><td><?php echo $varnish_headers[0]; ?></td></tr>
				<?php
				$rownum = '1';
				foreach ($varnish_headers as $header => $key ) {
					if ($rownum & 1) { 
						$rowclass = "even";
					} else {
						$rowclass = "odd";
					}
					if ( $header != '0' ) {
						
						if ( is_array($key) ) {
							$newkey = '';
							foreach ($key as $entry => $subkey ) {
								$newkey .= $subkey.'<br />';
							}
							$key = $newkey;
						}
						
						echo '<tr class="'.$rowclass.'"><td width="200px" style="text-align:right;">'.$header.':</td><td>'.$key.'</td></tr>';
						$rownum++;
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
        <div>isdreampressworking.com brought to you by:</div>
        <div><a href="https://www.dreamhost.com"><img src="assets/images/logo.dreamhost.svg" width="200px" /></a><div>
        <div><a href="https://www.dreamhost.com" target="_new" >DreamHost</a></div>
      </div><!-- end footer -->
    </div><!-- end container -->
  </body>
</html>