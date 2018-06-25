<?php /* SVN: $Id: $ */ ?>
<div class="placeClaimRequests index js-response">
    <ul class="filter-list clearfix">
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstPlaceClaimRequests::Approved){ echo 'class="active"';} ?>><span class="active" title="<?php echo __l('Approved'); ?>"><?php echo $this->Html->link( $this->Html->cInt($approved, false) . '<span>' . __l('Approved') . '</span>', array('controller' => 'place_claim_requests', 'action' => 'index', 'filter_id' => ConstPlaceClaimRequests::Approved), array('escape' => false)); ?></span></li>
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstPlaceClaimRequests::Pending){ echo 'class="active"';} ?>><span class="Unpublished" title="<?php echo __l('Pending'); ?>"><?php echo $this->Html->link( $this->Html->cInt($pending, false) . '<span>' . __l('Pending') . '</span>', array('controller' => 'place_claim_requests', 'action' => 'index', 'filter_id' => ConstPlaceClaimRequests::Pending), array('escape' => false)); ?></span></li>
			<li <?php if (isset($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstPlaceClaimRequests::Rejected){ echo 'class="active"';} ?>><span class="featured" title="<?php echo __l('Rejected'); ?>"><?php echo $this->Html->link( $this->Html->cInt($rejected, false) . '<span>' . __l('Rejected') . '</span>', array('controller' => 'place_claim_requests', 'action' => 'index', 'filter_id' => ConstPlaceClaimRequests::Rejected), array('escape' => false)); ?></span></li>
            <li <?php if (!isset($this->request->params['named']['filter_id'])) { echo 'class="active"';} ?>><span class="total" title="<?php echo __l('All'); ?>"><?php echo $this->Html->link( $this->Html->cInt($all, false) . '<span>' . __l('All') . '</span>', array('action' => 'index'), array('escape' => false)); ?></span></li>
    </ul>
	<div class="page-count-block clearfix">
    	<div class="grid_left">
    	   <?php echo $this->element('paging_counter');?>
    	</div>
    	<div class="grid_left">
        	<?php echo $this->Form->create('PlaceClaimRequest' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
    		<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
    		<?php echo $this->Form->submit(__l('Search'));?>

    	<?php echo $this->Form->end(); ?>
    	</div>
	</div>

	<?php echo $this->Form->create('PlaceClaimRequest' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
	<tr>
		<th rowspan="2" class="select"></th>
		<th rowspan="2" class="actions"><?php echo __l('Actions');?></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort('business_id');?></div></th>
		<th colspan="3"><?php echo __l('Place'); ?></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Approved?'), 'is_approved');?></div></th>
	</tr>
	<tr>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('place_id');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Total Claim Requests'), 'Place.place_claim_request_count');?></div></th>
		<th><?php echo __l('Currently Hold By'); ?></th>
	</tr>
	<?php
if (!empty($placeClaimRequests)):
	$i = 0;
	foreach ($placeClaimRequests as $placeClaimRequest):
		$class = null;
		$active_class = '';
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
		if($placeClaimRequest['PlaceClaimRequest']['is_approved']==ConstPlaceClaimRequests::Approved){
            $status_class = 'js-checkbox-active';
        }
        if($placeClaimRequest['PlaceClaimRequest']['is_approved']==ConstPlaceClaimRequests::Rejected){
            $status_class = 'js-checkbox-suspended';
			$active_class = ' inactive-record';
        }
        if($placeClaimRequest['PlaceClaimRequest']['is_approved']==ConstPlaceClaimRequests::Pending){
            $status_class = 'js-checkbox-inactive';
        }
	?>
	<tr class="<?php echo $class.$active_class;?>">
		<td class="actions"><?php echo $this->Form->input('PlaceClaimRequest.'.$placeClaimRequest['PlaceClaimRequest']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$placeClaimRequest['PlaceClaimRequest']['id'], 'label' => false, 'class' => $status_class . ' js-checkbox-list')); ?></td>
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
                              <?php if($placeClaimRequest['PlaceClaimRequest']['is_approved'] == ConstPlaceClaimRequests::Pending || $placeClaimRequest['PlaceClaimRequest']['is_approved'] == ConstPlaceClaimRequests::Rejected){ ?>
                              <li> <?php echo $this->Html->link(__l('Approve'), array('action'=>'admin_update_status', $placeClaimRequest['PlaceClaimRequest']['id'], 'status' => 'approve', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')),array('class'=>'approve','title' => __l('Approve'))); ?></li>
                              <?php }?>
                              <?php if($placeClaimRequest['PlaceClaimRequest']['is_approved'] == ConstPlaceClaimRequests::Pending || $placeClaimRequest['PlaceClaimRequest']['is_approved'] == ConstPlaceClaimRequests::Approved){ ?>
                              <li> <?php echo $this->Html->link(__l('Disapprove'), array('action'=>'admin_update_status', $placeClaimRequest['PlaceClaimRequest']['id'], 'status' => 'disapprove', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')),array('class'=>'pending','title' => __l('Disapprove'))); ?></li>
                              <?php }?>                     
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
		<td class="select">
			<?php echo $this->Html->cDateTimeHighlight($placeClaimRequest['PlaceClaimRequest']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($placeClaimRequest['Business']['name'], false), array('controller' => 'businesses', 'action' => 'view', $placeClaimRequest['Business']['slug'], 'admin' => false)); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($placeClaimRequest['Place']['name'], false), array('controller' => 'places', 'action' => 'view', $placeClaimRequest['Place']['slug'], 'admin' => false)); ?>
		<address><?php 	echo $placeClaimRequest['Place']['address2']; ?></address>
		</td>
		<td class="dr">
			<?php echo $this->Html->cInt($placeClaimRequest['Business']['place_claim_request_count']); ?>
		</td>
		<td class="dl">
			<?php 
				if(!empty($placeClaimRequest['Place']['Business'])) {
					echo $this->Html->link($this->Html->cText($placeClaimRequest['Place']['Business']['name'], false), array('controller' => 'businesses', 'action' => 'view', $placeClaimRequest['Place']['Business']['slug'], 'admin' => false));
				} else {
					echo "-"; 
				}
	?>
		</td>
		<td class="dc">
            <div class="status-block">
                <?php if($placeClaimRequest['PlaceClaimRequest']['is_approved'] == 0){
    				echo '<span class="waiting-approval round-5">'.__l('Pending').'</span>';
    			  } else if($placeClaimRequest['PlaceClaimRequest']['is_approved'] == 1){
    			  	echo '<span class="approved round-5">'.__l('Approved').'</span>';
    			  } else if($placeClaimRequest['PlaceClaimRequest']['is_approved'] == 2){
    			  	echo '<span class="rejected round-5">'.__l('Rejected').'</span>';
    			  }
    	          ?>
    		</div>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No Place Claim Requests available');?></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($placeClaimRequests)) { ?>
    <div class="clearfix">
	<div class="admin-select-block grid_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
                <?php echo $this->Html->link(__l('Approved'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Approved'))); ?>
				<?php echo $this->Html->link(__l('Rejected'), '#', array('class' => 'js-admin-select-disapproved', 'title' => __l('Rejected'))); ?>
				<?php echo $this->Html->link(__l('Pending'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Pending'))); ?>

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