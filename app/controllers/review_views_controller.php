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
class ReviewViewsController extends AppController
{
    public $name = 'ReviewViews';
    public function admin_index() 
    {
		$this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('Review Views');
		$conditions = array();
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ReviewView.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ReviewView.created) <= '] = 7;
            $this->pageTitle.= __l(' - in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ReviewView.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
		if(isset($this->request->params['named']['review'])) {
			$conditions['ReviewView.review_id'] = $this->request->params['named']['review'];
		}

		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['ReviewView']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->ReviewView->recursive = 0;
		$this->paginate = array(
			'conditions' => $conditions,
			'contain' => array(
				'Review' => array(
					'Sighting' => array(
						'Item',
						'Place'
					)
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
				'ReviewView.id' => 'DESC'
			),
		);
		if (!empty($this->request->data['ReviewView']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['ReviewView']['q']));
        }
        $this->set('reviewViews', $this->paginate());
		$moreActions = $this->ReviewView->moreActions;
		$this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ReviewView->id = $id;
        if (!$this->ReviewView->exists()) {
            throw new NotFoundException(__l('Invalid review view'));
        }
        if ($this->ReviewView->delete()) {
            $this->Session->setFlash(__l('Review view deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Review view was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
