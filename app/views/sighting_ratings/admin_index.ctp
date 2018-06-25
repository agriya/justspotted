<?php /* SVN: $Id: $ */ ?>
<div class="sightings index js-response">
<div class="sightingRatings index">
<div class="page-count-block clearfix">
	<div class="grid_left">
<?php echo $this->element('paging_counter');?>
</div>
	<div class="grid_left">
<?php echo $this->Form->create('SightingRating' , array('class' => 'normal search-form clearfix','action' => 'index')); ?>
    	<?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
    	<?php echo $this->Form->submit(__l('Search'));?>
    	<?php echo $this->Form->end(); ?>
    	</div>
    	</div>
<?php
	echo $this->Form->create('SightingRating' , array('class' => 'normal','action' => 'update'));
?> 
  <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>   	
	<div class="overflow-block">
<table class="list">
	<tr>
		<th class="select"></th>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><?php echo $this->Paginator->sort(__l('Rated On'), 'created');?></th>
				<th class="dl"><?php echo $this->Paginator->sort('sighting_id');?></th>
				<th><?php echo $this->Paginator->sort('user_id');?></th>
				<th><?php echo $this->Paginator->sort(__l('Rating Type'),'sighting_rating_type_id');?></th>
				<th><?php echo $this->Paginator->sort(__l('IP'), 'ip_id');?></th>
			</tr>
	<?php
if (!empty($sightingRatings)):
	$i = 0;
	foreach ($sightingRatings as $sightingRating):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td class="select">
			<?php echo $this->Form->input('SightingRating.'.$sightingRating['SightingRating']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$sightingRating['SightingRating']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
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
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $sightingRating['SightingRating']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($sightingRating['SightingRating']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($sightingRating['Sighting']['Item']['name'], false) .' @ '. $this->Html->cText($sightingRating['Sighting']['Place']['name'], false), array('controller' => 'sightings', 'action' => 'view', $sightingRating['Sighting']['id'],'admin' => false)); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($sightingRating['User']['username']), array('controller' => 'users', 'action' => 'view', $sightingRating['User']['username'],'admin' => false), array('escape' => false)); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($sightingRating['SightingRatingType']['name']); ?>
		</td>
		<td class="dl">
                        <?php if(!empty($sightingRating['Ip']['ip'])): ?>
                            <?php echo  $this->Html->cText($sightingRating['Ip']['ip']);
							?>
							<p>
							<?php
                            if(!empty($sightingRating['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($sightingRating['Ip']['Country']['iso2']); ?>" title ="<?php echo $sightingRating['Ip']['Country']['name']; ?>">
									<?php echo $sightingRating['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($sightingRating['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $sightingRating['Ip']['City']['name']; ?>    </span>
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
		<td colspan="8" class="notice"><?php echo __l('No Sighting Ratings available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($sightingRating)) { ?>
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
<?php } 
echo $this->Form->end();
?>
        
</div>
</div>