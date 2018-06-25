<li class="comment clearfix" id="comment-<?php echo $reviewComment['ReviewComment']['id']?>">
	<div class="grid_1 alpha">
		<?php 	
			echo $this->Html->link($this->Html->showImage('UserAvatar', $reviewComment['User']['UserAvatar'], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($reviewComment['User']['username'], false)), 'title' => $this->Html->cText($reviewComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $reviewComment['User']['username']), array('escape' => false));
		?>
	</div>
	<div class="content-block grid_9 omega">
		<h4>
		<?php echo $this->Html->link($reviewComment['User']['username'], array('controller' => 'users', 'action' => 'view', $reviewComment['User']['username']), array('escape' => false));?>
		</h4>
	  <?php echo $this->Html->cText(nl2br($reviewComment['ReviewComment']['comment']));?>
	   <p class="month date-info">
		  <?php echo __l('Recommended'); ?> <?php echo $this->Time->timeAgoInWords($reviewComment['ReviewComment']['created']) ;?>
	  </p>
	  <?php if ($this->Auth->sessionValid() &&  $reviewComment['User']['id'] == $this->Auth->user('id')) { ?>
	  <div class="add-block">
    		<?php echo $this->Html->link(__l('Delete'), array('controller' => 'review_comments', 'action' => 'delete', $reviewComment['ReviewComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
	</div>
	<?php } ?>
	</div>
</li>