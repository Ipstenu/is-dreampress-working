<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
  <head>
 	<meta charset="utf-8"></meta>
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"></meta>
	<meta content="initial-scale=1" name="viewport"></meta>
	<meta content="DreamPress is DreamHost's managed WordPress Offering. Having problems? Come check if it's working." name="description"></meta>

	<link href='//fonts.googleapis.com/css?family=Ubuntu:400,300italic,500,700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

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
    <link rel="stylesheet" href="assets/site.css" type="text/css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="assets/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body id="top" style="margin-bottom: 597px;">

	<header id="page-header">
	<div class="button-area">
		<a href="https://panel.dreamhost.com" class="btn-login">LOGIN</a>
		<a href="http://webmail.dreamhost.com" class="btn-login">WEBMAIL</a>
	</div>
	<a href="/" id="logo"><span>DreamHost</span></a>
	<button class="activate-menu">Menu</button>
	<nav class="header-nav">
		<button class="deactivate-menu">&times;</button>
		<ul>
			<li><a href="https://dreamhost.com/hosting/">Hosting</a></li>
			<li><a href="https://dreamhost.com/cloud/">Cloud</a></li>
			<li><a href="https://dreamhost.com/wordpress">WordPress</a></li>
			<li><a href="https://dreamhost.com/domains/">Domains</a></li>
			<li><a href="https://dreamhost.com/support/">Contact</a></li>
		</ul>
	</nav>

</header>

<div id="dreampress" class="main-content">
	<section class="section-intro maxwidth-700">
		<div class="section-wrap">
			<h1><a href="index.php">Is DreamPress Working?</a></h1>
			<p class="section-intro__lead-in">Please don't give this URL to customers yet! It's a work in progress!</p>
			<p>This site is a work in progress. Please contact Mika with issues.</p>
			<p>To use this properly, fill in the URL of the site and click the "Check It!" button. Go over the detailed results. Many have links to additional information. Hint. Hint. Read and follow the information to debug.</p>
			<span class="btn-signup">Last Updated: May 20, 2016</span>
		</div>
	</section>

	<section class="section-light maxwidth-700">

<?php

// Define the filename so I can move this around.
$filename=$_SERVER["PHP_SELF"];

// Icons
$icon_awesome	= '<i class="fa fa-heartbeat" style="color:#FF0099;"></i>';
$icon_good 		= '<i class="fa fa-beer" style="color:#008000;"></i>';
$icon_warning 	= '<i class="fa fa-exclamation-triangle" style="color:#FF9933"></i>';
$icon_awkward	= '<i class="fa fa-meh-o" style="color:#FF0099;"></i>';
$icon_bad		= '<i class="fa fa-bomb" style="color:#990000;"></i>';

/*
 * The Form
 *
 * We're doing a very basic check. Is it a post?
 */

