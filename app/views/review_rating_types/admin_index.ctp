<?php /* SVN: $Id: $ */ ?>
<div class="reviewRatingTypes index js-response">
	<div class="page-count-block clearfix">
    <div class="grid_left">
    	<?php echo $this->element('paging_counter');?>
	</div>
	<div class="grid_right"><?php echo $this->Html->link(__l('Add'), array('controller' => 'review_rating_types', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?></div>
    </div>
<div class="overflow-block">
<table class="list">
	<tr>
		<th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Review Ratings'), 'review_rating_count');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Active?'), 'is_active');?></div></th>
			</tr>
	<?php
if (!empty($reviewRatingTypes)):
	$i = 0;
	foreach ($reviewRatingTypes as $reviewRatingType):
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
                    <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $reviewRatingType['ReviewRatingType']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
         <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $reviewRatingType['ReviewRatingType']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
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
			<?php echo $this->Html->cDateTimeHighlight($reviewRatingType['ReviewRatingType']['created']); ?>
		</td>
		<td>
			<?php echo $this->Html->cText($reviewRatingType['ReviewRatingType']['name']); ?>
		</td>
		<td class="dr">
			<?php echo $this->Html->link($this->Html->cInt($reviewRatingType['ReviewRatingType']['review_rating_count']), array('controller' => 'review_ratings', 'action' => 'index', 'review_rating_type_id'=>$reviewRatingType['ReviewRatingType']['id']), array('title'=>__l('Add'), 'escape' => false)); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cBool($reviewRatingType['ReviewRatingType']['is_active']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Review Rating Types available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($reviewRatingTypes)) { ?>
    <div class="clearfix">
    <div class="js-pagination grid_right">
	    <?php echo $this->element('paging_links'); ?>
	</div>
	</div>
<?php }
?>
</div>
