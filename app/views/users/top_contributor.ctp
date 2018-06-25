<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="users top_contributor">
    <h3><?php echo __l('Top Contributors'); ?></h3>
    <ol class="people-list contributors-list">
        <?php
        if (!empty($users)):
            $i = 0;
            foreach ($users as $user):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' altrow';
            }
        ?>
        <li class="<?php echo $class;?> clearfix">
            <div class="grid_1">
                <?php
                    echo $this->Html->link($this->Html->showImage('UserAvatar', $this->Html->getUserAvatar($user['User']['id']), array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $user['User']['username']), 'title' => $user['User']['username'], 'escape' => false)), array('controller' => 'users', 'action' => 'view',  $user['User']['username']), array('escape' => false));
                ?>
            </div>
            <div class="grid_4">
                      <h4>
                        <?php
                        if(!empty($user['UserProfile']['first_name']) && !empty($user['UserProfile']['last_name'])) {
                        $full_name = $user['UserProfile']['first_name'].' '.$user['UserProfile']['last_name'];
                        echo $this->Html->link($this->Html->cText($full_name, false), array('controller' => 'users', 'action' => 'view', $user['User']['username']),array('title' => $this->Html->cText($full_name, false)));
                        } else {
                        echo $this->Html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['username']),array('title' => $user['User']['username']));
                        }
                        ?>
                    </h4>
					<?php echo __l('Added') . ' ' . $this->Html->cInt($item_counts[$user['User']['id']]) . ' ' . __l('Items'); ?>
             </div>
            <div class="grid_2 alpha grid_right">
                <?php
                if($this->Auth->sessionValid()) {
                    if(in_array($user['User']['id'], $user_followers)){
                        $user_follower = array_flip($user_followers); ?>
                        <div class="follow-block unfollow-block grid_right grid_2">
                            <?php
                            echo $this->Html->link(__l('Unfollow'), array('controller' => 'user_followers', 'action'=>'delete', $user_follower[$user['User']['id']]), array( 'title' => __l('Unfollow'))); ?>
                        </div>
                    <?php
                    }
                }
                if(!in_array($user['User']['id'], $user_followers)) { ?>
                    <div class="follow-block grid_right grid_2">
                    <?php
                    echo $this->Html->link(__l('Follow'), array('controller' => 'user_followers', 'action'=>'add', 'user' => $user['User']['username']), array( 'title' => __l('Follow'))); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </li>
        <?php
        endforeach;
        else :
        ?>
        <li class="notice">
            <p><?php echo __l('No Contributors available');?></p>
        </li>
        <?php
        endif; ?>
    </ol>
</div>