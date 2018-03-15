<?php

/* Is DreamPress Working?
 *
 * This is the GTMetrix Code
 *
 */

if( !defined( 'ISDREAMPRESSWORKING' ) ) {
	die('Direct access not permitted');
}
?>

<table class="table-standard wordpress-comparison">

	<?php
	
	/*
	* Pre Flight Notices!
	*
	* We're going to do some extra checks to make sure this is WordPress and that everything's above board.
	*/

	$x_cachable = ( isset( $varnish_headers['X-Cacheable'] ) && strpos( $varnish_headers['X-Cacheable'] ,'YES') !== false )? true : false;
	$x_varnish  = ( isset( $varnish_headers['X-Varnish'] ) )? true : false;
	$x_via      = ( is_numeric( strpos( $varnish_headers['Via'], 'arnish' ) ) )? true : false;
	$x_age      = ( isset( $varnish_headers[ 'Age' ] ) && $varnish_headers[ 'Age' ] > 0 )?  true : false;
	
	// VARNISH
	if ( $x_cachable || $x_varnish || $x_via ) {
	?><tr>
		<td width="40px"><?php echo $icon_good; ?></td>
		<td>Varnish is running properly so caching is happening.</td>
	</tr><?php
	} else {
	?><tr>
		<td width="40px"><?php echo $icon_warning; ?></td>
		<td>We're unable to detect Varnish on this server.</td>
	</tr><?php
	}
	
	// WORDPRESS
	$tags = get_meta_tags($varnish_url);
	if ( ( isset($tags['generator']) && strpos( $tags['generator'] ,'WordPress') !== false ) || strpos( $varnish_headers['Link'] , 'wp-json' ) !== false ) {
	?><tr>
		<td width="40px"><?php echo $icon_awesome; ?></td>
		<td>This is a WordPress site!</td>
	</tr><?php
	} else {
	?><tr>
		<td width="40px"><?php echo $icon_warning; ?></td>
		<td>We're not sure if this is a WordPress site. If you're using a security plugin, it may have made detection impossible.</td>
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
			<td>This site is on Pagely, not <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a>. You're welcome to use this service, but it may give inexact results.</td>
		</tr><?php
		}
		// Secondary Cloudflare
		if ( strpos( $varnish_headers['Server'] ,'cloudflare') !== false ) {
		?><tr>
			<td><?php echo $icon_warning; ?></td>
			<td>Because CloudFlare is running, you <em>may</em> experience some cache oddities. Please read <a href="https://help.dreamhost.com/hc/en-us/articles/214581728-DreamPress-FAQs">Using CloudFlare and Varnish</a></td>
		</tr><?php
		}
	}
	
	// X-HACKER (Automattic)
	if ( isset( $varnish_headers['X-hacker'] ) ) {
		if ( strpos( $varnish_headers['X-hacker'] ,'automattic') !== false ) {
		?><tr>
			<td><?php echo $icon_awkward; ?></td>
			<td>This site is on WordPress.com, not <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a>. Last we checked, they don't use Varnish.</td>
		</tr><?php
		}
	}
	
	// X-BACKEND (GoDaddy)
	if ( isset( $varnish_headers['X-Backend'] ) ) {
		if ( strpos( $varnish_headers['X-Backend'] ,'wpaas_web_') !== false ) {
		?><tr>
			<td><?php echo $icon_awkward; ?></td>
			<td>This site is on GoDaddy, not <a href="https://www.dreamhost.com/hosting/wordpress/">DreamPress</a>. While they use Varnish on their Managed Hosting, this scan was geared towards DreamPress and may not be fully accurate.</td>
		</tr><?php
		}
	}
	
	/* Advanced checking */
	
	// HHVM
	if ( isset( $varnish_headers['X-Powered-By'] ) ) {
		if ( strpos( $varnish_headers['X-Powered-By'] ,'HHVM') !== false ) {
		?><tr>
			<td><?php echo $icon_meh; ?></td>
			<td>You're using HHVM. WordPress is dropping support for it, so please consider PHP 7.1 and up. It's just as fast.</td>
		</tr><?php
		}
	}
	
	// GZIP
	if ( isset( $varnish_headers['Content-Encoding'] ) ) {
		if ( strpos( $varnish_headers['Content-Encoding'] ,'Fastly') !== false ) {
		?><tr>
			<td><?php echo $icon_good; ?></td>
			<td><a href="https://fastly.com">Fastly</a> fast can be! Your site is fast!</td>
		</tr><?php
		}
	}
	
	// Fastly
	if ( isset( $varnish_headers['Fastly-Debug-Digest'] ) || isset( $varnish_headers['Vary']) ) {
		if ( strpos( $varnish_headers['Vary'] ,'gzip') !== false ) {
		?><tr>
			<td><?php echo $icon_good; ?></td>
			<td>Gzippity zip zip! Your site is compressed and fast!</td>
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
				<td>You're using CloudFlare's DNS. That'll do.<br /><?php echo $nsrecords; ?></td>
			</tr><?php
		} elseif ( empty( $nsrecords ) ) {
			?><tr>
				<td><?php echo $icon_awkward; ?></td>
				<td>We can't detect your name servers. Don't panic! The PHP check for nameservers is imperfect. Just make sure you're using ours:<br />ns1.dreamhost.com, ns2.dreamhost.com, ns3.dreamhost.com</td>
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
				<td>You're setting a PHPSESSID cookie. This tells Varnish that the person visiting your site is unique, and <em>not</em> to deliver a cached page. Sadly, diagnosing this is hard, as you have to look through the code in your plugins and themes for calls like <code>session_start</code> or <code>start_session</code>.</td>
			</tr><?php
		}
		if ( strpos( $varnish_headers['Set-Cookie'], 'edd_wp_session' ) !== false ) {
			?><tr>
				<td><?php echo $icon_bad; ?></td>
				<td>We've spotted <a href="https://wordpress.org/plugins/easy-digital-downloads/">Easy Digital Downloads</a> being used with cookie sessions. This may cause your cache to misbehave. Please set <code>define( 'EDD_USE_PHP_SESSIONS', true );</code> in your <code>wp-config.php</code> file.</td>
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
				<td><?php echo $icon_warning; ?></td>
				<td>The plugin <a href="https://wordpress.org/plugins/wordfence">WordFence</a> is putting down cookies on every page load. Please disable that in your options (available from version 4.0.4 and up).</td>
			</tr><?php
		}
		if ( strpos( $varnish_headers['Set-Cookie'], 'invite-anyone' ) !== false ) {
			?><tr>
				<td><?php echo $icon_bad; ?></td>
				<td><a href="https://wordpress.org/plugins/invite-anyone/">Invite Anyone</a>, a plugin for BuddyPress, is putting down a cookie on every page load. This will prevent Varnish from caching.</td>
			</tr><?php
		}
		if ( strpos( $varnish_headers['Set-Cookie'], 'charitable_sessions' ) !== false ) {
			?><tr>
				<td><?php echo $icon_bad; ?></td>
				<td><a href="https://wordpress.org/plugins/charitable/">Charitable</a>, a plugin for WordPress, is putting down a cookie on every page load. This will prevent Varnish from caching. This has been fixed as of version 1.5.0, so please upgrade to the latest version.</td>
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
			<td>The "Age" header is set to less than 1, which means either you checked right when Varnish cleared it's cache for that url, or that page cannot be cached. Check again in 30 seconds. If it happens again, it could be one of the following reasons:
				<ul style=\"text-align: left;\">
					<li>That url is excluded from the cache on purpose in the Varnish vcl file (in which case, yay! It's working.)</li>
					<li>A theme or plugin is sending cache headers that are telling Varnish not to serve that content from cache. This means you'll have to fix the cache headers the application is sending to Varnish. A lot of the time those headers are Cache-Control and/or Expires.</li>
					<li>A theme or plugin is setting a session cookie, which can prevent Varnish from serving content from cache. This means you'll have to update the application and make it not send a session cookie for anonymous traffic.</li>
				</ul>
			</td>
		</tr><?php
		}
	}
	
	// CACHE-CONTROL
	if ( isset( $varnish_headers['Cache-Control'] ) && strpos( $varnish_headers['Cache-Control'] ,'no-cache') !== false ) {
		?><tr>
			<td><?php echo $icon_bad; ?></td>
			<td>Something is setting the header Cache-Control to 'no-cache' which means visitors will never get cached pages. <!-- (<a href="https://help.dreamhost.com/hc/en-us/articles/URL_HERE">Need help debugging no-cache headers?</a>) --></td>
		</tr><?php
	}
	
	// MAX AGE
	if ( isset( $varnish_headers['Cache-Control'] ) && strpos( $varnish_headers['Cache-Control'] ,'max-age=0') !== false ) {
		?><tr>
			<td><?php echo $icon_bad; ?></td>
			<td>Something is setting the header Cache-Control to 'max-age=0' which means a page can be no older than 0 seconds before it needs to regenerate the cache. It's probably a plugin or a theme.</td>
		</tr><?php
	}
	
	// PRAGMA
	if ( isset( $varnish_headers['Pragma'] ) && strpos( $varnish_headers['Pragma'] ,'no-cache') !== false ) {
		?><tr>
			<td><?php echo $icon_bad; ?></td>
			<td>Something is setting the header Pragma to 'no-cache' which means visitors will never get cached pages. <!-- (<a href="https://help.dreamhost.com/hc/en-us/articles/URL_HERE">Need help debugging no-cache headers?</a>) --></td>
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
				<td>Mod Pagespeed is active but it looks like your caching headers may not be right. DreamPress doesn't support Pagespeed anymore, so this may be a false negative if other parts of your site are overwriting headers. Fix all other errors <em>first</em>, then come back to this. If you're still having errors, you'll need to look into using htaccess or nginx to override the Pagespeed headers.</td>
			</tr><?php
		}
	}
	?>
</table>