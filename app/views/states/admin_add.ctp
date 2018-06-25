<?php /* SVN: $Id: $ */ ?>
<div class="states form">
    <div>
        <div>
            <h3><?php echo $this->Html->link(__l('States'), array('action' => 'index'),array('title' => __l('States')));?> &raquo; <?php echo __l('Add State'); ?> </h3>
        </div>
        <div>
            <?php echo $this->Form->create('State',  array('class' => 'normal','action'=>'add'));?>
            <?php
                echo $this->Form->input('country_id',array('empty'=>'Please Select'));
                echo $this->Form->input('name');
                echo $this->Form->input('code');
                echo $this->Form->input('adm1code');
                echo $this->Form->input('is_approved', array('label' => 'Approved?'));
            ?>
            <div class="submit-block">
            <?php echo $this->Form->end(__l('Add'));?>
            </div>
        </div>
    </div>
</div>
