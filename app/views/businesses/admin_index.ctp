<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="businesses index js-response">
<ul class="clearfix filter-list">
    <li <?php if (isset($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstBusinessRequests::Pending){ echo 'class="active"';} ?>><span class="yahoo" title="<?php echo __l('Admin Users'); ?>><span class="pending" title="<?php echo __l('Waiting for Approval'); ?>"><?php echo $this->Html->link( $this->Html->cInt($waiting_for_approval, false) . '<span>' . __l('Waiting for Approval') . '</span>', array('action' => 'index', 'main_filter_id' => ConstBusinessRequests::Pending), array('escape' => false)); ?></span></li>
	<li <?php if (isset($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstBusinessRequests::Accepted){ echo 'class="active"';} ?>><span class="active" title="<?php echo __l('Approved'); ?>"><?php echo $this->Html->link( $this->Html->cInt($approved, false) . '<span>' . __l('Approved') . '</span>', array('action'=>'index', 'main_filter_id' => ConstBusinessRequests::Accepted), array('escape' => false)); ?></span></li>
	<li <?php if (isset($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstBusinessRequests::Rejected){ echo 'class="active"';} ?>><span class="inactive" title="<?php echo __l('Rejected'); ?>"><?php echo $this->Html->link( $this->Html->cInt($rejected, false) . '<span>' . __l('Rejected') . '</span>', array('action' => 'index', 'main_filter_id' => ConstBusinessRequests::Rejected), array('escape' => false)); ?></span></li>
	<li <?php if (!isset($this->request->params['named']['main_filter_id'])) { echo 'class="active"';} ?>><span class="total" title="<?php echo __l('All'); ?>"><?php echo $this->Html->link( $this->Html->cInt($all, false) . '<span>' . __l('All') . '</span>', array('action' => 'index'), array('escape' => false)); ?></span></li>
</ul>
<div class="page-count-block clearfix">
    <?php echo $this->element('paging_counter');?>
    <div class="grid_left"> <?php echo $this->Form->create('Business', array('class' => 'normal search-form', 'action'=>'index')); ?> <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?> <?php echo $this->Form->submit(__l('Search'));?> <?php echo $this->Form->end(); ?> </div>
</div>
<?php echo $this->Form->create('Business' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
	<tr>
        <th class="select"><?php echo __l('Select');?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'user_id');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Business'), 'name');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Why Do You Want a Business Access'), 'why_do_you_want_a_business_access');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('My Own Business?'), 'is_my_own_business');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Approved?'), 'is_approved');?></div></th>
				
			</tr>
<?php
if (!empty($businesses)):
	$i = 0;
	foreach ($businesses as $business):
		$class = null;
		$active_class = '';
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
		if($business['Business']['is_approved'] == 1):
			$status_class = 'js-checkbox-active';
		else:
			$active_class = ' inactive-record';
			$status_class = 'js-checkbox-inactive';
		endif;
		if($business['Business']['is_approved'] == 0) :
			$status_class = 'js-checkbox-inactive';
		elseif($business['Business']['is_approved'] == 1) :
			$status_class = 'js-checkbox-active';
		elseif($business['Business']['is_approved'] == 2) :
			$status_class = 'js-checkbox-suspended';
		endif;

?>
	<tr class="<?php echo $class.$active_class;?>" >
        <td class="select">
<?php echo $this->Form->input('Business.'.$business['Business']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$business['Business']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?>
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
                              <?php if($business['Business']['is_approved'] == 0 || $business['Business']['is_approved'] == 2){ ?>
                              <li> <?php echo $this->Html->link(__l('Approve'), array('action'=>'admin_update_status', $business['Business']['id'], 'status' => 'approve', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')),array('class'=>'approve','title' => __l('Approve'))); ?></li>
                              <?php }?>
                              <?php if($business['Business']['is_approved'] == 1){ ?>
                              <li> <?php echo $this->Html->link(__l('Disapprove'), array('action'=>'admin_update_status', $business['Business']['id'], 'status' => 'disapprove', 'page' => (!empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '')),array('class'=>'pending','title' => __l('Disapprove'))); ?></li>
                              <?php }?>                     
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $business['Business']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
							<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $business['Business']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($business['Business']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($business['User']['username']), array('controller'=> 'users', 'action' => 'view', $business['User']['username'], 'admin' => false), array('escape' => false));?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($business['Business']['name']), array('controller'=> 'businesses', 'action' => 'view', $business['Business']['slug'], 'admin' => false), array('escape' => false));?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($business['Business']['about_your_business']);?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cBool($business['Business']['is_my_own_business']);?>
		</td>
		<td class="dc">
            <div class="status-block">
                <?php if($business['Business']['is_approved'] == 0){
        			echo  '<span class="waiting-approval round-5">'.__l('Waiting for Approval').'</span>';
        		  } else if($business['Business']['is_approved'] == 1){
        		  	echo '<span class="approved round-5">'.__l('Approved').'</span>';
        		  } else if($business['Business']['is_approved'] == 2){
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
		<td colspan="8" class="notice"><?php if (isset($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstBusinessRequests::Pending){ echo __l('No business access request available'); } else { echo __l('No business available'); }?></td>
	</tr>
<?php
endif;
?>
</table>
        <div class="admin-select-block grid_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
    		    <?php echo $this->Html->link(__l('Approved'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Approved'))); ?>
    		    <?php echo $this->Html->link(__l('Rejected'), '#', array('class' => 'js-admin-select-disapproved', 'title' => __l('Rejected'))); ?>
                <?php echo $this->Html->link(__l('Waiting for approval'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Waiting for approval'))); ?>
            </div>
            <div class="admin-checkbox-button">
                <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
            </div>
            <?php  echo $this->Form->end(); ?>
        </div>
</ol>
		
<?php
if (!empty($businesses)) {
    echo $this->element('paging_links');
}
?>
</div>