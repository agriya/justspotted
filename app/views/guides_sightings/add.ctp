<?php 
echo 'add|'.Router::url(array('controller' => 'guides_sightings', 'action' => 'delete', $guidesSighting_id, 'guide_id' => $guide['Guide']['id'], 'review_id' => $review_id), false);
?>