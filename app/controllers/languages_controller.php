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
class LanguagesController extends AppController
{
    public $name = 'Languages';
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'filter_id',
            'q',
        ));
        $this->pageTitle = __l('Languages');
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Language']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            $this->request->data[$this->modelClass]['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data[$this->modelClass]['filter_id'])) {
            if ($this->request->data[$this->modelClass]['filter_id'] == ConstMoreAction::Active) {
                $conditions[$this->modelClass . '.is_active'] = 1;
                $this->pageTitle.= __l(' - Active');
            } else if ($this->request->data[$this->modelClass]['filter_id'] == ConstMoreAction::Inactive) {
                $conditions[$this->modelClass . '.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive');
            }
            $this->request->params['named']['filter_id'] = $this->request->data[$this->modelClass]['filter_id'];
        }
        $this->Language->recursive = -1;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Language.name' => 'asc'
            )
        );
        if (!empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Language']['q']
            ));
        }
        $this->set('languages', $this->paginate());
        $filters = $this->Language->isFilterOptions;
        $moreActions = $this->Language->moreActions;
        $this->set(compact('moreActions', 'filters'));
        $this->set('active', $this->Language->find('count', array(
            'conditions' => array(
                'Language.is_active' => 1
            )
        )));
        $this->set('inactive', $this->Language->find('count', array(
            'conditions' => array(
                'Language.is_active' => 0
            )
        )));
    }
    public function change_language()
    {
        if (!empty($this->request->data)) {
            if ($this->Auth->user('id')) {
                 $this->Cookie->write('user_language', $this->request->data['Language']['language_id'], false);
            } else {
                 $this->Cookie->write('user_language', $this->request->data['Language']['language_id'], false, time()+60*60*4);
            }
            $this->redirect(Router::url('/', true) . $this->request->data['Language']['r']);
        }
    }
}
?>