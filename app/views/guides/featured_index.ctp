<?php /* SVN: $Id: simple_index.ctp 1327 2012-01-02 12:18:39Z vinothraja_091at09 $ */ ?>
<?php if (!empty($guides)): ?>
<h2><?php echo $this->Html->cText(__l('Featured Guides')); ?></h2>
<div class="guides index js-response js-responses">
<?php if(!empty($this->request->params['named']['user'])){ ?>
  <ul class="list clearfix">
	<li class="grid_right"><?php  echo $this->Html->link(__l('Create a Guide'), array('controller' => 'guides', 'action' => 'add'), array('class'=>'create-guide','title' => __l('Create a Guide')));?></li>
  </ul>
<?php } ?>
<ol class="guides-list clearfix">
<?php

$i = 0;
foreach ($guides as $guide):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
?>
     	<li class="clearfix grid_6 alpha omega <?php echo $class;?>">
		<div class="spot-tl">
			<div class="spot-tr">
			  <div class="spot-tm"> </div>
			</div>
		  </div>
		  <div class="spot-lm">
			<div class="spot-rm">
			  <div class="spot-middle guides-spot-middle center-spot-middle clearfix">
    		 	<div class="img-block">
                    <?php echo $this->Html->link($this->Html->showImage('Guide', $guide['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $guide['Guide']['name']), 'title' => $guide['Guide']['name'])), array('controller'=> 'guides', 'action' => 'view', $guide['Guide']['slug']), array('escape' => false)); ?>
                 </div>
                <div class="popular-details">
             		<h4><?php echo $this->Html->link($this->Html->cText($this->Html->truncate($guide['Guide']['name'],50)), array('controller'=> 'guides', 'action' => 'view', $guide['Guide']['slug']), array('title'=>$this->Html->cText($guide['Guide']['name'],false),'escape' => false));?></h4>
                </div>
                  <?php echo $this->Html->cText($this->Html->truncate($guide['Guide']['tagline'],100));?>
			 </div>
            </div>
            </div>
            <div class="spot-bl">
                <div class="spot-br">
                  <div class="spot-bm"> </div>
                </div>
              </div>
     	</li>
<?php
    endforeach;
?>
</ol>
</div>
<?php
endif;
?>
