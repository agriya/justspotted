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
class SightingRatingsController extends AppController
{
    public $name = 'SightingRatings';
    public function index()
    {
        $conditions = array();
		$limit = 20;
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == "raters") {
			if(!empty($this->request->params['named']['sighting_id']) && !empty($this->request->params['named']['sighting_rating_type_id'])) {
				$limit = 6;
				$conditions['SightingRating.sighting_rating_type_id'] = $this->request->params['named']['sighting_rating_type_id'];
			}
        }
        $this->pageTitle = __l('Sighting Ratings');
        $this->SightingRating->recursive = 0;
        if (!empty($this->request->params['named']['sighting_id'])) {
            $conditions['SightingRating.sighting_id'] = $this->request->params['named']['sighting_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'SightingRatingType',
				'User' => array(
                	'UserAvatar',
				)
			) ,
			'order' => array('SightingRating.id' => 'desc'),
			'limit' => $limit
        );
        $this->set('sightingRatings', $this->paginate());
        if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'raters' && !empty($this->request->params['named']['sighting_id'])) {
			if(!empty($this->request->params['named']['sighting_rating_type_id'])) {
			$total_count = $this->SightingRating->SightingRatingType->SightingRatingStat->find('first', array(
                'conditions' => array(
                    'SightingRatingStat.sighting_id' => $this->request->params['named']['sighting_id'],
                    'SightingRatingStat.sighting_rating_type_id' => $this->request->params['named']['sighting_rating_type_id']
                ) ,
                'fields' => array(
                    'SightingRatingStat.count'
                ) ,
                'recursive' => -1,
            ));
            $this->set('total_count', $total_count);
		     $this->render('raters');
		}

        }

    }
    public function add()
    {
        $this->pageTitle = __l('Add Sighting Rating');
        $this->SightingRating->Sighting->id = $this->request->params['named']['sighting_id'];
        $this->SightingRating->SightingRatingType->id = $this->request->params['named']['sighting_rating_type_id'];
		$this->request->data['SightingRating']['ip_id'] = $this->SightingRating->toSaveIp();
        if (!$this->SightingRating->Sighting->exists() || !$this->SightingRating->SightingRatingType->exists()) {
            throw new NotFoundException(__l('Invalid sighting rating'));
        }
        $sight_rating = $this->SightingRating->find('first', array(
            'conditions' => array(
                'SightingRating.sighting_id = ' => $this->request->params['named']['sighting_id'],
                'SightingRating.user_id = ' => $this->Auth->user('id') ,
                'SightingRating.sighting_rating_type_id = ' => $this->request->params['named']['sighting_rating_type_id']
            )
        ));
        $sussess_flag=0;
        if (empty($sight_rating)) {
            $this->SightingRating->create();
            $this->request->data['SightingRating']['sighting_id'] = $this->request->params['named']['sighting_id'];
            $this->request->data['SightingRating']['user_id'] = $this->Auth->user('id');
            $this->request->data['SightingRating']['sighting_rating_type_id'] = $this->request->params['named']['sighting_rating_type_id'];
            if (!$this->RequestHandler->isAjax()) {
                if ($this->SightingRating->save($this->request->data)) {
                    $sight_rating_count = $this->SightingRating->find('count', array(
                        'conditions' => array(
                            'SightingRating.user_id' => $this->Auth->user('id')
                        ) ,
                        'recursive' => -1
                    ));
                    $this->SightingRating->User->updateAll(array(
                        'User.want_count' => $sight_rating_count,
                    ) , array(
                        'User.id' => $this->Auth->user('id')
                    ));
                    $sussess_flag=1;
                    $this->Session->setFlash(__l('sighting rating has been added') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('sighting rating could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->SightingRating->save($this->request->data);
				$sussess_flag=1;
            }
        } else {
            $this->SightingRating->id = $sight_rating['SightingRating']['id'];
            if (!$this->RequestHandler->isAjax()) {
                if ($this->SightingRating->delete($sight_rating['SightingRating']['id'])) {
                    $sight_rating_count = $this->SightingRating->find('count', array(
                        'conditions' => array(
                            'SightingRating.user_id' => $this->Auth->user('id')
                        ) ,
                        'recursive' => -1
                    ));
                    $this->SightingRating->User->updateAll(array(
                        'User.want_count' => $sight_rating_count,
                    ) , array(
                        'User.id' => $this->Auth->user('id')
                    ));
                    $sussess_flag=2;
                    $this->Session->setFlash(__l('Sighting rating deleted') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Sighting rating was not deleted') , 'default', null, 'error');
                }
            } else {
                $this->SightingRating->delete($sight_rating['SightingRating']['id']);
				$sussess_flag=2;
            }
        }
        if ($this->RequestHandler->prefers('json')) {
				$total_count = $this->SightingRating->SightingRatingType->SightingRatingStat->find('first', array(
					'conditions' => array(
						'SightingRatingStat.sighting_id' => $this->request->params['named']['sighting_id'],
						'SightingRatingStat.sighting_rating_type_id' => $this->request->params['named']['sighting_rating_type_id']
					) ,
					'fields' => array(
						'SightingRatingStat.count'
					) ,
					'recursive' => -1,
				));		
				$sucess['count']=$total_count['SightingRatingStat']['count'];
				$this->view = 'Json';
                $sucess['success']=$sussess_flag;
				$this->set('json', (empty($this->viewVars['iphone_response'])) ? $sucess : $this->viewVars['iphone_response']);
        }
        else{
            if (!$this->RequestHandler->isAjax()) {
                $this->redirect(array(
                    'controller' => 'sightings',
                    'action' => 'view',
                    $this->request->params['named']['sighting_id']
                ));
            } else {
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
        $this->pageTitle = __l('Sighting Ratings');
        if (!empty($this->request->params['named']['sighting_id'])) {
            $conditions['SightingRating.sighting_id'] = $this->request->params['named']['sighting_id'];
        }
        if (!empty($this->request->params['named']['sighting_rating_type_id'])) {
            $conditions['SightingRating.sighting_rating_type_id'] = $this->request->params['named']['sighting_rating_type_id'];
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['SightingRating']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->SightingRating->recursive = 0;
		$order = array(
            'SightingRating.id' => 'DESC'
        );
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => $order,
            'contain' => array(
                'Sighting' => array(
                    'Item',
                    'Place',
                ) ,
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
                'Sighting',
                'SightingRatingType'
            ) ,
        );
        if (!empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['SightingRating']['q']
            ));
        }
        $this->set('sightingRatings', $this->paginate());
		$moreActions = $this->SightingRating->moreActions;
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
        $this->SightingRating->id = $id;
        if (!$this->SightingRating->exists()) {
            throw new NotFoundException(__l('Invalid sighting rating'));
        }
        if ($this->SightingRating->delete()) {
            $this->Session->setFlash(__l('Sighting rating deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Sighting rating was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function admin_update()
    {
        if (!empty($this->request->data['SightingRating'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $sightingratingIds = array();
            foreach($this->request->data['SightingRating'] as $sightingrating_id => $is_checked) {
                if ($is_checked['id']) {
                    $sightingratingIds[] = $sightingrating_id;
                }
            }
            if ($actionid && !empty($sightingratingIds)) {
				if ($actionid == ConstMoreAction::Delete) {
                    $this->SightingRating->deleteAll(array(
                        'SightingRating.id' => $sightingratingIds
                    ));
                    $this->Session->setFlash(__l('Checked sighting rating has been deleted') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
	}
}
