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
class BusinessesController extends AppController
{
    public $name = 'Businesses';
    public $components = array(
        'Email'
    );
	public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Attachment.filename',
			'Item.id',
            'Item.name',
            'BusinessUpdate.updates',
            'BusinessUpdate.business_id',
            'BusinessUpdate.user_id',
            'Place',
			'BusinessUpdate'
        );
        parent::beforeFilter();
    }
    public function index()
    {
		$this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Businesses');
		$conditions['Business.is_approved'] = 1;
		$order = array(
            'Business.id' => 'DESC'
        );
		if (isset($this->request->params['named']['following'])) {
            $businessFollowers = $this->Business->BusinessFollower->find('list', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['following']
                ) ,
                'fields' => array(
                    'BusinessFollower.id',
                    'BusinessFollower.business_id',
                ) ,
                'recursive' => 0
            ));
            if (!empty($businessFollowers)) {
                $conditions['Business.id'] = $businessFollowers;
            } else {
                $conditions['Business.id'] = 0;
            }
             $this->pageTitle.= ' - ' . __l('Followed by ') . $this->request->params['named']['following'];
        }
		if (isset($this->request->params['named']['type'])) {
            $order = array(
                'Business.business_follower_count' => 'DESC'
            );
			$conditions['Business.business_follower_count != '] = 0;
        }
		if(!empty($this->request->params['named']['user']) || !empty($this->request->params['named']['following'])) {
            $limit = 20;
        } elseif (!empty($this->request->params['named']['type'])) {
			$limit = 10;
        } else {
			$limit = 20;
		}
		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Business']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		$this->paginate = array(
            'conditions' => $conditions,
			 'contain' => array(
				'Attachment',
                'User' => array(
                    'UserAvatar'
                ) ,
				'BusinessFollower' => array(
						'conditions' => array(
							'BusinessFollower.user_id' => $this->Auth->user('id') ,
						) ,
						'limit' => 1,
					) ,
			),
            'order' => $order,
			'limit' => $limit,
        );
		if (!empty($this->request->data['Guide']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Guide']['q']
            ));
        }
        $this->Business->recursive = 1;
        $this->set('businesses', $this->paginate());
		if (isset($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'simple') {
            $this->render('simple_index');
        }
    }
    public function view($slug = null)
    {
        $this->pageTitle = __l('Business');
        $this->Business->slug = $slug;
        if (!$slug) {
            throw new NotFoundException(__l('Invalid business'));
        }

		        $business = $this->Business->find('first', array(
            'conditions' => array(
				'Business.slug' =>  $slug
			),
            'contain' => array(
                'Attachment',
                'User' => array(
                    'UserAvatar'
                ) ,
                'BusinessFollower' => array(
                    'conditions' => array(
                        'BusinessFollower.user_id' => $this->Auth->user('id') ,
                    ) ,
                    'limit' => 1,
                ) ,
            )
        ));



        if (empty($business)) {
            throw new NotFoundException(__l('Invalid request'));
        }

        $this->Business->BusinessView->create();
        $this->request->data['BusinessView']['user_id'] = $this->Auth->user('id');
        $this->request->data['BusinessView']['business_id'] = $business['Business']['id'];
        $this->request->data['BusinessView']['ip_id'] = $this->Business->BusinessView->toSaveIp();
        $this->Business->BusinessView->save($this->request->data);
        $this->pageTitle.= ' - ' . $business['Business']['name'];
        $this->set('business', $business);
    }
    public function add()
    {
        $this->pageTitle = __l('Request Business Access');
        if ($this->request->is('post')) {
            $this->Business->create();
            if ($this->Business->save($this->request->data)) {
                //if (Configure::read('affiliate.is_admin_mail_after_affiliate_request')) {
                $this->_sendBusinessRequestMail($this->Auth->user('id'));
                //}
                $this->Session->setFlash(__l('Business access request has been added, Admin will activate your business shortly') , 'default', null, 'success');
             	$status = "pending";
            } else {
                $this->Session->setFlash(__l('Business access request could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $businesses = $this->Business->find('first', array(
                'conditions' => array(
                    'Business.user_id' => $this->Auth->user('id')
                ) ,
                'contain' => array(
                    'User'
                ) ,
                'recursive' => 0,
                'limit' => 1,
                'order' => array(
                    'Business.id' => 'DESC'
                ) ,
            ));
            if (!empty($businesses) && $businesses['User']['is_business_user'] == 1) {
                $this->Session->setFlash(__l('You have already added a business') , 'default', null, 'error');
                $this->redirect(array(
                    'action' => 'index'
                ));
            }
            $status = '';
            if (!empty($businesses)) {
                if ($businesses['Business']['is_approved'] == 2) {
                    $status = "rejected";
                } else if ($businesses['Business']['is_approved'] == 0) {
                    $status = "pending";
                }
            }
            else{
                $status = "add";
            }
        }
		$this->set('status', $status);
        $users = $this->Business->User->find('list');
        $this->set(compact('users'));
    }
    public function _sendBusinessRequestMail($user_id)
    {
        $user = $this->Business->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'recursive' => -1
        ));
        $emailFindReplace = array(
            '##USERNAME##' => $user['User']['username'],
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/', true)
        );
        $this->loadModel('EmailTemplate');
        $email = $this->EmailTemplate->selectTemplate('Business Request');
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? $user['User']['email'] : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? $user['User']['email'] : $email['reply_to'];
        $this->Email->to = Configure::read('EmailTemplate.admin_email');
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        if ($this->Email->send(strtr($email['email_content'], $emailFindReplace))) {
            return true;
        }
    }
    public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Business');
        $this->Business->Attachment->Behaviors->attach('ImageUpload', Configure::read('avatar.file'));
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Business->id = $id;
        if (!$this->Business->exists()) {
            throw new NotFoundException(__l('Invalid business'));
        }
        $business = $this->Business->find('first', array(
            'conditions' => array(
                'Business.id = ' => $id
            ) ,
            'contain' => array(
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir',
                        'Attachment.width',
                        'Attachment.height'
                    ) ,
                )
            ) ,
            'recursive' => 0,
        ));
        if ($this->request->is('post') || $this->request->is('put')) {
            $ini_upload_error = 1;
            if ($this->request->data['Attachment']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            if (!empty($business)) {
                if (!empty($business['Attachment']['id'])) {
                    $this->request->data['Attachment']['id'] = $business['Attachment']['id'];
                }
            }
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['Attachment']['filename']['name']) || (!Configure::read('avatar.file.allowEmpty') && empty($this->request->data['Attachment']['id']))) {
                $this->Business->Attachment->set($this->request->data);
            }
            if ($this->Business->validates() &$this->Business->Attachment->validates() && $ini_upload_error) {
            	$business_id_ary[] = $this->request->data['Business']['id'];
				$this->__updateBusinessUser($business_id_ary, $this->request->data['Business']['is_approved']);
                if ($this->Business->save($this->request->data)) {
                    if (!empty($this->request->data['Attachment']['filename']['name'])) {
                        $this->Business->Attachment->create();
                        $this->request->data['Attachment']['class'] = 'Business';
                        $this->request->data['Attachment']['foreign_id'] = $id;
                        $this->Business->Attachment->save($this->request->data['Attachment']);
                    }
                    $this->Session->setFlash(__l('business has been updated') , 'default', null, 'success');
					if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
						$this->redirect(array(
	                        'action' => 'admin_index',
	                    ));
					}
					else{
						$this->redirect(array(
	                        'action' => 'my_business'
	                    ));
                    }
                } else {
                    $this->Session->setFlash(__l('business could not be updated. Please, try again.') , 'default', null, 'error');
                }
            }
        } else {
            $this->data = $this->Business->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['Business']['name'];
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_update()
    {
        if (!empty($this->request->data[$this->modelClass])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $ids = array();
            foreach($this->request->data[$this->modelClass] as $id => $is_checked) {
                if ($is_checked['id']) {
                    $ids[] = $id;
                }
            }
            if ($actionid && !empty($ids)) {
                switch ($actionid) {
                    case ConstMoreAction::Disapproved:
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_approved' => ConstBusinessRequests::Rejected
                            ) , array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->__updateBusinessUser($ids, 0);
                        $this->Session->setFlash(__l('Checked requests has been disapproved') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Approved:
                        $this->__updateBusinessUser($ids, 1);
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_approved' => ConstBusinessRequests::Accepted
                            ) , array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->Session->setFlash(__l('Checked requests has been approved') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Delete:
                        $this->__updateBusinessUser($ids, 0);
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->deleteAll(array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->Session->setFlash(__l('Checked requests has been deleted') , 'default', null, 'success');
                        break;
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    public function __updateBusinessUser($ids, $status)
    { 
        if ($status == 2) {
            $status = 0;
        }
		if(!is_array($ids)){
			$ids = array($ids);
		}
        foreach($ids as $id) {
            $business = $this->Business->find('first', array(
                'conditions' => array(
                    'Business.id' => $id
                ) ,
                'recursive' => -1
            ));
            $this->Business->User->updateAll(array(
                'User.is_business_user' => $status
            ) , array(
                'User.id' => $business['Business']['user_id']
            ));
            if ($status) {
                $this->_sendBusinessApprovedMail($business['Business']['user_id']);
            }
        }
    }
    public function _sendBusinessApprovedMail($user_id)
    {
        $this->loadModel('EmailTemplate');
        $user = $this->Business->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'fields' => array(
                'User.username',
                'User.id',
                'User.email'
            ) ,
            'contain' => array(
                'UserProfile'
            ) ,
            'recursive' => 1
        ));
        $this->loadModel('EmailTemplate');
        $email = $this->EmailTemplate->selectTemplate('Admin Approve Business');
        $emailFindReplace = array(
            '##USERNAME##' => $user['User']['username'],
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/', true)
        );
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->to = $this->Business->User->formatToAddress($user);
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Businesses');
        $conditions = array();
        $this->set('waiting_for_approval', $this->Business->find('count', array(
            'conditions' => array(
                'Business.is_approved = ' => ConstBusinessRequests::Pending,
            ) ,
            'recursive' => -1
        )));
        $this->set('approved', $this->Business->find('count', array(
            'conditions' => array(
                'Business.is_approved = ' => ConstBusinessRequests::Accepted,
            ) ,
            'recursive' => -1
        )));
        $this->set('rejected', $this->Business->find('count', array(
            'conditions' => array(
                'Business.is_approved = ' => ConstBusinessRequests::Rejected,
            ) ,
            'recursive' => -1
        )));
        $this->set('all', $this->Business->find('count', array(
            'recursive' => -1
        )));
        if (isset($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] == ConstBusinessRequests::Pending) {
                $conditions['Business.is_approved'] = ConstBusinessRequests::Pending;
                $this->pageTitle.= __l(' - Waiting for Approval');
            } elseif ($this->request->params['named']['main_filter_id'] == ConstBusinessRequests::Accepted) {
                $conditions['Business.is_approved'] = ConstBusinessRequests::Accepted;
                $this->pageTitle.= __l(' - Approved');
            } elseif ($this->request->params['named']['main_filter_id'] == ConstBusinessRequests::Rejected) {
                $conditions['Business.is_approved'] = ConstBusinessRequests::Rejected;
                $this->pageTitle.= __l(' - Rejected');
            }
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['Business']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->Business->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Business.id' => 'desc'
            )
        );
        if (isset($this->request->data['Business']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $moreActions = $this->Business->moreActions;
        $this->set(compact('moreActions'));
        $this->set('businesses', $this->paginate());
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null)
    {
        $this->setAction('edit', $id);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Business->id = $id;
        if (!$this->Business->exists()) {
            throw new NotFoundException(__l('Invalid business'));
        }
        if ($this->Business->delete()) {
            $this->Session->setFlash(__l('Business deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Business was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function my_business()
    {
        $this->pageTitle = __l('My Business');
        $business = $this->Business->find('first', array(
            'conditions' => array(
                'Business.user_id = ' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'Business.name',
                'Business.about_your_business',
                'Business.slug',
                'Attachment.id',
                'Attachment.filename',
                'Attachment.dir',
                'Attachment.width',
                'Attachment.height'
            ) ,
            'recursive' => 0,
        ));
        if (empty($business)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $business['Business']['name'];
        $this->set('business', $business);
    }
	public function admin_update_status($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'approve')) {
			$field_name = 'is_approved';
			if (isset($this->{$this->modelClass}->_schema['is_approved'])) {
				$field_name = 'is_approved';
			}
            $this->{$this->modelClass}->updateAll(array(
                $this->modelClass .'.' . $field_name => 1
            ) , array(
                $this->modelClass .'.id' => $id
            ));
			$this->__updateBusinessUser($id, 1);
            $this->Session->setFlash(__l($this->modelClass .' has been approved') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['status']) && ($this->request->params['named']['status'] == 'disapprove')) {
			$field_name = 'is_approved';
			if (isset($this->{$this->modelClass}->_schema['is_approved'])) {
				$field_name = 'is_approved';
			}
            $this->{$this->modelClass}->updateAll(array(
                $this->modelClass .'.' . $field_name => 2
            ) , array(
                $this->modelClass .'.id' => $id
            ));
			$this->__updateBusinessUser($id, 0);
            $this->Session->setFlash(__l($this->modelClass . ' has been disapproved') , 'default', null, 'success');
        }
		if(empty($_GET['f']) && $this->Auth->user('user_type_id') == ConstUserTypes::Admin){
			$_GET['f'] = 'admin/'.$this->request->params['controller'].'/index';
			if(!empty($this->request->params['named']['page'])){
				$_GET['f'] = 'admin/'.$this->request->params['controller'].'/index/page:'.$this->request->params['named']['page'];
			}
		}
		$this->redirect(Router::url('/', true) . $_GET['f']);
    }
}
