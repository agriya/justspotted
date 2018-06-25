<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="cities index js-response">
 	<ul class="filter-list clearfix">
		<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Active) { echo 'class="active"';} ?>><span class="active system-flagged" title="<?php echo __l('Approved Cities'); ?>"><?php echo $this->Html->link( $this->Html->cInt($active, false) . '<span>' . __l('Approved') . '</span>', array('controller' => 'cities', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('escape' => false)); ?></span></li>
		<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) { echo 'class="active"';} ?>><span class="inactive system-flagged" title="<?php echo __l('Unapproved Cities'); ?>"><?php echo $this->Html->link($this->Html->cInt($inactive, false) . '<span>' . __l('Unapproved') . '</span>', array('controller' => 'cities', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('escape' => false)); ?></span></li>
		<li <?php if (!isset($this->request->params['named']['main_filter_id']) && !isset($this->request->params['named']['filter_id'])) { echo 'class="active"';} ?>><span class="total system-flagged" title="<?php echo __l('Total cities'); ?>"><?php echo $this->Html->link($this->Html->cInt($active + $inactive, false) . '<span>' . __l('Total') . '</span>', array('controller' => 'cities', 'action' => 'index'), array('escape' => false)); ?></span></li>


	</ul>
	<div class="page-count-block clearfix">
	<div class="grid_left">
	   <?php echo $this->element('paging_counter');?>
	</div>
	<div class="grid_left">
        <?php echo $this->Form->create('City', array('class' => 'normal search-form', 'action'=>'index')); ?>
        <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        <?php echo $this->Form->submit(__l('Search'));?>
    	<?php echo $this->Form->end(); ?>
	</div>
	<div class="grid_right">
		<?php echo $this->Html->link(__l('Add'), array('controller' => 'cities', 'action' => 'add'), array('title' => __l('Add New City'), 'class' => 'add')); ?>
	</div>
	</div>

	<div>
		<?php
		echo $this->Form->create('City', array('action' => 'update','class'=>'normal')); ?>
		<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>

		<div class="overflow-block">
		<table class="list">
			<tr>
				<th class="select"></th>
				<th><?php echo __l('Actions');?></th>
				<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('Country', 'Country.name', array('url'=>array('controller'=>'cities', 'action'=>'index')));?></div></th>
				<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('State', 'State.name', array('url'=>array('controller'=>'cities', 'action'=>'index')));?></div></th>
				<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('latitude');?></div></th>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('longitude');?></div></th>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('timezone');?></div></th>
				<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('county');?></div></th>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Approved?'), 'is_approved');?></div></th>
			</tr>
			<?php
			if (!empty($cities)):
				$i = 0;
				foreach ($cities as $city):
					$class = null;
					$active_class = '';
					if ($i++ % 2 == 0):
						$class = ' altrow';
					endif;
					if($city['City']['is_approved'])  :
						$status_class = 'js-checkbox-active';
					else:
						$status_class = 'js-checkbox-inactive';
						$active_class = ' inactive-record';
					endif;
				?>
					<tr class="<?php echo $class.$active_class;?>">
						<td class="select">
							<?php
							echo $this->Form->input('City.'.$city['City']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$city['City']['id'],'label' => false , 'class' => $status_class.' js-checkbox-list'));
							?>
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
                    <ul class="action-link action-link1 clearfix">

                                 	<?php
								if($city['City']['is_approved']):?>
								<li>
								<?php
									echo $this->Html->link(__l('Approved'), array('controller' => 'cities', 'action' => 'update_status', $city['City']['id'], 'status' => 'inactive'), array('class' => 'approve', 'title' => __l('Approved')));
                                ?>
                                </li>
                                <?php
                                else: ?>
                                <li>
                                <?php
									echo $this->Html->link(__l('Disapproved'), array('controller' => 'cities', 'action' => 'update_status', $city['City']['id'], 'status' => 'active'), array('class' => 'pending', 'title' => __l('Disapproved')));
                                ?>
                                </li>
                                <?php
                                endif;?>
                                <li>
                                <?php
								echo $this->Html->link(__l('Edit'), array('action'=>'edit', $city['City']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
                                </li>
                                <li>
                                <?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $city['City']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));
							?>
                                </li>
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
						<td class="dl"><?php echo $this->Html->cText($city['Country']['name'], false);?></td>
						<td class="dl"><?php echo $this->Html->cText($city['State']['name'], false);?></td>
						<td class="dl"><?php echo $this->Html->cText($city['City']['name'], false);?></td>
						<td class="dc"><?php echo $this->Html->cFloat($city['City']['latitude']);?></td>
						<td class="dc"><?php echo $this->Html->cFloat($city['City']['longitude']);?></td>
						<td class="dc"><?php echo $this->Html->cText($city['City']['timezone']);?></td>
						<td class="dl"><?php echo $this->Html->cText($city['City']['county']);?></td>
						<td class="dc"><?php echo $this->Html->cBool($city['City']['is_approved']);?></td>
					</tr>
				<?php
				endforeach;
				else:
				?>
				<tr>
					<td class="notice" colspan="10"><?php echo __l('No cities available');?></td>
				</tr>
				<?php
				endif;
				?>
		</table>
		</div>
		<?php
			if (!empty($cities)) :
				?>
				<div class="clearfix">
				<div class="admin-select-block clearfix grid_left">
				<div>
					<?php echo __l('Select:'); ?>
					<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
					<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
					<?php echo $this->Html->link(__l('Unapproved'), '#', array('class' => 'js-admin-select-pending','title' => __l('Unapproved'))); ?>
					<?php echo $this->Html->link(__l('Approved'), '#', array('class' => 'js-admin-select-approved','title' => __l('Approved'))); ?>
				</div>
				<div>
					<?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
				</div>
				</div>
				<div class="js-pagination grid_right">
					<?php  echo $this->element('paging_links'); ?>
				</div>
                </div>
				<div class="hide">
					<?php echo $this->Form->submit('Submit');  ?>
				</div>
				<?php
			endif;
		?>
	<?php
	echo $this->Form->end();
	?>
	</div>
</div>