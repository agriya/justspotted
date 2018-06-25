<?php /* SVN: $Id: $ */ ?>
<div class="reviewComments index js-response">
    <?php if(empty($this->params['named']['simple_view'])) {?>
	<ul class="filter-list clearfix">
		<li  <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Flagged){ echo 'class="active"';} ?>><span class="system-flagged" title="<?php echo __l('System Flagged'); ?>"><?php echo $this->Html->link( $this->Html->cInt($flagged, false) . '<span>' . __l('System Flagged') . '</span>', array('controller' => 'review_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('escape' => false)); ?></span></li>
		<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Suspend){ echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('Admin Suspended'); ?>"><?php echo $this->Html->link($this->Html->cInt($suspended, false) . '<span>' . __l('Admin Suspended') . '</span>', array('controller' => 'review_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('escape' => false)); ?></span></li>
		<li <?php if (!isset($this->request->params['named']['filter_id'])){ echo 'class="active"';} ?>><span class="total" title="<?php echo __l('All'); ?>"><?php echo $this->Html->link($this->Html->cInt($all, false) . '<span>' . __l('All') . '</span>', array('controller' => 'review_comments', 'action' => 'index'), array('escape' => false)); ?></span></li>

	</ul>
	<div class="page-count-block clearfix">
    	<div class="grid_left">
    	   <?php echo $this->element('paging_counter');?>
    	</div>
    	<div class="grid_left">
        	<?php echo $this->Form->create('ReviewComment' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
        	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>

        </div>
    </div>
    <?php echo $this->Form->create('ReviewComment' , array('class' => 'normal','action' => 'update')); ?>
        <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <?php } ?>
<div class="overflow-block">
<table class="list">
	<tr>
        <?php if(empty($this->params['named']['simple_view'])) {?>
    		<th class="select"></th>
        <?php } ?>
		<th class="actions"><?php echo __l('Actions');?></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
		<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('review_id');?></div></th>
		<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Sighting'), 'Review.sighting_id');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('comment');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'ip_id');?></div></th>
	</tr>
	<?php
if (!empty($reviewComments)):
	$i = 0;
	foreach ($reviewComments as $reviewComment):
		$class = '';
		$active_status = '';
		if($reviewComment['ReviewComment']['admin_suspend']) {
			$active_status = ' inactive-record'; 
		}
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}

		
	?>
	<tr class="<?php echo $class.$active_status;?>">
        <?php if(empty($this->params['named']['simple_view'])) {?>
		  <td class="select">
			 <?php echo $this->Form->input('ReviewComment.'.$reviewComment['ReviewComment']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$reviewComment['ReviewComment']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
		  </td>
        <?php } ?>
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
                                  <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $reviewComment['ReviewComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
								<?php if($reviewComment['ReviewComment']['is_system_flagged']):?>
									<li><?php echo $this->Html->link(__l('Clear Flag'), array('action' => 'admin_update_status', $reviewComment['ReviewComment']['id'], 'status' => 'unflag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'clear-flag', 'title' => __l('Clear Flag')));?></li>
								<?php else:?>
									<li><?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_status', $reviewComment['ReviewComment']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'flag', 'title' => __l('Flag')));?></li>
								<?php endif;?>
								<?php if($reviewComment['ReviewComment']['admin_suspend']):?>
									<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_status', $reviewComment['ReviewComment']['id'], 'status' => 'unsuspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'unsuspend-icon', 'title' => __l('Unsuspend')));?></li>
								<?php else:?>
									<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_status', $reviewComment['ReviewComment']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($reviewComment['ReviewComment']['created']); ?>
		</td>
		<td class="dl">
	       	<div class="status-block">
            	<?php
    				if($reviewComment['ReviewComment']['is_system_flagged']):
    					echo '<span class="flagged round-5">'.__l('System Flagged').'</span>';
    				endif;
    			?>
    			<?php
    				if($reviewComment['ReviewComment']['admin_suspend']):
    					echo '<span class="suspended round-5">'.__l('Admin Suspended').'</span>';
    				endif;
    			?>
			</div>
			<?php echo $this->Html->link($this->Html->cText($this->Html->truncate($reviewComment['Review']['notes']), false), array('controller' => 'reviews', 'action' => 'view', $reviewComment['Review']['id'], 'admin' => false)); ?>
            
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($reviewComment['Review']['Sighting']['Item']['name'], false) .' @ '. $this->Html->cText($reviewComment['Review']['Sighting']['Place']['name'], false), array('controller' => 'sightings', 'action' => 'view', $reviewComment['Review']['Sighting']['id'], 'admin' => false)); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($reviewComment['User']['username'], false), array('controller' => 'users', 'action' => 'view', $reviewComment['User']['username'], 'admin' => false)); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($this->Html->truncate($reviewComment['ReviewComment']['comment'])); ?>
		</td>
		<td class="dl">
		<?php if(!empty($reviewComment['Ip']['ip'])): ?>
                            <?php echo  $this->Html->cText($reviewComment['Ip']['ip']);
							?>
							<p>
							<?php
                            if(!empty($reviewComment['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($reviewComment['Ip']['Country']['iso2']); ?>" title ="<?php echo $reviewComment['Ip']['Country']['name']; ?>">
									<?php echo $reviewComment['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($reviewComment['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $reviewComment['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Review Comments available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($reviewComments)) { ?>
    <div class="clearfix">
    <?php if(empty($this->params['named']['simple_view'])) {?>
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
    <?php } ?>
	<div class="js-pagination grid_right">
	    <?php echo $this->element('paging_links'); ?>
	</div>
	</div>
	<?php if(empty($this->params['named']['simple_view'])) {?>
	<div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
    <?php } ?>
<?php }
if(empty($this->params['named']['simple_view'])) {
    echo $this->Form->end();
}
?>
</div>