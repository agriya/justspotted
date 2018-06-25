<?php
	echo $this->requestAction(array('controller' => 'guides_sightings', 'action' => 'index', 'sighting_id' => $sighting_id), array('key' => $sighting_id, 'return'));
?>