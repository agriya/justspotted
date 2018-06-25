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
class GuideViewsController extends AppController
{
    public $name = 'GuideViews';
    public function admin_index() 
    {
		$conditions = array();
        $this->_redirectPOST2Named(array(
            'q'
        ));
		$this->pageTitle = __l('Guide Views');
		if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(GuideView.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(GuideView.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(GuideView.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }

		if (!empty($this->request->params['named']['q'])) {
            $this->request->data['GuideView']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		if (isset($this->request->params['named']['guide'])) {
            $guideConditions = array(
                'Guide.slug' => $this->request->params['named']['guide']
            );
            $guide = $this->{$this->modelClass}->Guide->find('first', array(
                'conditions' => $guideConditions,
                'fields' => array(
                    'Guide.id',
                    'Guide.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($guide)) {
                throw new NotFoundException(__l('Invalid request'));
            }

			$conditions['GuideView.guide_id'] = $guide['Guide']['id'];	
            $this->pageTitle.= ' - ' . $guide['Guide']['name'];
        }
        $this->GuideView->recursive = 0;
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
                'Guide'=>array(
                    'fields'=>array(
                        'Guide.name',
                        'Guide.slug'
                    )
                ),
            ),
			'order' => array(
				'GuideView.id' => 'DESC'
			),
        );
		if (!empty($this->request->data['GuideView']['q'])) {
            $this->paginate = array_merge($this->paginate, array('search' => $this->request->data['GuideView']['q']));
        }
        $this->set('guideViews', $this->paginate());
		$moreActions = $this->GuideView->moreActions;
		$this->set(compact('moreActions'));;
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->GuideView->id = $id;
        if (!$this->GuideView->exists()) {
            throw new NotFoundException(__l('Invalid guide view'));
        }
        if ($this->GuideView->delete()) {
            $this->Session->setFlash(__l('Guide view deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Guide view was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
