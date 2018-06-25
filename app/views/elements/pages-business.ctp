<?php
	$passed = array();
	if(!empty($slug)) {
		$passed['slug'] = $slug;
	}	
	echo $this->requestAction(array_merge(array('controller' => 'pages', 'action' => 'business'), $passed), array('return'));
?>