    <?php
    if(!empty($sightingRatingTypes)) :
    foreach($sightingRatingTypes as $sightingRatingType) : 
		if(!empty($sightingRatingType['SightingRatingStat'])):
	?>
        <h3 class="rating-title"><?php echo !empty($sightingRatingType['SightingRatingType']['filter_name']) ? $sightingRatingType['SightingRatingType']['filter_name'] : $sightingRatingType['SightingRatingType']['name'];
    	echo ' ' . __l('by:'); ?></h3>
    	<?php
    	    echo $this->element('sighting_ratings-index', array('sighting_id' => $this->request->params['named']['sighting_id'], 'view' => 'raters', 'sighting_rating_type_id' => $sightingRatingType['SightingRatingType']['id']));
    	endif;
	endforeach;
    endif;
    ?>