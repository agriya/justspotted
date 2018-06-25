<?php /* SVN: $Id: $ */ ?>
<div class="sightingRatingStats view">
<h2><?php echo __l('Sighting Rating Stat');?></h2>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($sightingRatingStat['SightingRatingStat']['id']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($sightingRatingStat['SightingRatingStat']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($sightingRatingStat['SightingRatingStat']['modified']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Sighting');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cInt($sightingRatingStat['Sighting']['id']), array('controller' => 'sightings', 'action' => 'view', $sightingRatingStat['Sighting']['id']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Sighting Rating Type');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($sightingRatingStat['SightingRatingType']['name']), array('controller' => 'sighting_rating_types', 'action' => 'view', $sightingRatingStat['SightingRatingType']['slug']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Count');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($sightingRatingStat['SightingRatingStat']['count']);?></dd>
	</dl>
</div>