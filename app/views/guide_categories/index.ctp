<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="people-list-block">
  <div class="round-tl">
      <div class="round-tr">
        <div class="round-tm"> </div>
      </div>
    </div>
    <div class="people-list-inner">
<div class="guideCategories index js-response js-responses">
<h3><?php echo __l('Guide Categories');?></h3>
<?php if(!$this->request->params['requested']) : ?>
<?php echo $this->element('paging_counter');?>
<?php endif; ?>
<ul class="categories-list">
<?php
if (!empty($guideCategories)):
	$i = 0;
	foreach ($guideCategories as $guideCategory):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = "altrow";
	}
	if(!empty($this->request->params['named']['category']) && ($this->request->params['named']['category']==$guideCategory['GuideCategory']['slug'])){
        $class.= " active";
	}
?>
	<li class="<?php echo $class;?> clearfix">
		<?php 
			echo $this->Html->link($this->Html->cText($guideCategory['GuideCategory']['name']).' ('.$this->Html->cInt($guideCategory['GuideCategory']['guide_count'], false).')', array('controller' => 'guides', 'action' => 'index', 'category' => $guideCategory['GuideCategory']['slug']), array('title' => $guideCategory['GuideCategory']['name'].' ('.$guideCategory['GuideCategory']['guide_count'].')', 'escape' => false)); 
		?>
	</li>
<?php
    endforeach;
else:
?>
	<li class="notice">
		<p><?php echo __l('No Guide Categories available');?></p>
	</li>
<?php
endif;
?>
</ul>
<?php
if (!empty($guideCategories) && !$this->request->params['requested']) { ?>
            <div class="js-pagination">
                <?php echo $this->element('paging_links'); ?>
            </div>
        <?php
}
?>

</div>
</div>

        <div class="round-bl">
          <div class="round-br">
            <div class="round-tm"> </div>
          </div>
        </div>

</div>