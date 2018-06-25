<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="sightingRatingStats index">
<h2><?php echo __l('Sighting Rating Stats');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($sightingRatingStats)):

$i = 0;
foreach ($sightingRatingStats as $sightingRatingStat):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cInt($sightingRatingStat['SightingRatingStat']['id']);?></p>
		<p><?php echo $this->Html->cDateTime($sightingRatingStat['SightingRatingStat']['created']);?></p>
		<p><?php echo $this->Html->cDateTime($sightingRatingStat['SightingRatingStat']['modified']);?></p>
		<p><?php echo $this->Html->link($this->Html->cInt($sightingRatingStat['Sighting']['id']), array('controller'=> 'sightings', 'action' => 'view', $sightingRatingStat['Sighting']['id']), array('escape' => false));?></p>
		<p><?php echo $this->Html->link($this->Html->cText($sightingRatingStat['SightingRatingType']['name']), array('controller'=> 'sighting_rating_types', 'action' => 'view', $sightingRatingStat['SightingRatingType']['slug']), array('escape' => false));?></p>
		<p><?php echo $this->Html->cInt($sightingRatingStat['SightingRatingStat']['count']);?></p>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $sightingRatingStat['SightingRatingStat']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $sightingRatingStat['SightingRatingStat']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Sighting Rating Stats available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($sightingRatingStats)) {
    echo $this->element('paging_links');
}
?>
</div>
