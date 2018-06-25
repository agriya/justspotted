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
class UserPoint extends AppModel
{
    public $name = 'UserPoint';
    public $actsAs = array(
        'Polymorphic' => array(
            'classField' => 'class',
            'foreignKey' => 'foreign_id',
        )
    );
    public $belongsTo = array(
        'OwnerUser' => array(
            'className' => 'User',
            'foreignKey' => 'owner_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'OtherUser' => array(
            'className' => 'User',
            'foreignKey' => 'other_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'ReviewRating' => array(
            'className' => 'ReviewRating',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'SightingRating' => array(
            'className' => 'SightingRating',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'ReviewComment' => array(
            'className' => 'ReviewComment',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'UserFollower' => array(
            'className' => 'UserFollower',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
    );
    //$validate set in __construct for multi-language support
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'owner_user_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'model' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'foreign_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
        );
		
		$this->moreActions = array(
            ConstMoreAction::Delete => __l('Delete')
        );
    }
    function afterSave($created)
    {
        $this->__updateUserTipPoints($this->data);
    }
    function beforeDelete()
    {
        $this->data = $this->find('first', array(
            'conditions' => array(
                'UserPoint.id' => $this->id,
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
        $this->__updateUserTipPoints($this->data);
    }
    function __updateUserTipPoints($data)
    {
        if (!empty($data['UserPoint']['owner_user_id'])) {
            $userPoint = $this->find('first', array(
                'conditions' => array(
                    'UserPoint.owner_user_id' => $data['UserPoint']['owner_user_id']
                ) ,
                'fields' => array(
                    'SUM(point) as tip_points'
                ) ,
                'recursive' => -1
            ));
            $userData['User']['id'] = $data['UserPoint']['owner_user_id'];
            $userData['User']['tip_points'] = $userPoint[0]['tip_points'];
            $this->UserFollower->User->save($userData);
        }
    }
}
