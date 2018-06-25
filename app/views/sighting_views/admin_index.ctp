<?php /* SVN: $Id: $ */ //pr($sightingViews);exit; ?>
<div class="sightingViews index js-response">
    <?php if(empty($this->params['named']['simple_view'])) {?>
    <div class="page-count-block clearfix">
    <div class="grid_left">
    	<?php echo $this->element('paging_counter');?>
    </div>
	</div>
	<?php echo $this->Form->create('SightingView' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <?php } ?>
<div class="overflow-block">
<table class="list">
	<tr>
        <?php if(empty($this->params['named']['simple_view'])) : ?>
	       <th class="select"></th>
        <?php endif; ?>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('sighting_id');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'ip_id');?></div></th>
			</tr>
	<?php
if (!empty($sightingViews)):
	$i = 0;
	foreach ($sightingViews as $sightingView):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
        <?php if(empty($this->params['named']['simple_view'])) : ?>
    		<td class="select">
			     <?php echo $this->Form->input('SightingView.'.$sightingView['SightingView']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$sightingView['SightingView']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
		    </td>
        <?php endif; ?>
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
                  <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $sightingView['SightingView']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($sightingView['SightingView']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($sightingView['Sighting']['Item']['name'], false) . ' @ ' . $this->Html->cText($sightingView['Sighting']['Place']['name'], false), array('controller' => 'sightings', 'action' => 'view', $sightingView['Sighting']['id'], 'admin' => false)); ?>
		</td>
		<td class="dl">
			<?php 
				if(!empty($sightingView['User']['username'])) {
					echo $this->Html->link($this->Html->cText($sightingView['User']['username'], false), array('controller' => 'users', 'action' => 'view', $sightingView['User']['username'], 'admin' => false)); 
				} else {
					echo __l('Guest');
				}
			?>
		</td>
		<td class="dl">
		<?php if(!empty($sightingView['Ip']['ip'])): ?>
                            <?php echo  $this->Html->cText($sightingView['Ip']['ip']);
							?>
							<p>
							<?php
                            if(!empty($sightingView['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($sightingView['Ip']['Country']['iso2']); ?>" title ="<?php echo $sightingView['Ip']['Country']['name']; ?>">
									<?php echo $sightingView['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($sightingView['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $sightingView['Ip']['City']['name']; ?>    </span>
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
		<td colspan="7" class="notice"><?php echo __l('No Sighting Views available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($sightingViews)) { ?>
<div class="clearfix">
    <?php if(empty($this->params['named']['simple_view'])) : ?>
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
    <?php endif; ?>
	<div class="js-pagination grid_right">
	    <?php echo $this->element('paging_links'); ?>
	</div>
	</div>
	<?php if(empty($this->params['named']['simple_view'])) : ?>
	<div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
    <?php endif; ?>
<?php }
if(empty($this->params['named']['simple_view'])) {
    echo $this->Form->end();
}
?>
</div>
