<?php
/**
 * Just Spotted
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    justspotted
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class UserFollowersController extends AppController
{
    public $name = 'UserFollowers';
    public function index()
    {
        $this->pageTitle = __l('userFollowers');
        $condition = array();
        if (isset($this->request->params['named']['following']) || isset($this->request->params['named']['follower'])) {
            if (isset($this->request->params['named']['following'])) {
                $username = $this->request->params['named']['following'];
            }
            if (isset($this->request->params['named']['follower'])) {
                $username = $this->request->params['named']['follower'];
            }
            $user = $this->UserFollower->User->find('first', array(
                'conditions' => array(
                    'User.username' => $username
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if (isset($this->request->params['named']['following'])) {
                $this->pageTitle = __l('Following List');
                $condition['UserFollower.follower_user_id'] = $user['User']['id'];
            }
            if (isset($this->request->params['named']['follower'])) {
                $this->pageTitle = __l('Follower List');
                $condition['UserFollower.user_id'] = $user['User']['id'];
            }
        }
        $this->paginate = array(
            'conditions' => $condition,
            'contain' => array(
                'User' => array(
                    'UserAvatar',
                ) ,
                'FollowerUser' => array(
                    'UserAvatar',
                ) ,
                'Ip',
            ) ,
            'limit' => 6,
        );
        if (isset($this->request->params['named']['follower'])) {
            $followers = $this->UserFollower->find('count', array(
                'conditions' => $condition,
                'recursive' => -1
            )) -6;
            $this->set('followers', $followers);
        }
        if (isset($this->request->params['named']['following'])) {
            $followings = $this->UserFollower->find('count', array(
                'conditions' => $condition,
                'recursive' => -1
            )) -6;
            $this->set('followings', $followings);
        }
        if (!empty($this->request->params['named']['type'])) {
            $this->autoRender=false;
            return $this->paginate();
        }
        $this->set('pageTitle', $this->pageTitle);
        $this->set('userFollowers', $this->paginate());
    }
    public function add()
    {
        $this->pageTitle = __l('Add User Follower');
        if (!empty($this->request->params['named']['user'])) {
            $user = $this->UserFollower->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                    'User.email',
                ) ,
                'recursive' => -1
            ));
            $user_follower = $this->UserFollower->find('first', array(
                'conditions' => array(
                    'UserFollower.follower_user_id' => $this->Auth->user('id'),
                    'UserFollower.user_id' => $user['User']['id']
                ) ,
                'fields' => array(
                    'UserFollower.id',
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if (!empty($user_follower)) {
                $this->Session->setFlash(__l('You\'re already following this user.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'view',
                    $this->request->params['named']['user'],
                ));
            }
            $this->UserFollower->create();
            $this->request->data['UserFollower']['follower_user_id'] =$this->Auth->user('id');
            $this->request->data['UserFollower']['user_id'] =  $user['User']['id'];
            $this->request->data['UserFollower']['ip_id'] = $this->UserFollower->toSaveIp();
            if ($this->UserFollower->save($this->request->data)) {
                $this->Session->setFlash(__l('You have added') . ' "' . $user['User']['username'] . '" ' . __l('to your following list') , 'default', null, 'success');
				// -- Sending Mail -- //
				$mail_data = array();
				$email_template = 'User Follow';
				$mail_data['to_username'] = $user['User']['username'];
				$mail_data['to_userid'] = $user['User']['id'];
				$mail_data['to_email'] = $user['User']['email'];
				$mail_data['follow_user'] = $this->Auth->user('username');
				$mail_data['other_username'] = $this->Auth->user('username');
				$mail_data['other_userid'] = $this->Auth->user('id');
				$mail_data['mail_notification_id'] = ConstMailNotification::Follow;
				$this->UserFollower->_readyMailSend($email_template, $mail_data);
				// -- Sending Mail -- //
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'view',
                    $user['User']['username'],
                ));
            } else {
                $this->Session->setFlash(__l('User follower could not be added. Please, try again.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'index'
                ));
            }
        }
    }
    public function delete($id = null)
    {
		if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user_follower = $this->UserFollower->find('first', array(
            'conditions' => array(
                'UserFollower.id' => $id,
                'UserFollower.follower_user_id' => $this->Auth->user('id') ,
            ) ,
            'recursive' => 0
        ));
        $user = $this->UserFollower->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_follower['User']['id'],
            ) ,
            'fields' => array(
                'User.id',
                'User.username',
            ) ,
            'recursive' => -1
        ));
        if ($this->UserFollower->delete($user_follower['UserFollower']['id'])) {
            $this->Session->setFlash(__l('You have removed') . ' "' . $user['User']['username'] . '" ' . __l('from your user following list.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('User follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'index'
        ));
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('User Followers');
        $conditions = array();
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserFollower.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserFollower.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserFollower.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['UserFollower']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
			$conditions['OR'] = array(
                'FollowerUser.username LIKE' => $this->request->data['UserFollower']['q'] . '%'
            );
        }
        if (isset($this->request->params['named']['username']) && isset($this->request->params['named']['type'])) {
            $userConditions = array(
                'User.username' => $this->request->params['named']['username']
            );
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => $userConditions,
                'fields' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if ($this->request->params['named']['type'] == 'following') {
                $conditions['UserFollower.user_id'] = $user['User']['id'];
            } elseif ($this->request->params['named']['type'] == 'follower') {
                $conditions['UserFollower.follower_user_id'] = $user['User']['id'];
            }
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        $this->UserFollower->recursive = 2;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User',
                'FollowerUser',
                 'Ip' => array(
                    'City' => array(
                        'fields' => array(
                            'City.name',
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name',
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                            'Country.iso2',
                        )
                    ) ,
                    'Timezone' => array(
                        'fields' => array(
                            'Timezone.name',
                        )
                    ) ,
                    'fields' => array(
                        'Ip.ip',
                        'Ip.latitude',
                        'Ip.longitude'
                    )
                ) ,
            ) ,
            'recursive' => 2,
			'order' => array(
                'UserFollower.id' => 'desc'
            )
        );
        if (!empty($this->request->data['UserFollower']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['UserFollower']['q']
            ));
        }
        $this->set('userFollowers', $this->paginate());
        $moreActions = $this->UserFollower->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->UserFollower->id = $id;
        if (!$this->UserFollower->exists()) {
            throw new NotFoundException(__l('Invalid user follower'));
        }
        if ($this->UserFollower->delete()) {
            $this->Session->setFlash(__l('User follower deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('User follower was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
