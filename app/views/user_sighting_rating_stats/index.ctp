<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userSightingRatingStats index">
    <ul class="sighting-list sighting-list1 clearfix">
        <?php
        if (!empty($sightingRatingTypes)):
        	foreach ($sightingRatingTypes as $sightingRatingType):
        		$i = 0;
        		foreach($userSightingRatingStats as $userSightingRatingStat):
        			if($userSightingRatingStat['UserSightingRatingStat']['sighting_rating_type_id'] == $sightingRatingType['SightingRatingType']['id'] && $sightingRatingType['SightingRatingType']['is_active']) :
    				$i++;
    			?>
    				<li class="<?php echo $sightingRatingType['SightingRatingType']['slug'];?>">
                        <span class="sighting">
                    		<?php echo $sightingRatingType['SightingRatingType']['name'].': '.$this->Html->cInt($userSightingRatingStat['UserSightingRatingStat']['count']); ?>
                        </span>
                    </li>

    			<?php
    			endif;
        		 endforeach;
            endforeach;
        endif;
        ?>
    </ul>
</div>
