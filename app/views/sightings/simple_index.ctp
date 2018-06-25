<?php
if (!empty($simpleSightings)):
?>
<?php if(!empty($simpleSightings[0]['Place']['item_count']) && empty($this->request->params['isAjax'])):?>
<h3><?php echo __l('More @ '.$simpleSightings[0]['Place']['name']);?></h3>
<?php
 endif;?>
<div class="people-list-block following-block clearfix">
            <div class="round-tl">
              <div class="round-tr">
                <div class="round-tm"> </div>
              </div>
            </div>
            <div class="people-list-inner">
            <div class="sightings index js-response js-responses">
<?php
endif;
?>
 <ol class="sighting-list existing-list clearfix">
<?php

if (!empty($simpleSightings)):
	$i = 1;
	foreach ($simpleSightings as $simpleSighting):
	if($i < 8):
?>
      <li>
         <div class="image-block">
        <?php  echo $this->Html->link($this->Html->showImage('Attachment', $simpleSighting['Review'][0]['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $simpleSighting['Item']['name']), 'title' => $simpleSighting['Item']['name'], 'class' => 'tool-tip')), array('controller'=> 'sightings', 'action' => 'view', $simpleSighting['Sighting']['id']), array('escape' => false));
        ?>
        </div>
	  </li>
    <?php
		endif;
	$i++;
    endforeach;
   	if(count($simpleSightings) > 7):
	$simpleSightings_count = count($simpleSightings)-7;
	?>
	<li class="more-users"><span><?php echo $simpleSightings_count.' '.__l('Others'); ?></span></li>
    <?php endif; ?>
    <?php
endif;
?>
</ol>
<?php
if (!empty($simpleSightings)) {
        ?>
    <div class="js-pagination">
        <?php echo $this->element('paging_links'); ?>
    </div>
  <?php   }?>
    </div>
    </div>
<div class="round-bl">
  <div class="round-br">
    <div class="round-tm"> </div>
  </div>
</div>
</div>