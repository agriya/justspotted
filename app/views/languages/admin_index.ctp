<?php /* SVN: $Id: admin_index.ctp 1279 2011-05-26 05:07:26Z siva_063at09 $ */ ?>
<div class="languages index js-response">
    	<ul class="filter-list clearfix">
		<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Active) { echo 'class="active"';} ?>><span class="active system-flagged" title="<?php echo __l('Active Languages'); ?>"><?php echo $this->Html->link( $this->Html->cInt($active, false) . '<span>' . __l('Active') . '</span>', array('controller' => 'languages', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('escape' => false)); ?></span></li>
		<li <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) { echo 'class="active"';} ?>><span class="inactive system-flagged" title="<?php echo __l('Inactive Languages'); ?>"><?php echo $this->Html->link($this->Html->cInt($inactive, false) . '<span>' . __l('Inactive') . '</span>', array('controller' => 'languages', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('escape' => false)); ?></span></li>
		<li <?php if (!isset($this->request->params['named']['main_filter_id']) && !isset($this->request->params['named']['filter_id'])) { echo 'class="active"';} ?>><span class="total system-flagged" title="<?php echo __l('Total Languages'); ?>"><?php echo $this->Html->link($this->Html->cInt($active + $inactive, false) . '<span>' . __l('Total') . '</span>', array('controller' => 'languages', 'action' => 'index'), array('escape' => false)); ?></span></li>
	</ul>
	<div class="clearfix">
	<div class="page-count-block clearfix">
    	<div class="grid_left">
    	   <?php echo $this->element('paging_counter');?>
    	 </div>
    	<div class="grid_left">
            <?php
                  echo $this->Form->create('Language', array('class' => 'normal search-form clearfix', 'action'=>'index'));
                  echo $this->Form->input('filter_id',array('type'=>'select', 'empty' => __l('Please Select'))); ?>
                  <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
                  <?php echo $this->Form->submit(__l('Search')); ?>
                 <?php echo $this->Form->end();
            ?>
        </div>
    </div>

    <?php echo $this->Form->create('Language' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
 
    <table class="list">
        <tr>
            <th class="select"></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('iso2');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('iso3');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Active'), 'is_active'); ?></div></th>
        </tr>
        <?php
        if (!empty($languages)):
            $i = 0;
            foreach ($languages as $language):
                $class = null;
				$active_class = '';
                if ($i++ % 2 == 0) :
                    $class = 'altrow';
                endif;
				if($language['Language']['is_active'])  :
					$status_class = 'js-checkbox-active';
				else:
					$status_class = 'js-checkbox-inactive';
					$active_class = ' inactive-record';
				endif; 
                ?>
                <tr class="<?php echo $class.$active_class;?>">
                    <td class="select"><?php echo $this->Form->input('Language.'.$language['Language']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$language['Language']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
                    <td class="dl"><?php echo $this->Html->cText($language['Language']['name']);?></td>
                    <td class="dl"><?php echo $this->Html->cText($language['Language']['iso2']);?></td>
                    <td class="dl"><?php echo $this->Html->cText($language['Language']['iso3']);?></td>
                    <td class="dc"><?php echo $this->Html->cBool($language['Language']['is_active']); ?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="5" class="notice"><?php echo __l('No Languages available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    <?php
    if (!empty($languages)) :
        ?>
     <div class="clearfix">
       <div class="admin-select-block grid_left">
        <div>
    		<?php echo __l('Select:'); ?>
    		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
    		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
    		<?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Inactive'))); ?>
    		<?php echo $this->Html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Active'))); ?>
    	</div>
        <div class="admin-checkbox-button">
            <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
        </div>
        <div class="js-pagination grid_right">
            <?php echo $this->element('paging_links'); ?>
        </div>
        </div>
        <div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>