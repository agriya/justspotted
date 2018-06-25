<?php /* SVN: $Id: $ */ ?>
<?php
	if(!empty($sightingRatingTypes)):
		foreach($sightingRatingTypes as $sightingRatingType): ?>
				<li><em></em> <?php echo $this->Html->link((!empty($sightingRatingType['SightingRatingType']['filter_name']) ? $sightingRatingType['SightingRatingType']['filter_name'] : $sightingRatingType['SightingRatingType']['name']) , array('controller' => 'sightings', 'action' => 'index', 'user'=>$this->request->params['named']['user'], 'sighting_rating_type' => $sightingRatingType['SightingRatingType']['slug']), array('title' => (!empty($sightingRatingType['SightingRatingType']['filter_name']) ? $sightingRatingType['SightingRatingType']['filter_name'] : $sightingRatingType['SightingRatingType']['name'])));?></li>
		<?php endforeach;	
	endif;
?>