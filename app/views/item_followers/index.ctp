<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="js-response">
<ol class="user-list clearfix">
<?php
if (!empty($itemFollowers)):
	$i = 0;
	foreach ($itemFollowers as $itemFollower):
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
	?>
		<li class="<?php echo $class;?>">
			<?php
				echo $this->Html->link($this->Html->cText($itemFollower['Item']['name'], false), array('controller' => 'sightings', 'action' => 'index', 'item' => $itemFollower['Item']['slug']), array('title' => $this->Html->cText($itemFollower['Item']['name'], false)));
			?>
		</li>
	<?php
		endforeach;
else:
	?>
		<li class="notice">
			<p><?php echo __l('No Item Followed');?></p>
		</li>
	<?php
endif;
?>
</ol>
<div class="js-pagination">
	<?php
	if (!empty($itemFollowers)) {
	?>
		<div class="js-pagination">
	   <?php echo $this->element('paging_links'); ?>
	   </div>
	<?php
	}
?>
</div>
</div>