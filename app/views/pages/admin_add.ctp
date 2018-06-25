<?php /* SVN: $Id: admin_add.ctp 63884 2011-08-22 09:47:12Z arovindhan_144at11 $ */ ?>
<?php
    if(!empty($page)):
        ?>
        <div class="js-tabs">
        <ul class="clearfix tab-menu">
            <li><em></em><?php echo $this->Html->link(__l('Preview'), '#preview'); ?></li>
            <li><em></em><?php echo $this->Html->link(__l('Change'), '#add'); ?></li>
        </ul>
        <div id="preview">
            <div class="page">
                <h2><?php echo $page['Page']['title']; ?></h2>
                <div class="entry">
                   <?php echo $page['Page']['content']; ?>
                </div>
            </div>
        </div>
        <?php
    endif;
?>
<div id="add">
    <?php echo $this->element('js_tiny_mce_setting', array('cache' => array('config' => 'site_element_cache')));?>
    <div class="pages form">
        <?php echo $this->Form->create('Page', array('class' => 'normal'));?>
        <fieldset>
            <?php
                echo $this->Form->input('title', array('between' => '', 'label' =>__l('Page title')));
                echo $this->Form->input('content', array('type' => 'textarea', 'class' => 'js-editor', 'label' => __l('Body'), 'info' => __l('Available Variables: ##SITE_NAME##, ##SITE_URL##, ##ABOUT_US_URL##, ##CONTACT_US_URL##, ##FAQ_URL##')));                                
                echo $this->Form->input('slug',array('label' => __l('Slug'),'info' => __l('When you create link for this page, url should be page/value of this field.')));
			?>
            <div class="submit-block clearfix">
            	<?php
					echo $this->Form->submit(__l('Add'), array('name' => 'data[Page][Add]'));
					echo $this->Form->submit(__l('Preview'), array('name' => 'data[Page][Preview]'));
				?>
            </div>
        </fieldset>
            <?php echo $this->Form->end();  ?>
    </div>
</div>
<?php
    if(!empty($page)):
    ?>
    </div> <!-- js-tabs end !>
    <?php
endif;
?>
