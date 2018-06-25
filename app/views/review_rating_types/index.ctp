<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<li class="js-review-rating-block js-review-rating-response-<?php echo $sighting_id.'-'.$review_id; ?>">
    <ul class="clearfix">
        <?php
        if (!empty($reviewRatingTypes)):
        $class = '';
        if($this->Auth->user('id')) :
        $class = ' js-rating-update-ajax {"container":"js-review-rating-response-'.$sighting_id.'-'.$review_id.'"}';
        endif;

        $i = 0;
        foreach ($reviewRatingTypes as $reviewRatingType):
        ?>
        	<li class="great <?php echo str_replace(" ", "", strtolower($reviewRatingType['ReviewRatingType']['name'])); ?>">
        		<?php
        			$disp = (!empty($reviewRatingType['ReviewRatingStat'][0]['count']) ? ($reviewRatingType['ReviewRatingStat'][0]['count']) : 0);
        		?>
        		<?php echo $this->Html->link($reviewRatingType['ReviewRatingType']['name'] .' ('.$disp.')' , array('controller' => 'review_ratings', 'action'=>'add', 'sighting_id' =>  $sighting_id, 'review_id' =>  $review_id , 'review_rating_type_id' =>  $reviewRatingType['ReviewRatingType']['id']), array('class' => $reviewRatingType['ReviewRatingType']['name'].$class, 'title' => $reviewRatingType['ReviewRatingType']['name'] .' ('.$disp.')'));?>

        	</li>

        <?php
            endforeach;
             $limit=2;?>
        	 <li>
        	 <?php
             if(empty($this->request->params['isAjax'])) {
        		         $grid_class = "grid_10 omega alpha ";
        		      } else {
        			   $grid_class = "grid_9 omega alpha";
	       	}
               if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='home' ){
				$grid_class = "grid_10 omega alpha";
		      }
              if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='view' ){
				$grid_class = "grid_14 omega alpha";
		}?>
             <ol class="comment-users <?php echo $grid_class; ?>">
             <?php
            foreach ($reviewRatingTypes as $reviewRatingType){
            $user_count=count($reviewRatingType['ReviewRating']);
            if(!empty($reviewRatingType['ReviewRating'])){ ?>
            <li class="round-3">
            <?php
                $i=1;
                foreach($reviewRatingType['ReviewRating'] as $review_rating){
                        if($i<=$limit){
                            if($i==1){
                                echo $this->Html->link($review_rating['User']['username'], array('controller' => 'users', 'action'=>'view', $review_rating['User']['username']), array('title' => $review_rating['User']['username'] ));
                            }
                            else{
                                echo ", " . $this->Html->link($review_rating['User']['username'], array('controller' => 'users', 'action'=>'view', $review_rating['User']['username']), array('title' => $review_rating['User']['username'] ));
                            }
                        }
                        $i++;
                }
                if($user_count>$limit){
                    $remaining_count=$user_count - $limit;
                    if($remaining_count==1){
                        $label='other';
                    }
                    else{
                        $label='others';
                    }
                    echo ' ' . __l('and') . ' ' . $this->Html->link($remaining_count . ' ' . $label , array('controller' => 'review_ratings', 'action'=>'index', 'review_rating_type' =>  $reviewRatingType['ReviewRatingType']['slug'], 'review_id' =>  $review_id ), array('class' =>'js-thickbox', 'title' => $remaining_count . ' ' . $label)) . ' ';
                 }
                 echo ' ' . __l('said') . ' ' .'<span class="find-info">' .$reviewRatingType['ReviewRatingType']['name'] . '!'.'</span>';?>
                </li>
                <?php
            }
            } ?>
            </ol>
        	</li>
            <?php

        endif;
        ?>
    </ul>
</li>