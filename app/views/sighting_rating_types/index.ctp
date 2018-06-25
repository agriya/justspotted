<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<li class="js-sighting-rating-block js-sighting-rating-response-<?php echo $sighting_id; ?>">
    <ul>
        <?php
        if (!empty($sightingRatingTypes)):
        $class = '';
        if($this->Auth->user('id')) :
        $class = ' js-rating-update-ajax {"container":"js-sighting-rating-response-'.$sighting_id.'"}';
        endif;

        $i = 0;
        foreach ($sightingRatingTypes as $sightingRatingType):
        ?>
        	<li class="<?php echo $sightingRatingType['SightingRatingType']['slug']; ?>">
        		<?php
        			$disp = (!empty($sightingRatingType['SightingRatingStat'][0]['count']) ? ($sightingRatingType['SightingRatingStat'][0]['count']) : 0);
        		?>
        		<?php echo $this->Html->link($sightingRatingType['SightingRatingType']['name'] .' ('.$disp.')' , array('controller' => 'sighting_ratings', 'action'=>'add', 'sighting_id' =>  $sighting_id , 'sighting_rating_type_id' =>  $sightingRatingType['SightingRatingType']['id']), array('class' => $sightingRatingType['SightingRatingType']['slug'].$class, 'title' => $sightingRatingType['SightingRatingType']['name'] .' ('.$disp.')'));
        		?>
        	</li>
        <?php
            endforeach;
        endif;
        ?>
    </ul>
</li>