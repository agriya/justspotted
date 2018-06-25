<div class="places index place-index-block js-response js-responses">
<?php if(!empty($this->request->params['named']['user'])){ ?>
   	<?php  echo $this->Html->link(__l('Create a Place'), array('controller' => 'places', 'action' => 'add'), array('class'=>'create-place','title' => __l('Create a Place')));?>
<?php } ?>
<ol class="people-list branch-list clearfix">
<?php
if (!empty($places)):
$i = 0;
foreach ($places as $place):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
?>
     	<li class="clearfix <?php echo $class;?>">
              <div class="popular-details">
         		<h5><?php echo $this->Html->link($this->Html->cText($this->Html->truncate($place['Place']['name'],65)), array('controller'=> 'places', 'action' => 'view', $place['Place']['slug']), array('title'=>$this->Html->cText($place['Place']['name'],false),'escape' => false));?></h5>
			     <address class="address-block">
    				<span><?php echo $this->Html->cText($place['Place']['address2']);?></span>
    			</address>
              <ul class="sighting-list clearfix">
                <li class="follower">
                    <?php echo __l('followers');?>
                    <?php echo $this->Html->cInt($place['Place']['place_follower_count']); ?>
                </li>
              </ul>
            </div>
     	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Places available');?></p>
	</li>
<?php
endif;
?>
</ol>
</div>