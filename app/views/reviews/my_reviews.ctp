<div class="js-response">
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
        		    echo $this->Html->link($this->Html->showImage('Review', $review['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $review['Sighting']['Item']['name']), 'title' => $review['Sighting']['Item']['name'] . ' @ ' .  $review['Sighting']['Place']['name'], 'class' => 'tool-tip')), array('controller' => 'reviews', 'action' => 'view',  $review['Review']['id']), array('escape' => false));
        			?>
        			</div>


        		   </li>
         <?php
    	 $i++;
         endforeach; ?>
       </ol>
      <div class="js-pagination"> <?php echo $this->element('paging_links'); ?> </div>
       <?php
       else:?>
     	<p class="notice"><?php echo __l('No Sightings available');?></p>
     <?php  endif; ?>
</div>