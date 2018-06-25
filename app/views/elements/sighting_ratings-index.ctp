<?php
	$passed = array();
	if(isset($view)) {
		$passed['view'] = $view;
	}
	if(isset($sighting_id)) {
		$passed['sighting_id'] = $sighting_id;
	}
	if(isset($sighting_rating_type_id)) {
		$passed['sighting_rating_type_id'] = $sighting_rating_type_id;
	}
	echo $this->requestAction(array_merge(array('controller' => 'sighting_ratings','action' => 'index'), $passed), array('return'));
?>
