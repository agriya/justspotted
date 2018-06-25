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
class ReviewComment extends AppModel
{
    public $name = 'ReviewComment';
	var $actsAs = array(
        'SuspiciousWordsDetector' => array(
            'fields' => array(                
                'comment',
            )
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'review_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
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
            'counterCache' => false
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'review_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'user_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'comment' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'ip_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
        );
        $this->moreActions = array(
            ConstMoreAction::Suspend => __l('Suspend'),
            ConstMoreAction::Unsuspend => __l('Unsuspend'),
            ConstMoreAction::Flagged => __l('Flagged'),
            ConstMoreAction::Unflagged => __l('Clear Flag'),								
            ConstMoreAction::Delete => __l('Delete')
        );
    }
    function afterSave($created)
    {
		if($created) {
			$this->__updateUserPoint($this->data);
		}
    }
    function __updateUserPoint($data)
    {
		App::import('Model', 'UserPoint');
		$this->UserPoint = new UserPoint();
		$review = $this->Review->find('first', array(
			'conditions' => array(
				'Review.id' => $this->data['ReviewComment']['review_id']
			) ,
			'recursive' => -1,
		));
		$userpoint['UserPoint']['owner_user_id'] = $review['Review']['user_id'];
		$userpoint['UserPoint']['other_user_id'] = $this->data['ReviewComment']['user_id'];
		$userpoint['UserPoint']['foreign_id'] = $this->id;
		$userpoint['UserPoint']['model'] = 'ReviewComment';
		$userpoint['UserPoint']['point'] = Configure::read('tip_point.review_comment');
		$this->UserPoint->save($userpoint);
    }
    function beforeDelete()
    {
        $this->data = $this->find('first', array(
            'conditions' => array(
                'ReviewComment.id' => $this->id,
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
        $this->__removeUserPoint($this->data);
    }
    function __removeUserPoint($data)
    {
        App::import('Model', 'UserPoint');
        $this->UserPoint = new UserPoint();
        $this->UserPoint->deleteAll(array(
            'UserPoint.other_user_id' => $data['ReviewComment']['user_id'],
            'UserPoint.foreign_id' => $data['ReviewComment']['id'],
            'UserPoint.model' => 'ReviewComment'
        ));
    }
}

