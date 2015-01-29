<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Is DreamPress working? Find out for sure!</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <div id="container">
      <div id="content">

<?php
	
if (!$_POST) { 
	
	// Define the filename so I can move this around.
	$filename=$_SERVER["PHP_SELF"];
?>
	
	<div id="title">Is DreamPress Working?</div>
	
	<p>So you have a site hosted on DreamPress and you're not sure if it's working right or caching fully? Let us help!</p>
	
<?php
} elseif (!$_POST['url']) {
?>

	<div id="title">Whoops!</div>
	
	<p>Did you forget to put in a URL?</p>
	
	<p>Try again!</p>

<?php	
} else {

	$varnish_url = filter_var($_POST['url'], FILTER_SANITIZE_URL);
	
	$varnish_host = preg_replace('#^https?://#', '', $varnish_url);
	
	if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
	    $varnish_url = "http://" . $varnish_url;
	}
	
	if ( !filter_var( $varnish_url , FILTER_VALIDATE_URL ) ) {
	?>

		<div id="title">Egad!</div>
		
		<p><?php echo $varnish_url; ?> is not a valid URL.</p>
		
		<p>Try again!</p>

	<?php	
	} else {

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
		$varnish_headers = get_headers($varnish_url,1);
		
		if ( !isset($varnish_headers['X-Cacheable']) ) {
			
			?>
			<div id="title">Alas, no.</div>
			
			<p>Our robots were not find the "X-Varnish" header in the response from the server. That means Varnish is probably not running, which in turn means you actually may not be on DreamPress!</p>
			
			<p>To help you debug, here are your nameservers:</p>
			
			<ul><?php
			
			$nameservers = dns_get_record($varnish_host,DNS_NS);
						
			foreach ($nameservers as $record) {
				echo '<li>'.$record['target'].'</li>';
			}
			?>
			</ul>
			
			<p>If you're hosted on DreamPress, they should be ns1.dreamhost.com, ns2.dreamhost.com, and ns3.dreamhost.com</p>
			
			<?php
			
		} elseif ( strpos( $varnish_headers['X-Cacheable'], 'yes') !== false || strpos( $varnish_headers['X-Cacheable'], 'YES') !== false ) {
			?>
			<p><img src="/images/robot.presents.right.png" style="float:left;margin:0 5px 0 0;" width="150" /></p>
			<div id="title">Yes!</div>
			<p>Well, congratulations to you!</p>
			<p>Looks like DreamPress is running and serving content from the Varnish Cache.</p><br style="clear:both;" />
			<?php

		} else {
			?>
			<div id="title">No!</div>
			
			<p>Faaaail</p>
			<?php
		}
		
		/*
		
		To Do
		
		1) This site doesn't look like WP
		2) Look for known COOKIE PROBLEMS
		3) Failures
		4) Not Varnish (i.e. not DreamPress) - Show nameservers
		5) If cloudflare...
		6) PHPSessions - explain
		7) Max-Age 0
		8) If HTTPS...
			
		*/

		?>
			<p>Here are the actual headers we received:</p>
				
				<table id="headers">
					<tr class="even"><td width="200px" style="text-align:right;">The url we checked:</td><td><?php echo $varnish_url; ?></td></tr>
					<tr class="odd"><td width="200px">&nbsp;</td><td><?php echo $varnish_headers[0]; ?></td></tr>
				<?php

				$rownum = '0';
				foreach ($varnish_headers as $entry => $key ) {
					if ( $entry != '0' ) {
						if ($rownum & 1) { 
							$rowclass = "even";
						} else {
							$rowclass = "odd";
						}

						echo '<tr class="'.$rowclass.'"><td width="200px" style="text-align:right;">'.$entry.':</td><td>'.$key.'</td></tr>';
					}
					$rownum++;
				}
				?>
				</table>
				
				<p>&nbsp;</p>
				
				<p>And the headers we sent:</p>
				
				<table id="headers">
				  <tr class="odd"><td width="200px">&nbsp;</td><td>HEAD / HTTP/1.1
				</td></tr>
				  <tr class="even"><td width="200px" style="text-align:right;">Host:</td><td><?php echo $varnish_host; ?></td></tr>
				  <tr class="odd"><td width="200px" style="text-align:right;">Accept:</td><td>*/*</td></tr>
				  <tr class="even"><td width="200px" style="text-align:right;">Accept-Encoding:</td><td>gzip, deflate</td></tr>
				</table>
	        <div style="margin: 30px;font-weight: bold;font-size: 18pt;">Check another site!</div>
	    <?php
	}	
} 
?>

	<form method="POST" action="<?php echo $filename; ?>" id="check_dreampress_form">
          <input name="url" id="url" value="" type="text">
          <input name="check_it" id="check_it" value="Check It!" type="submit">
    </form>

	<p><img src="/images/robot.sleeping.png" width="50%" ></p>

      </div><!-- end content -->
      <div id="footer">
        <div>isdreampressworking.com brought to you by:</div>
        <div><a href="https://www.dreamhost.com"><img src="/images/DH_logo_blue.png" width="200px" /></a><div>
        <div><a href="https://www.dreamhost.com" target="_new" >DreamHost</a></div>
      </div><!-- end footer -->
    </div><!-- end container -->
  </body>
</html>
