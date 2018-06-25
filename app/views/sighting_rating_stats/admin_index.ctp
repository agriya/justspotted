<?php /* SVN: $Id: $ */ ?>
<div class="sightingRatingStats index">
	<h2><?php echo __l('Sighting Rating Stats');?></h2>
<?php echo $this->element('paging_counter');?>
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><?php echo $this->Paginator->sort('id');?></th>
				<th><?php echo $this->Paginator->sort('created');?></th>
				<th><?php echo $this->Paginator->sort('modified');?></th>
				<th><?php echo $this->Paginator->sort('sighting_id');?></th>
				<th><?php echo $this->Paginator->sort('sighting_rating_type_id');?></th>
				<th><?php echo $this->Paginator->sort('count');?></th>
			</tr>
	<?php
if (!empty($sightingRatingStats)):
	$i = 0;
	foreach ($sightingRatingStats as $sightingRatingStat):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td class="actions"><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $sightingRatingStat['SightingRatingStat']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $sightingRatingStat['SightingRatingStat']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
		<td class="dc">
			<?php echo $this->Html->cInt($sightingRatingStat['SightingRatingStat']['id']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cDateTime($sightingRatingStat['SightingRatingStat']['created']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cDateTime($sightingRatingStat['SightingRatingStat']['modified']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->link($this->Html->cInt($sightingRatingStat['Sighting']['id']), array('controller' => 'sightings', 'action' => 'view', $sightingRatingStat['Sighting']['id'])); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($sightingRatingStat['SightingRatingType']['name']), array('controller' => 'sighting_rating_types', 'action' => 'view', $sightingRatingStat['SightingRatingType']['id'])); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cInt($sightingRatingStat['SightingRatingStat']['count']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No Sighting Rating Stats available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($sightingRatingStats)) {
    echo $this->element('paging_links');
}
?>
</div>
