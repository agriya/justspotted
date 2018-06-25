<?php /* SVN: $Id: $ */ ?>
<div class="places index js-response">
<ol class="people-list branch-list clearfix">
<?php
if (!empty($places)):

	$i = 0;
	foreach ($places as $place):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>		
		<li class="clearfix">
    			<h5><?php echo $this->Html->link($this->Html->cText($place['Place']['name']), array('controller'=> 'places', 'action' => 'view', $place['Place']['slug']), array('escape' => false));?></h5>
                <div class="action-block">
                     <?php echo $this->Html->link(__l('Edit'), array('controller' => 'places', 'action'=>'edit', $place['Place']['id']), array('class' => 'edit', 'title' => __l('Edit'))); ?>
                </div>
                <address class="address-block">
                    <?php echo $this->Html->cText($place['Place']['address2']); ?>
    			</address>
    			<div class="clearfix">
        			<dl class="sighting-list clearfix">
                    	<dt class="updates"><?php echo __l('Updates');?></dt>
                        <dd><?php echo $this->Html->cInt($place['Place']['business_update_count']); ?></dd>
            	       	<dt class="follower"><?php echo __l('Followers'); ?></dt>
            			<dd><?php echo $this->Html->cInt($place['Place']['place_follower_count']); ?></dd>
            	       	<dt class="items"><?php echo __l('Items');?></dt>
                        <dd><?php echo $this->Html->cInt($place['Place']['item_count']); ?></dd>
                    </dl>
                </div>
        </li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<?php echo __l('No Places available');?>
	</li>
<?php
endif;
?>
</ol>

<div class="js-pagination">
<?php
if (!empty($place)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>