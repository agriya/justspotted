<?php /* SVN: $Id: admin_index.ctp 1279 2011-05-26 05:07:26Z siva_063at09 $ */ ?>
<div class="userLogins index js-response">
     <div class="page-count-block clearfix">
     <div class="grid_left">
        <?php echo $this->element('paging_counter');?>
     </div>
    <div class="grid_left">
        <?php echo $this->Form->create('UserLogin' , array('class' => 'normal search-form','action' => 'index')); ?>
    	<div class="filter-section clearfix">
    			<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
    			<?php echo $this->Form->submit(__l('Search'));?>
    	</div>
    	<?php echo $this->Form->end(); ?>
	</div>
	</div>
    <?php echo $this->Form->create('UserLogin' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
   
    <div class="overflow-block">
    <table class="list">
        <tr>
            <th class="select"></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Login Time'), 'created');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User Login IP'), 'Ip.ip');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User Agent'), 'user_agent');?></div></th>
        </tr>
        <?php
        if (!empty($userLogins)):
            $i = 0;
            foreach ($userLogins as $userLogin):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td class="select"><?php echo $this->Form->input('UserLogin.'.$userLogin['UserLogin']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userLogin['UserLogin']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
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
                                    <li>
                                    <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userLogin['UserLogin']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span>
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
                    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($userLogin['UserLogin']['created']);?></td>
                    <td class="dl"><?php echo $this->Html->link($this->Html->cText($userLogin['User']['username']), array('controller'=> 'users', 'action'=>'view', $userLogin['User']['username'], 'admin' => false), array('escape' => false));?></td>
                    <td class="dl">
                        <?php if(!empty($userLogin['Ip']['ip'])): ?>
                            <?php echo  $this->Html->cText($userLogin['Ip']['ip']);
							?>
							<p>
							<?php
                            if(!empty($userLogin['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($userLogin['Ip']['Country']['iso2']); ?>" title ="<?php echo $userLogin['Ip']['Country']['name']; ?>">
									<?php echo $userLogin['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($userLogin['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $userLogin['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
						</td>
                    <td class="dl"><?php echo $this->Html->cText($userLogin['UserLogin']['user_agent']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="6" class="notice"><?php echo __l('No User Logins available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
</div>
    <?php
    if (!empty($userLogins)) :
        ?>
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
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>