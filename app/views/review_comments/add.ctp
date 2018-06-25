<?php /* SVN: $Id: $ */ ?>
<?php
	$review_id = (!empty($review_id) ? $review_id : (!empty($this->request->data['ReviewComment']['review_id']) ? $this->request->data['ReviewComment']['review_id'] : ''));
?>
<div class="reviewComments form js-ajax-form-container<?php echo (!empty($review_id) ? '-'.$review_id : '');?>">
<?php echo $this->Form->create('ReviewComment', array('id' => 'review-commends-'.$review_id, 'class' => "send-comment normal js-comment-form {container:'js-ajax-form-container".(!empty($review_id) ? '-'.$review_id : '')."',responsecontainer:'js-responses-".$review_id."'}"));?>

	<?php
		if(!empty($review_id)):
			echo $this->Form->input('review_id', array('type' => 'hidden', 'value' => $review_id));
		else:
			echo $this->Form->input('review_id', array('type' => 'hidden'));
		endif;
        echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
		echo $this->Form->input('comment',array('class'=>" js-show-submit-block {'review_id':'" . $review_id ."'}"));
	?>
<div class="submit-block clearfix hide js-review-add-block<?php echo $review_id;?>">
<?php echo $this->Form->Submit(__l('Post your comment'));?>
</div>
<?php echo $this->Form->end();?>
</div>