if (!$_POST) {
	// If this is NOT a post, we should show the basic welcome.
	?>

	<div class="section-wrap">
		<div class="section-cta">
			<p>So you have a site hosted on <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a> and you're not sure if it's working right or caching fully? Let us help!</p>
			<p>This site will actually check any site and give you results, so other hosts will also show up as working if they happen to use Varnish. But you should check out DreamPress. We're pretty cool.</p>
		</div>
	</div>

	<center><h2>Check a site!<br /></h2></center>

	<?php
} elseif (!$_POST['url']) {
	// This IS a post, but you forgot a URL. Can't scan nothing.
	?>

	<div class="section-wrap">
		<h3>We can't tell what site you're trying to check.</h3>
		<p>Did you forget to put in a URL?</p>
	</div>
	
	<center><h2>Try again<br /></h2></center>

	<?php
} else {

	// We are a post, we're checking for a URL, let's do the magic!

	// Let's do some basic CYA here to prevent people from being dicks.
	session_start();
	
	if ( isset($_SESSION['last_submit']) && time()-$_SESSION['last_submit'] < 60 ) {
	    ?>
	
	<div class="section-wrap">
	    <p><img src="assets/images/robot.sleeping.png" style="float:left;margin:0 5px 0 0;" width="150" /></p>
	    
	    <p>Hold on there, Nelly! You're checking too many sites too fast.</p>
	    <p>We get it, though. You want to make sure you fix everything on your site and that it's working perfectly. Before you re-run a test, make sure you've changed everything, uploaded it, <em>and</em> flush Varnish on your server.</p>
	    <p>You did all that? Cool!</p>
		<p>Please wait at least 60 seconds and try again.</p>
	</div>
	    
	    <?php
	} else {
	    $_SESSION['last_submit'] = time();
	
		// Sanitize the URL
		$varnish_url  = (string) rtrim( filter_var($_POST['url'], FILTER_SANITIZE_URL), '/' );
		$varnish_url  = (string) $_POST['url'];
	
		// Set Varnish Host for reasons
		$varnish_host = (string) preg_replace('#^https?://#', '', $varnish_url);
	
		if (preg_match("~^https://~i", $varnish_url)) {
			$varnish_host = $varnish_url;
			$varnish_url = $varnish_url;
		} elseif (!preg_match("~^(?:f|ht)tp?://~i", $varnish_url)) {
		    $varnish_host = "http://" . $varnish_url;
		    	$varnish_url = "http://" . $varnish_url;
		}
	
		// Is it a real URL?
	
		// Call StrictURLValidator becuase FILTER_VALIDATE_URL thinks http://foo is okay, even when you tell it you want the damn host.
		require_once 'StrictUrlValidator.php';
	
		if ( StrictUrlValidator::validate( $varnish_url, true, true ) === false ) {
		?>
	
			<div id="subtitle">Egad!</div>
	
			<p><?php echo $varnish_url; ?> is not a valid URL.</p>
	
			<?php echo "<p>URL validation failed: " . StrictUrlValidator::getError() . "</p>"; ?>
	
			<p>Try again!</p>
	
		<?php
		} else {
			// Good, we're a real URL.
	
			// Since we reuse this, let's have a curl function
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
				    CURLOPT_VERBOSE => true,
				    CURLOPT_RETURNTRANSFER => true,
				    CURLOPT_ENCODING => 'gzip, deflate',
				    CURLOPT_URL => $url ) );
				
				return $curl;
				curl_close($curl);
			}
			
			// We also call the response a couple times
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
			while ( strpos( $varnish_headers[0] , '200') === false ) {
				$varnish_headers = curl_response( curl_headers( $varnish_headers['Location'] ) );
			}
		?>
		
		<div class="section-wrap">

		<?php
			if ( !isset($varnish_headers['X-Cacheable']) ) {
	
				?>
				<h3>Alas, no.</h3>
				<p>Our robots were not find the "X-Varnish" header in the response from the server. That means Varnish is probably not running, which in turn means you actually may not be on DreamPress!</p>
				<p>If you're sure you <em>are</em> on DreamPress, take the information below and send it in a support ticket to our awesome techs. That will help us debug things even faster!</p>
	
				<?php
	
			} elseif ( strpos( $varnish_headers['X-Cacheable'], 'yes') !== false || strpos( $varnish_headers['X-Cacheable'], 'YES') !== false && isset($varnish_headers['Age']) && $varnish_headers['Age'] > 0 ) {
				?>
				<p><img src="assets/images/robot.presents.right.svg" style="float:left;margin:0 5px 0 0;" width="150" /></p>
				<h3>Yes!</h3>
				<p>Well, congratulations to you!</p>
				<p>Looks like your site is running with a Varnish cache.</p>
				<p>Want to know more about the site? Check the results below:</p><br style="clear:both;" />
				<?php
	
			} else {
				?>
				<h3>Not Exactly</h3>
	
				<p>Varnish is running, but it can't serve up the cache properly. Why? Check out the red-bombs and yellow-warnings below.</p>
	
				<?php
			}
			?>
			<table class="table-standard wordpress-comparison">
			<?php
	
			/*
			 * Pre Flight Notices!
			 *
			 * We're going to do some extra checks to make sure this is WordPress and that everything's above board.
			 */
	
			// VARNISH
			if ( isset( $varnish_headers['X-Cacheable'] ) && strpos( $varnish_headers['X-Cacheable'] ,'YES') !== false ) {
				?><tr>
					<td width="40px"><?php echo $icon_good; ?></td>
					<td>Varnish is running properly so caching is happening.</td>
				</tr><?php
			} elseif (isset( $varnish_headers['X-Cacheable'] ) && strpos( $varnish_headers['X-Cacheable'] ,'NO') !== false ) {
				?><tr>
					<td width="40px"><?php echo $icon_bad; ?></td>
					<td>Varnish is running but can't cache.</td>
				</tr><?php
			} else {
				?><tr>
					<td width="40px"><?php echo $icon_warning; ?></td>
					<td>We can't find Varnish on this server.</td>
				</tr><?php
			}
	
			// WORDPRESS
			$tags = get_meta_tags($varnish_url);
			if ( ( isset($tags['generator']) && strpos( $tags['generator'] ,'WordPress') !== false ) || strpos( $varnish_headers['Link'] ,'wp-json') !== false ) {
				?><tr>
					<td width="40px"><?php echo $icon_awesome; ?></td>
					<td>This is a WordPress site!</td>
				</tr><?php
			} else {
				?><tr>
					<td width="40px"><?php echo $icon_warning; ?></td>
					<td>We're not sure if this is a WordPress site. Did you strip the meta tags?</td>
				</tr><?php
			}
			
			/* Let's see who your host is */
			
			// SERVER (nginx, pagely)
			if ( isset( $varnish_headers['Server'] ) ) {
				// nginx
				if ( strpos( $varnish_headers['Server'] ,'nginx') !== false && strpos( $varnish_headers['Server'] ,'cloudflare') == false ) {
				?><tr>
					<td><?php echo $icon_awkward; ?></td>
					<td>Your server is on nginx and DreamPress is Apache only. Something's weird...</td>
				</tr><?php		
				} 
				// Pagely
				if ( strpos( $varnish_headers['Server'] ,'Pagely') !== false ) {
				?><tr>
					<td><?php echo $icon_awkward; ?></td>
					<td>This site is on Pagely, not <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a>.</td>
				</tr><?php
				}
				// Secondary Cloudflare
				if ( strpos( $varnish_headers['Server'] ,'cloudflare') !== false ) {
				?><tr>
					<td><?php echo $icon_warning; ?></td>
					<td>Because CloudFlare is running, you <em>may</em> experience some cache oddities. <a href="cloudflare.php">Read More</a></td>
				</tr><?php
				}
			}
			
			// X-HACKER (Automattic)
			if ( isset( $varnish_headers['X-hacker'] ) ) {
				if ( strpos( $varnish_headers['X-hacker'] ,'automattic') !== false ) {
				?><tr>
					<td><?php echo $icon_awkward; ?></td>
					<td>This site is on WordPress.com which is cool, but you ain't on <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a>.</td>
				</tr><?php
				}
			}
	
			// X-BACKEND (GoDaddy)
			if ( isset( $varnish_headers['X-Backend'] ) ) {
				if ( strpos( $varnish_headers['X-Backend'] ,'wpaas_web_') !== false ) {
				?><tr>
					<td><?php echo $icon_awkward; ?></td>
					<td>This site is on GoDaddy. Have you met <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a>?</td>
				</tr><?php
				}
			}
			
			/* Advanced checking */
			
			// HHVM
			if ( isset( $varnish_headers['X-Powered-By'] ) ) {
				if ( strpos( $varnish_headers['X-Powered-By'] ,'HHVM') !== false ) {
				?><tr>
					<td><?php echo $icon_awesome; ?></td>
					<td>You are so awesome! You're on HHVM!</td>
				</tr><?php
				}
			}
			
			/* Big Fat DNS */
	
			// DNS - Check for DH and CloudFlare specifically
			$nameservers = dns_get_record($varnish_host, DNS_NS );
			$ip = gethostbyname($varnish_host);
			if ( isset( $nameservers ) && is_array( $nameservers )  ) {
				$nsrecords = '';
				foreach ($nameservers as $record) {
					$nsrecords .= $record['target'].' ';
				}
				if ( isset( $nsrecords ) && strpos( $nsrecords ,'dreamhost') !== false ) {
					?><tr>
						<td><?php echo $icon_awesome; ?></td>
						<td>Huzzah! DreamHost's nameservers are in use.<br /><?php echo $nsrecords; ?></td>
					</tr><?php
				} elseif ( strpos( $nsrecords ,'cloudflare') !== false ) {
					?><tr>
						<td><?php echo $icon_good; ?></td>
						<td>You're using CloudFlare's DNS. Smart choice!<br /><?php echo $nsrecords; ?></td>
					</tr><?php
				} elseif ( empty( $nsrecords ) ) {
					?><tr>
						<td><?php echo $icon_awkward; ?></td>
						<td>We can't detect your name servers because the PHP check is imperfect. Just make sure you're using ours: ns1.dreamhost.com, ns2.dreamhost.com, ns3.dreamhost.com</td>
					</tr><?php
				} else {
					?><tr>
						<td><?php echo $icon_warning; ?></td>
						<td>These aren't our nameservers:<br /><?php echo $nsrecords; ?><br />(Ours are ns1.dreamhost.com, ns2.dreamhost.com, ns3.dreamhost.com)</td>
					</tr>
					<tr>
						<td><?php echo $icon_warning; ?></td>
						<td>Your IP address is set to <?php echo $ip; ?> - Make sure that matches what Panel's DNS entry thinks it should be.</td>
					</tr>
					<?php
				}
			}
			
			/* Shit that breaks Varnish */
	
			// SET COOKIE
			if ( isset( $varnish_headers['Set-Cookie'] ) ) {
	
				if ( strpos( $varnish_headers['Set-Cookie'] , 'PHPSESSID') !== false ) {
					?><tr>
						<td><?php echo $icon_bad; ?></td>
						<td>You're setting a PHPSESSID cookie. This makes Varnish not deliver cached pages. (<a href="phpsessid.php">Need help debugging php sessions?</a>)</td>
					</tr><?php
				}
				if ( strpos( $varnish_headers['Set-Cookie'], 'edd_wp_session' ) !== false ) {
					?><tr>
						<td><?php echo $icon_bad; ?></td>
						<td>We've spotted <a href="https://wordpress.org/plugins/easy-digital-downloads/">Easy Digital Downloads</a> being used with cookie sessions. This causes your cache to misbehave. Please set <code>define( 'EDD_USE_PHP_SESSIONS', true );</code> in your <code>wp-config.php</code> file.</td>
					</tr><?php
				}
				if ( strpos( $varnish_headers['Set-Cookie'], 'edd_items_in_cart' ) !== false ) {
					?><tr>
						<td><?php echo $icon_warning; ?></td>
						<td>Avast! We spy <a href="https://wordpress.org/plugins/easy-digital-downloads/">Easy Digital Downloads</a>. When customers add items to their cart, they'll no longer be using cached pages. Thought you ought to know.</td>
					</tr><?php				
				}
				if ( strpos( $varnish_headers['Set-Cookie'], 'wfvt_' ) !== false ) {
					?><tr>
						<td><?php echo $icon_bad; ?></td>
						<td>The plugin <a href="https://wordpress.org/plugins/wordfence">WordFence</a> is putting down cookies on every page load. Please disable that in your options (available from version 4.0.4 and up)</td>
					</tr><?php
				}
				if ( strpos( $varnish_headers['Set-Cookie'], 'invite-anyone' ) !== false ) {
					?><tr>
						<td><?php echo $icon_bad; ?></td>
						<td><a href="https://wordpress.org/plugins/invite-anyone/">Invite Anyone</a>, a plugin for BuddyPress, is putting down a cookie on every page load. This will prevent Varnish from caching :(</td>
					</tr><?php
				}
			}
	
			// AGE
			if( !isset($varnish_headers['Age']) ) {
				?><tr>
					<td><?php echo $icon_bad; ?></td>
					<td>There's no "Age" header, which means we can't tell if the page is actually serving from cache.</td>
				</tr><?php
			} elseif( $varnish_headers['Age'] <= 0 || $varnish_headers['Age'] == 0 ) {
				if( !isset($varnish_headers['Cache-Control']) || strpos($varnish_headers['Cache-Control'], 'max-age') === FALSE ) {
				?><tr>
					<td><?php echo $icon_warning; ?></td>
					<td>The "Age" header is set to less than 1, which means you checked right when Varnish cleared it's cache for that url, or for whatever reason Varnish is not actually serving the content for that url from cache. Check again (hit the recheck button below) but if it happens again, it could be one of the following reasons:
						<ul style=\"text-align: left;\">
							<li>That url is excluded from the cache on purpose in the Varnish vcl file (in which case, yay! It's working.)</li>
							<li>A theme or plugin is sending cache headers that are telling Varnish not to serve that content from cache. This means you'll have to fix the cache headers the application is sending to Varnish. A lot of the time those headers are Cache-Control and/or Expires.</li>
							<li>A theme or plugin is setting a session cookie, which can prevent Varnish from serving content from cache. This means you'll have to update the application and make it not send a session cookie for anonymous traffic. (<a href="phpsessid.php">Need help debugging php sessions?</a>)</li>
							<li>Drunk robots.</li>
						</ul>
					</td>
				</tr><?php			
				}
			}
	
			// CACHE-CONTROL
			if ( isset( $varnish_headers['Cache-Control'] ) && strpos( $varnish_headers['Cache-Control'] ,'no-cache') !== false ) {
				?><tr>
					<td><?php echo $icon_bad; ?></td>
					<td>Something is setting the header Cache-Control to 'no-cache' which means visitors will never get cached pages. (<a href="no-cache.php">Need help debugging no-cache headers?</a>)</td>
				</tr><?php
			}
	
			// MAX AGE
			if ( isset( $varnish_headers['Cache-Control'] ) && strpos( $varnish_headers['Cache-Control'] ,'max-age=0') !== false ) {
				?><tr>
					<td><?php echo $icon_bad; ?></td>
					<td>Something is setting the header Cache-Control to 'max-age=0' which means a page can be no older than 0 seconds before it needs to regenerate the cache. (<a href="max-age.php">Need help debugging max-age headers?</a>)</td>
				</tr><?php
			}
	
			// PRAGMA
			if ( isset( $varnish_headers['Pragma'] ) && strpos( $varnish_headers['Pragma'] ,'no-cache') !== false ) {
				?><tr>
					<td><?php echo $icon_bad; ?></td>
					<td>Something is setting the header Pragma to 'no-cache' which means visitors will never get cached pages. (<a href="no-cache.php">Need help debugging no-cache headers?</a>)</td>
				</tr><?php
			}
			
			// X-CACHE (we're not running this)
			if ( isset( $varnish_headers['X-Cache-Status'] ) && strpos( $varnish_headers['X-Cache-Status'] ,'MISS') !== false ) {
				?><tr>
					<td><?php echo $icon_bad; ?></td>
					<td>X-Cache missed, which means it's not able to serve this page as cached.</td>
				</tr><?php
			}
	
			/* Server features */
	
			// PAGESPEED
			if ( isset( $varnish_headers['X-Mod-Pagespeed'] ) ) {
				if ( strpos( $varnish_headers['X-Cacheable'] , 'YES:Forced') !== false ) {
					?><tr>
						<td><?php echo $icon_good; ?></td>
						<td>Mod Pagespeed is active and working properly with Varnish.</td>
					</tr><?php
				} else {
					?><tr>
						<td><?php echo $icon_bad; ?></td>
						<td>Mod Pagespeed is active but it looks like your caching headers may not be right. <a href="pagespeed.php">Don't know how to fix that? Read this!</a> (Note: This may be a false negative if other parts of your site are overwriting headers. Fix all other errors <em>first</em>, then come back to this.)</td>
					</tr><?php
				}
			}
	
			?>
			</table>
	
			<?php
			// No matter what, we're going to show the headers etc. right? Wrong! If it wasn't a valid URL, we shouldn't
	
			if ( StrictUrlValidator::validate( $varnish_url, true, true ) === true ) {
				?>
				<p>Here are some more gory details about the site:</p>
	
				<table class="table-standard wordpress-comparison">
					<tr><td width="200px" style="text-align:right;">The url we checked:</td><td><a href="http://<?php echo $varnish_host; ?>"><?php echo $varnish_host; ?></a></td></tr>
					<tr><td width="200px">&nbsp;</td><td><?php echo $varnish_headers[0]; ?></td></tr>
					<?php
					foreach ($varnish_headers as $header => $key ) {
						if ( $header != '0' ) {
							echo '<tr><td style="text-align:right;">'.$header.':</td><td>'.htmlspecialchars($key).'</td></tr>';
						}
					}
					?>
				</table>
		</div>
		
		<center><h2>Check another site!</h2></center>
	
		    <?php
			}
		}
	}
}
?>

	<center>
	<form method="POST" action="<?php echo $filename; ?>" id="check_dreampress_form">
          <input name="url" id="url" value="<?php if (isset($varnish_host) ) echo $varnish_host; ?>" type="text">
          <div class="g-recaptcha" data-sitekey="6LfsogkTAAAAAMuZHeO_l9qN3k-V-xhyZkEtM_IE"></div>
          <p>&nbsp;</p>
          <input name="check_it" id="check_it" value="Check It!" type="submit" class="btn dreampress-tech-features-trigger">
    </form>
	</center>
	
	<p>&nbsp;</p>
	
