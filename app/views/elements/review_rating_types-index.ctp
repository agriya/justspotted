<?php
	echo $this->requestAction(array('controller' => 'review_rating_types', 'action' => 'index','sighting_id' => $sighting_id, 'review_id' => $review_id,'type'=>$type), array('return'));
?>