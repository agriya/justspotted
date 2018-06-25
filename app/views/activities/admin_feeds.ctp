<?php /* SVN: $Id: $ */ ?>
<div class="feeds index js-response">
<div class="page-info"><?php echo __l('Module and table wide changes done recently are displayed for content moderation / audit.'); ?></div>
<table class="list">
	<tr>		
		<th class="model-name"><?php echo __l('Date');?></th>
		<th class="model-name"><?php echo  __l('Action');?></th>
		<th><?php echo  __l('Description');?></th>
	</tr>
<?php	
	$siteUrl = Router::url('/', true);
	foreach($activities as $activity) {
		foreach($activity as $key => $modelValues) {
			foreach($modelValues as $modelname => $value) {
?>
	<tr>
		<td class="dl model-name">
<?php			echo $this->Time->timeAgoInWords($value[$modelname]['modified']).'&nbsp;'; 
?>
		</td>
		<td class="dl model-name">
			<div class="status-block">
               <?php   if($value[$modelname]['modified'] != $value[$modelname]['created']) {
                    ?><span class="modified round-5"> <?php echo __l('Modified'); ?> </span>
                <?php
    			} else { ?>
    				<span class="created round-5"><?php echo __l('Created').'&nbsp;'; ?></span>
                <?php
    			}
                ?>
			</div>
			<span class="modal-name">
                <?php	echo Inflector::humanize(Inflector::tableize($modelname));
                ?>
            </span>
		</td>
		<td class="dl">
			<span class="feed-description">
<?php				eval("echo $textTemplate[$modelname];");
?>			</span>
		</td>
	</tr>
<?php
			}
		}
	}
?>
</table>
</div>
