<?php /* SVN: $Id: $ */ ?>
<div class="userSightingRatingStats index">
	<h2><?php echo __l('User Sighting Rating Stats');?></h2>
<?php echo $this->element('paging_counter');?>
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><?php echo $this->Paginator->sort('id');?></th>
				<th><?php echo $this->Paginator->sort('created');?></th>
				<th><?php echo $this->Paginator->sort('modified');?></th>
				<th><?php echo $this->Paginator->sort('user_id');?></th>
				<th><?php echo $this->Paginator->sort('sighting_rating_type_id');?></th>
				<th><?php echo $this->Paginator->sort('count');?></th>
			</tr>
	<?php
if (!empty($userSightingRatingStats)):
	$i = 0;
	foreach ($userSightingRatingStats as $userSightingRatingStat):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td class="actions"><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $userSightingRatingStat['UserSightingRatingStat']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userSightingRatingStat['UserSightingRatingStat']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
		<td class="dc">
			<?php echo $this->Html->cInt($userSightingRatingStat['UserSightingRatingStat']['id']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cDateTime($userSightingRatingStat['UserSightingRatingStat']['created']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cDateTime($userSightingRatingStat['UserSightingRatingStat']['modified']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($userSightingRatingStat['User']['username']), array('controller' => 'users', 'action' => 'view', $userSightingRatingStat['User']['id'])); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($userSightingRatingStat['SightingRatingType']['name']), array('controller' => 'sighting_rating_types', 'action' => 'view', $userSightingRatingStat['SightingRatingType']['id'])); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cInt($userSightingRatingStat['UserSightingRatingStat']['count']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No User Sighting Rating Stats available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($userSightingRatingStats)) {
    echo $this->element('paging_links');
}
?>
</div>
