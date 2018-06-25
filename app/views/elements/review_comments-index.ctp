<?php
    echo $this->requestAction(array('controller' => 'review_comments', 'action' => 'index', $review['Review']['id'],'type'=>$type), array('key' => $review['Review']['id'], 'return'));
?>