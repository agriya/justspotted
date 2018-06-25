<?php /* SVN: $Id: $ */ ?>
<div class="businessUpdates index">
	<h2><?php echo __l('Business Updates');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="guide-list  clearfix">
<?php
if (!empty($businessUpdates)):

	$i = 0;
	foreach ($businessUpdates as $businessUpdate):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
?>


        <li>
                <p><?php echo $this->Html->cText($businessUpdate['BusinessUpdate']['updates']); ?></p>
                <p> Item <?php echo $this->Html->link($this->Html->cText($businessUpdate['Item']['name']), array('controller' => 'items', 'action' => 'view', $businessUpdate['Item']['id']),array('escape'=>false)); ?> @
				<?php echo $this->Html->link($this->Html->cText($businessUpdate['Place']['name']), array('controller' => 'places', 'action' => 'view', $businessUpdate['Place']['id']),array('escape'=>false)); ?></p>
        </li>

<?php

    endforeach;
else:
?>
	<li class="notice">
	<?php echo __l('No Business Updates available');?>
	</li>
<?php
endif;
?>
</ol>
<?php


if (!empty($businessUpdates)) {
    echo $this->element('paging_links');
}
?>
</div>
