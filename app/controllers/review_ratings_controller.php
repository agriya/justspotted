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
class ReviewRatingsController extends AppController
{
    public $name = 'ReviewRatings';
    public function index()
    {
        $this->pageTitle = __l('Review Ratings');
        $conditions = array();
        if (empty($this->request->params['named']['review_id'])) {
            throw new NotFoundException(__l('Invalid sighting rating'));
        }
        if (empty($this->request->params['named']['review_rating_type'])) {
            throw new NotFoundException(__l('Invalid sighting rating type'));
        }
        $reviewRatings = $this->ReviewRating->find('all', array(
            'conditions' => array(
                'ReviewRating.review_id = ' => $this->request->params['named']['review_id'],
                'ReviewRatingType.slug = ' => $this->request->params['named']['review_rating_type']
            ) ,
			'contain' => array(
				'User'=>array(
                    'UserAvatar',
                    'UserProfile' => array(
                        'City' => array(
                            'fields' => array(
                                'City.name'
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name'
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name'
                            )
                        )
                    ) ,
                ),
                'ReviewRatingType'=>array(
                    'fields'=>array(
                        'ReviewRatingType.name'
                    )
                )
			),
            'recursive' => 3,
        ));
        $user_followers = $this->ReviewRating->User->UserFollower->find('list', array(
            'conditions' => array(
                'UserFollower.follower_user_id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'UserFollower.user_id',
            ) ,
            'recursive' => -1
        ));
        $this->set('user_followers', $user_followers);
        $this->set('reviewRatings', $reviewRatings);
    }
    public function add()
    {
        $this->pageTitle = __l('Add Review Rating');
        $this->ReviewRating->Review->id = $this->request->params['named']['review_id'];
        $this->ReviewRating->ReviewRatingType->id = $this->request->params['named']['review_rating_type_id'];
		$this->request->data['ReviewRating']['ip_id'] = $this->ReviewRating->toSaveIp();
        if (!$this->ReviewRating->Review->exists() || !$this->ReviewRating->ReviewRatingType->exists()) {
            throw new NotFoundException(__l('Invalid review rating'));
        }
        $review_rating = $this->ReviewRating->find('first', array(
            'conditions' => array(
                'ReviewRating.review_id = ' => $this->request->params['named']['review_id'],
                'ReviewRating.user_id = ' => $this->Auth->user('id') ,
                'ReviewRating.review_rating_type_id = ' => $this->request->params['named']['review_rating_type_id']
            )
        ));
		$sussess_flag=0;
        if (empty($review_rating)) {
            $this->ReviewRating->create();
            $this->request->data['ReviewRating']['review_id'] = $this->request->params['named']['review_id'];
            $this->request->data['ReviewRating']['user_id'] = $this->Auth->user('id');
            $this->request->data['ReviewRating']['review_rating_type_id'] = $this->request->params['named']['review_rating_type_id'];
            if ($this->ReviewRating->save($this->request->data)) {
				$sussess_flag=1;
                if (!$this->RequestHandler->isAjax()) {
                    $this->Session->setFlash(__l('Review rating has been added') , 'default', null, 'success');
                }
                $review_rating_type = $this->ReviewRating->ReviewRatingType->find('first', array(
                    'conditions' => array(
                        'ReviewRatingType.id' => $this->request->params['named']['review_rating_type_id']
                    ) ,
                    'fields' => array(
                        'ReviewRatingType.name'
                    ) ,
                    'recursive' => -1
                ));
                $review = $this->ReviewRating->Review->find('first', array(
                    'conditions' => array(
                        'Review.id' => $this->request->params['named']['review_id'],
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
                    ) ,
                    'recursive' => 2
                ));
                // -- Sending Mail -- //
                $mail_data = array();
                $email_template = 'Review Rating';
                $mail_data['to_username'] = $review['User']['username'];
                $mail_data['review_id'] = $review['Review']['id'];
                $mail_data['to_userid'] = $review['User']['id'];
                $mail_data['to_email'] = $review['User']['email'];
                $mail_data['rating_type'] = $review_rating_type['ReviewRatingType']['name'];
                $mail_data['item_name'] = $review['Sighting']['Item']['name'];
                $mail_data['place_name'] = $review['Sighting']['Place']['name'];
                $mail_data['other_username'] = $this->Auth->user('username');
                $mail_data['other_userid'] = $this->Auth->user('id');
                $mail_data['mail_notification_id'] = ConstMailNotification::ReviewRating;
                $this->ReviewRating->_readyMailSend($email_template, $mail_data);
                // -- Sending Mail -- //

            } else {
                $this->Session->setFlash(__l('Review rating could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->ReviewRating->id = $review_rating['ReviewRating']['id'];
            if (!$this->RequestHandler->isAjax()) {
                if ($this->ReviewRating->delete($review_rating['ReviewRating']['id'])) {
					$sussess_flag=2;
                    $this->Session->setFlash(__l('Review rating deleted') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Review rating was not deleted') , 'default', null, 'error');
                }
            } else {
				$sussess_flag=2;
                $this->ReviewRating->delete($review_rating['ReviewRating']['id']);
            }
        }
		if ($this->RequestHandler->prefers('json')) {
				$total_count = $this->ReviewRating->ReviewRatingType->ReviewRatingStat->find('first', array(
					'conditions' => array(
						'ReviewRatingStat.review_id' => $this->request->params['named']['review_id'],
						'ReviewRatingStat.review_rating_type_id' => $this->request->params['named']['review_rating_type_id']
					) ,
					'fields' => array(
						'ReviewRatingStat.count'
					) ,
					'recursive' => -1,
				));		
				$sucess['count']=$total_count['ReviewRatingStat']['count'];		
				$this->view = 'Json';
                $sucess['success']=$sussess_flag;
				$this->set('json', (empty($this->viewVars['iphone_response'])) ? $sucess : $this->viewVars['iphone_response']);
        }
        else{
			if (!$this->RequestHandler->isAjax()) {
				if (!empty($this->request->params['named']['sighting_id'])) {
					$this->redirect(array(
						'controller' => 'sightings',
						'action' => 'view',
						$this->request->params['named']['sighting_id']
					));
				}
				$this->redirect(array(
					'controller' => 'reviews',
					'action' => 'view',
					$this->request->params['named']['review_id']
				));
			} else {
				$this->set('review_id', $this->request->params['named']['review_id']);
				$this->set('sighting_id', $this->request->params['named']['sighting_id']);
				$this->render('simple_index');
			}
		}
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
		$conditions = array();
        $this->pageTitle = __l('Review Ratings');
        if (!empty($this->request->params['named']['review_id'])) {
            $conditions['ReviewRating.review_id'] = $this->request->params['named']['review_id'];
        }
        if (!empty($this->request->params['named']['review_rating_type_id'])) {
            $conditions['ReviewRating.review_rating_type_id'] = $this->request->params['named']['review_rating_type_id'];
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['ReviewRating']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->ReviewRating->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
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
                    'fields' => array(
                        'Ip.ip',
                        'Ip.latitude',
                        'Ip.longitude'
                    )
                ) ,
                'User',
                'Review',
                'ReviewRatingType'
            ) ,
			'order' => array(
                'ReviewRating.id' => 'desc'
            )
        );
        if (!empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['ReviewRating']['q']
            ));
        }
        $this->set('reviewRatings', $this->paginate());
		$moreActions = $this->ReviewRating->moreActions;
		$this->set(compact('moreActions'));
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewRating->id = $id;
        if (!$this->ReviewRating->exists()) {
            throw new NotFoundException(__l('Invalid review rating'));
        }
        if ($this->ReviewRating->delete()) {
            $this->Session->setFlash(__l('Review rating deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Review rating was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
