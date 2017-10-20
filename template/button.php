<center>
<form method="POST" action="<?php echo $filename; ?>" id="check_dreampress_form">
	<input name="url" id="url" value="<?php if (isset($varnish_host) ) echo $varnish_host; ?>" type="text" size="100" placeholder="http://dreamhost.com">

	<p><div class="g-recaptcha" data-sitekey="6LfsogkTAAAAAMuZHeO_l9qN3k-V-xhyZkEtM_IE" data-callback="onSubmit"></div></p>
	<input name="check_it" id="check_it" value="Check It!" type="submit" class="btn dreampress-tech-features-trigger">
</form>
</center>