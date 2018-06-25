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
class ReviewCommentsController extends AppController
{
    public $name = 'ReviewComments';
    public function index($id = null)
    {
        $conditions = $order = array();
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            $conditions['ReviewComment.admin_suspend !='] = 1;
        }
        if (!empty($this->request->params['named']['review_id'])) {
            $conditions['Review.id'] = $this->request->params['named']['review_id'];
        }
        if (!empty($id)) {
            $this->request->params['named']['review_id'] = $conditions['Review.id'] = $id;
        }
        $order = array(
            'ReviewComment.id' => 'desc'
        );
        if (empty($this->request->params['named']['type']) || $this->request->params['named']['type'] != 'all') {
            $limit = 5;
        } else {
            $review_comment_count = $this->ReviewComment->find('count', array(
                'conditions' => array(
                    'ReviewComment.review_id' => $id
                ) ,
                'recursive' => -1
            ));
            $limit = $review_comment_count;
        }
        $this->pageTitle = __l('Review Comments');
        $this->ReviewComment->recursive = 1;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Review' => array(
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height',
                        )
                    ) ,
                    'Sighting' => array(
                        'Place' => array(
                            'fields' => array(
                                'Place.id',
                                'Place.name'
                            )
                        ) ,
                        'Item' => array(
                            'fields' => array(
                                'Item.id',
                                'Item.name',
                                'Item.slug',
                            )
                        ) ,
                        'fields' => array(
                            'Sighting.id'
                        )
                    )
                ) ,
                'User' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    ) ,
                    'fields' => array(
                        'username'
                    ) ,
                ) ,
            ) ,
            'order' => $order,
            'limit' => $limit,
            'recursive' => 3
        );
        $this->set('reviewComments', $this->paginate());
        if (!empty($this->request->params['named']['user'])) {
            $this->render('index-user');
        }
    }
    public function view($id = null, $view_name = 'view')
    {
        $this->pageTitle = __l('Review Comment');
        $this->ReviewComment->id = $id;
        if (!$this->ReviewComment->exists()) {
            throw new NotFoundException(__l('Invalid Review comment'));
        }
        $reviewComment = $this->ReviewComment->find('first', array(
            'conditions' => array(
                'ReviewComment.id = ' => $id
            ) ,
            'contain' => array(
                'User' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    ) ,
                    'fields' => array(
                        'username'
                    ) ,
                ) ,
            ) ,
            'recursive' => 2,
        ));
        if (empty($reviewComment)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $reviewComment['ReviewComment']['id'];
        $this->set('reviewComment', $reviewComment);
        $this->render($view_name);
    }
    public function add()
    {
        $this->pageTitle = __l('Add Review Comment');
        $this->ReviewComment->create();
        if ($this->request->is('post')) {
            $this->request->data['ReviewComment']['user_id'] = $this->Auth->user('id');
            $this->request->data['ReviewComment']['ip_id'] = $this->ReviewComment->toSaveIp();
            // Checking Review User Owner/Commentor //
            $review = $this->ReviewComment->Review->find('first', array(
                'conditions' => array(
                    'Review.id' => $this->request->data['ReviewComment']['review_id'],
                ) ,
                'contain' => array(
                    'Sighting' => array(
                        'Item' => array(
                            'fields' => array(
                                'Item.id',
                                'Item.name',
                            )
                        ) ,
                        'Place' => array(
                            'fields' => array(
                                'Place.id',
                                'Place.name',
                            )
                        ) ,
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.id',
                            'User.username',
                            'User.email',
                        )
                    ) ,
                    'ReviewComment' => array(
                        'User' => array(
                            'fields' => array(
                                'User.id',
                                'User.username',
                                'User.email',
                            )
                        ) ,
                    )
                ) ,
                'recursive' => 2
            ));
            if (empty($review)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if ($this->ReviewComment->save($this->request->data)) {
                $reviews = $this->ReviewComment->Review->find('first', array(
                    'conditions' => array(
                        'Review.id' => $this->request->data['ReviewComment']['review_id'],
                    ) ,
                    'fields' => array(
                        'Review.id',
                    ) ,
                    'recursive' => -1
                ));
                $this->Session->setFlash(__l('Review comment has been added') , 'default', null, 'success');
                // -- Sending Mail -- //
                $mail_data = $mail_datas = array();
                if ($review['Review']['user_id'] == $this->Auth->user('id')) { // Review Owner //
                    $email_template = 'Review Owner Comment';
                    $i = 0;
                    foreach($review['ReviewComment'] as $review_commentors) {
                        if ($review_commentors['User']['id'] != $review['Review']['user_id']) { // Not to send to review owner //
                            $mail_datas[$i]['to_username'] = $review_commentors['User']['username'];
                            $mail_datas[$i]['to_userid'] = $review_commentors['User']['id'];
                            $mail_datas[$i]['to_email'] = $review_commentors['User']['email'];
                            $mail_datas[$i]['item_name'] = $review['Sighting']['Item']['name'];
                            $mail_datas[$i]['place_name'] = $review['Sighting']['Place']['name'];
                            $mail_datas[$i]['other_username'] = $this->Auth->user('username');
                            $mail_datas[$i]['other_userid'] = $this->Auth->user('id');
                            $mail_datas[$i]['comment_message'] = $this->request->data['ReviewComment']['comment'];
                            $mail_datas[$i]['review_id'] = $review['Review']['id'];
                            $mail_datas[$i]['mail_notification_id'] = ConstMailNotification::Comment;
                            $i++;
                        }
                    }
                } else { // When Not Owner //
                    $email_template = 'Review Comment';
                    $mail_data['to_username'] = $review['User']['username'];
                    $mail_data['review_id'] = $review['Review']['id'];
                    $mail_data['to_userid'] = $review['User']['id'];
                    $mail_data['to_email'] = $review['User']['email'];
                    $mail_data['item_name'] = $review['Sighting']['Item']['name'];
                    $mail_data['place_name'] = $review['Sighting']['Place']['name'];
                    $mail_data['other_username'] = $this->Auth->user('username');
                    $mail_data['other_userid'] = $this->Auth->user('id');
                    $mail_data['comment_message'] = $this->request->data['ReviewComment']['comment'];
                    $mail_data['mail_notification_id'] = ConstMailNotification::Comment;
                }
                if (!empty($mail_datas)) {
                    foreach($mail_datas as $mail_data) {
                        $this->ReviewComment->_readyMailSend($email_template, $mail_data);
                    }
                } elseif (!empty($mail_data)) {
                    $this->ReviewComment->_readyMailSend($email_template, $mail_data);
                }
                // -- Sending Mail -- //
				if ($this->RequestHandler->prefers('json')) {
						$this->view = 'Json';
						$this->redirect(array(
							'controller' => 'reviews',
							'action' => 'lst',
							$reviews['Review']['id'],
							"command" => 'fail',
							'ext' => 'json'
						));
				}
				else{
					if (!$this->RequestHandler->isAjax()) {
						$this->redirect(array(
							'controller' => 'reviews',
							'action' => 'view',
							$reviews['Review']['id']
						));
					} else {
						// Ajax: return added answer
						$this->setAction('view', $this->ReviewComment->getLastInsertId() , 'view_ajax');
					}
				}
            } else {
                $this->Session->setFlash(__l('Review comment could not be added. Please, try again.') , 'default', null, 'error');
				if ($this->RequestHandler->prefers('json')) {
						$this->view = 'Json';
						$this->redirect(array(
							'controller' => 'reviews',
							'action' => 'lst',
							$review['Review']['id'],
							"command" => 'fail',
							'ext' => 'json'
						));
				}
            }
        }
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewComment->id = $id;
        $review = $this->ReviewComment->find('first', array(
            'conditions' => array(
                'ReviewComment.id' => $id
            ) ,
            'contain' => array(
                'Review' => array(
                    'fields' => array(
                        'Review.id',
                    )
                )
            ) ,
            'recursive' => 0
        ));
        if (!$this->ReviewComment->exists()) {
            throw new NotFoundException(__l('Invalid review comment'));
        }
        if ($this->ReviewComment->delete()) {
            $this->Session->setFlash(__l('Review comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'reviews',
                'action' => 'view',
                $review['Review']['id']
            ));
        }
        $this->Session->setFlash(__l('Review comment was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q',
        ));
        $this->pageTitle = __l('Review Comments');
        $conditions = array();
        if (isset($this->request->params['named']['username'])) {
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
            $conditions['ReviewComment.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['ReviewComment.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - System Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['ReviewComment.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Admin Suspend ');
            }
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['ReviewComment']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (isset($this->request->params['named']['review'])) {
            $conditions['ReviewComment.review_id'] = $this->request->params['named']['review'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Review' => array(
                    'Sighting' => array(
                        'Item',
                        'Place'
                    )
                ) ,
                'User',
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
            'order' => array(
                'ReviewComment.id' => 'DESC'
            ) ,
        );
        if (!empty($this->request->data['ReviewComment']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['ReviewComment']['q']
            ));
        }
        $this->set('reviewComments', $this->paginate());
        $moreActions = $this->ReviewComment->moreActions;
        $this->set(compact('moreActions'));
        $this->set('flagged', $this->ReviewComment->find('count', array(
            'conditions' => array(
                'ReviewComment.is_system_flagged = ' => 1,
            )
        )));
        $this->set('all', $this->ReviewComment->find('count'));
        $this->set('suspended', $this->ReviewComment->find('count', array(
            'conditions' => array(
                'ReviewComment.admin_suspend = ' => 1,
            )
        )));
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Review Comment');
        if ($this->request->is('post')) {
            $this->ReviewComment->create();
            if ($this->ReviewComment->save($this->request->data)) {
                $this->Session->setFlash(__l('review comment has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('review comment could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $reviews = $this->ReviewComment->Review->find('list');
        $users = $this->ReviewComment->User->find('list');
        $ips = $this->ReviewComment->Ip->find('list');
        $this->set(compact('reviews', 'users', 'ips'));
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Review Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewComment->id = $id;
        if (!$this->ReviewComment->exists()) {
            throw new NotFoundException(__l('Invalid review comment'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->ReviewComment->save($this->request->data)) {
                $this->Session->setFlash(__l('review comment has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('review comment could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->ReviewComment->read(null, $id);
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['ReviewComment']['id'];
        $reviews = $this->ReviewComment->Review->find('list');
        $users = $this->ReviewComment->User->find('list');
        $ips = $this->ReviewComment->Ip->find('list');
        $this->set(compact('reviews', 'users', 'ips'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewComment->id = $id;
        if (!$this->ReviewComment->exists()) {
            throw new NotFoundException(__l('Invalid review comment'));
        }
        if ($this->ReviewComment->delete()) {
            $this->Session->setFlash(__l('Review comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Review comment was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
