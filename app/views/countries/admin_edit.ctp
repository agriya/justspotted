<?php /* SVN: $Id: $ */ ?>
<div>
            <h3><?php echo $this->Html->link(__l('Countries'), array('action' => 'index'),array('title' => __l('Countries')));?> &raquo; <?php echo __l('Edit Country - ').$this->Html->cText($this->request->data['Country']['name'], false); ?> </h3>
        <div>
            <?php echo $this->Form->create('Country', array('action' => 'edit', 'class' => 'normal'));?>
            <?php
            echo $this->Form->input('id');
            echo $this->Form->input('name');
            echo $this->Form->input('fips104');
    		echo $this->Form->input('iso2');
    		echo $this->Form->input('iso3');
    		echo $this->Form->input('ison');
    		echo $this->Form->input('internet');
    		echo $this->Form->input('capital');
    		echo $this->Form->input('map_reference');
    		echo $this->Form->input('nationality_singular');
    		echo $this->Form->input('nationality_plural');
    		echo $this->Form->input('currency');
    		echo $this->Form->input('currency_code');
    		echo $this->Form->input('population', array('info' => 'Ex: 2001600'));
    		echo $this->Form->input('title');
    		echo $this->Form->input('comment');
            ?>
        <div class="submit-block clearfix">
		<?php echo $this->Form->Submit(__l('Update'));?>
		</div>
		<?php echo $this->Form->end();?>
        </div>
</div>
