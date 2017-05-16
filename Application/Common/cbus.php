<?php
function xiajiadate($datestr) {
	$datestr = $datestr+24*60*60;
	return date("m月d日", $datestr);
}