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
class UserPreferenceCategoriesController extends AppController
{
    public $name = 'UserPreferenceCategories';
    public function admin_index()
    {
        $this->pageTitle = __l('User Preference Categories');
        $this->UserPreferenceCategory->recursive = 0;
        $this->set('userPreferenceCategories', $this->paginate());
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit User Preference Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->UserPreferenceCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('User Preference Category has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('User Preference Category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->UserPreferenceCategory->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['UserPreferenceCategory']['name'];
    }
}
?>