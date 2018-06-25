<?php /* SVN: $Id: $ */ ?>
<div class="businessUpdates index js-response">
	
<ol class="guide-list clearfix">
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
        <div class="business-updateinfo">
            <div class="business-updateinformation">
<?php			echo $this->Html->link($this->Html->cText($businessUpdate['Business']['name']), array('controller' => 'businesses', 'action' => 'view', $businessUpdate['Business']['slug']),array('escape'=>false));
?>
                <p class="sighting-caption"><?php echo $this->Html->cText($businessUpdate['BusinessUpdate']['updates']); ?></p>
            </div>
                <?php   if($businessUpdate['Item']['name'])
    			{
                    ?>
                <?php
    				echo $this->Html->link($this->Html->cText($businessUpdate['Item']['name']), array('controller' => 'sightings', 'action' => 'item', $businessUpdate['Item']['slug']),array('escape'=>false));
    			}
    			if($businessUpdate['Place']['name'])
    			{
    				echo ' @ '.$this->Html->link($this->Html->cText($businessUpdate['Place']['name']), array('controller' => 'places', 'action' => 'view', $businessUpdate['Place']['slug']),array('escape'=>false));
                ?>
    			<address>
                    <?php
    				echo $this->Html->cText($businessUpdate['Place']['address2']);
    				echo $this->Html->cText($businessUpdate['Place']['zip_code']);
                    ?>
    			</address>
                <?php
    			}
                ?>
        </div>
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
<div class="js-pagination">
<?php
if (!empty($businessUpdates)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>
