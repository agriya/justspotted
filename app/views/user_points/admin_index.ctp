<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userPoints index js-response">
<?php echo $this->element('paging_counter');?>
<div class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
     <?php echo $this->Form->create('UserPoint' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<div class="overflow-block">
<table class="list">
	<tr>
        <th class="select"></th>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'owner_user_id');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Points'), 'point');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Description'), 'description');?></div></th>
			</tr>
	<?php
if (!empty($userPoints)):
	$i = 0;
	foreach ($userPoints as $userPoint):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = 'class="altrow"';
		}
	?>
	<tr <?php echo $class;?>>
        <td class="select">
			<?php echo $this->Form->input('UserPoint.'.$userPoint['UserPoint']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userPoint['UserPoint']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                          <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userPoint['UserPoint']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($userPoint['UserPoint']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($userPoint['OwnerUser']['username']); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->cInt($userPoint['UserPoint']['point']); ?>
		</td>
		<td class="dl">
			<?php 
				if(!empty($userPoint['OtherUser']['username']))
				{
					echo $this->Html->cText($userPoint['OtherUser']['username']).'&nbsp;';
				}
				echo $this->Html->notificationDescription($userPoint); 
			?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Sighting Rating Types available');?></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($userPoints)) { ?>
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
        </div>
<?php
echo $this->Form->end();
}
?>
</div>
</div>