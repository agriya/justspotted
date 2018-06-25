<?php /* SVN: $Id: $ */ ?>
<div class="users index js-response">
<div class="sightingRatingTypes index">
	<div class="clearfix">
	<div class="grid_left">
	<?php echo $this->element('paging_counter');?>
	</div>
	<div class="grid_right"><?php echo $this->Html->link(__l('Add'), array('controller' => 'sighting_rating_types', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?></div>
    </div>
<div class="overflow-block">
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('filter_name');?></div></th>                
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Sighting Ratings'), 'sighting_rating_count');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Active?'), 'is_active');?></div></th>
			</tr>
	<?php
if (!empty($sightingRatingTypes)):
	$i = 0;
	foreach ($sightingRatingTypes as $sightingRatingType):
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
                          <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $sightingRatingType['SightingRatingType']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $sightingRatingType['SightingRatingType']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($sightingRatingType['SightingRatingType']['created']); ?>
		</td>
		<td>
			<?php echo $this->Html->cText($sightingRatingType['SightingRatingType']['name']); ?>
		</td>
		<td>
			<?php echo $this->Html->cText($sightingRatingType['SightingRatingType']['filter_name']); ?>
		</td>
		
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($sightingRatingType['SightingRatingType']['sighting_rating_count']), array('controller' => 'sighting_ratings', 'action' => 'index', 'sighting_rating_type_id'=>$sightingRatingType['SightingRatingType']['id']), array('title'=>__l('Add'), 'escape' => false)); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cBool($sightingRatingType['SightingRatingType']['is_active']); ?>
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
</div>
<?php
if (!empty($sightingRatingTypes)) { ?>
  <div class="clearfix">
    <div class="grid_right">
    <?php
        echo $this->element('paging_links');
    } ?>
    </div>
    </div>
</div>
</div>