<?php
	if(!empty($business)):
		echo $this->requestAction(array('controller' => 'business_followers', 'action' => 'index', 'follower' => $business), array('key' => $business, 'return'));
	endif;
?>