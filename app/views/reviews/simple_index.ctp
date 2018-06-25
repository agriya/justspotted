<div class="js-response">
 <h3><?php echo __l('Add from your existing sightings'); ?></h3>
    <?php

    if (!empty($reviews)): ?>
    <ol class="sighting-list existing-list clearfix">
      <?php
	  $i=0;
         foreach ($reviews as $review):
        ?>
    		   <li>
                <div class="image-block">
        		     <?php
            		    echo $this->Html->link($this->Html->showImage('Review', $review['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $review['User']['username']), 'title' => $review['Sighting']['Item']['name']. ' @ ' .$review['Sighting']['Place']['name'], 'class' => 'tool-tip')), array('controller' => 'reviews', 'action' => 'view',  $review['Review']['id']), array('escape' => false,'target' =>'_blank'));
        			?>
                    <div class="reveiw-name"><?php echo $this->Html->cText($this->Html->truncate($review['Sighting']['Item']['name'],60));?></div>
       			</div>
        		<span class="js-link-append js-link-append-delete-<?php echo $i; ?> <?php echo (empty($review['GuidesSighting']))? "hide": ""; ?>">
    				<?php
    					echo $this->Html->link(__l('Delete'), array('controller' => 'guides_sightings', 'action' => 'delete', $review['GuidesSighting'][0]['id'], 'guide_id' => $guide['Guide']['id'], 'review_id' => $review['Review']['id']), array('title' => __l('Delete'), 'class' => "delete delete-link js-map-to-guide-delete {'add': 'js-link-append-add-". $i. "', 'del': 'js-link-append-delete-". $i. "'}"));
    				?>
                 </span>
    		  	   <span class="js-link-append js-link-append-add-<?php echo $i; ?> <?php echo (empty($review['GuidesSighting']))? "": "hide"; ?>">
                      <?php
        					echo $this->Html->link(__l('Add'), array('controller' => 'guides_sightings', 'action' => 'add', 'guide_id' => $guide['Guide']['id'], 'review_id' => $review['Review']['id']), array('title' => __l('Add'), 'class' => "add add-link js-map-to-guide-add {'add': 'js-link-append-add-". $i. "', 'del': 'js-link-append-delete-". $i. "'}"));
        				?>
                  </span>
                    <div class="sighting-name">
                        <?php
                        echo $this->Html->link($this->Html->truncate($review['Sighting']['Item']['name']. ' @ ' .$review['Sighting']['Place']['name'], 10), array('controller' => 'sightings', 'action' => 'view',  $review['Sighting']['id']), array('escape' => false,'target' =>'_blank','title' => $this->Html->cText($review['Sighting']['Item']['name'],false) . ' @ ' . $this->Html->cText($review['Sighting']['Place']['name'], false))); ?>
    				</div>
    		   </li>
     <?php
	 $i++;
     endforeach; ?>
        </ol>
        <div class="clearfix redirect-button-block">
        <?php
        	if (!empty($reviews)) { ?>
            <div class="js-pagination grid_left">
            	<?php
           			echo $this->element('paging_links');
           		?>
           	</div>
        <?php	} ?>
        <?php if($this->Html->params['isAjax']): ?>
            <div class="redirect-button grid_right">
              <a href="javascript:void(0)" class="js-done-redirect redirect-button">Done</a>
            </div>
        <?php endif;?>
       </div>
     <?php
       else:?>
     	<p class="notice"><?php echo __l('No Sightings available');?></p>
     <?php  endif; ?>
</div>