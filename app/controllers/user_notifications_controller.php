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
class UserNotificationsController extends AppController
{
    public $name = 'UserNotifications';   
    public function edit($id = null) 
    {
        $this->pageTitle = __l('Manage Email settings');
		$conditions = array();
		$conditions['UserNotification.user_id'] = $this->Auth->user('id');			
		$user_notifications = $this->UserNotification->find('first', array(
			'conditions' => $conditions,
			'recursive' => -1
		));	
		$this->request->data['UserNotification']['id'] = $user_notifications['UserNotification']['id'];
        if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['UserNotification']['user_id'] = $this->Auth->user('id');
		    if ($this->UserNotification->save($this->request->data)) {
                $this->Session->setFlash(__l('user notification has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'edit',
                    $this->request->data['UserNotification']['user_id'],
                ));
            } else {
                $this->Session->setFlash(__l('user notification could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } 
        $this->request->data = $user_notifications;
    }   
}
