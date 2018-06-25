<?php /* SVN: $Id: $ */ ?>
<div class="labels form">
    <h2 class="title"><?php echo __l('Labels');?></h2>
    <table class="list" >
        <tr>
             <th class="label dl"><?php echo __l('Labels');?></th>
    	     <th class="actions"><?php echo __l('Actions');?></th>
        </tr>
        <?php
        if (!empty($labelsUsers)) :
            $i = 0;
            foreach ($labelsUsers as $labelsUser) :
            	$class = null;
            	if ($i++ % 2 == 0) :
            		$class = ' class="altrow"';
                endif;
                ?>
            	<tr<?php echo $class;?>>
            		<td class="label dl"><?php echo $this->Html->link($this->Html->cText($labelsUser['Label']['name'], false),array('controller'=>'messages','action'=>'label',$labelsUser['Label']['slug']),array('title' => $this->Html->cText($labelsUser['Label']['name'])));?></td>
            		<td class="actions"><span><?php echo $this->Html->link(__l('Rename'), array('controller'=>'labels_users','action' => 'edit',$labelsUser['LabelsUser']['id']), array('class' => 'edit js-label-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('controller'=>'labels_users','action' => 'delete', $labelsUser['LabelsUser']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
            	</tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
        		<td colspan="2" class="notice"><?php echo __l('No labels added yet.');?></td>
        	</tr>
            <?php
        endif;
        ?>
    </table>
    <h2  class="message-title-info"><?php echo __l('Create Label'); ?></h2>
    <div class="form-blocks js-corner round-5">
        <?php
            echo $this->Form->create('Label', array('class' => 'normal js-form'));
            echo $this->Form->input('name');
            echo $this->Form->end(__l('Add'));
        ?>
    </div>
</div>
