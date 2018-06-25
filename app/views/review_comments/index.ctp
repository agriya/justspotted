<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="reviewComments index js-responses js-response">
<?php if(empty($this->request->params['requested']) && empty($this->request->params['isAjax'])): ?>
<h4><?php echo __l('Review Comments');?></h4>
<?php endif; 
?>
<ol class="comment-list js-responses-<?php echo $this->request->params['named']['review_id']; ?>" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($reviewComments)):

$i = 0;
foreach ($reviewComments as $reviewComment):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
<?php
  if(empty($this->request->params['isAjax'])) {
		         $grid_class = "grid_9 omega";
		      } else {
			   $grid_class = "grid_8 omega";
		}
		if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='home' ){
				$grid_class = "grid_9 omega";
		}
		if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='view' ){
				$grid_class = "grid_13 omega";
		}
	 ?>
	<li class="list-row clearfix" id="comment-<?php echo $reviewComment['ReviewComment']['id']; ?>" >

	   	<div class="grid_1 omega">
			<?php 	
				echo $this->Html->link($this->Html->showImage('UserAvatar', $reviewComment['User']['UserAvatar'], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($reviewComment['User']['username'], false)), 'title' => $this->Html->cText($reviewComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $reviewComment['User']['username']), array('escape' => false));
			?>
	    </div>
		<div class="content-block <?php echo $grid_class;?> ">
		<?php if (!empty($this->request->params['named']['review_id'])):?>
		<!--	<h3><?php echo $this->Html->link($this->Html->cText($reviewComment['Review']['Sighting']['Item']['name'])." @ ".$this->Html->cText($reviewComment['Review']['Sighting']['Place']['name']), array('controller'=> 'sightings', 'action' => 'index','sighting_id' =>  $reviewComment['Review']['Sighting']['id'], 'item' => $reviewComment['Review']['Sighting']['Item']['slug']), array('escape' => false, 'title' => $this->Html->cText($reviewComment['Review']['Sighting']['Item']['name'], false)." @ ".$this->Html->cText($reviewComment['Review']['Sighting']['Place']['name'], false)));?></h3>-->
		<?php endif;?>
			<h5><?php echo $this->Html->link($reviewComment['User']['username'], array('controller' => 'users', 'action' => 'view', $reviewComment['User']['username']), array('escape' => false));?></h5>
		  <?php echo $this->Html->cText(nl2br($reviewComment['ReviewComment']['comment']));?>
		<p class="month date-info">
	       	  <?php echo __l('about'); ?> <?php echo $this->Time->timeAgoInWords($reviewComment['ReviewComment']['created']) ;?>
		  </p>
		  <?php if ($this->Auth->sessionValid() &&  $reviewComment['User']['id'] == $this->Auth->user('id')) { ?>
          <div class="add-block">
            	<?php echo $this->Html->link(__l('Delete'), array('controller' => 'review_comments', 'action' => 'delete', $reviewComment['ReviewComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
				<!-- Flagged Admin block> -->
				<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):?>		
					<?php if(!empty($reviewComment['ReviewComment']['is_system_flagged']) || !empty($reviewComment['ReviewComment']['admin_suspend'])) { ?>
						<?php if(!empty($reviewComment['ReviewComment']['is_system_flagged'])) { ?>
								<p><?php echo __l('Flagged');?></p>
								<?php echo $this->Html->link(__l('Clear flag'), array('controller' => 'review_comments', 'action' => 'update_status', $reviewComment['ReviewComment']['id'], 'status' => 'unflag', 'admin' => true), array('class' => 'clear-flag', 'title' => __l('Clear flag')));?>
                            
							<?php } ?>
							<?php if(!empty($reviewComment['ReviewComment']['admin_suspend'])) { ?>
								<p><?php echo __l('Suspended');?></p>
								<?php echo $this->Html->link(__l('Unsuspend'), array('controller' => 'review_comments', 'action' => 'update_status', $reviewComment['ReviewComment']['id'], 'status' => 'unsuspend', 'admin' => true), array('class' => 'clear-flag', 'title' => __l('Unsuspend')));?>
							<?php } ?>
							<?php if(!empty($reviewComment['ReviewComment']['detected_suspicious_words'])) { ?>
								<p><?php echo __l('Suspicious Words');?></p>
								<span><?php 
									$detected_words = implode(', ', unserialize($reviewComment['ReviewComment']['detected_suspicious_words']));
									echo $detected_words;					
								?>
								</span>
							<?php } ?>				
					<?php } ?>
				<?php endif;?>
				<!-- <Flagged Admin block -->
			</div>
		<?php } ?>
		</div>
	</li>
<?php
    endforeach;
endif;
?>
</ol>
</div>
