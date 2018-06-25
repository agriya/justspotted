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
class SightingViewsController extends AppController
{
    public $name = 'SightingViews';   
    public function admin_index() 
    {
		$this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('Sighting Views');
		$conditions = array();
		if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(SightingView.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(SightingView.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(SightingView.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['SightingView']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		if(isset($this->request->params['named']['sighting'])) {
			$conditions['SightingView.sighting_id'] = $this->request->params['named']['sighting'];
		}
		$this->SightingView->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'Sighting' => array(
					'Item',
					'Place',
				),
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
			),
			'order' => array(
				'SightingView.id' => 'DESC'
			)
        );
		if (!empty($this->request->data['SightingView']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['SightingView']['q']));
        }
        $this->set('sightingViews', $this->paginate());
		$moreActions = $this->SightingView->moreActions;
		$this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->SightingView->id = $id;
        if (!$this->SightingView->exists()) {
            throw new NotFoundException(__l('Invalid sighting view'));
        }
        if ($this->SightingView->delete()) {
            $this->Session->setFlash(__l('Sighting view deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Sighting view was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
