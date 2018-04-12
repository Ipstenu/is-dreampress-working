<?php
/**
	Copyright 2016-2018 Mika Epstein (email: ipstenu@halfelf.org)

	This file was forked from Varnish HTTP Purge, a plugin for WordPress, and is
	a part of Is-DreamPess-Working.

	Is-DreamPess-Working is free software: you can redistribute it and/or modify
	it under the terms of the Apache License 2.0 license.

	Is-DreamPess-Working is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

if ( !defined( 'ISDREAMPRESSWORKING' ) ) die();

/**
 * Varnish Debug
 *
 * @since 4.4
 */

class VarnishDebug {

	/**
	 * remote_get_headers function.
	 */
	static function remote_get_headers( $url ) {

		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_FAILONERROR => true,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_HEADER => true,
			CURLOPT_NOBODY => true,
			CURLOPT_VERBOSE => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => 'gzip, deflate',
			CURLOPT_URL => $url ) 
		);

		$varnish_result = curl_exec( $curl );

		// If an error occured, bail
		if ( curl_errno( $curl ) ) return false;

		// Get the headers
		$varnish_headerinfo     = curl_getinfo( $curl );
		$varnish_headers        = array();
		$varnish_responseheader = explode( "\n" , trim( mb_substr( $varnish_result, 0, $varnish_headerinfo['header_size'] ) ) );
	
		// Reformatting 0 entry for playback
		$varnish_headers[0]     = $varnish_responseheader[0];
		unset( $varnish_responseheader[0] );
	
		foreach( $varnish_responseheader as $line ) {
			list( $key, $val) = explode( ':' , $line , 2 );
			$varnish_headers[$key] = trim($val);
		}

		curl_close( $curl );
		return $varnish_headers;
	}

	/**
	 * remote_get_json function.
	 */
	static function remote_get_json( $url ) {

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $ch, CURLOPT_URL, $url );

		$result = curl_exec( $ch );

		curl_close( $ch );
		
		$json = json_decode( $result );

		return $json;
	}

	/**
	 * Basic checks that should stop a scan
	 */
	static function preflight( $response ) {

		// Defaults
		$preflight = true;
		$message   = 'Success';

		if ( !$response ) {
			$preflight = false;
			$message   = 'This URL is unavailable. Please check your typing and try again.';
		}

		$return = array( 
			'preflight' => $preflight,
			'message'   => $message,
		);
		
		return $return;
	}

	/**
	 * Check for remote IP
	 */
	static function remote_ip( $headers ) {

		if ( isset( $headers['X-Forwarded-For'] ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$remote_ip = $headers['X-Forwarded-For'];
		} elseif ( isset( $headers['HTTP_X_FORWARDED_FOR'] ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
		) {
			$remote_ip = $headers['HTTP_X_FORWARDED_FOR'];
		} elseif ( isset( $headers['Server'] ) && strpos( $headers['Server'] ,'cloudflare') !== false ) {
			$remote_ip = 'cloudflare';
		} else {
			$remote_ip = false;
		}
		
		return $remote_ip;
	}

	/**
	 * Varnish Results on the Varnish calls
	 */
	static function varnish_results( $headers ) {

		// Set the defaults
		$return = array( 
			'icon'    => 'good',
			'message' => 'Varnish is running properly and caching is happening.',
		);

		if ( !isset( $headers ) ) {
			$kronk = false;
		} else {
			$kronk = true;

			// Check if the headers are set AND if the values are valid
			$x_cachable = ( isset( $headers['X-Cacheable'] ) && strpos( $headers['X-Cacheable'] ,'YES') !== false )? true : false;
			$x_varnish  = ( isset( $headers['X-Varnish'] ) )? true : false;
			$x_via      = ( isset( $headers['Via'] ) && is_numeric( strpos( $headers['Via'], 'arnish' ) ) )? true : false;
			$x_age      = ( isset( $headers[ 'Age' ] ) && $headers[ 'Age' ] > 0 )?  true : false;
	
			// If this is TRUE it's NOT Cachable
			$not_cachable     = ( ( isset( $headers['X-Cacheable'] ) && strpos( $headers['X-Cacheable'] ,'NO') !== false ) || ( isset( $headers['Pragma'] ) && strpos( $headers['Pragma'] ,'no-cache') !== false ) || !$x_age )? true : false;
			$cacheheaders_set = ( isset( $headers['X-Cacheable'] ) || isset( $headers['X-Varnish'] ) || isset( $headers['X-Cache'] ) || $x_via )? true : false;
		}

		if ( !$kronk ) {
			$return['icon']    = 'bad';
			$return['message'] = 'Something went very wrong with this request. Please try again.';
		} elseif ( !$cacheheaders_set || !$x_varnish ) {
			$return['icon']    = 'warning';
			$return['message'] = 'We were unable find Varnish active for this domain. Please review the output below to understand why.';
		} elseif ( !$not_cachable && ( $x_cachable || $x_varnish ) ) {
			$return['icon']    = 'awesome';
			$return['message'] = 'Varnish is running properly and caching is happening.';
		} else {
			$return['icon']    = 'warning';
			$return['message'] = 'Varnish is running but is unable to cache your site. Please review the following output to diagnose the issue.';
		}

		return $return;
	}

	/**
	 * Server Details - Includes nginx, hhvm, cloudflare, and more
	 */
	static function server_results( $headers ) {

		// Set the defaults
		$return = array();

		if ( isset( $headers['Server'] ) ) {
			// nginx
			if ( strpos( $headers['Server'] ,'nginx') !== false && strpos( $headers['Server'] ,'cloudflare') == false ) {
				$return['nginx'] = array( 
					'icon'    => 'awkward',
					'message' => 'Your server is running nginx and Apache was expected. This may be fine, especially if you use a passthrough proxy, but keep it in mind.',
				);
			}
			
			// Cloudflare
			if ( strpos( $headers['Server'] ,'cloudflare') !== false ) {
				$return['cloudflare'] = array( 
					'icon'    => 'warning',
					'message' => 'CloudFlare has been detected. While this is generally fine, you may experience some cache oddities. Make sure you configure WordPress for Cloudflare.',
				);
			}

			// HHVM: Note, WP is dropping support so ...
			if ( isset( $headers['X-Powered-By'] ) && strpos( $headers['X-Powered-By'] ,'HHVM') !== false ) {
				$return['hhvm'] = array( 
					'icon'    => 'awkward',
					'message' => 'You are running HHVM instead of PHP. While that is compatible with Varnish, you should consider PHP 7. WordPress will cease support for HHVM in 2018.',
				);
			}

			// Pagely
			if ( strpos( $headers['Server'] ,'Pagely') !== false ) {
				$return['pagely'] = array( 
					'icon'    => 'good',
					'message' => 'This site is hosted on Pagely.',
				);
			}
		}

		if ( isset( $headers['X-hacker'] ) ) {
			$return['wordpresscom'] = array( 
				'icon'    => 'bad',
				'message' => 'This site is hosted on WordPress.com, which is cool but, last we checked, they don\'t use Varnish.',
			);
		}
		
		if ( isset( $headers['X-Backend'] ) && strpos( $headers['X-Backend'] ,'wpaas_web_') !== false ) {
			$return['godaddy'] = array( 
				'icon'    => 'good',
				'message' => 'This site is hosted on GoDaddy.',
			);
		}

		return $return;
	}

	/**
	 * GZIP
	 *
	 * Results on GZIP
	 */
	static function gzip_results( $headers ) {

		// Set the defaults
		$return = false;

		// GZip
		if( strpos( $headers['Content-Encoding'] ,'gzip') !== false || ( isset( $headers['Vary'] ) && strpos( $headers['Vary'] ,'gzip' ) !== false ) ) {
			$return = array( 
				'icon'    => 'good',
				'message' => 'Your site is compressing content and making the internet faster.',
			);
		}

		// Fastly
		if ( strpos( $headers['Content-Encoding'] ,'Fastly') !== false ) {
			$return = array( 
				'icon'    => 'good',
				'message' => 'Fastly is speeding up your site. Keep in mind, it may cache your CSS and images longer than Varnish does. Remember to empty all caches in all locations.',
			);
		}

		return $return;
	}

	/**
	 * Cookies break Varnish. Sometimes.
	 */
	static function cookie_results( $headers ) {

		// Defaults
		$return = $almost = array();

		// Early check. If there are no cookies, skip!
		if ( !isset( $headers['Set-Cookie'] ) ) return $return;

		// We have at least one cookie, so let's set this now:
		$return['cookies'] = array(
			'icon'    => 'warning',
			'message' => 'Cookies have been detected on your site. This can cause Varnish to not properly cache unless it\'s configured specially to accommodate. Since it\'s impossible to cover all possible situations, please take the following alerts with a grain of salt. If you know certain cookies are safe on your server, this is fine. If you aren\'t sure, pass the details on to your webhost.',
		);

		// Call the cookies!
		$request = remote_get_json( 'https://varnish-http-purge.objects-us-east-1.dream.io/cookies.json' );

		if( is_wp_error( $request ) ) return $return; // Bail if we can't hit the server

		$body    = wp_remote_retrieve_body( $request );
		$cookies = json_decode( $body );

		if( empty( $cookies ) ) return $return; // Bail if the data was empty for some reason

		foreach ( $cookies as $cookie => $info ) {
			$has_cookie = false;

			// If cookies are an array, scan the whole thing. Otherwise, we can use strpos.
			if ( is_array( $headers['Set-Cookie'] ) ) {
				if ( in_array( $info->cookie, $headers['Set-Cookie'], true ) ) $has_cookie = true;
			} else {
				$strpos = strpos( $headers['Set-Cookie'], $info->cookie );
				if ( $strpos !== false ) $has_cookie = true;
			}

			if ( $has_cookie ) {
				$return[ $cookie ] = array( 'icon' => $info->type, 'message' => $info->message );
			}
		}

		return $return;
	}

	/**
	 * Cache - Checking Age, Max Age, Cache Control, Pragma and more
	 */
	static function cache_results( $headers ) {

		$return = array();

		// Cache Control
		if ( isset( $headers['Cache-Control'] ) ) {

			// No-Cache Set
			if ( strpos( $headers['Cache-Control'], 'no-cache' ) !== false ) {
				$return['no_cache'] = array(
					'icon'    => 'bad',
					'message' => 'Something is setting the header Cache-Control to "no-cache" which means visitors will never get cached pages.',
				);
			}

			// Max-Age is 0
			if ( strpos( $headers['Cache-Control'], 'max-age=0' ) !== false ) {
				$return['max_age'] = array(
					'icon'    => 'bad',
					'message' => 'Something is setting the header Cache-Control to "max-age=0" which means a page can be no older than 0 seconds before it needs to regenerate the cache.',
				);
			}
		}

		// Age Headers
		if ( !isset( $headers['Age'] ) ) {
			$return['age'] = array(
				'icon'    => 'bad',
				'message' => 'Your domain does not report an "Age" header, which means we can\'t tell if the page is actually serving from cache.',
			);
		} elseif( $headers['Age'] <= 0 || $headers['Age'] == 0 ) {
			$return['age'] = array(
				'icon'    => 'warning',
				'message' => 'The "Age" header is set to less than 1, which means you checked right when Varnish cleared the cache for that url or Varnish is not serving cached content for that url. Check again but if it happens again, then either the URL is intentionally excluded from caching, or a theme or plugin is sending cache headers or cookies that instruct varnish not to cache.',
			);
		} else {
			$return['age'] = array(
				'icon'    => 'good',
				'message' => 'Your site is returning proper "Age" headers.',
			);
		}

		// Pragma
		if ( isset( $headers['Pragma'] ) && strpos( $headers['Pragma'] ,'no-cache') !== false ) {
			$return['pragma'] = array(
				'icon'    => 'bad',
				'message' =>  'Something is setting the header Pragma to "no-cache" which means visitors will never get cached pages. This is usually done by plugins.',
			);
		}

		// X-Cache
		if ( isset( $headers['X-Cache-Status'] ) && strpos( $headers['X-Cache-Status'] ,'MISS') !== false ) {
			$return['X-Cache'] = array(
				'icon'    => 'bad',
				'message' =>  'X-Cache missed, which means it was not able to serve this page as cached. This may be resolved by rerunning the scan. If not, then a plugin or theme is forcing this setting.',
			);
		}

		// Mod-PageSpeed
		if ( isset( $headers['X-Mod-Pagespeed'] ) ) {
			if ( strpos( $headers['X-Cacheable'] , 'YES:Forced') !== false ) {
				$return['mod_pagespeed'] = array(
					'icon'    => 'good',
					'message' =>  'Mod Pagespeed is active and configured to work properly with Varnish.',
				);
			} else {
				$return['mod_pagespeed'] = array(
					'icon'    => 'bad',
					'message' =>  'Mod Pagespeed is active but it looks like your caching headers may not be right. This may be a false negative if other parts of your site are overwriting headers. Fix all other errors listed, then come back to this. If you are still having errors, you will need to look into using htaccess or nginx to override the Pagespeed headers.',
				);
			}
		}

		return $return;
	}

	/**
	 * Themes known to be problematic
	 */
	static function bad_themes_results() {

		$return  = array();
		$request = remote_get_json( 'https://varnish-http-purge.objects-us-east-1.dream.io/themes.json' );

		if( is_wp_error( $request ) ) {
			return $return; // Bail early
		}

		$body    = wp_remote_retrieve_body( $request );
		$themes  = json_decode( $body );

		if( empty( $themes ) ) {
			return $return; // Bail early
		}

		// Check all the themes. If one of the questionable ones are active, warn
		foreach ( $themes as $theme => $info ) {
			$my_theme = wp_get_theme( $theme );
			$message  = 'Active Theme ' . ucfirst( $theme ) . ': ' . $info->message;
			$warning  = $info->type;
			if ( $my_theme->exists() ) {
				$return[ 'theme' ] = array( 'icon' => $warning, 'message' => $message );
			}
		}

		return $return;
	}

	/**
	 * Plugins known to be problematic
	 */
	static function bad_plugins_results( ) {

		$return   = array();
		$messages = array(
			'incompatible' => 'Causes unexpected results with Varnish and not function properly.',
			'translation'  => 'Translation plugins generally use cookies and/or sessions, which prevent Varnish from caching.',
			'sessions'     => 'This plugin uses sessions, which conflicts with Varnish Caching.',
			'cache'        => 'This type of caching plugin does not work well with Varnish.',
		);
		$request = remote_get_json( 'https://varnish-http-purge.objects-us-east-1.dream.io/plugins.json' );

		if( is_wp_error( $request ) ) {
			return $return; // Bail early
		}

		$body    = wp_remote_retrieve_body( $request );
		$plugins  = json_decode( $body );

		if( empty( $plugins ) ) {
			return $return; // Bail early
		}

		// Check all the plugins. If one of the questionable ones are active, warn
		foreach ( $plugins as $plugin => $info ) {
			if ( is_plugin_active( $info->path ) ) {
				$message  = 'Active plugin ' . $plugin . ': ' . $messages[ $info->reason ];
				$warning  = $info->type;
				$return[ $plugin ] = array( 'icon' => $warning, 'message' => $message );
			}
		}

		return $return;
	}

	/**
	 * Get all the results
	 *
	 * Collect everything, get all the data spit it out.
	 * 
	 * @since 4.4.0
	 */
	static function get_all_the_results( $headers ) {
		$output = array();
		$output['varnish']   = self::varnish_results( $headers );

		// Server Results
		$server_results      = self::server_results( $headers );
		$output              = array_merge( $output, $server_results );

		// Cache Results
		$cache_results       = self::cache_results( $headers );
		$output              = array_merge( $output, $cache_results );

		// Cookies
		$cookie_results      = self::cookie_results( $headers );
		$output              = array_merge( $output, $cookie_results );

/*
		// Sadly these need to be rewritten to work well remotely :(

		// Themes that don't play nicely with Varnish)
		$bad_themes_results  = self::bad_themes_results();
		$output              = array_merge( $output, $bad_themes_results );

		// Plugins that don't play nicely with Varnish)
		$bad_plugins_results  = self::bad_plugins_results();
		$output              = array_merge( $output, $bad_plugins_results );
*/
		return $output;
	}

}

if ( class_exists( 'VarnishDebug' ) ) $varnish_debug = new VarnishDebug();