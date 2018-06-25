<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="businesses index js-response">
<div class="page-info"><?php echo __l('Updates are announcement from business to followers'); ?></div>
<div class="page-count-block clearfix">
    <?php echo $this->element('paging_counter');?>
    <div class="grid_left"> <?php echo $this->Form->create('BusinessUpdate', array('class' => 'normal search-form', 'action'=>'index')); ?> <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?> <?php echo $this->Form->submit(__l('Search'));?> <?php echo $this->Form->end(); ?> </div>
    
  </div>
<?php echo $this->Form->create('BusinessUpdate' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
	<tr>
        <th class="select"></th>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'user_id');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Business'), 'business_id');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Update'), 'updates');?></div></th>
			</tr>
<?php
if (!empty($businessUpdates)):
	$i = 0;
	foreach ($businessUpdates as $businessUpdate):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = 'class="altrow"';
		}
?>
	<tr<?php echo $class;?>>
        <td class="select">
<?php echo $this->Form->input('BusinessUpdate.'.$businessUpdate['BusinessUpdate']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$businessUpdate['BusinessUpdate']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
		</td>
		<td class="actions">
          <div class="action-block">
            <span class="action-information-block">
                <span class="action-left-block">&nbsp;&nbsp;</span>
                    <span class="action-center-block">
                        <span class="action-info">
                            <?php echo __l('Action');?>
                         </span>
                    </span>
                </span>
                <div class="action-inner-block">
                    <div class="action-inner-left-block">
                        <ul class="action-link clearfix">
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $businessUpdate['BusinessUpdate']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
							<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $businessUpdate['BusinessUpdate']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
					   </ul>
					</div>
	                <div class="action-bot-left">
                      <div class="action-bot-right">
						<div class="action-bot-mid"></div>
						</div>
                     </div>
				  </div>
		</div>
				 
        </td>
		<td class="dc">
			<?php echo $this->Html->cDateTimeHighlight($businessUpdate['BusinessUpdate']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($businessUpdate['User']['username']), array('controller'=> 'users', 'action' => 'view', $businessUpdate['User']['username'], 'admin' => false), array('escape' => false));?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($businessUpdate['Business']['name']), array('controller'=> 'business', 'action' => 'view', $businessUpdate['Business']['slug'], 'admin' => false), array('escape' => false));?>
		</td>
		<td class="dl">
			<?php 
				echo $this->Html->cText($businessUpdate['BusinessUpdate']['updates']);
				if($businessUpdate['Item']['name'])
				{
					echo ' '.__l('For').' '.$this->Html->link($this->Html->cText($businessUpdate['Item']['name'], false), array('controller' => 'sightings', 'action' => 'item', $businessUpdate['Item']['slug'], 'admin' => false),array('escape'=>false));					
				}
				if($businessUpdate['Place']['name'])
				{
					echo ' @ '.$this->Html->link($this->Html->cText($businessUpdate['Place']['name']), array('controller' => 'places', 'action' => 'view', $businessUpdate['Place']['slug'], 'admin' => false),array('escape'=>false)); 				
				}
			?>			
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Business available');?></td>
	</tr>
<?php
endif;
?>
</table>
<?php if (!empty($businessUpdate)) { ?>
    <div class="clearfix">
	<div class="admin-select-block grid_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
            </div>
           <div class="admin-checkbox-button">
                <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
            </div>
         </div>
	<div class="js-pagination grid_right">
	    <?php echo $this->element('paging_links'); ?>
	</div>
	</div>
	<div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
<?php } 
echo $this->Form->end();
?>
</div>