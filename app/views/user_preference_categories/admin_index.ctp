<?php /* SVN: $Id: admin_index.ctp 1279 2011-05-26 05:07:26Z siva_063at09 $ */ ?>
<div class="userPreferenceCategories index js-response">
    <?php echo $this->element('paging_counter');?>
    <table class="list">
        <tr>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('description');?></div></th>
        </tr>
        <?php
        if (!empty($userPreferenceCategories)):
            $i = 0;
            foreach ($userPreferenceCategories as $userPreferenceCategory):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td class="actions">
                      <div class="action-block">
                        <span class="action-information-block">
                            <span class="action-left-block">&nbsp;&nbsp;</span>
                                <span class="action-center-block">
                                    <span class="action-info">
                                        <?php echo __l('Action');?>
                                     </span>
                                </span>
                            </span>
                            <div class="action-inner-block">
                            <div class="action-inner-left-block">
                                <ul class="action-link clearfix">
                                    <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $userPreferenceCategory['UserPreferenceCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
        						 </ul>
        						</div>
                	                <div class="action-bot-left">
	                          <div class="action-bot-right">
        						<div class="action-bot-mid"></div>
        						</div>
                             </div>
							  </div>
	  				 </div>
                    </td>
                    <td class="dl"><?php echo $this->Html->cText($userPreferenceCategory['UserPreferenceCategory']['name']);?></td>
                    <td class="dl"><?php echo $this->Html->cText($userPreferenceCategory['UserPreferenceCategory']['description']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="3" class="notice"><?php echo __l('No User Preference Categories available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    <?php
    if (!empty($userPreferenceCategories)) :
        echo $this->element('paging_links');
    endif;
    ?>
</div>
