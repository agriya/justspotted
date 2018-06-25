<?php /* SVN: $Id: $ */ ?>
<div class="places index js-response">
	<ul class="filter-list clearfix">
		<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Flagged){ echo 'class="active"';} ?>><span class="system-flagged" title="<?php echo __l('System Flagged'); ?>"><?php echo $this->Html->link( $this->Html->cInt($flagged, false) . '<span>' . __l('System Flagged') . '</span>', array('controller' => 'places', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('escape' => false)); ?></span></li>
		<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Suspend){ echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('Admin Suspended'); ?>"><?php echo $this->Html->link($this->Html->cInt($suspended, false) . '<span>' . __l('Admin Suspended') . '</span>', array('controller' => 'places', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('escape' => false)); ?></span></li>
		<li <?php if (!isset($this->request->params['named']['filter_id'])){ echo 'class="active"';} ?>><span class="total" title="<?php echo __l('All'); ?>"><?php echo $this->Html->link($this->Html->cInt($all, false) . '<span>' . __l('All') . '</span>', array('controller' => 'places', 'action' => 'index'), array('escape' => false)); ?></span></li>

	</ul>
	 <div class="page-count-block clearfix">
    <div class="grid_left">
	   <?php echo $this->element('paging_counter');?>
	</div>
	 <div class="grid_right"> <?php echo $this->Html->link(__l('Add'), array('controller' => 'places', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
        </div>
    <div class="grid_left">
    	<?php echo $this->Form->create('Place' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
    	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
    	<?php echo $this->Form->submit(__l('Search'));?>
    	<?php echo $this->Form->end(); ?>
	</div>
	</div>
	<?php echo $this->Form->create('Place' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
	<tr>
		<th class="select"><?php echo __l('Select');?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Created By'), 'user_id');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Place Type'), 'PlaceType.name');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Business'), 'Business.name');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('Place');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('latitude');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('longitude');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('place_claim_request_count');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Items'), 'item_count');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Followers'), 'place_follower_count');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Views'), 'place_view_count');?></div></th>
	</tr>
	<?php
if (!empty($places)):
	$i = 0;
	foreach ($places as $place):
		$class = '';
		$active_status = '';
		if($place['Place']['admin_suspend']) {
			$active_status = ' inactive-record'; 
		}
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
	?>
	<tr class="<?php echo $class.$active_status;?>" >
		<td class="select">
			<?php echo $this->Form->input('Place.'.$place['Place']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$place['Place']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                         <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $place['Place']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $place['Place']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
						<?php if($place['Place']['is_system_flagged']):?>
							<li><?php echo $this->Html->link(__l('Clear Flag'), array('action' => 'admin_update_status', $place['Place']['id'], 'status' => 'unflag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'clear-flag', 'title' => __l('Clear Flag')));?></li>
						<?php else:?>
							<li><?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_status', $place['Place']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'flag', 'title' => __l('Flag')));?></li>
						<?php endif;?>
						<?php if($place['Place']['admin_suspend']):?>
							<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_status', $place['Place']['id'], 'status' => 'unsuspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'unsuspend-icon', 'title' => __l('Unsuspend')));?></li>
						<?php else:?>
							<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_status', $place['Place']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($place['Place']['created']); ?>
		</td>
		<td class="dl">
			<?php
			if(!empty($place['User']['username'])){
			 echo $this->Html->link($this->Html->cText($place['User']['username'], false), array('controller' => 'users', 'action' => 'index', $place['User']['username'])); } ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($place['PlaceType']['name']); ?>
		</td>
		<td class="dl">
			 <?php if(!empty($place['Business']['slug'])){
					echo $this->Html->link($this->Html->showImage('Business', $place['Business']['Attachment'], array('dimension' => 'micro_thumb', 'alt' => sprintf(__l('[Image: %s]'), $place['Business']['name']), 'title' => $place['Business']['name'])), array('controller'=> 'businesses', 'action' => 'view', $place['Business']['slug'], 'admin' => false), array('escape' => false));
                                    ?>
			<?php echo $this->Html->link($this->Html->cText($place['Business']['name']), array('controller'=> 'businesses', 'action' => 'view', $place['Business']['slug'], 'admin' => false), array('escape' => false)); } ?>
		</td>
		<td class="dl">
			
<div class="clearfix">
                <div class="status-block grid_left">
			<?php 
				if($place['Place']['is_system_flagged']):
					echo '<span class="flagged">'.__l('System Flagged').'</span>';
				endif;
			?>
			<?php			
				if($place['Place']['admin_suspend']):
					echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
				endif;
			?>
		</div>
		<div class="grid_left">
		<?php echo $this->Html->link($this->Html->cText($place['Place']['name'], false), array('controller' => 'places', 'action' => 'view', $place['Place']['slug'], 'admin' => false)); ?>
		</div><br/>
			<address><?php echo $this->Html->cText($place['Place']['address2']); ?></address>
			 <?php echo $this->Html->cText($place['Place']['zip_code']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cText($place['Place']['latitude']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cText($place['Place']['longitude']); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($place['Place']['place_claim_request_count']), array('controller' => 'place_claim_requests', 'action' => 'index', 'place_id' => $place['Place']['id']),array('escape'=>false));?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($place['Place']['item_count']), array('controller' => 'items', 'action' => 'index', 'place_id' => $place['Place']['id']),array('escape'=>false));?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($place['Place']['place_follower_count']), array('controller' => 'place_followers', 'action' => 'index', 'place' => $place['Place']['slug']),array('escape'=>false));?>
		</td>		
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($place['Place']['place_view_count']), array('controller' => 'place_views', 'action' => 'index', 'place' => $place['Place']['slug']),array('escape'=>false));?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="18" class="notice"><?php echo __l('No Places available');?></td>
	</tr>
<?php
endif;
?>
</table>
<?php if (!empty($places)) { ?>
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
