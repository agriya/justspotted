<?php
	echo $this->requestAction(array('controller' => 'reviews', 'action' => 'index', 'sighting_id' => $sighting_id, 'view' => $view), array('key' => $sighting_id, 'return'));
?>