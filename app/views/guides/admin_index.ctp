<?php /* SVN: $Id: $ */ ?>
<div class="guides index js-response">
		<ul class="filter-list clearfix">
			<!--<li><span class="active round-5"><?php echo $this->Html->link($this->Html->cInt($published, false), array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Published), array('title' => __l('Published'))); ?></span><span class="active round-5"><?php echo __l('Published'); ?></span></li>
			<li><span class="Unpublished round-5"><?php echo $this->Html->link($this->Html->cInt($unpublished, false), array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Unpublished), array('title' => __l('UnPublished'))); ?></span><span class="Unpublished round-5"><?php echo __l('UnPublished'); ?></span></li>
			<li><span class="featured round-5"><?php echo $this->Html->link($this->Html->cInt($featured, false), array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Featured), array('title' => __l('Featured'))); ?></span><span class="featured round-5"><?php echo __l('Featured'); ?></span></li>
			<li><span class="not-featured round-5"><?php echo $this->Html->link($this->Html->cInt($notfeatured, false), array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Notfeatured), array('title' => __l('Not Featured'))); ?></span><span class="not-featured round-5"><?php echo __l('Not Featured'); ?></span></li>
			<li><span class="system-flagged round-5"><?php echo $this->Html->link($this->Html->cInt($flagged, false), array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged'))); ?></span><span class="system-flagged round-5"><?php echo __l('System Flagged'); ?></span></li>
			<li><span class="inactive round-5"><?php echo $this->Html->link($this->Html->cInt($suspended, false), array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended'))); ?></span><span class="inactive round-5"><?php echo __l('Admin Flagged'); ?></span></li>-->

			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Published){ echo 'class="active"';} ?>><span class="active" title="<?php echo __l('Published'); ?>"><?php echo $this->Html->link( $this->Html->cInt($published, false) . '<span>' . __l('Published') . '</span>', array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Published), array('escape' => false)); ?></span></li>
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Unpublished){ echo 'class="active"';} ?>><span class="Unpublished" title="<?php echo __l('Unpublished'); ?>"><?php echo $this->Html->link( $this->Html->cInt($unpublished, false) . '<span>' . __l('Unpublished') . '</span>', array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Unpublished), array('escape' => false)); ?></span></li>			
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Featured){ echo 'class="active"';} ?>><span class="featured" title="<?php echo __l('Featured'); ?>"><?php echo $this->Html->link( $this->Html->cInt($featured, false) . '<span>' . __l('Featured') . '</span>', array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Featured), array('escape' => false)); ?></span></li>			
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Notfeatured){ echo 'class="active"';} ?>><span class="not-featured" title="<?php echo __l('Notfeatured'); ?>"><?php echo $this->Html->link( $this->Html->cInt($notfeatured, false) . '<span>' . __l('Notfeatured') . '</span>', array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Notfeatured), array('escape' => false)); ?></span></li>
			
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Flagged){ echo 'class="active"';} ?>><span class="system-flagged" title="<?php echo __l('System Flagged'); ?>"><?php echo $this->Html->link( $this->Html->cInt($flagged, false) . '<span>' . __l('System Flagged') . '</span>', array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('escape' => false)); ?></span></li>
			
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Suspend){ echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('Admin Suspended'); ?>"><?php echo $this->Html->link( $this->Html->cInt($suspended, false) . '<span>' . __l('Admin Suspended') . '</span>', array('controller' => 'guides', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('escape' => false)); ?></span></li>

			<li <?php if (!isset($this->request->params['named']['main_filter_id']) && !isset($this->request->params['named']['filter_id'])) { echo 'class="active"';} ?>><span class="total" title="<?php echo __l('Total Guides'); ?>"><?php echo $this->Html->link( $this->Html->cInt($published + $unpublished, false) . '<span>' . __l('Total') . '</span>', array('controller' => 'guides', 'action' => 'index'), array('escape' => false)); ?></span></li>
            
	   </ul>
	<div class="page-count-block clearfix">
    	<div class="grid_left">
    	   <?php echo $this->element('paging_counter');?>
    	</div>
		<div class="grid_right"> <?php echo $this->Html->link(__l('Add'), array('controller' => 'guides', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
        </div>
    	<div class="grid_left">
        	<?php echo $this->Form->create('Guide' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
    		<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
    		<?php echo $this->Form->submit(__l('Search'));?>

    	<?php echo $this->Form->end(); ?>
    	</div>
	</div>

	<?php echo $this->Form->create('Guide' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>

<div class="overflow-block">
<table class="list" id="js-expand-table">
	       <tr class="js-even">
	           	<th class="select"><?php echo __l('Select');?></th>
	       		<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Created by'), 'User.username');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Followers'), 'guide_follower_count');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Sightings'), 'sighting_count');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Views'), 'guide_view_count');?></div></th>
			</tr>
	<?php
if (!empty($guides)):
	$i = 0;
	foreach ($guides as $guide):
		$class = '';
		$active_status = '';
		if($guide['Guide']['admin_suspend']) {
			$active_status = ' inactive-record'; 
		}
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
		if($guide['Guide']['is_featured']):
			$status_class = 'js-checkbox-featured';
		else:

			$status_class = 'js-checkbox-notfeatured';
		endif;
		if($guide['Guide']['is_published']):
			$status_class .= ' js-checkbox-active';
		else:
			$status_class .= ' js-checkbox-inactive';
		endif;
	?>
	<tr class="js-odd expand-row <?php echo $class.$active_status;?>">
		<td class="select">
		<div class="arrow"></div>
		<?php echo $this->Form->input('Guide.'.$guide['Guide']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$guide['Guide']['id'], 'label' => false, 'class' => $status_class . ' js-checkbox-list')); ?>
  </td>
	<td class="dc">
			<?php echo $this->Html->cDateTimeHighlight($guide['Guide']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($guide['User']['username']); ?>
		</td>
		<td class="dl">
		  <div class="status-block">
			<?php
				if($guide['Guide']['is_published'] == 0):
					echo '<span class="unpublished round-5">'.__l('Unpublished').'</span>';
				endif;
			?>
			<?php
				if($guide['Guide']['is_featured'] == 1):
					echo '<span class="featured round-5">'.__l('Featured').'</span>';
				endif;
			?>
			<?php
				if($guide['Guide']['is_system_flagged']):
					echo '<span class="flagged round-5">'.__l('System Flagged').'</span>';
				endif;
			?>
			<?php
				if($guide['Guide']['admin_suspend']):
					echo '<span class="suspended round-5">'.__l('Admin Suspended').'</span>';
				endif;
			?>
				</div>
				<?php echo $this->Html->cText($guide['Guide']['name']); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->cInt($guide['Guide']['guide_follower_count']); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->cInt($guide['Guide']['sighting_count']); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->cInt($guide['Guide']['guide_view_count']); ?>
		</td>
	</tr>
	<tr class="hide">
        <td colspan="9" class="action-block">
        <div class="action-info-block clearfix">
        <div class="action-left-block">
          <h3><?php echo __l('Actions');?> </h3>
          <ul>
    			<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $guide['Guide']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
    			<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $guide['Guide']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
    			<?php if($guide['Guide']['is_system_flagged']):?>
    				<li><?php echo $this->Html->link(__l('Clear Flag'), array('action' => 'admin_update_status', $guide['Guide']['id'], 'status' => 'unflag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'clear-flag', 'title' => __l('Clear Flag')));?></li>
    			<?php else:?>
    				<li><?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_status', $guide['Guide']['id'], 'status' => 'flag', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'flag', 'title' => __l('Flag')));?></li>
    			<?php endif;?>
    			<?php if($guide['Guide']['admin_suspend']):?>
    				<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_status', $guide['Guide']['id'], 'status' => 'unsuspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'unsuspend-icon', 'title' => __l('Unsuspend')));?></li>
    			<?php else:?>
    				<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_status', $guide['Guide']['id'], 'status' => 'suspend', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')), array('class' => 'suspend-icon', 'title' => __l('Suspend')));?></li>
    			<?php endif;?>
    	     </ul>
        </div>
          <div class="clearfix action-right-block">
          <div class="clearfix">
            <div class="action-right action-right1">
              <h3><?php echo __l('Guides'); ?></h3>
                  <dl class="clearfix">
                        <?php if(!empty($guide['GuideCategory']['name'])): ?>
        				<dt><?php echo __l('Guide Category'); ?></dt>
                        <dd>
                          <?php echo $this->Html->cText($guide['GuideCategory']['name']); ?>
                        </dd>
        				<?php endif; ?>
                        <dt><?php echo __l('Anyone Add Additional Sightings To This Guide?'); ?></dt>
                        <dd>
                          <?php echo $this->Html->cBool($guide['Guide']['is_anyone_add_additional_sightings_to_this_guide']); ?>
                        </dd>
                        <dt><?php echo __l('No of maximum sightings'); ?></dt>
                        <dd>
                          <?php echo $this->Html->cInt($guide['Guide']['no_of_max_sightings']); ?>
                        </dd>
                        <dt><?php echo __l('City'); ?></dt>
                        <dd>
                            <?php echo $this->Html->cText($guide['City']['name']); ?>
                        </dd>
                        <dt><?php echo __l('Tagline'); ?></dt>
                        <dd> <?php echo $this->Html->cText($guide['Guide']['tagline']); ?></dd>
                  </dl>
                   <div class="guide-description">
                        <h3><?php echo __l('Description'); ?></h3>
                        <div class="description-block">
                           <?php echo $this->Html->cText($guide['Guide']['description']); ?>
                        </div>
                    </div>
             </div>
            <div class="action-right action-right2">
                    <?php echo $this->Html->link($this->Html->showImage('Guide', (!empty($guide['Attachment']) ? $guide['Attachment'] : ''), array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($guide['Guide']['name'], false)), 'title' => $this->Html->cText($guide['Guide']['name'], false))), array('controller' => 'guides', 'action' => 'view', $guide['Guide']['slug'], 'admin' => false), array('title'=>$this->Html->cText($guide['Guide']['name'],false),'escape' => false));?>
            </div>
            </div>
           
          </div>
          </div>
          </td>
      </tr>	
<?php
    endforeach;
else:
?>
	<tr class="js-even">
		<td colspan="19" class="notice"><?php echo __l('No Guides available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($guides)) { ?>
    <div class="clearfix">
	<div class="admin-select-block grid_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
				<?php echo $this->Html->link(__l('Not featured'), '#', array('class' => 'js-admin-select-notfeatured', 'title' => __l('Inactive'))); ?>
				<?php echo $this->Html->link(__l('Featured'), '#', array('class' => 'js-admin-select-featured', 'title' => __l('Active'))); ?>
				<?php echo $this->Html->link(__l('Unpublished'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Inactive'))); ?>
				<?php echo $this->Html->link(__l('Published'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Active'))); ?>
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
