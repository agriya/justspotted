<?php /* SVN: $Id: $ */ ?>
<?php
	if(!empty($sightingRatingTypes)):
		foreach($sightingRatingTypes as $sightingRatingType): ?>
				<li><em></em><?php echo $this->Html->link((!empty($sightingRatingType['SightingRatingType']['filter_name']) ? $sightingRatingType['SightingRatingType']['filter_name'] : $sightingRatingType['SightingRatingType']['name']) , array('controller' => 'sightings', 'action' => 'index', 'sighting_rating_type_id' => $sightingRatingType['SightingRatingType']['id']), array('title' => (!empty($sightingRatingType['SightingRatingType']['filter_name']) ? $sightingRatingType['SightingRatingType']['filter_name'] : $sightingRatingType['SightingRatingType']['name']), 'class' => 'js-index-search-rating-filter {"param_data":"sighting_rating_type_id:'.$sightingRatingType['SightingRatingType']['id'].'"}'));?></li>
		<?php endforeach;	
	endif;
?>