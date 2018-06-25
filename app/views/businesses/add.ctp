<?php /* SVN: $Id: $ */ ?>
<div class="businesses form">
<div class="side1 grid_16 alpha omega">
  <div class="spot-tl">
        <div class="spot-tr">
            <div class="spot-tm"> </div>
        </div>
    </div>
    <div class="spot-lm">
        <div class="spot-rm">
            <div class="spot-middle center-spot-middle clearfix">
<?php
	if($status == 'pending'):
?>
	 <div class="page-info"><?php echo __l('Your request will be confirmed after admin approval.'); ?> </div>
<?php
	elseif($status == 'rejected' && empty($this->request->params['named']['status'])):
?>
	 <div class="page-info"><?php echo sprintf(__l('Sorry, admin declined your request. If you want submit once again please').' %s', $this->Html->link(__l('Click Here'), array('controller' => 'businesses', 'action' => 'index', 'status' =>'add'), array('class' => '', 'title' => __l('Click Here')))); ?> </div>
<?php
	elseif($status == 'add' || (!empty($this->request->params['named']['status']) &&  $this->request->params['named']['status'] == 'add')):
?>
    <div class="page-info"><?php echo __l('This request will be confirmed after admin approval.'); ?> </div>
<?php echo $this->Form->create('Business', array('class' => 'normal'));?>
	<h2><?php echo $this->pageTitle; ?></h2>
	<?php
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
			echo $this->Form->input('user_id');
		} else {
			echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
		}
		echo $this->Form->input('name', array('label' => __l('Business Name')));
		echo $this->Form->input('why_do_you_want_a_business_access', array('label' => __l('Why Do You Want a Business Access')));
		echo $this->Form->input('is_my_own_business', array('label' => __l('My Own Business?')));
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
?>
			<span class="label-content label-content-radio"> <?php echo __l(' Approved?');?> </span>
<?php
			echo $this->Form->input('is_approved', array('legend' => false, 'type' => 'radio', 'options' => array(0 => 'Waiting for Approval', 1 => 'Approved', '2' => 'Rejected')));
		} 
	?>
    <div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Request'));?>
    </div>
     <?php echo $this->Form->end();?>
    <?php endif;?>
        </div>
    </div>
  </div>
    <div class="spot-bl">
        <div class="spot-br">
            <div class="spot-bm"> </div>
        </div>
    </div>
</div>
<div class="side2 grid_8 alpha omega">
    <div class="round-tl">
      <div class="round-tr">
         <div class="round-tm"> </div>
      </div>
    </div>
    <div class="people-list-inner clearfix">
    <?php
    	echo $this->element('pages-view', array('slug' => 'business-note' ,'config' => 'site_element_cache_10_min'));
    ?>
    </div>
    <div class="round-bl">
          <div class="round-br">
                <div class="round-tm"> </div>
          </div>
    </div>
</div>
</div>