<?php /* SVN: $Id: $ */ ?>
<div class="guideViews index js-response">
    <?php if(empty($this->params['named']['simple_view'])) {?>
    <div class="page-count-block clearfix">
        <div class="grid_left">
        	<?php echo $this->element('paging_counter');?>
        </div>
        <div class="grid_left">
            <?php echo $this->Form->create('GuideView' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
        	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>
    	</div>
	</div>
	<?php echo $this->Form->create('GuideView' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <?php } ?>
<div class="overflow-block">
<table class="list">
	<tr>
        <?php if(empty($this->params['named']['simple_view'])) {?>
		  <th class="select"></th>
		<?php } ?>
		<th class="actions"><?php echo __l('Actions');?></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Guide'), 'Guide.name');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Viewed User'), 'User.username');?></div></th>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'ip_id');?></div></th>
	</tr>
	<?php
if (!empty($guideViews)):
	$i = 0;
	foreach ($guideViews as $guideView):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
        <?php if(empty($this->params['named']['simple_view'])) {?>
		<td class="select">
			<?php echo $this->Form->input('GuideView.'.$guideView['GuideView']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$guideView['GuideView']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
		</td>
		<?php } ?>
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
                  <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $guideView['GuideView']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($guideView['GuideView']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($guideView['Guide']['name'], false), array('controller' => 'guides', 'action' => 'view', $guideView['Guide']['slug'], 'admin' => false)); ?>
		</td>
		<td class="dl">
			<?php echo !empty($guideView['User']['username']) ? $this->Html->link($this->Html->cText($guideView['User']['username'], false), array('controller' => 'users', 'action' => 'view', $guideView['User']['username'], 'admin' => false), array('escape' => false, 'title' => $this->Html->cText($guideView['User']['username'], false))): __l('Guest');?>
		</td>
		<td class="dl">
		<?php if(!empty($guideView['Ip']['ip'])): ?>
                            <?php echo  $this->Html->cText($guideView['Ip']['ip']);
							?>
							<p>
							<?php
                            if(!empty($guideView['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($guideView['Ip']['Country']['iso2']); ?>" title ="<?php echo $guideView['Ip']['Country']['name']; ?>">
									<?php echo $guideView['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($guideView['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $guideView['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No Guide Views available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($guideViews)) { ?>
    <div class="clearfix">
    <?php if(empty($this->params['named']['simple_view'])) {?>
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
    <?php } ?>
	<div class="js-pagination grid_right">
	    <?php echo $this->element('paging_links'); ?>
	</div>
    </div>
    <?php if(empty($this->params['named']['simple_view'])) {?>
	<div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
    </div>
    <?php } ?>
<?php }
if(empty($this->params['named']['simple_view'])) {
    echo $this->Form->end();
}
?>
</div>
