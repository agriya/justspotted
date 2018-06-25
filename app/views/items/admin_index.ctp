<?php /* SVN: $Id: $ */ ?>
<div class="items index js-response">
	<ul class="filter-list clearfix">
		<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Flagged){ echo 'class="active"';} ?>><span class="system-flagged" title="<?php echo __l('System Flagged'); ?>"><?php echo $this->Html->link( $this->Html->cInt($flagged, false) . '<span>' . __l('System Flagged') . '</span>', array('controller' => 'items', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('escape' => false)); ?></span></li>
		<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Suspend){ echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('Admin Suspended'); ?>"><?php echo $this->Html->link($this->Html->cInt($suspended, false) . '<span>' . __l('Admin Suspended') . '</span>', array('controller' => 'items', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('escape' => false)); ?></span></li>
		<li <?php if (!isset($this->request->params['named']['filter_id'])){ echo 'class="active"';} ?>><span class="total" title="<?php echo __l('All'); ?>"><?php echo $this->Html->link($this->Html->cInt($all, false) . '<span>' . __l('All') . '</span>', array('controller' => 'items', 'action' => 'index'), array('escape' => false)); ?></span></li>
	</ul>
	<div class="page-count-block clearfix">
        <div class="grid_left">
        	<?php echo $this->element('paging_counter');?>
    	</div>
		 <div class="grid_right"> <?php echo $this->Html->link(__l('Add'), array('controller' => 'items', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
        </div>
    	<div class="grid_left">
        	<?php echo $this->Form->create('Item' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
        	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>
    	</div>
	</div>
	<?php echo $this->Form->create('Item' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>

	<div class="overflow-block">
    <table class="list">
	<tr>
		<th class="select"></th>
		<th class="actions"><?php echo __l('Actions');?></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'user_id');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Places'), 'place_count');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Followers'), 'item_follower_count');?></div></th>
	</tr>
	<?php
if (!empty($items)):
	$i = 0;
	foreach ($items as $item):
		$class = '';
		$active_status = '';
		if($item['Item']['admin_suspend']) {
			$active_status = ' inactive-record'; 
		}
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
	?>
	<tr class="<?php echo $class.$active_status;?>">
		<td class="select">
			<?php echo $this->Form->input('Item.'.$item['Item']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$item['Item']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                 	<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $item['Item']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                     <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $item['Item']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
						<?php if($item['Item']['is_system_flagged']):?>
							<li><?php echo $this->Html->link(__l('Clear Flag'), array('action' => 'admin_update_status', $item['Item']['id'], 'status' => 'unflag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'clear-flag', 'title' => __l('Clear Flag')));?></li>
						<?php else:?>
							<li><?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_status', $item['Item']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'flag', 'title' => __l('Flag')));?></li>
						<?php endif;?>
						<?php if($item['Item']['admin_suspend']):?>
							<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_status', $item['Item']['id'], 'status' => 'unsuspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'unsuspend-icon', 'title' => __l('Unsuspend')));?></li>
						<?php else:?>
							<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_status', $item['Item']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?></li>
						<?php endif;?>
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
			<?php echo $this->Html->cDateTimeHighlight($item['Item']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($item['User']['username'], false), array('controller' => 'users', 'action' => 'index', $item['User']['username']));?>
		</td>
		<td class="dl">
            <div class="status-block">
			<?php 
				if($item['Item']['is_system_flagged']):
					echo '<span class="flagged round-5">'.__l('System Flagged').'</span>';
				endif;
			?>
			<?php			
				if($item['Item']['admin_suspend']) :
					echo '<span class="suspended round-5">'.__l('Admin Suspended').'</span>';
				endif;
			?>
			</div>
			<?php echo $this->Html->cText($item['Item']['name']); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($item['Item']['place_count']), array('controller' => 'places', 'action' => 'index', 'item_id' => $item['Item']['id']), array('escape' => false));?>
		</td>		
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($item['Item']['item_follower_count']), array('controller' => 'item_followers', 'action' => 'index', 'item' => $item['Item']['slug']), array('escape' => false));?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Items available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($items)) { ?>
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
<?php
echo $this->Form->end();
}
?>
</div>
