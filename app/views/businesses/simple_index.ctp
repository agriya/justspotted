<div class="businesses index business-index-block js-response js-responses">
<?php if(!empty($this->request->params['named']['user'])){ ?>
   	<?php  echo $this->Html->link(__l('Create a Business'), array('controller' => 'businesses', 'action' => 'add'), array('class'=>'create-business','title' => __l('Create a Business')));?>
<?php } ?>
<ol class="popular-list clearfix">
<?php
if (!empty($businesses)):
$i = 0;
foreach ($businesses as $business):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
?>
     	<li class="clearfix <?php echo $class;?>">
		 	<div class="img-block grid_left">
             <?php echo $this->Html->link('<span class="avator-shadow avator-shadow1">&nbsp;</span>'. $this->Html->showImage('Business', $business['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $business['Business']['name']), 'title' => $business['Business']['name'])), array('controller'=> 'businesses', 'action' => 'view', $business['Business']['slug']), array('escape' => false)); ?></div>
            <div class="popular-details grid_5 omega alpha">
         		<h4><?php echo $this->Html->link($this->Html->cText($this->Html->truncate($business['Business']['name'],65)), array('controller'=> 'businesses', 'action' => 'view', $business['Business']['slug']), array('title'=>$this->Html->cText($business['Business']['name'],false),'escape' => false));?></h4>
				<p class="follower"><?php echo $this->Html->cInt($business['Business']['business_follower_count']).' '.__l('followers'); ?></p>
            </div>
     	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No businesses available');?></p>
	</li>
<?php
endif;
?>
</ol>
</div>