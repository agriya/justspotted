<?php
if (!empty($guidesSightings)):
?>
<h4><?php echo __l('Featured on ').count($guidesSightings).__l(' guides'); ?></h4>
<?php
endif;
?>

<?php
if (!empty($guidesSightings)):
?>
<ol class="featured-list clearfix">
<?php
	$i = 1;
	foreach ($guidesSightings as $guidesSighting):
		if($i < 7):
?>
      <li>
        <?php 
		echo $this->Html->link('<span class="avator-shadow"> </span>'. $this->Html->showImage('Guide', $guidesSighting['Guide']['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $guidesSighting['Guide']['name']), 'title' => $guidesSighting['Guide']['name'], 'escape' => false)), array('controller'=> 'guides', 'action' => 'view', $guidesSighting['Guide']['slug']), array('title'=>$guidesSighting['Guide']['name'],'escape' => false));
      	?>
	  </li>
<?php
		endif;
	$i++;
    endforeach;
	if(count($guidesSightings) > 6):
	$guidesSightings_count = count($guidesSightings)-6;
	?>
	<li class="more-users"><span><?php echo $guidesSightings_count.' '.__l('Others'); ?></span></li>

    <?php endif; ?>
    </ol>
    <?php
endif;
?>


