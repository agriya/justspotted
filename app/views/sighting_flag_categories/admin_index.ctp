<?php /* SVN: $Id: $ */ ?>
<div class="sightingFlagCategories index js-response">
	<div class="page-count-block clearfix">
    <div class="grid_left">
	<?php echo $this->element('paging_counter');?>
	</div>
	 <div class="grid_right"><?php echo $this->Html->link(__l('Add'), array('controller' => 'sighting_flag_categories', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?></div>
    </div>
<div class="overflow-block">
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Sightings'), 'sighting_flag_count');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Active?'), 'is_active');?></div></th>
			</tr>
	<?php
if (!empty($sightingFlagCategories)):
	$i = 0;
	foreach ($sightingFlagCategories as $sightingFlagCategory):
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
                   <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $sightingFlagCategory['SightingFlagCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
             <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $sightingFlagCategory['SightingFlagCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($sightingFlagCategory['SightingFlagCategory']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($sightingFlagCategory['SightingFlagCategory']['name']); ?>
		</td>

		<td class="dc">
			<?php echo $this->Html->link($this->Html->cInt($sightingFlagCategory['SightingFlagCategory']['sighting_flag_count'], false), array('controller' => 'sightings', 'action' => 'index', 'sighting_flag_category' => $sightingFlagCategory['SightingFlagCategory']['slug'])); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cBool($sightingFlagCategory['SightingFlagCategory']['is_active']); ?>
		</td>	
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Sighting Flag Categories available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($sightingFlagCategories)) { ?>
    <div class="clearfix">
    <div class="js-pagination grid_right">
	    <?php echo $this->element('paging_links'); ?>
	</div>
	</div>
<?php }
?>
</div>
