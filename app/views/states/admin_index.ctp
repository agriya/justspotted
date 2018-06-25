<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="states index js-response">
   	<ul class="filter-list clearfix">
    		<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Active) { echo 'class="active"';} ?>><span class="active system-flagged" title="<?php echo __l('Approved States'); ?>"><?php echo $this->Html->link( $this->Html->cInt($active, false) . '<span>' . __l('Approved') . '</span>', array('controller' => 'states', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('escape' => false)); ?></span></li>
		<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) { echo 'class="active"';} ?>><span class="inactive system-flagged" title="<?php echo __l('Unapproved States'); ?>"><?php echo $this->Html->link($this->Html->cInt($inactive, false) . '<span>' . __l('Unapproved') . '</span>', array('controller' => 'states', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('escape' => false)); ?></span></li>
		<li <?php if (!isset($this->request->params['named']['main_filter_id']) && !isset($this->request->params['named']['filter_id'])) { echo 'class="active"';} ?>><span class="total system-flagged" title="<?php echo __l('Total States'); ?>"><?php echo $this->Html->link($this->Html->cInt($active + $inactive, false) . '<span>' . __l('Total') . '</span>', array('controller' => 'states', 'action' => 'index'), array('escape' => false)); ?></span></li>
	</ul>
    <div class="page-count-block clearfix">
	<div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
    <div class="grid_left">
        <?php echo $this->Form->create('State', array('class' => 'normal search-form clearfix', 'action'=>'index')); ?>
         <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        <?php echo $this->Form->submit(__l('Search'));?>
    	<?php echo $this->Form->end(); ?>
    </div>
     <div class="grid_right">
        <?php echo $this->Html->link(__l('Add'), array('controller' => 'states', 'action' => 'add'), array('title' => __l('Add New State'), 'class' => 'add'));?>
    </div>
    </div>
	<div>
        <?php
        echo $this->Form->create('State' , array('action' => 'update','class'=>'normal'));?>
        <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
      
        <div class="overflow-block">
        <table class="list">
            <tr>
                <th class="select"></th>
                <th class="actions"><?php echo __l('Actions');?></th>
                <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Country'), 'Country.name');?></div></th>
                <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
                <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('code');?></div></th>
                <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('adm1code');?></div></th>
                <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Approved?'), 'is_approved');?></div></th>
            </tr>
            <?php
                if (!empty($states)):
                $i = 0;
                    foreach ($states as $state):
                        $class = null;
						$active_class = '';
                        if ($i++ % 2 == 0) :
                            $class = 'altrow';
                        endif;
                        if($state['State']['is_approved'])  :
                            $status_class = 'js-checkbox-active';
                        else:
                            $status_class = 'js-checkbox-inactive';
							$active_class = ' inactive-record';
                        endif;
                        ?>
                        <tr class="<?php echo $class.$active_class;?>">
                            <td class="select">
                                <?php
                                    echo $this->Form->input('State.'.$state['State']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$state['State']['id'],'label' => false , 'class' => $status_class.' js-checkbox-list'));
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
                                <ul class="action-link clearfix">
                                  		<?php
									if($state['State']['is_approved']): ?>
                                    <li>
                                    <?php
										echo $this->Html->link(__l('Approved'), array('controller' => 'states', 'action' => 'update_status', $state['State']['id'], 'status' => 'inactive'), array('class' => 'approve', 'title' => __l('Approved')));
                                    ?>
                                    </li>
                                    <?php
                                    else: ?>
                                    <li>
                                    <?php
										echo $this->Html->link(__l('Disapproved'), array('controller' => 'states', 'action' => 'update_status', $state['State']['id'], 'status' => 'active') ,array('class' => 'pending', 'title' => __l('Disapproved')));
                                    ?>
                                    </li>
                                  <?php  endif; ?>
                                    <li>
                                    <?php
									echo $this->Html->link(__l('Edit'), array('action' => 'edit', $state['State']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));
                                    ?>
                                    </li>
                                    <li>
                                    <?php
                                    echo $this->Html->link(__l('Delete'), array('action' => 'delete', $state['State']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));
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
                            <td class="dl"><?php echo $this->Html->cText($state['Country']['name']);?></td>
                            <td class="dl"><?php echo $this->Html->cText($state['State']['name']);?></td>
                            <td class="dl"><?php echo $this->Html->cText($state['State']['code']);?></td>
                            <td class="dl"><?php echo $this->Html->cText($state['State']['adm1code']);?></td>
                            <td class="dc"><?php echo $this->Html->cBool($state['State']['is_approved']);?></td>
                        </tr>
                        <?php
                    endforeach;
            else:
                ?>
                <tr>
                    <td class="notice" colspan="6"><?php echo __l('No states available');?></td>
                </tr>
                <?php
            endif;
            ?>
        </table>
        </div>
        <?php
         if (!empty($states)) : ?>
         <div class="clearfix">
         <div class="admin-select-block grid_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title'=>__l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title'=>__l('None'))); ?>
                <?php echo $this->Html->link(__l('Unapproved'), '#', array('class' => 'js-admin-select-pending','title'=>__l('Unapproved'))); ?>
                <?php echo $this->Html->link(__l('Approved'), '#', array('class' => 'js-admin-select-approved','title'=>__l('Approved'))); ?>
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
         endif; ?>
        <?php echo $this->Form->end();?>
	</div>
</div>