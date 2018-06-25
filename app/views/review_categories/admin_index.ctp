<?php /* SVN: $Id: $ */ ?>
<div class="reviewCategories index js-response">
	 <div class="page-count-block clearfix">
    	<div class="grid_left">
    	   <?php echo $this->element('paging_counter');?>
    	</div>
    	<div class="grid_left">
        	<?php echo $this->Form->create('ReviewCategory' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
        	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>
        	<?php echo $this->Form->create('ReviewCategory' , array('class' => 'normal','action' => 'update')); ?>
            <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
        </div>
    </div>
<table class="list">
	<tr>
		<th class="select"></th>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Reviews'), 'review_count');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Active?'), 'is_active');?></div></th>
			</tr>
	<?php
if (!empty($reviewCategories)):
	$i = 0;
	foreach ($reviewCategories as $reviewCategory):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td class="select">
			<?php echo $this->Form->input('ReviewCategory.'.$reviewCategory['ReviewCategory']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$reviewCategory['ReviewCategory']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                 <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $reviewCategory['ReviewCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $reviewCategory['ReviewCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($reviewCategory['ReviewCategory']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($reviewCategory['ReviewCategory']['name']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->link($this->Html->cInt($reviewCategory['ReviewCategory']['review_count'], false), array('controller' => 'reviews', 'action' => 'index', 'category' => $reviewCategory['ReviewCategory']['slug'])); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cBool($reviewCategory['ReviewCategory']['is_active']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Review Categories available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($reviewCategories)) { ?>
    <div class="clearfix">
	<div class="admin-select-block grdi_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
            </div>
           <div class="admin-checkbox-button">
                <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
            </div>
         </div>
	<div class="js-pagination grdi_right">
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
