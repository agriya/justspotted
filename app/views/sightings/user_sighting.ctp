<div class="js-response">
 <h3><?php echo __l('Add from your existing sightings'); ?></h3>
    <?php

    if (!empty($sightings)): ?>
    <ol class="sighting-list existing-list clearfix">
      <?php
	  $i=0;
         foreach ($sightings as $sighting):
        ?>
    		   <li>
                <div class="image-block <?php echo (empty($sighting['GuidesSighting']))? "add-sighting": "remove-sighting"; ?>">
        		     <?php
            		    echo $this->Html->link($this->Html->showImage('Review', $sighting['Review'][0]['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $sighting['Review'][0]['User']['username']), 'title' => $sighting['Item']['name']. ' @ ' .$sighting['Place']['name'], 'class' => 'tool-tip')), array('controller' => 'reviews', 'action' => 'view',  $sighting['Review'][0]['id']), array('escape' => false,'target' =>'_blank'));
        			?>
       			</div>
        		<span class="js-link-append js-link-append-delete-<?php echo $i; ?> <?php echo (empty($sighting['GuidesSighting']))? "hide": ""; ?>">
    				<?php
    					echo $this->Html->link(__l('Delete'), array('controller' => 'guides_sightings', 'action' => 'delete', $sighting['GuidesSighting'][0]['id'], 'guide_id' => $guide['Guide']['id'], 'review_id' => $sighting['Review'][0]['id']), array('title' => __l('Delete'), 'class' => "delete delete-link js-map-to-guide-delete {'add': 'js-link-append-add-". $i. "', 'del': 'js-link-append-delete-". $i. "'}"));
    				?>
                 </span>
    		  	   <span class="js-link-append js-link-append-add-<?php echo $i; ?> <?php echo (empty($sighting['GuidesSighting']))? "": "hide"; ?>">
                      <?php
        					echo $this->Html->link(__l('Add'), array('controller' => 'guides_sightings', 'action' => 'add', 'guide_id' => $guide['Guide']['id'], 'review_id' => $sighting['Review'][0]['id']), array('title' => __l('Add'), 'class' => "add add-link js-map-to-guide-add {'add': 'js-link-append-add-". $i. "', 'del': 'js-link-append-delete-". $i. "'}"));
        				?>
                  </span>
                    <div class="sighting-name">
                        <?php
                        echo $this->Html->link($this->Html->truncate($sighting['Item']['name']. ' @ ' .$sighting['Place']['name'], 10), array('controller' => 'sightings', 'action' => 'view',  $sighting['Sighting']['id']), array('escape' => false,'target' =>'_blank','title' => $this->Html->cText($sighting['Item']['name'],false) . ' @ ' . $this->Html->cText($sighting['Place']['name'], false))); ?>
    				</div>
    		   </li>
     <?php
	 $i++;
     endforeach; ?>
        </ol>
        <div class="clearfix redirect-button-block">
        <?php
        	if (!empty($sighting)) { ?>
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