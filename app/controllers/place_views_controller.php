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
class PlaceViewsController extends AppController
{
    public $name = 'PlaceViews';
    public function admin_index() 
    {
		$this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('Place Views');
		$conditions = array();
		if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PlaceView.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PlaceView.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PlaceView.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['PlaceView']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		if (isset($this->request->params['named']['place'])) {
            $placeConditions = array(
                'Place.slug' => $this->request->params['named']['place']
            );
            $place = $this->{$this->modelClass}->Place->find('first', array(
                'conditions' => $placeConditions,
                'fields' => array(
                    'Place.id',
                    'Place.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($place)) {
                throw new NotFoundException(__l('Invalid request'));
            }
			$conditions['PlaceView.place_id'] = $place['Place']['id'];
            $this->pageTitle.= ' - ' . $place['Place']['name'];
        }
        $this->PlaceView->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain'=>array(
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
                'User'=>array(
                    'fields'=>array(
                        'User.username'
                    )
                ),
                'Place'=>array(
                    'fields'=>array(
                        'Place.name',
                        'Place.slug'
                    )
                ),
            ),
			'order' => array(
				'PlaceView.id' => 'DESC'
			),
        );
		if (!empty($this->request->data['PlaceView']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['PlaceView']['q']));
        }
        $this->set('placeViews', $this->paginate());
		$moreActions = $this->PlaceView->moreActions;
		$this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->PlaceView->id = $id;
        if (!$this->PlaceView->exists()) {
            throw new NotFoundException(__l('Invalid place view'));
        }
        if ($this->PlaceView->delete()) {
            $this->Session->setFlash(__l('Place view deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Place view was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
