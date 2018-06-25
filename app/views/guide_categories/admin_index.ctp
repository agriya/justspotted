<?php /* SVN: $Id: $ */ ?>
<div class="guideCategories index js-response">
	<div class="clearfix">
	<div class="grid_left">
	<?php echo $this->element('paging_counter');?>
	</div>
	<div class="grid_right"><?php echo $this->Html->link(__l('Add'), array('controller' => 'guide_categories', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?></div>
    </div>
<div class="overflow-block">
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('description');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Guides'), 'guide_count');?></div></th>
			</tr>
	<?php
if (!empty($guideCategories)):
	$i = 0;
	foreach ($guideCategories as $guideCategory):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
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
                  <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $guideCategory['GuideCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                  <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $guideCategory['GuideCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($guideCategory['GuideCategory']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($guideCategory['GuideCategory']['name']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($this->Html->truncate($guideCategory['GuideCategory']['description'])); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($guideCategory['GuideCategory']['guide_count'], false), array('controller' => 'guides', 'action' => 'index', 'guide_category' => $guideCategory['GuideCategory']['slug'])); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Guide Categories available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($guideCategories)) { ?>
    <div class="clearfix">
    <div class="js-pagination grid_right">
	    <?php echo $this->element('paging_links'); ?>
	</div>
	</div>
<?php }
?>
</div>
