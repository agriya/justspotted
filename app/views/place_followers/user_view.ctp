<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="js-response">
<ol class="user-list  clearfix">
<?php
if (!empty($placeFollowers)):
	$i = 0;
	foreach ($placeFollowers as $placeFollower):
		if ($i++ % 2 == 0) {
			$class = 'altrow ';
		}
	?>
		<li class="<?php echo $class;?>">
			<?php
				echo $this->Html->link($this->Html->cText($placeFollower['Place']['name'], false), array('controller' => 'places', 'action' => 'view', $placeFollower['Place']['slug']), array('title' => $this->Html->cText($placeFollower['Place']['name'], false)));
			?>
		</li>
	<?php
		endforeach;
else:
	?>
		<li class="notice">
			<p><?php echo __l('No Place Followed');?></p>
		</li>
	<?php
endif;
?>
</ol>
<div class="js-pagination">
	<?php
	if (!empty($placeFollowers)) {
	?>
		<div class="js-pagination">
	   <?php echo $this->element('paging_links'); ?>
	   </div>
	<?php
	}
?>
</div>
</div>