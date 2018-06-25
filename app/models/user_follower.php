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
class UserFollower extends AppModel
{
    public $name = 'UserFollower';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'FollowerUser' => array(
            'className' => 'User',
            'foreignKey' => 'follower_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->moreActions = array(
            ConstMoreAction::Delete => __l('Delete')
        );
    }
    function afterSave($created)
    {
        if ($created) {
            $this->__updateUserPoint($this->data);
        }
    }
    function __updateUserPoint($data)
    {
        App::import('Model', 'UserPoint');
        $this->UserPoint = new UserPoint();
        $userpoint['UserPoint']['owner_user_id'] = $data['UserFollower']['user_id'];
        $userpoint['UserPoint']['other_user_id'] = $data['UserFollower']['follower_user_id'];
        $userpoint['UserPoint']['foreign_id'] = $this->id;
        $userpoint['UserPoint']['model'] = 'UserFollower';
        $userpoint['UserPoint']['point'] = Configure::read('tip_point.user_follows');
        $this->UserPoint->save($userpoint);
    }
    function beforeDelete()
    {
        $this->data = $this->find('first', array(
            'conditions' => array(
                'UserFollower.id' => $this->id,
            ) ,
            'recursive' => -1
        ));
        if (!empty($this->data)) {
            return true;
        } else {
            return false;
        }
    }
    function afterDelete()
    {
        App::import('Model', 'UserPoint');
        $this->UserPoint = new UserPoint();
        $this->UserPoint->deleteAll(array(
            'UserPoint.foreign_id' => $this->data['UserFollower']['id'],
            'UserPoint.model' => 'UserFollower'
        ));
    }
}
