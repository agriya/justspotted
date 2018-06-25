<div class="userOpenids form main-content-block round-5 js-corner">
<?php echo $this->Form->create('UserOpenid', array('class' => 'normal'));?>
	<fieldset>
	<?php 
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
			echo $this->Form->input('user_id');
		endif;
	?>		
	<?php
		echo $this->Form->input('openid', array('id' => "openid_identifier", 'class' => 'bg-openid-input', 'label' => __l('OpenID')));
	?>
	<?php 
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
			echo $this->Form->input('verify',array('type' => 'checkbox'));
		endif;
	?>		
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->Submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>
<script type="text/javascript" id="__openidselector" src="https://www.idselector.com/widget/button/1"></script>