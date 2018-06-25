<?php /* SVN: $Id: $ */ ?>
<div class="sightings index js-response">
	<ul class="filter-list clearfix">
		<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Flagged){ echo 'class="active"';} ?>><span class="system-flagged" title="<?php echo __l('System Flagged'); ?>"><?php echo $this->Html->link( $this->Html->cInt($flagged, false) . '<span>' . __l('System Flagged') . '</span>', array('controller' => 'sightings', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('escape' => false)); ?></span></li>
         <li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::UserFlagged){ echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('User Flagged'); ?>"><?php echo $this->Html->link($this->Html->cInt($userflagged, false) . '<span>' . __l('User Flagged') . '</span>', array('controller' => 'sightings', 'action' => 'index', 'filter_id' => ConstMoreAction::UserFlagged), array('escape' => false)); ?></span></li>
		<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Suspend){ echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('Admin Suspended'); ?>"><?php echo $this->Html->link($this->Html->cInt($suspended, false) . '<span>' . __l('Admin Suspended') . '</span>', array('controller' => 'sightings', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('escape' => false)); ?></span></li>
		<li <?php if (!isset($this->request->params['named']['filter_id'])){ echo 'class="active"';} ?>><span class="total" title="<?php echo __l('All'); ?>"><?php echo $this->Html->link($this->Html->cInt($all, false) . '<span>' . __l('All') . '</span>', array('controller' => 'sightings', 'action' => 'index'), array('escape' => false)); ?></span></li>
	</ul>
	<div class="clearfix page-count-block">
	    <div class="grid_left">
            <?php echo $this->element('paging_counter');?>
        </div>
        <div class="grid_left">
        	<?php echo $this->Form->create('Sighting' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
        	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>
    	</div>
    </div>
     <?php echo $this->Form->create('Sighting' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
	<tr>
		<th rowspan="2" class="select"></th>
		<th rowspan="2" class="actions"><?php echo __l('Actions');?></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Sighting'), 'Sighting.id');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Item'), 'Item.name');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Place'), 'Place.name');?></div></th>
		<?php if(!empty($sightingRatingTypes)){?>
		<th colspan="<?php echo $sightingRatingTypes_count;?>"><?php echo __l('Ratings');?></th>
		<?php } ?>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Reviews'), 'review_count');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Views'), 'sighting_view_count');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Flags'), 'sighting_flag_count');?></div></th>
	</tr>
	<?php if(!empty($sightingRatingTypes)){?>
   <tr>
        <?php foreach($sightingRatingTypes as $sightingRatingType){?>
            <th><?php echo $sightingRatingType;?></th>
        <?php } ?>
   </tr>
   <?php } ?>
	<?php 
if (!empty($sightings)):
	$i = 0;
	foreach ($sightings as $sighting):
		$class = '';
		$active_status = '';
		if($sighting['Sighting']['admin_suspend']) {
			$active_status = ' inactive-record'; 
		}
		if ($i++ % 2 == 0) {
			$class = ' altrow';
		}
	?>
	<tr class="<?php echo $class.$active_status;?>">
		<td class="select">
			<?php echo $this->Form->input('Sighting.'.$sighting['Sighting']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$sighting['Sighting']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                           <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $sighting['Sighting']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
            				<?php if($sighting['Sighting']['is_system_flagged']):?>
            					<li><?php echo $this->Html->link(__l('Clear Flag'), array('action' => 'admin_update_status', $sighting['Sighting']['id'], 'status' => 'unflag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'clear-flag', 'title' => __l('Clear Flag')));?></li>
            				<?php else:?>
            					<li><?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_status', $sighting['Sighting']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'flag', 'title' => __l('Flag')));?></li>
            				<?php endif;?>
            				<?php if($sighting['Sighting']['admin_suspend']):?>
            					<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_status', $sighting['Sighting']['id'], 'status' => 'unsuspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'unsuspend-icon', 'title' => __l('Unsuspend')));?></li>
            				<?php else:?>
            					<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_status', $sighting['Sighting']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($sighting['Sighting']['created']); ?>
		</td>
		<td class="dl">
			<div class="clearfix">
				<div class="grid_left">
					<?php echo $this->Html->link($this->Html->showImage('Review', $sighting['Review'][0]['Attachment'], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $sighting['Item']['name']), 'title' => $sighting['Item']['name'])).' '.$this->Html->cText($sighting['Item']['name'], false).' @ '. $this->Html->cText($sighting['Place']['name'], false), array('controller' => 'sightings', 'action' => 'view', 'admin' => false,  $sighting['Sighting']['id']), array('escape' => false)); ?>
				 </div>
                 <div class="status-block grid_left">
        			<?php
        				if($sighting['Sighting']['is_system_flagged']):
        					echo '<span class="flagged round-5">'.__l('System Flagged').'</span>';
        				endif;
        			?>
        			<?php
        				if($sighting['Sighting']['admin_suspend']) :
        					echo '<span class="suspended round-5">'.__l('Admin Suspended').'</span>';
        				endif;
        			?>
        			<?php
        				if($sighting['Sighting']['sighting_flag_count'] > 0) :
        					echo '<span class="user-flagged round-5">'.__l('User Flagged').'</span>';
        				endif;
        			?>
    			</div>
			</div>
		</td>
		<td class="dl">
    				<?php echo $this->Html->cText($sighting['Item']['name']); ?>
        </td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($sighting['Place']['name'], false), array('controller' => 'places', 'action' => 'view', $sighting['Place']['slug'], 'admin' => false)); ?>, 
			<address><?php echo $this->Html->cText($this->Html->cText($sighting['Place']['address2']));?></address>
		</td>
			<?php if(!empty($sightingRatingTypes)){?>
        <?php foreach($sightingRatingTypes as $key=>$sightingRatingType){
        $count=0;
			if(isset($sighting['SightingRatingStat'])) {
            foreach($sighting['SightingRatingStat'] as $sighting_rating_stats){
                if(!empty($sighting_rating_stats)){
                    $count=0;
                    if(($sighting_rating_stats['sighting_id']==$sighting['Sighting']['id']) && ($sighting_rating_stats['sighting_rating_type_id']==$key)){
                       $count=$sighting_rating_stats['count'];
					   $id = $sighting_rating_stats['sighting_rating_type_id'];
                    }
                }
            }
			} ?>
             <td class="dr"><?php echo $this->Html->link($this->Html->cInt($count), array('controller' => 'sighting_ratings', 'action' => 'index', 'sighting_id' => $sighting['Sighting']['id'],'sighting_rating_type_id'=>$key),array('escape'=>false)); ?></td>
        <?php } ?>
   <?php } ?>
   		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($sighting['Sighting']['review_count']), array('controller' => 'reviews', 'action' => 'index', 'sighting' => $sighting['Sighting']['id']),array('escape'=>false)); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($sighting['Sighting']['sighting_view_count']), array('controller' => 'sighting_views', 'action' => 'index', 'sighting' => $sighting['Sighting']['id']),array('escape'=>false)); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($sighting['Sighting']['sighting_flag_count']), array('controller' => 'sighting_flags', 'action' => 'index', 'sighting' => $sighting['Sighting']['id']),array('escape'=>false)); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Sightings available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($sightings)) { ?>
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
