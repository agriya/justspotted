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
class PlaceClaimRequestsController extends AppController
{
    public $name = 'PlaceClaimRequests';
    public $components = array(
        'Email'
    );
    public function add() 
    {
        $this->pageTitle = __l('Add Place Claim Request');
        if (!empty($this->request->params['named']['place'])) {
            $business=$this->PlaceClaimRequest->Business->find('first',array(
                'conditions'=>array(
                    'Business.user_id'=> $this->Auth->user('id'),
                    'Business.is_approved'=> ConstBusinessRequests::Accepted
                ),
                'fields'=>array(
                    'Business.id'
                ),
                'recursive'=>-1
            ));
            $place=$this->PlaceClaimRequest->Place->find('first',array(
                'conditions'=>array(
                    'Place.slug'=> $this->request->params['named']['place']
                ),
                'fields'=>array(
                    'Place.id'
                ),
                'recursive'=>-1
            ));
            if(!empty($business) && !empty($place)){
                $PlaceClaimRequest=$this->PlaceClaimRequest->find('first',array(
                'conditions'=>array(
                    'PlaceClaimRequest.business_id'=> $business['Business']['id'],
                    'PlaceClaimRequest.place_id'=> $place['Place']['id']
                ),
                'fields'=>array(
                    'PlaceClaimRequest.id',
                    'PlaceClaimRequest.is_approved'
                ),
                'recursive'=>-1
            ));
            }
            else{
                throw new NotFoundException(__l('Invalid request'));
            }
            if(empty($PlaceClaimRequest) || ($PlaceClaimRequest['PlaceClaimRequest']['is_approved']== ConstPlaceClaimRequests:: Rejected)){
                $this->request->data['PlaceClaimRequest']['business_id']=$business['Business']['id'];
                $this->request->data['PlaceClaimRequest']['place_id']=$place['Place']['id'];
                 $this->request->data['PlaceClaimRequest']['is_approved']=0;
                $this->PlaceClaimRequest->create();
                if ($this->PlaceClaimRequest->save($this->request->data)) {
                $this->_sendPlaceClaimRequestMail($this->Auth->user('id'));
                    $this->Session->setFlash(__l('place claim request has been added, It approved by admin shortly') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller'=>'places',
                        'action' => 'view',
                        $this->request->params['named']['place']
                    ));
                } else {
                    $this->Session->setFlash(__l('place claim request could not be added. Please, try again.') , 'default', null, 'error');
                }
            }
            else{
                $this->Session->setFlash(__l('place claim request already added') , 'default', null, 'error');
                 $this->redirect(array(
                        'controller'=>'places',
                        'action' => 'view',
                        $this->request->params['named']['place']
                ));
            }
            
        }
    }
    public function admin_index() 
    {
        $this->_redirectPOST2Named(array(
            'q',
        ));
        $conditions = array();
        $this->pageTitle = __l('Place Claim Requests');
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['PlaceClaimRequest']['filter_id'] = $this->request->params['named']['filter_id'];
        }
         if (isset($this->request->data['PlaceClaimRequest']['filter_id'])) {
            if ($this->request->data['PlaceClaimRequest']['filter_id'] == ConstPlaceClaimRequests::Approved) {
                $conditions['PlaceClaimRequest.is_approved'] = ConstPlaceClaimRequests::Approved;
                $this->pageTitle.= __l(' - Approved ');
            } elseif ($this->request->data['PlaceClaimRequest']['filter_id'] == ConstPlaceClaimRequests::Rejected) {
                $conditions['PlaceClaimRequest.is_approved'] = ConstPlaceClaimRequests::Rejected;
                $this->pageTitle.= __l(' - Rejected ');
            } elseif ($this->request->data['PlaceClaimRequest']['filter_id'] == ConstPlaceClaimRequests::Pending) {
                $conditions['PlaceClaimRequest.is_approved'] = ConstPlaceClaimRequests::Pending;
                $this->pageTitle.= __l(' - Pending ');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['PlaceClaimRequest']['filter_id'];
        }
		if (isset($this->request->params['named']['place_id'])) {
			$conditions['PlaceClaimRequest.place_id'] = $this->request->params['named']['place_id'];	
        }
		if (isset($this->request->params['named']['business_id'])) {
			$conditions['PlaceClaimRequest.business_id'] = $this->request->params['named']['business_id'];	
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['PlaceClaimRequest']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->PlaceClaimRequest->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'Place' => array(
					'Business'
				),
				'Business'
			),
            'order' => array(
                'PlaceClaimRequest.id' => 'DESC'
            ) ,
        );
        if (isset($this->request->data['PlaceClaimRequest']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['PlaceClaimRequest']['q']
            ));
        }
        $this->set('placeClaimRequests', $this->paginate());
        $moreActions = $this->PlaceClaimRequest->moreActions;
        $this->set(compact('moreActions'));
        $this->set('approved', $this->PlaceClaimRequest->find('count', array(
            'conditions' => array(
                'PlaceClaimRequest.is_approved = ' => ConstPlaceClaimRequests::Approved,
            )
        )));
        $this->set('pending', $this->PlaceClaimRequest->find('count', array(
            'conditions' => array(
                'PlaceClaimRequest.is_approved = ' => ConstPlaceClaimRequests::Pending,
            )
        )));
        $this->set('rejected', $this->PlaceClaimRequest->find('count', array(
            'conditions' => array(
                'PlaceClaimRequest.is_approved = ' => ConstPlaceClaimRequests::Rejected,
            )
        )));
		$this->set('all', $this->PlaceClaimRequest->find('count', array(
           'recursive' => 0
        )));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->PlaceClaimRequest->id = $id;
        if (!$this->PlaceClaimRequest->exists()) {
            throw new NotFoundException(__l('Invalid place claim request'));
        }
        if ($this->PlaceClaimRequest->delete()) {
            $this->Session->setFlash(__l('Place claim request deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Place claim request was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function _sendPlaceClaimRequestMail($user_id)
    {
        $user = $this->PlaceClaimRequest->Business->User->find('first', array(
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
        $email = $this->EmailTemplate->selectTemplate('Place Claim Request');
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? $user['User']['email'] : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? $user['User']['email'] : $email['reply_to'];
        $this->Email->to = Configure::read('EmailTemplate.admin_email');
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        if ($this->Email->send(strtr($email['email_content'], $emailFindReplace))) {
            return true;
        }
    }
    function update($id){
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $place=$this->PlaceClaimRequest->Place->find('first',array(
                'conditions'=>array(
                    'Place.id'=> $id
                ),
                'fields'=>array(
                    'Place.slug'
                ),
                'recursive'=>-1
        ));
        $data['Place']['id']=$id;
        $data['Place']['business_id']='';
        $this->PlaceClaimRequest->Place->save($data);
		$this->Session->setFlash(__l('Place removal has been successfully completed.') , 'default', null, 'success');
        $this->redirect(array(
			'controller'=>'places',
            'action' => 'view',
            $place['Place']['slug']
        ));

    }
}
