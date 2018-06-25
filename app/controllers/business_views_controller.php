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
class BusinessViewsController extends AppController
{
    public $name = 'BusinessViews';
    public function admin_index() 
    {
		$conditions = array();
        $this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('Business Views');
		if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(BusinessView.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(BusinessView.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(BusinessView.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }

		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['BusinessView']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		if (isset($this->request->params['named']['business'])) {
            $businessConditions = array(
                'Business.slug' => $this->request->params['named']['business']
            );
            $business = $this->{$this->modelClass}->Business->find('first', array(
                'conditions' => $businessConditions,
                'fields' => array(
                    'Business.id',
                    'Business.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($business)) {
                throw new NotFoundException(__l('Invalid request'));
            }

			$conditions['BusinessView.business_id'] = $business['Business']['id'];	
            $this->pageTitle.= ' - ' . $business['Business']['name'];
        }
        $this->BusinessView->recursive = 0;
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
                'Business'=>array(
                    'fields'=>array(
                        'Business.name',
                        'Business.slug'
                    )
                ),
            ),
			'order' => array(
				'BusinessView.id' => 'DESC'
			),
        );
		if (!empty($this->request->data['BusinessView']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['BusinessView']['q']));
        }
        $this->set('businessViews', $this->paginate());
		$moreActions = $this->BusinessView->moreActions;
		$this->set(compact('moreActions'));;
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->BusinessView->id = $id;
        if (!$this->BusinessView->exists()) {
            throw new NotFoundException(__l('Invalid business view'));
        }
        if ($this->BusinessView->delete()) {
            $this->Session->setFlash(__l('Business view deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Business view was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
