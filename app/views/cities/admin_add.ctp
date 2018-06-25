<?php /* SVN: $Id: $ */ ?>
<div>
    <div>
        <div>
            <h3><?php echo $this->Html->link(__l('Cities'), array('action' => 'index'),array('title' => __l('Cities')));?> &raquo; <?php echo __l('Add Cities');?></h3>
        </div>
        <div>
            <?php echo $this->Form->create('City', array('class' => 'normal','action'=>'add'));?>
            <?php
                echo $this->Form->input('country_id', array('empty'=>'Please Select'));
                echo $this->Form->input('state_id', array('empty'=>'Please Select'));
                echo $this->Form->input('name');
                echo $this->Form->input('latitude');
                echo $this->Form->input('longitude');
                echo $this->Form->input('timezone');
                echo $this->Form->input('county');
                echo $this->Form->input('code');
                echo $this->Form->input('is_approved', array('label' => 'Approved?'));
            ?>
        	<div class="submit-block clearfix">
		<?php echo $this->Form->Submit(__l('Add'));?>
		</div>
		<?php echo $this->Form->end();?>
        </div>
    </div>
</div>