<div class="list-outer-block clearfix">
<ul class="paging-list grid_left clearfix">
	<li class="active">
	<span class="left">&nbsp;</span>
		<?php echo $this->Html->link(__l('Latest'), array('controller' => 'sightings', 'action' => 'index', 'sort' => 'id', 'direction' => 'desc'), array('title' => __l('Latest'), 'class' => 'js-index-search-rating-filter {"param_data":"sort:id/direction:desc"}'));?>
	</li>
	
    	<?php echo $this->requestAction(array('controller' => 'sighting_rating_types', 'action' => 'menu'), array('return')); ?>
        <li>
        <span class="right">&nbsp;</span>
		<?php echo $this->Html->link(__l('Most Viewed'), array('controller' => 'sightings', 'action' => 'index', 'sort' => 'sighting_view_count', 'direction' => 'desc'), array('title' => __l('Most Viewed'), 'class' => 'js-index-search-rating-filter {"param_data":"sort:sighting_view_count/direction:desc"}'));?>
	</li>
</ul>
</div>