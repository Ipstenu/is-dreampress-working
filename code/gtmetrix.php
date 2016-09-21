<?php

/* Is DreamPress Working?
 *
 * This is the GTMetrix Code
 *
 */

if( !defined('ISDREAMPRESSWORKING') ) {
   die('Direct access not permitted');
}

// Load the web test framework class.
require_once("Services_WTF_Test.php");

$gtmetrix_test = new Services_WTF_Test("mika.epstein@dreamhost.com", "7c51c4254e7c73ba44b9888e56a9aa49");

$gtmetrix_testid = $gtmetrix_test->test(array(
    'url' => $varnish_url
));

if ( !$gtmetrix_testid ) {
    echo "<p>Test ID failed: " . $gtmetrix_test->error() . "</p>";
} else {
	$gtmetrix_test->get_results();

	if ($gtmetrix_test->error()) {
		echo "<p>Test failed: " . $gtmetrix_test->error() ."</p>";
	} else {		
		$gtmetrix_results = $gtmetrix_test->results();
		?>
		
		<table class="table-standard">
			<tr>
				<th>Page Load Time</th>
				<th>Total Page Size</th>
				<th>Requests</th>
			</tr>
			
			<tr>
				<td><?php echo round ( ( $gtmetrix_results['page_load_time'] / 1000 ), 2); ?>s</td>
				<td><?php echo round ( ( $gtmetrix_results['page_bytes'] / 1048576 ), 2); ?>MB</td>
				<td><?php echo $gtmetrix_results['page_elements']; ?></td>
			</tr>
		</table>
		
		<p>&nbsp;</p>

		<h3>Scores</h3>

		<table class="table-standard">
			<tr>
				<th>Pagespeed</th>
				<th>ySlow</th>
			</tr>
			
			<tr>
				<td><?php 
					echo get_letter_grade ( $gtmetrix_results['pagespeed_score'] ); 
					echo " (".$gtmetrix_results['pagespeed_score']."%)";
				?></td>
				<td><?php 
					echo get_letter_grade ( $gtmetrix_results['yslow_score'] ); 
					echo " (".$gtmetrix_results['yslow_score']."%)"; 
				?></td>
			</tr>
		</table>	
		<p><a href="<?php echo $gtmetrix_results['report_url']; ?>">Gtmetrix Results</a> (link valid for 30 days)</p>
		<?php
	}
}