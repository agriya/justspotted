<div class="js-tabs">
        <ul class="list tab-menu clearfix">
            <?php
             $class = ($this->request->params['controller'] == 'user_profiles') ? ' class="active"' : null; ?>		
	        <li><em></em><?php echo $this->Html->link(__l('My Notifications'), array('controller' => 'user_points', 'action' => 'index'), array('title' => __l('My Notifications')));?></li>
            <li><em></em><?php echo $this->Html->link(__l('Manage Email Settings'), array('controller' => 'user_notifications', 'action' => 'edit', $this->Auth->user('id')), array('title' => 'Manage Email Settings')); ?></li>
            <li><em></em><?php echo $this->Html->link(__l('My Connections'), array('controller' => 'users', 'action' => 'profile_image', 'connect' => 'linked_accounts', $this->Auth->user('id'), 'admin' => false), array('title' => 'My Connections', 'rel'=> '#Connect')); ?></li>
	       <li><em></em><?php echo $this->Html->link(__l('Change Password'), array('controller' => 'users', 'action' => 'change_password',  $this->Auth->user('id'), 'admin' => false), array('title' => 'Change Password')); ?></li>
        </ul>
</div>
