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
class BlockedUsersController extends AppController
{
    public $name = 'BlockedUsers';
    public function add($username = null)
    {
        $this->pageTitle = __l('Add Blocked User');
        // check is user exists
        $user = $this->BlockedUser->User->find('first', array(
            'conditions' => array(
                'User.username' => $username
            ) ,
            'fields' => array(
                'User.id',
                'User.username'
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // Check is already added
        $blocked = $this->BlockedUser->find('first', array(
            'conditions' => array(
                'BlockedUser.user_id' => $this->Auth->user('id') ,
                'BlockedUser.blocked_user_id' => $user['User']['id']
            ) ,
            'fields' => array(
                'BlockedUser.id'
            ) ,
            'recursive' => -1
        ));
        if (empty($blocked)) {
            $this->request->data['BlockedUser']['user_id'] = $this->Auth->user('id');
            $this->request->data['BlockedUser']['blocked_user_id'] = $user['User']['id'];
            $this->BlockedUser->create();
            if ($this->BlockedUser->save($this->request->data)) {
                $this->Session->setFlash(__l('User blocked successfully.') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'view',
                    $user['User']['username']
                ));
            } else {
            }
        } else {
            $this->Session->setFlash(__l('Already added') , 'default', null, 'error');
            $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'view',
                    $user['User']['username']
                ));
        }
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $blocked = $this->BlockedUser->find('first', array(
            'conditions' => array(
                'BlockedUser.id' => $id
            ) ,
            'fields' => array(
                'Blocked.username'
            ) ,
            'recursive' => 0
        ));
        if (!$blocked) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->BlockedUser->delete($id)) {
            $this->Session->setFlash(__l('Blocked User deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'view',
                $blocked['Blocked']['username']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Blocked Users');
        $this->BlockedUser->recursive = 0;
        $this->set('blockedUsers', $this->paginate());
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->BlockedUser->delete($id)) {
            $this->Session->setFlash(__l('Blocked User deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>