<?php /* SVN: $Id: $ */ ?>
<div class="userOpenids index js-response">
    <div class="page-count-block clearfix">
        <div class="grid_left">
            <?php echo $this->element('paging_counter');?>
        </div>
        <div class="grid_left">
            <?php echo $this->Form->create('UserOpenid' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
        	<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>
    	</div>
         <div class="add-block grid_right">
             <?php echo $this->Html->link(__l('Add'), array('action' => 'add'), array('class'=>'add','title' => __l('Add')));?>
         </div>
    </div>
    <?php echo $this->Form->create('UserOpenid' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <div class="overflow-block clearfix">
    <table class="list">
        <tr>
            <th class="select"></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('openid');?></div></th>
        </tr>
        <?php
        if (!empty($userOpenids)):
            $i = 0;
            foreach ($userOpenids as $userOpenid):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td class="select"><?php echo $this->Form->input('UserOpenid.'.$userOpenid['UserOpenid']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userOpenid['UserOpenid']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
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
                                    <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userOpenid['UserOpenid']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
                    <td class="dl"><?php echo $this->Html->link($this->Html->cText($userOpenid['User']['username']), array('controller'=> 'users', 'action'=>'view', $userOpenid['User']['username'], 'admin' => false), array('escape' => false));?></td>
                    <td class="dl"><?php echo $this->Html->cText($userOpenid['UserOpenid']['openid']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="4" class="notice"><?php echo __l('No User Openids available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    </div>
    <?php
    if (!empty($userOpenids)) :
        ?>
        <div class="clearfix">
        <div class="admin-select-block grid_left">
        <div>
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all')); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none')); ?>
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
    endif;
    echo $this->Form->end();
    ?>
</div>