<?php /* SVN: $Id: $ */ ?>
<div class="businessUpdates index js-response">
<h3><?php echo __l('Updates'); ?></h3>
<ol class="guide-list business-guide-list clearfix">
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
        <div class="date-info">
                <span class="date-info">
                  <?php echo $this->Html->cDateTimeHighlight($businessUpdate['BusinessUpdate']['created']); ?>
                </span>
                    <div class="action-block">
                    <?php
					if($this->Auth->user('id')==$businessUpdate['BusinessUpdate']['user_id']){
					   echo $this->Html->link(__l('Edit'), array('controller' => 'business_updates', 'action' => 'edit', $businessUpdate['BusinessUpdate']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit'))); ?>
				    	<?php echo $this->Html->link(__l('Delete'), array('controller' => 'business_updates', 'action' => 'delete', $businessUpdate['BusinessUpdate']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));  }?>
                    </div>
                    </div>
        <div class="business-updateinfo">
            <div class="business-updateinformation">
                <?php echo $this->Html->cText($businessUpdate['BusinessUpdate']['updates']); ?>
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