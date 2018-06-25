<?php /* SVN: $Id: $ */ ?>
<div class="placeTypes index js-response">
	<div class="clearfix">
    	<div class="grid_left">
    	   <?php echo $this->element('paging_counter');?>
    	</div>
        <div class="grid_right"><?php echo $this->Html->link(__l('Add'), array('controller' => 'place_types', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?></div>
    </div>
<div class="overflow-block">
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Places'), 'place_count');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Active?'), 'is_active');?></div></th>
			</tr>
	<?php
if (!empty($placeTypes)):
	$i = 0;
	foreach ($placeTypes as $placeType):
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
                     <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $placeType['PlaceType']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                    <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $placeType['PlaceType']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($placeType['PlaceType']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($placeType['PlaceType']['name']); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($placeType['PlaceType']['place_count'], false), array('controller' => 'places', 'action' => 'index', 'place_type' => $placeType['PlaceType']['slug'])); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cBool($placeType['PlaceType']['is_active']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Place Types available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($placeTypes)) {?>
    <div class="clearfix">
    <div class="js-pagination grdi_right">
	    <?php echo $this->element('paging_links'); ?>
	</div>
	</div>
<?php }
?>
</div>