</section>

</div>

<footer id="page-footer">
	<div class="top-bar">
		<h2>Proudly hosting over <span>1,500,000</span> dreams since 1997.</h2>
	</div>

	<div class="section-wrap">
		<ul class="get-started">
			<li><h3><a href="https://dreamhost.com/hosting/shared/">Get Started</a></h3></li>
			<li><a href="https://dreamhost.com/hosting/shared/" class="sign-up">Sign up</a></li>
			<li><a href="https://panel.dreamhost.com">Log in</a></li>
		</ul>
		<ul class="products">
			<li><h3><a href="https://dreamhost.com/hosting/">Services</a></h3></li>
			<li></li>
			<li><a href="https://dreamhost.com/domains/">Domains</a></li>
			<li><a href="https://dreamhost.com/hosting/wordpress/">WordPress Hosting</a></li>
			<li><a href="https://dreamhost.com/hosting/shared/">Web Hosting</a></li>
			<li><a href="https://dreamhost.com/cloud/storage/">Cloud Storage</a></li>
			<li><a href="https://dreamhost.com/hosting/vps/">VPS Hosting</a></li>
			<li><a href="https://dreamhost.com/cloud/computing/">Cloud Computing</a></li>
			<li><a href="https://dreamhost.com/hosting/dedicated/">Dedicated Servers</a></li>
			<li><a href="https://dreamhost.com/cloud/cdn/">CDN</a></li>
		</ul>
		<ul class="company">
			<li><h3><a href="https://dreamhost.com/company/">Company</a></h3></li>
			<li></li>
			<li><a href="https://dreamhost.com/company/">About</a></li>
			<li><a href="https://dreamhost.com/affiliates/">Affiliates</a></li>
			<li><a href="//www.dreamhost.com/blog">Blog</a></li>
			<li><a href="https://dreamhost.com/partners/">Partners</a></li>
			<li><a href="https://dreamhost.com/careers/">Careers</a></li>
			<li><a href="https://dreamhost.com/company/were-green/">Green Hosting</a></li>
			<li><a href="https://dreamhost.com/press/">Press &amp; News</a></li>
			<li><a href="https://dreamhost.com/legal/">Legal</a></li>
		</ul>
		<ul class="support">
			<li><h3><a href="https://dreamhost.com/support/">Support</a></h3></li>
			<li><a href="https://dreamhost.com/support/">Contact</a></li>
			<li><a href="http://wiki.dreamhost.com">Wiki</a></li>
			<li><a href="//discussion.dreamhost.com">Forums</a></li>
			<li><a href="https://dreamhost.com/legal/abuse/">Report Abuse</a></li>
		</ul>
		<div class="boring-stuff">
			<a href="https://dreamhost.com/legal/terms-of-service/">Terms of Service</a><a href="https://dreamhost.com/legal/privacy-policy/">Privacy Policy</a><a href="http://whoisweb.dreamhost.com/">Whois</a>
		</div>
	</div>

	<ul class="social">
		<li><a href="//twitter.com/dreamhost"><span>Twitter</span><i aria-hidden="true">l</i></a></li>
		<li><a href="//facebook.com/dreamhost"><span>Facebook</span><i aria-hidden="true">f</i></a></li>
		<li><a href="//instagram.com/dreamhost"><span>Instagram</span><i aria-hidden="true">i</i></a></li>
		<li><a href="//youtube.com/user/dreamhostusa"><span>YouTube</span><i aria-hidden="true">x</i></a></li>
	</ul>

	<div class="made-in-la">
		Dreamed up with <span class="love"></span> in Los Angeles.
	</div>

</footer>

</body>
</html>