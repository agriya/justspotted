<?php /* SVN: $Id: $ */ ?>
<div class="clearfix">
<?php if(empty($this->request->params['isAjax'])){ ?>
	<div class="side1 grid_15 alpha omega">
<?php } ?>
<?php if(!empty($this->request->params['isAjax'])){ ?>
<h2><?php echo __l('Manage Email Settings');?></h2>
<?php }
    if(empty($this->request->params['isAjax'])){?>
         <?php echo $this->element('users-top_links'); ?>
	<?php }
    if(!empty($this->request->params['isAjax'])){?>
	<div class="info-details info-details1">
		<?php echo __l('Here you can manage your mail related settings. Enable/disable the below options which you would like to receive or not');?>
	</div>
	<div class="clearfix">
	   <div class="edit-profile-block">
			<?php echo $this->Form->create('UserNotification', array('action' => 'edit', 'class' => 'normal'));?>
			 <div class="overflow-block">
						<div>
							<table class="list">								
								<tr>
									<td class='dl'>
										<?php echo $this->Form->input('is_receive_comment', array('label' => __l('Receive notification when you receive any comments')));?>
									</td>
								</tr>
								<tr>
									<td class='dl'>
										<?php echo $this->Form->input('is_receive_compliment', array('label' => __l('Receive notification when you receive any compliment such as Great Shot etc.,')));?>
									</td>
								</tr>
								<tr>
									<td class='dl'>
										<?php echo $this->Form->input('is_receive_followers', array('label' => __l('Receive notification when you have followed by other users')));?>
									</td>
								</tr>								
							</table>
						</div>
				<div class="submit-block grid_right clearfix">
					<?php echo $this->Form->end(__l('Update'));?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
<?php if(empty($this->request->params['isAjax'])){ ?>
</div>
<?php } ?>
<?php
    if(empty($this->request->params['isAjax'])){?>
 <div class="side2 grid_8 alpha omega">
		<?php echo $this->element('users-sidebar', array('username' => $this->Auth->user('username'), 'config' => 'site_element_cache_5_min')); ?>
    </div>
    <?php } ?>
</div>	