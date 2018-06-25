<?php /* SVN: $Id: $ */ ?>
<div class="reviewRatingStats index">
<?php echo $this->element('paging_counter');?>
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><?php echo $this->Paginator->sort('id');?></th>
				<th><?php echo $this->Paginator->sort('created');?></th>
				<th><?php echo $this->Paginator->sort('modified');?></th>
				<th><?php echo $this->Paginator->sort('review_id');?></th>
				<th><?php echo $this->Paginator->sort('review_rating_type_id');?></th>
				<th><?php echo $this->Paginator->sort('count');?></th>
			</tr>
	<?php
if (!empty($reviewRatingStats)):
	$i = 0;
	foreach ($reviewRatingStats as $reviewRatingStat):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td class="actions"><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $reviewRatingStat['ReviewRatingStat']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $reviewRatingStat['ReviewRatingStat']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
		<td class="dc">
			<?php echo $this->Html->cInt($reviewRatingStat['ReviewRatingStat']['id']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cDateTime($reviewRatingStat['ReviewRatingStat']['created']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cDateTime($reviewRatingStat['ReviewRatingStat']['modified']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->link($this->Html->cInt($reviewRatingStat['Review']['id']), array('controller' => 'reviews', 'action' => 'view', $reviewRatingStat['Review']['id'])); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($reviewRatingStat['ReviewRatingType']['name']), array('controller' => 'review_rating_types', 'action' => 'view', $reviewRatingStat['ReviewRatingType']['id'])); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cInt($reviewRatingStat['ReviewRatingStat']['count']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No Review Rating Stats available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($reviewRatingStats)) {
    echo $this->element('paging_links');
}
?>
</div>
