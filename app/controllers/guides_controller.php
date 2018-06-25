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
class GuidesController extends AppController
{
    public $name = 'Guides';
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'City.name',
            'City.id',
            'Guide.user_id',
            'Guide.guide_category_id',
            'ReviewCategory.id',
            'Place.id',
            'Place.name',
            'Item.id',
            'Item.name'
        );
        parent::beforeFilter();
    }
    public function index()
    {
        $this->_redirectPOST2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Guides');
        $conditions = array();
        $order = array(
            'Guide.id' => 'DESC'
        );
		$conditions['Guide.admin_suspend !='] = 1;
         if (isset($this->request->params['named']['filter'])) {
                $conditions['Guide.is_featured'] =1;
         }

        if (isset($this->request->params['named']['user'])) {
            $user = $this->Guide->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user']
                ) ,
                'feilds' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Guide.user_id'] = $user['User']['id'];
            $order = array(
                'Guide.is_published' => 'ASC',
                'Guide.id' => 'DESC'
            );
            $this->pageTitle.= ' - ' . __l('Created by ') . $user['User']['username'];
        }
        if (isset($this->request->params['named']['following'])) {
            $guideFollowers = $this->Guide->GuideFollower->find('list', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['following']
                ) ,
                'fields' => array(
                    'GuideFollower.id',
                    'GuideFollower.guide_id',
                ) ,
                'recursive' => 0
            ));
            if (!empty($guideFollowers)) {
                $conditions['Guide.id'] = $guideFollowers;
            } else {
                $conditions['Guide.id'] = 0;
            }
             $this->pageTitle.= ' - ' . __l('Followed by ') . $this->request->params['named']['following'];
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Guide']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (isset($this->request->params['named']['type'])) {
            $order = array(
                'Guide.guide_follower_count' => 'DESC'
            );
        }
        if (!empty($this->request->params['named']['user']) || !empty($this->request->params['named']['following'])) {
            $limit = 5;
        } else if (!empty($this->request->params['named']['type'])) {
            $limit = 10;
        } else if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] == "featured") {
            $limit = 4;
        } else {
            $limit = (!empty($this->paginate['limit'])) ? $this->paginate['limit'] : 20;
        }
        if (isset($this->request->params['named']['category']) && !empty($this->request->params['named']['category'])) {
            $guideCategory = $this->Guide->GuideCategory->find('first', array(
                'conditions' => array(
                    'GuideCategory.slug' => $this->request->params['named']['category']
                ) ,
                'recursive' => -1
            ));
            if (empty($guideCategory)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Guide.guide_category_id'] = $guideCategory['GuideCategory']['id'];
            $this->pageTitle.= ' - ' . __l('Category') . ' - ' . $guideCategory['GuideCategory']['name'];
        }		
		$conditions[]['OR'] = array(
			'AND' => array(
				'Guide.user_id !=' => $this->Auth->user('id'),
				'Guide.is_published' => 1
			),
			'Guide.user_id' => $this->Auth->user('id'),
		);	
		if (isset($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'simple') {
            $limit = 10;
        }		
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Attachment',
                'User' => array(
                    'UserAvatar'
                ) ,
                'City',
                'GuideCategory',
                'GuideFollower' => array(
                    'conditions' => array(
                        'GuideFollower.user_id' => $this->Auth->user('id') ,
                    ) ,
                    'limit' => 1,
                ) ,
            ) ,
            'order' => $order,
            'limit' => $limit,
        );
        if (!empty($this->request->data['Guide']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Guide']['q']
            ));
        }
        $this->set('guides', $this->paginate());
        if (isset($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'simple') {
            $this->render('simple_index');
        }
        if (isset($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'featured') {
            $this->render('featured_index');
        }
    }
    public function view($slug = null)
    {
        $this->pageTitle = __l('Guide');
        $this->Guide->slug = $slug;
        if (empty($slug)) {
            throw new NotFoundException(__l('Invalid guide'));
        }
		$guide_detail = $this->Guide->find('first', array(
            'conditions' => array(
				'Guide.slug' => $slug
			),
			'fields' => array(
				'Guide.user_id',
				'Guide.is_published'
			)
        ));
		$conditions = array();
		if($guide_detail['Guide']['user_id'] != $this->Auth->user('id') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin && $guide_detail['Guide']['is_published'] != 1) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$conditions['Guide.slug'] = $slug;				
		if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
			$conditions['Guide.admin_suspend !='] = 1;			
		}		
        $guide = $this->Guide->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
                'Attachment',
                'User' => array(
                    'UserAvatar'
                ) ,
                'City',
                'GuideCategory',
                'GuideFollower' => array(
                    'conditions' => array(
                        'GuideFollower.user_id' => $this->Auth->user('id') ,
                    ) ,
                    'limit' => 1,
                ) ,
            )
        ));
        if (empty($guide)) {
			$this->Session->delete('Message.success');
			$this->Session->setFlash(__l('This guide is suspended by administrator') , 'default', null, 'error');
            throw new NotFoundException(__l('Invalid request'));
        }
        if (empty($guide['Attachment']['id'])) {
            $guide['Attachment']['id'] = array();
        }
		if (!empty($guide['Guide']['name'])) {
            Configure::write('meta.guide_name', $guide['Guide']['name']);
        }
		if (!empty($guide['Attachment']['id'])) {
            $image_options = array(
                'dimension' => 'small_large',
                'class' => '',
                'alt' => $guide['Guide']['name'],
                'title' => $guide['Guide']['name'],
                'type' => 'png'
            );
			getimagesize(Router::url('/', true) . $this->getImageUrl('Guide', $guide['Attachment'], $image_options));
            $guide_image = $this->getImageUrl('Guide', $guide['Attachment'], $image_options);
            Configure::write('meta.guide_image', $guide_image);
        }
        $this->Guide->GuideView->create();
        $this->request->data['GuideView']['user_id'] = $this->Auth->user('id');
        $this->request->data['GuideView']['guide_id'] = $guide['Guide']['id'];
        $this->request->data['GuideView']['ip_id'] = $this->Guide->GuideView->toSaveIp();
        $this->Guide->GuideView->save($this->request->data);
        $this->pageTitle.= ' - ' . $guide['Guide']['name'];
        $this->set('guide', $guide);
    }
    public function add()
    {
        $this->pageTitle = __l('Create a Guide');
        $this->Guide->Attachment->Behaviors->attach('ImageUpload', Configure::read('guide.file'));
        $ini_upload_error = 1;
        if (!empty($this->request->data)) {
            $this->Guide->create();
            $this->Guide->set($this->request->data);
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['Attachment']['filename']['name']) || (!Configure::read('guide.file.allowEmpty') && empty($this->request->data['Attachment']['id']))) {
                $this->Guide->Attachment->set($this->request->data);
            }
            if ($this->request->data['Attachment']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            if ($this->Guide->validates() && $ini_upload_error && $this->Guide->Attachment->validates()) {
                $this->request->data['Guide']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Guide->City->findOrSaveAndGetId($this->request->data['City']['name']);
                $this->Guide->save($this->request->data);
                $guide_id = $this->Guide->getLastInsertId();
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->Guide->Attachment->create();
                    $this->request->data['Attachment']['class'] = 'Guide';
                    $this->request->data['Attachment']['foreign_id'] = $guide_id;
                    $this->Guide->Attachment->save($this->request->data['Attachment']);
                }
                $this->Session->setFlash(__l('guide has been added') , 'default', null, 'success');
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $guide_id = $this->Guide->getLastInsertId();
                    $guide = $this->Guide->find('first', array(
                        'conditions' => array(
                            'Guide.id' => $guide_id,
                        ) ,
                        'fields' => array(
                            'Guide.id',
                            'Guide.slug',
                        ) ,
                        'recursive' => -1
                    ));
                    $this->redirect(array(
                        'controller' => 'guides',
                        'action' => 'view',
                        $guide['Guide']['slug']
                    ));
                }
            } else {
                if ($this->request->data['Attachment']['filename']['error'] == 1) {
                    $this->Guide->Attachment->validationErrors['filename'] = sprintf(__l('The file uploaded is too big, only files less than %s permitted') , ini_get('upload_max_filesize'));
                }
                $this->Session->setFlash(__l('guide could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if (isset($this->request->params['named']['category'])) {
            $guide_category = $this->Guide->GuideCategory->find('first', array(
                'conditions' => array(
                    'GuideCategory.slug' => $this->request->params['named']['category']
                ) ,
                'recursive' => -1
            ));
            if (!empty($guide_category)) {
                $this->request->data['Guide']['guide_category_id'] = $guide_category['GuideCategory']['id'];
            }
        }
        $guideCategories = $this->Guide->GuideCategory->find('list');
        $users = $this->Guide->User->find('list');
        $this->set(compact('guideCategories', 'users'));
    }
    public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Guide');
        $this->Guide->Attachment->Behaviors->attach('ImageUpload', Configure::read('guide.file'));
        $ini_upload_error = 1;
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Guide->id = $id;
        if (!$this->Guide->exists()) {
            throw new NotFoundException(__l('Invalid guide'));
        }
        if (!empty($this->request->data)) {
            $guide = $this->Guide->find('first', array(
                'conditions' => array(
                    'Guide.id' => $this->Guide->id
                ) ,
                'contain' => array(
                    'Attachment',
                )
            ));
            if (!empty($guide)) {
                $this->request->data['Guide']['id'] = $guide['Guide']['id'];
                if (!empty($guide['Attachment']['id'])) {
                    $this->request->data['Attachment']['id'] = $guide['Attachment']['id'];
                }
            }
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['Attachment']['filename']['name']) || (!Configure::read('guide.file.allowEmpty') && empty($this->request->data['Attachment']['id']))) {
                $this->Guide->Attachment->set($this->request->data);
            }
            if ($this->request->data['Attachment']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            $this->Guide->set($this->request->data);
            if ($this->Guide->validates() && $ini_upload_error && $this->Guide->Attachment->validates()) {
                $this->Guide->save($this->request->data);
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->Guide->Attachment->create();
                    $this->request->data['Attachment']['class'] = 'Guide';
                    $this->request->data['Attachment']['foreign_id'] = $this->request->data['Guide']['id'];
                    $this->Guide->Attachment->save($this->request->data['Attachment']);
                }
                $this->Session->setFlash(__l('guide has been updated') , 'default', null, 'success');
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $guide = $this->Guide->find('first', array(
                        'conditions' => array(
                            'Guide.id' => $this->request->data['Guide']['id'],
                        ) ,
                        'fields' => array(
                            'Guide.id',
                            'Guide.slug',
                        ) ,
                        'recursive' => -1
                    ));
                    $this->redirect(array(
                        'controller' => 'guides',
                        'action' => 'view',
                        $guide['Guide']['slug']
                    ));
                }
            } else {
                $this->Session->setFlash(__l('guide could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->Guide->find('first', array(
                'conditions' => array(
                    'Guide.id' => $id,
                ) ,
                'contain' => array(
                    'Attachment',
                    'City',
                ) ,
                'recursive' => 1
            ));
            if (empty($this->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->data['Guide']['name'];
        $guideCategories = $this->Guide->GuideCategory->find('list');
        $users = $this->Guide->User->find('list');
        $this->set(compact('guideCategories', 'users'));
    }
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'q',
            'filter_id',
        ));
        $conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            $this->request->data['Guide']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        $this->pageTitle = __l('Guides');
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Guide.created) <= '] = 0;
            $this->pageTitle.= __l(' - today');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Guide.created) <= '] = 7;
            $this->pageTitle.= __l('- in this week');
        }
        if (!empty($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Guide.created) <= '] = 30;
            $this->pageTitle.= __l(' - in this month');
        }
        if (!empty($this->request->data['Guide']['filter_id'])) {
            if ($this->request->data['Guide']['filter_id'] == ConstMoreAction::Published) {
                $conditions['Guide.is_published'] = 1;
                $this->pageTitle.= __l(' - Published ');
            } elseif ($this->request->data['Guide']['filter_id'] == ConstMoreAction::Unpublished) {
                $conditions['Guide.is_published'] = 0;
                $this->pageTitle.= __l(' - Unpublished ');
            } elseif ($this->request->data['Guide']['filter_id'] == ConstMoreAction::Featured) {
                $conditions['Guide.is_featured'] = 1;
                $this->pageTitle.= __l(' - Featured ');
            } elseif ($this->request->data['Guide']['filter_id'] == ConstMoreAction::Notfeatured) {
                $conditions['Guide.is_featured'] = 0;
                $this->pageTitle.= __l(' - Not Featured ');
            } elseif ($this->request->data['Guide']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Guide.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - System Flagged ');
            } elseif ($this->request->data['Guide']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Guide.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Admin Suspend ');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['Guide']['filter_id'];
        }
        if (isset($this->request->params['named']['guide_category'])) {
            $guideCategoryConditions = array(
                'GuideCategory.slug' => $this->request->params['named']['guide_category']
            );
            $guide_category = $this->{$this->modelClass}->GuideCategory->find('first', array(
                'conditions' => $guideCategoryConditions,
                'fields' => array(
                    'GuideCategory.id',
                    'GuideCategory.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($guide_category)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Guide.guide_category_id'] = $guide_category['GuideCategory']['id'];
            $this->pageTitle.= ' - ' . $guide_category['GuideCategory']['name'];
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['Guide']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
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
            $conditions['Guide.user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        $this->Guide->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Guide.id' => 'DESC'
            ) ,
        );
        if (!empty($this->request->data['Guide']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Guide']['q']
            ));
        }
        $this->set('guides', $this->paginate());
        $moreActions = $this->Guide->moreActions;
        $filters = $this->Guide->isFilterOptions;
        $this->set(compact('moreActions', 'filters'));
        $this->set('published', $this->Guide->find('count', array(
            'conditions' => array(
                'Guide.is_published = ' => 1,
            )
        )));
        $this->set('unpublished', $this->Guide->find('count', array(
            'conditions' => array(
                'Guide.is_published = ' => 0,
            )
        )));
        $this->set('featured', $this->Guide->find('count', array(
            'conditions' => array(
                'Guide.is_featured = ' => 1,
            )
        )));
        $this->set('notfeatured', $this->Guide->find('count', array(
            'conditions' => array(
                'Guide.is_featured = ' => 0,
            )
        )));
        $this->set('flagged', $this->Guide->find('count', array(
            'conditions' => array(
                'Guide.is_system_flagged = ' => 1,
            )
        )));
		$this->set('suspended', $this->Guide->find('count', array(
            'conditions' => array(
                'Guide.admin_suspend = ' => 1,
            )
        )));
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null)
    {
        $this->setAction('edit', $id);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Guide->id = $id;
        if (!$this->Guide->exists()) {
            throw new NotFoundException(__l('Invalid guide'));
        }
        if ($this->Guide->delete()) {
            $this->Session->setFlash(__l('Guide deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Guide was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function update()
    {
        if (!empty($this->request->params['named']['guide'])) {
            $guide = $this->Guide->find('first', array(
                'conditions' => array(
                    'Guide.slug' => $this->request->params['named']['guide'],
                    'Guide.user_id' => $this->Auth->user('id') ,
                    'Guide.is_published' => 0
                ) ,
                'fields' => array(
                    'Guide.id'
                ) ,
                'recursive' => -1
            ));
            if (!empty($guide)) {
                $this->request->data['Guide']['id'] = $guide['Guide']['id'];
                $this->request->data['Guide']['is_published'] = 1;
                $this->Guide->create();
                $this->Guide->save($this->request->data['Guide']);
                $this->Session->setFlash(__l('Guide published') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
