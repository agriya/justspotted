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
class ReviewRating extends AppModel
{
    public $name = 'ReviewRating';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'review_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'ReviewRatingType' => array(
            'className' => 'ReviewRatingType',
            'foreignKey' => 'review_rating_type_id',
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
            'review_rating_type_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
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
            ConstMoreAction::Delete => __l('Delete') ,
        );
    }
    function afterSave($created)
    {
        if ($created) {
            $this->__updateRating($this->data);
            $this->__updateUserPoint($this->data);
        }
    }
    function __updateUserPoint($data)
    {
        if (!empty($data['ReviewRating']['review_id']) && !empty($data['ReviewRating']['review_rating_type_id'])) {
            $review_rating_type = $this->ReviewRatingType->find('first', array(
                'conditions' => array(
                    'ReviewRatingType.id' => $data['ReviewRating']['review_rating_type_id']
                ) ,
                'recusive' => -1
            ));
            if (!empty($review_rating_type['ReviewRatingType']['tip_points'])) {
                App::import('Model', 'UserPoint');
                $this->UserPoint = new UserPoint();
                $review = $this->Review->find('first', array(
                    'conditions' => array(
                        'Review.id' => $data['ReviewRating']['review_id']
                    ) ,
                    'recursive' => -1,
                ));
                $userpoint['UserPoint']['owner_user_id'] = $review['Review']['user_id'];
                $userpoint['UserPoint']['other_user_id'] = $data['ReviewRating']['user_id'];
                $userpoint['UserPoint']['foreign_id'] = $this->id;
                $userpoint['UserPoint']['model'] = 'ReviewRating';
                $userpoint['UserPoint']['point'] = $review_rating_type['ReviewRatingType']['tip_points'];
                $this->UserPoint->save($userpoint);
            }
        }
    }
    function __updateRating($data)
    {
        // insert/update ReviewRatingStat
        $rating_count = $this->find('count', array(
            'conditions' => array(
                'ReviewRating.review_id' => $data['ReviewRating']['review_id'],
                'ReviewRating.review_rating_type_id' => $data['ReviewRating']['review_rating_type_id']
            )
        ));
        $rating_stat = $this->Review->ReviewRatingStat->find('first', array(
            'conditions' => array(
                'ReviewRatingStat.review_id' => $data['ReviewRating']['review_id'],
                'ReviewRatingStat.review_rating_type_id' => $data['ReviewRating']['review_rating_type_id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($rating_stat)) {
            $this->data['ReviewRatingStat']['id'] = $rating_stat['ReviewRatingStat']['id'];
        } else {
            $this->Review->ReviewRatingStat->create();
        }
        $this->data['ReviewRatingStat']['review_id'] = $data['ReviewRating']['review_id'];
        $this->data['ReviewRatingStat']['review_rating_type_id'] = $data['ReviewRating']['review_rating_type_id'];
        $this->data['ReviewRatingStat']['count'] = $rating_count;
        $this->Review->ReviewRatingStat->save($this->data['ReviewRatingStat']);
        // insert/update UserReviewRatingStat
        $user_rating_count = $this->find('count', array(
            'conditions' => array(
                'ReviewRating.user_id' => $data['ReviewRating']['user_id'],
                'ReviewRating.review_rating_type_id' => $data['ReviewRating']['review_rating_type_id']
            )
        ));
        $user_rating_stat = $this->ReviewRatingType->UserReviewRatingStat->find('first', array(
            'conditions' => array(
                'UserReviewRatingStat.user_id' => $data['ReviewRating']['user_id'],
                'UserReviewRatingStat.review_rating_type_id' => $data['ReviewRating']['review_rating_type_id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($user_rating_stat)) {
            $this->data['UserReviewRatingStat']['id'] = $user_rating_stat['UserReviewRatingStat']['id'];
        } else {
            $this->ReviewRatingType->UserReviewRatingStat->create();
        }
        $this->data['UserReviewRatingStat']['user_id'] = $data['ReviewRating']['user_id'];
        $this->data['UserReviewRatingStat']['review_rating_type_id'] = $data['ReviewRating']['review_rating_type_id'];
        $this->data['UserReviewRatingStat']['count'] = $user_rating_count;
        $this->ReviewRatingType->UserReviewRatingStat->save($this->data['UserReviewRatingStat']);
    }
    function beforeDelete()
    {
        $this->data = $this->find('first', array(
            'conditions' => array(
                'ReviewRating.id' => $this->id,
            ) ,
            'recursive' => -1
        ));
        if (!empty($this->data)) return true;
        else return false;
    }
    function afterDelete()
    {
        $this->__updateRating($this->data);
        $this->__removeUserPoint($this->data);
    }
    function __removeUserPoint($data)
    {
        App::import('Model', 'UserPoint');
        $this->UserPoint = new UserPoint();
        $this->UserPoint->deleteAll(array(
            'UserPoint.other_user_id' => $data['ReviewRating']['user_id'],
            'UserPoint.foreign_id' => $data['ReviewRating']['id'],
            'UserPoint.model' => 'ReviewRating'
        ));
    }
}
