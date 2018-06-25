<?php /* SVN: $Id: $ */ ?>
<div class="bannedIps form">
    <div class="js-corner round-5" id="form-content">
        <?php echo $this->Form->create('BannedIp', array('class' => 'normal'));?>
      		<div><span><?php echo __l('Your IP: ');?><?php echo $ip;?></span></div>
             	<?php echo __l('Your Hostname: ');?><span><?php echo gethostbyaddr($ip);?></span>
               <h3><?php echo __l('Type');?></h3>
                <?php echo $this->Form->input('type_id', array('type' => 'radio', 'label' => __l('Select method'),'legend' => false));?>
                <?php echo $this->Form->input('address', array('label' => __l('Address/Range'))); ?>
                <?php echo $this->Form->input('range', array('label' => '', 'after' => __l('(IP address, domain or hostname)'))); ?>
             	<h3><?php echo __l('Possibilities:'); ?></h3>
      			<p><?php echo __l('- Single IP/Hostname: Fill in either a hostname or IP address in the first field.'); ?></p>
    			<p><?php echo __l('- IP Range: Put the starting IP address in the left and the ending IP address in the right field.'); ?></p>
    			<p><?php echo __l('- Referer block: To block google.com put google.com in the first field. To block google altogether.'); ?></p>
                <?php
            		echo $this->Form->input('reason', array('info' => __l('(optional, shown to victim)')));
            		echo $this->Form->input('redirect', array('info' => __l('(optional)')));
            		echo $this->Form->input('duration_id', array('label' => __l('How long')));
            		echo $this->Form->input('duration_time', array('label' => '', 'info' => __l('Leave field empty when using permanent. Fill in a number higher than 0 when using another option!')));
              	?>
          		<h3><?php echo __l('Hints and tips:'); ?></h3>
            	<p><?php echo __l('- Banning hosts in the 10.x.x.x / 169.254.x.x / 172.16.x.x or 192.168.x.x range probably won\'t work.'); ?></p>
            	<p><?php echo __l('- Banning by internet hostname might work unexpectedly and resulting in banning multiple people from the same ISP!'); ?></p>
            	<p><?php echo __l('- Wildcards on IP addresses are allowed. Block 84.234.*.* to block the whole 84.234.x.x range!'); ?></p>
            	<p><?php echo __l('- Setting a ban on a range of IP addresses might work unexpected and can result in false positives!'); ?></p>
            	<p><?php echo __l('- An IP address always contains 4 parts with numbers no higher than 254 separated by a dot!'); ?></p>
            	<p><?php echo __l('- If a ban does not seem to work try to find out if the person you\'re trying to ban doesn\'t use <a href="http://en.wikipedia.org/wiki/DHCP" target="_blank">DHCP.</a>'); ?></p>
            	<div class="submit-block clearfix">
		          <?php echo $this->Form->Submit(__l('Add'));?>
		       </div>
		<?php echo $this->Form->end();?>
    </div>
</div>