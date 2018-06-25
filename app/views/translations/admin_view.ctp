<?php /* SVN: $Id: admin_view.ctp 1251 2011-04-28 05:34:11Z boopathi_026ac09 $ */ ?>
<div class="translations view">
<h2><?php echo __l('Translation');?></h2>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($translation['Translation']['id']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($translation['Translation']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($translation['Translation']['modified']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Language');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($translation['Language']['name']), array('controller' => 'languages', 'action' => 'view', $translation['Language']['id']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Key');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($translation['Translation']['key']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Lang Text');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($translation['Translation']['lang_text']);?></dd>
	</dl>
</div>

