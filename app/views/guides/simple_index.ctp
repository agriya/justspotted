<div class="guides index guide-index-block js-response js-responses">
<?php if(!empty($this->request->params['named']['user'])){ ?>
   	<?php  echo $this->Html->link(__l('Create a Guide'), array('controller' => 'guides', 'action' => 'add'), array('class'=>'create-guide','title' => __l('Create a Guide')));?>
<?php } ?>
<ol class="popular-list clearfix">
<?php

if (!empty($guides)):
$i = 0;
foreach ($guides as $guide):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
?>
     	<li class="clearfix <?php echo $class;?>">
		 	<div class="img-block grid_left">
             <?php echo $this->Html->link('<span class="avator-shadow avator-shadow1">&nbsp;</span>'. $this->Html->showImage('Guide', $guide['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $guide['Guide']['name']), 'title' => $guide['Guide']['name'])), array('controller'=> 'guides', 'action' => 'view', $guide['Guide']['slug']), array('escape' => false)); ?></div>
            <div class="popular-details grid_5 omega alpha">
         		<h4><?php echo $this->Html->link($this->Html->cText($this->Html->truncate($guide['Guide']['name'],35)), array('controller'=> 'guides', 'action' => 'view', $guide['Guide']['slug']), array('title'=>$this->Html->cText($guide['Guide']['name'],false),'escape' => false));?></h4>
				<p class="follower"><?php echo $this->Html->cInt($guide['Guide']['guide_follower_count']).' '.__l('followers'); ?></p>
            </div>
     	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Guides available');?></p>
	</li>
<?php
endif;
?>
</ol>
</div>