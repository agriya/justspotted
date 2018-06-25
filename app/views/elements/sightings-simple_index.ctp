<?php
echo $this->requestAction(array('controller' => 'sightings', 'action' => 'simple_index', 'sighting_id' => $sighting_id, 'place_id' => $place_id), array('return'));
?>