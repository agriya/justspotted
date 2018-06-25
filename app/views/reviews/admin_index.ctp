<?php /* SVN: $Id: $ */ ?>
<div class="reviews index js-response">
		<ul class="filter-list clearfix">	
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Flagged){ echo 'class="active"';} ?>><span class="system-flagged" title="<?php echo __l('System Flagged'); ?>"><?php echo $this->Html->link( $this->Html->cInt($flagged, false) . '<span>' . __l('System Flagged') . '</span>', array('controller' => 'reviews', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('escape' => false)); ?></span></li>
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Suspend){ echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('Admin Suspended'); ?>"><?php echo $this->Html->link($this->Html->cInt($suspended, false) . '<span>' . __l('Admin Suspended') . '</span>', array('controller' => 'reviews', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('escape' => false)); ?></span></li>
			<li <?php if (!isset($this->request->params['named']['filter_id'])){ echo 'class="active"';} ?>><span class="total" title="<?php echo __l('All'); ?>"><?php echo $this->Html->link($this->Html->cInt($all, false) . '<span>' . __l('All') . '</span>', array('controller' => 'reviews', 'action' => 'index'), array('escape' => false)); ?></span></li>
		</ul>
	<div class="page-count-block clearfix">
    	<div class="grid_left">
    	   <?php echo $this->element('paging_counter');?>
    	</div>
        
    	<div class="grid_left">
        	<?php echo $this->Form->create('Review' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
        	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>
    	</div>
    </div>
<?php echo $this->Form->create('Review' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));?>

<div class="overflow-block">
<table class="list">
	<tr>
		<th rowspan="2" class="select"></th>
		<th rowspan="2" class="actions"><?php echo __l('Actions');?></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Category'), 'ReviewCategory.name');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort('sighting_id');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Review'),'notes');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Review By'), 'User.username');?></div></th>
		<?php if(!empty($reviewRatingTypes)){?>
		<th colspan="<?php echo $reviewRatingTypes_count;?>"><?php echo __l('Ratings');?></th>
		<?php } ?>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Views'), 'review_view_count');?></div></th>
   </tr>
   <?php if(!empty($reviewRatingTypes)){?>
   <tr>
        <?php foreach($reviewRatingTypes as $reviewRatingType){?>
            <th><?php echo $reviewRatingType;?></th>
        <?php } ?>
   </tr>
   <?php } ?>
	<?php
if (!empty($reviews)):
	$i = 0;
	foreach ($reviews as $review):
		$class = '';
		$active_status ='';
		if($review['Review']['admin_suspend']) {
			$active_status = ' inactive-record'; 
		}
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
	?>
	<tr class="<?php echo $class.$active_status;?>">
		<td class="select">
			<?php echo $this->Form->input('Review.'.$review['Review']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$review['Review']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
						<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $review['Review']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
						<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $review['Review']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
						<?php if($review['Review']['is_system_flagged']):?>
							<li><?php echo $this->Html->link(__l('Clear Flag'), array('action' => 'admin_update_status', $review['Review']['id'], 'status' => 'unflag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'clear-flag', 'title' => __l('Clear Flag')));?></li>
						<?php else:?>
							<li><?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_status', $review['Review']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'flag', 'title' => __l('Flag')));?></li>
						<?php endif;?>
						<?php if($review['Review']['admin_suspend']):?>
							<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_status', $review['Review']['id'], 'status' => 'unsuspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'unsuspend-icon', 'title' => __l('Unsuspend')));?></li>
						<?php else:?>
							<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_status', $review['Review']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($review['Review']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($review['ReviewCategory']['name']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($review['Sighting']['Item']['name'], false) .' @ '. $this->Html->cText($review['Sighting']['Place']['name'], false), array('controller' => 'sightings', 'action' => 'view', $review['Sighting']['id'], 'admin' => false)); ?>
		</td>
		<td class="dl">
		  <div class="status-block">
			<?php 
				if($review['Review']['is_system_flagged']):
					echo '<span class="flagged round-5">'.__l('System Flagged').'</span>';
				endif;
			?>
			<?php			
				if($review['Review']['admin_suspend']):
					echo '<span class="suspended round-5">'.__l('Admin Suspended').'</span>';
				endif;
			?>
            </div>
            <?php echo $this->Html->link($this->Html->showImage('Review', (!empty($review['Attachment']) ? $review['Attachment'] : ''), array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($review['Review']['notes'], false)), 'title' => $this->Html->cText($review['Review']['notes'], false))), array('controller' => 'reviews', 'action' => 'view', $review['Review']['id'], 'admin' => false), array('title'=>$this->Html->cText($review['Review']['notes'],false),'escape' => false));?>
			<?php echo $this->Html->cText($this->Html->truncate($review['Review']['notes'])); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($review['User']['username'], false), array('controller' => 'users', 'action' => 'view', $review['User']['username'], 'admin' => false)); ?>
		</td>
		<?php if(!empty($reviewRatingTypes)){?>
        <?php foreach($reviewRatingTypes as $key=>$reviewRatingType){
        $count=0;
            foreach($review['ReviewRatingStat'] as $review_rating_stats){
                if(!empty($review_rating_stats)){
                    $count=0;
                    if(($review_rating_stats['review_id']==$review['Review']['id']) && ($review_rating_stats['review_rating_type_id']==$key)){
                        $count=$review_rating_stats['count'];
                    }
                }
            }?>
			 <td class="dr"><?php echo $this->Html->link($this->Html->cInt($count), array('controller' => 'review_ratings', 'action' => 'index', 'review_id' => $review['Review']['id'],'review_rating_type_id'=>$key),array('escape'=>false)); ?></td>
        <?php } ?>
   <?php } ?>
   <td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($review['Review']['review_view_count']), array('controller' => 'review_views', 'action' => 'index', 'review' => $review['Review']['id']),array('escape'=>false)); ?>
	</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="9" class="notice"><?php echo __l('No Reviews available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($reviews)) { ?>
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
