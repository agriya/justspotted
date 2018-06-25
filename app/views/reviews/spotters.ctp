<ol class="user-list clearfix">
<?php
if (!empty($reviews)):
	$i = 1;
	foreach ($reviews as $review):
		if($i < 7):
?>
      <li>
        <?php echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($review['User']['id']), array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $review['User']['username']), 'title' => $review['User']['username'], 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $review['User']['username']), array('escape' => false)); ?>
      </li>
<?php
		endif;
	$i++;
    endforeach;
	
	if(count($reviews) > 6):
	$spotters_count = count($reviews)-6;
	?>
	<li class="more-users"><span><?php echo $spotters_count.' '.__l('Others'); ?></span></li>
    <?php endif; ?>
    <?php
else:
?>
	<li class="notice">
		<p><?php echo __l('No Spotters available');?></p>
	</li>
<?php
endif;
?>
</ol>
