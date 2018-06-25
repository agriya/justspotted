<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="sightingRatings index">
<h2><?php echo __l('Sighting Ratings');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($sightingRatings)):

$i = 0;
foreach ($sightingRatings as $sightingRating):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cDateTime($sightingRating['SightingRating']['created']);?></p>
		<p><?php echo $this->Html->link($this->Html->cInt($sightingRating['Sighting']['id']), array('controller'=> 'sightings', 'action' => 'view', $sightingRating['Sighting']['id']), array('escape' => false));?></p>
		<p><?php echo $this->Html->link($this->Html->cText($sightingRating['User']['username']), array('controller'=> 'users', 'action' => 'view', $sightingRating['User']['username']), array('escape' => false));?></p>
		<p><?php echo $this->Html->cText($sightingRating['SightingRatingType']['name']);?></p>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Sighting Ratings available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($sightingRatings)) {
    echo $this->element('paging_links');
}
?>
</div>
