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
class UserPointsController extends AppController
{
    public $name = 'UserPoints';
    public function index()
    {
        $this->pageTitle = __l('Notifications');
        $this->UserPoint->recursive = 0;
        $conditions = array(
            'UserPoint.owner_user_id' => $this->Auth->user('id') ,
            'UserPoint.model !=' => 'Sighting'
        );
		if (!empty($this->request->params['named']['user'])) {
			unset($conditions);
			$this->loadModel('User');
			$user=$this->User->find('first',array(
				'conditions'=>array(
					'User.username'=>$this->request->params['named']['user']
				),
				'fields'=>array(
					'User.id'
				),
				'recursive'=>-1
			));
            $conditions = array(
            'UserPoint.owner_user_id' => $user['User']['id'],
            'UserPoint.model !=' => 'Sighting'
			);
        }
        $limit = 20;
        if (!empty($this->request->params['requested'])) {
            $limit = 10;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'OwnerUser' => array(
                    'fields' => array(
                        'OwnerUser.id',
                        'OwnerUser.username'
                    ),
                ) ,
                'OtherUser' => array(
                    'fields' => array(
                        'OtherUser.id',
                        'OtherUser.username'
                    ) ,
					'conditions' => array(
						'OtherUser.is_active' => 1
					),
                    'UserAvatar'
                ) ,
                'ReviewRating' => array(
                    'ReviewRatingType' => array(
                        'fields' => array(
                            'ReviewRatingType.name'
                        )
                    ) ,
                    'Review' => array(
                        'fields' => array(
                            'Review.id',
                            'Review.sighting_id'
                        ) ,
                        'Sighting' => array(
                            'fields' => array(
                                'Sighting.id',
                                'Sighting.place_id',
                                'Sighting.item_id'
                            ) ,
                            'Item' => array(
                                'fields' => array(
                                    'Item.name'
                                )
                            ) ,
                            'Place' => array(
                                'fields' => array(
                                    'Place.name'
                                )
                            )
                        )
                    )
                ) ,
                'SightingRating' => array(
                    'SightingRatingType' => array(
                        'fields' => array(
                            'SightingRatingType.id',
                            'SightingRatingType.name'
                        )
                    ) ,
                    'Sighting' => array(
                        'fields' => array(
                            'Sighting.id',
                            'Sighting.place_id',
                            'Sighting.item_id'
                        ) ,
                        'Item' => array(
                            'fields' => array(
                                'Item.name'
                            )
                        ) ,
                        'Place' => array(
                            'fields' => array(
                                'Place.name'
                            )
                        )
                    )
                ) ,
                'ReviewComment' => array(
                    'fields' => array(
                        'ReviewComment.id',
                        'ReviewComment.review_id'
                    ) ,
                    'Review' => array(
                        'fields' => array(
                            'Review.id',
                            'Review.sighting_id'
                        ) ,
                        'Sighting' => array(
                            'fields' => array(
                                'Sighting.id',
                                'Sighting.place_id',
                                'Sighting.item_id'
                            ) ,
                            'Item' => array(
                                'fields' => array(
                                    'Item.name'
                                )
                            ) ,
                            'Place' => array(
                                'fields' => array(
                                    'Place.name'
                                )
                            )
                        )
                    )
                ) ,
                'UserFollower' => array(
                    'fields' => array(
                        'UserFollower.id'
                    )
                ) ,
            ) ,
            'limit' => $limit,
            'order' => array(
                'UserPoint.id' => 'desc'
            )
        );
        if (!empty($this->request->params['named']['type'])) {
            $this->autoRender=false;
            return $this->paginate();
        }
        if (!empty($this->request->params['requested'])) {
            $this->set('total_records', $this->UserPoint->find('count', array(
                'conditions' => $conditions,
                'recursive' => -1
            )));
        }
        $this->set('userPoints', $this->paginate());
		if (!empty($this->request->params['requested'])) {
            $this->render('simple_index');
        }
    }
    function activity_ratings()
    {
        $conditions['UserPoint.owner_user_id'] = $this->Auth->user('id');
		 if (empty($this->request->params['named']['type'])) {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserPoint.created) <= '] = 7;
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserPoint.created) <= '] = 7;
        }
        $review_count = $this->UserPoint->find('count', array(
            'conditions' => array(
                array_merge(array(
                    'UserPoint.model' => 'ReviewRating'
                ) , $conditions)
            ) ,
            'recursive' => -1
        ));
        $sighting_count = $this->UserPoint->find('count', array(
            'conditions' => array(
                array_merge(array(
                    'UserPoint.model' => 'SightingRating'
                ) , $conditions)
            ) ,
            'recursive' => -1
        ));
        $comment_count = $this->UserPoint->find('count', array(
            'conditions' => array(
                array_merge(array(
                    'UserPoint.model' => 'ReviewComment'
                ) , $conditions)
            ) ,
            'recursive' => -1
        ));
        $follow_count = $this->UserPoint->find('count', array(
            'conditions' => array(
                array_merge(array(
                    'UserPoint.model' => 'UserFollower'
                ) , $conditions)
            ) ,
            'recursive' => -1
        ));
        $reviewRatingTypes = $this->UserPoint->ReviewRating->ReviewRatingType->find('all', array(
            'fields' => array(
                'ReviewRatingType.id',
                'ReviewRatingType.name',
            ) ,
            'conditions' => array(
                'ReviewRatingType.is_active' => 1
            ) ,
            'recursive' => -1
        ));
        $sightingRatingTypes = $this->UserPoint->SightingRating->SightingRatingType->find('all', array(
            'conditions' => array(
                'SightingRatingType.is_active' => 1
            ) ,
            'fields' => array(
                'SightingRatingType.id',
                'SightingRatingType.name'
            ) ,
            'recursive' => -1
        ));
        if (!empty($reviewRatingTypes)) {
            $i = 0;
            $reviewText = '';
            foreach($reviewRatingTypes as $reviewRatingType) {
                if ($i) {
                    if (count($reviewRatingTypes) -1 == $i) {
                        $reviewText.= ' ' . __l('and') . ' ';
                    } else {
                        $reviewText.= ', ';
                    }
                }
                $reviewText.= $reviewRatingType['ReviewRatingType']['name'];
                $i++;
            }
        }
        if (!empty($sightingRatingTypes)) {
            $i = 0;
            $sightingText = '';
            foreach($sightingRatingTypes as $sightingRatingType) {
                if ($i) {
                    if (count($sightingRatingTypes) -1 == $i) {
                        $sightingText.= ' ' . __l('and') . ' ';
                    } else {
                        $sightingText.= ', ';
                    }
                }
                $sightingText.= $sightingRatingType['SightingRatingType']['name'];
                $i++;
            }
        }
        $this->set('review_count', $review_count);
        $this->set('sighting_count', $sighting_count);
        $this->set('comment_count', $comment_count);
        $this->set('follow_count', $follow_count);
        $this->set('reviewRatingTypes', $reviewRatingTypes);
        $this->set('sightingRatingTypes', $sightingRatingTypes);
        $this->set('reviewText', $reviewText);
        $this->set('sightingText', $sightingText);
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('User Points');
        $conditions = array();
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['UserPoints']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['username'])) {
            $conditions = array(
                'OwnerUser.username' => $this->request->params['named']['username']
            );
        }
        $this->UserPoint->recursive = 0;
        $user_points = $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'OtherUser' => array(
                    'fields' => array(
                        'OtherUser.id',
                        'OtherUser.username'
                    ) ,
                    'UserAvatar'
                ) ,
                'OwnerUser' => array(
                    'fields' => array(
                        'OwnerUser.id',
                        'OwnerUser.username'
                    ) ,
                    'UserAvatar'
                ) ,
                'ReviewRating' => array(
                    'ReviewRatingType' => array(
                        'fields' => array(
                            'ReviewRatingType.name',
                            'ReviewRatingType.tip_points'
                        )
                    ) ,
                    'Review' => array(
                        'fields' => array(
                            'Review.id',
                            'Review.sighting_id'
                        ) ,
                        'Sighting' => array(
                            'fields' => array(
                                'Sighting.id',
                                'Sighting.place_id',
                                'Sighting.item_id'
                            ) ,
                            'Item' => array(
                                'fields' => array(
                                    'Item.name'
                                )
                            ) ,
                            'Place' => array(
                                'fields' => array(
                                    'Place.name'
                                )
                            )
                        )
                    )
                ) ,
                'SightingRating' => array(
                    'SightingRatingType' => array(
                        'fields' => array(
                            'SightingRatingType.id',
                            'SightingRatingType.name',
                            'SightingRatingType.tip_points'
                        )
                    ) ,
                    'Sighting' => array(
                        'fields' => array(
                            'Sighting.id',
                            'Sighting.place_id',
                            'Sighting.item_id'
                        ) ,
                        'Item' => array(
                            'fields' => array(
                                'Item.name'
                            )
                        ) ,
                        'Place' => array(
                            'fields' => array(
                                'Place.name'
                            )
                        )
                    )
                ) ,
                'ReviewComment' => array(
                    'fields' => array(
                        'ReviewComment.id',
                        'ReviewComment.review_id'
                    ) ,
                    'Review' => array(
                        'fields' => array(
                            'Review.id',
                            'Review.sighting_id'
                        ) ,
                        'Sighting' => array(
                            'fields' => array(
                                'Sighting.id',
                                'Sighting.place_id',
                                'Sighting.item_id'
                            ) ,
                            'Item' => array(
                                'fields' => array(
                                    'Item.name'
                                )
                            ) ,
                            'Place' => array(
                                'fields' => array(
                                    'Place.name'
                                )
                            )
                        )
                    )
                ) ,
                'UserFollower' => array(
                    'fields' => array(
                        'UserFollower.id'
                    )
                ) ,
            ) ,
            'order' => array(
                'UserPoint.id' => 'desc'
            )
        );
        if (!empty($this->request->data['UserPoints']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['UserPoints']['q']
            ));
        }
        $this->set('userPoints', $this->paginate());
        $moreActions = $this->UserPoint->moreActions;
		$this->set(compact('moreActions'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->UserPoint->id = $id;
        if (!$this->UserPoint->exists()) {
            throw new NotFoundException(__l('Invalid user point'));
        }
        if ($this->UserPoint->delete()) {
            $this->Session->setFlash(__l('User point deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('User point was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
