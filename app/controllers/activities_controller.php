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
class ActivitiesController extends AppController
{
    public $name = 'Activities';
    public function admin_feeds()
    {
        $this->pageTitle = __l('Recent Activities');
        $this->loadModel('User');
        $records[]['UserFollower'] = $this->User->UserFollower->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'UserFollower.modified' => 'DESC'
            )
        ));
        $records[]['User'] = $this->User->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'User.modified' => 'DESC'
            )
        ));
        $records[]['UserView'] = $this->User->UserView->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'UserView.modified' => 'DESC'
            )
        ));
		$records[]['UserProfile'] = $this->User->UserProfile->find('all', array(
			'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'UserProfile.modified' => 'DESC'
            )
        ));
        $records[]['PlaceFollower'] = $this->User->Review->Sighting->Place->PlaceFollower->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'PlaceFollower.modified' => 'DESC'
            )
        ));
        $records[]['BusinessFollower'] = $this->User->BusinessFollower->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'BusinessFollower.modified' => 'DESC'
            )
        ));
        $records[]['Guide'] = $this->User->Guide->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'Guide.modified' => 'DESC'
            )
        ));

        $records[]['GuideView'] = $this->User->GuideView->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'GuideView.modified' => 'DESC'
            )
        ));
        $records[]['GuideFollower'] = $this->User->GuideFollower->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'GuideFollower.modified' => 'DESC'
            )
        ));
        $records[]['Item'] = $this->User->Review->Sighting->Item->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'Item.modified' => 'DESC'
            )
        ));
        $records[]['ItemFollower'] = $this->User->ItemFollower->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'ItemFollower.modified' => 'DESC'
            )
        ));
        $records[]['Place'] = $this->User->PlaceFollower->Place->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'Place.modified' => 'DESC'
            )
        ));
        $records[]['PlaceView'] = $this->User->PlaceView->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'PlaceView.modified' => 'DESC'
            )
        ));
        $records[]['Review'] = $this->User->Review->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'Review.modified' => 'DESC'
            )
        ));
        $records[]['ReviewView'] = $this->User->ReviewView->find('all', array(
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'ReviewView.modified' => 'DESC'
            )
        ));
        $records[]['ReviewRating'] = $this->User->ReviewRating->find('all', array(
			'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'ReviewRating.modified' => 'DESC'
            )
        ));
		$records[]['Sighting'] = $this->User->Sighting->find('all', array(
			'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'Sighting.modified' => 'DESC'
            )
        ));
		$records[]['SightingRating'] = $this->User->SightingRating->find('all', array(
			'contain' => array(
				'Sighting' => array(
					'Item',
					'Place'
				) ,
				'SightingRatingType',
				'User'
			),
			'recursive' => 2,
            'limit' => 10,
            'order' => array(
                'SightingRating.modified' => 'DESC'
            )
        ));
		$records[]['SightingView'] = $this->User->SightingView->find('all', array(
			'contain' => array(
				'Sighting' => array(
					'Item',
					'Place'
				) ,
				'User'
			),
			'recursive' => 2,
            'limit' => 10,
            'order' => array(
                'SightingView.modified' => 'DESC'
            )
        ));
		$records[]['Business'] = $this->User->Business->find('all', array(
			'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'Business.modified' => 'DESC'
            )
        ));
		$records[]['BusinessUpdate'] = $this->User->Business->BusinessUpdate->find('all', array(
			'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'BusinessUpdate.modified' => 'DESC'
            )
        ));
		$records[]['BusinessView'] = $this->User->Business->BusinessView->find('all', array(
			'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'BusinessView.modified' => 'DESC'
            )
        ));
		$records[]['ReviewComment'] = $this->User->ReviewComment->find('all', array(
			'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'ReviewComment.modified' => 'DESC'
            )
        ));
		$records[]['SightingFlag'] = $this->User->SightingFlag->find('all', array(
			'contain' => array(
				'Sighting' => array(
					'Item',
					'Place'
				) ,
				'User'
			),
			'recursive' => 2,
            'limit' => 10,
            'order' => array(
                'SightingFlag.modified' => 'DESC'
            )
        ));
		foreach($records as $modelRecords):
            foreach($modelRecords as $modelname => $modelRecord):
                foreach($modelRecord as $key => $values):
		            if(!empty($modelRecord)){
					    $values['User']['username'] = empty($values['User']['username']) ? __l('Guest User') : $values['User']['username'];
						$activities[$values[$modelname]['modified']][][$modelname] = $values;
					}
                endforeach;
            endforeach;
        endforeach;
		krsort($activities);
        $textTemplate['UserFollower'] = '"<a href=\'$siteUrl"."user/" . $value[\'FollowerUser\'][\'username\'] . "\'>" . $value[\'FollowerUser\'][\'username\'] . "</a>" . "' . ' ' . __l('follows') . ' ' . '" . "<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>"';
        $textTemplate['PlaceFollower'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('follows') . ' ' . '" . "<a href=\'$siteUrl"."place/" . $value[\'Place\'][\'slug\'] . "\'>" . $value[\'Place\'][\'name\'] . "</a>"';
        $textTemplate['BusinessFollower'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('follows') . ' ' . '" . "<a href=\'$siteUrl"."business/" . $value[\'Business\'][\'slug\'] . "\'>" . $value[\'Business\'][\'name\'] . "</a>"';
        $textTemplate['Guide'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' -> ' . '" . "<a href=\'$siteUrl"."guide/" . $value[\'Guide\'][\'slug\'] . "\'>" . $value[\'Guide\'][\'name\'] . "</a>"';
        $textTemplate['GuideView'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('viewed') . ' ' . '" . "<a href=\'$siteUrl"."guide/" . $value[\'Guide\'][\'slug\'] . "\'>" . $value[\'Guide\'][\'name\'] . "</a>"';
        $textTemplate['GuideFollower'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('follows') . ' ' . '" . "<a href=\'$siteUrl"."guide/" . $value[\'Guide\'][\'slug\'] . "\'>" . $value[\'Guide\'][\'name\'] . "</a>"';
        $textTemplate['Item'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l(' updated ') . ' ' . '" . "<a href=\'$siteUrl"."sightings/item/" . $value[\'Item\'][\'slug\'] . "\'>" . $value[\'Item\'][\'name\'] . "</a>"';
        $textTemplate['ItemFollower'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('follows') . ' ' . '" . "<a href=\'$siteUrl"."sightings/item/" . $value[\'Item\'][\'slug\'] . "\'>" . $value[\'Item\'][\'name\'] . "</a>"';
        $textTemplate['Place'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' -> ' . '" . "<a href=\'$siteUrl"."place/" . $value[\'Place\'][\'slug\'] . "\'>" . $value[\'Place\'][\'name\'] . "</a>"';
        $textTemplate['PlaceView'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('viewed') . ' ' . '" . "<a href=\'$siteUrl"."place/" . $value[\'Place\'][\'slug\'] . "\'>" . $value[\'Place\'][\'name\'] . "</a>"';
        $textTemplate['Review'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('submitted review') . ' ' . '" . "<a href=\'$siteUrl"."review/" . $value[\'Review\'][\'id\'] . "\'>" . $value[\'Review\'][\'notes\'] . "</a>"';
        $textTemplate['ReviewView'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('viewed') . ' ' . '" . "<a href=\'$siteUrl"."review/" . $value[\'Review\'][\'id\'] . "\'>" . $value[\'Review\'][\'notes\'] . "</a>"';
        $textTemplate['ReviewRating'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l(' rated ') . ' ' . '" . "" . $value[\'ReviewRatingType\'][\'name\'] . " - ". "<a href=\'$siteUrl"."review/" . $value[\'Review\'][\'id\'] . "\'>" . $value[\'Review\'][\'notes\'] . "</a>"';
        $textTemplate['User'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('has joined'). ' ' . '" . "" . ""';
        $textTemplate['UserView'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('viewed') . ' ' . '" . "<a href=\'$siteUrl"."user/" . $value[\'ViewingUser\'][\'username\'] . "\'>" . $value[\'ViewingUser\'][\'username\'] . "</a>"';
        $textTemplate['SightingView'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('viewed') . ' ' . '" . "<a href=\'$siteUrl"."sighting/" . $value[\'Sighting\'][\'id\'] . "\'>" . $value[\'Sighting\'][\'Item\'][\'name\'] . "</a>"';
        $textTemplate['BusinessUpdate'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l(' updates ') . ' ' . '" . "<a href=\'$siteUrl"."business/" . $value[\'Business\'][\'slug\'] . "\'>" . $value[\'Business\'][\'name\'] . "</a> ".$value[\'BusinessUpdate\'][\'updates\']';
        $textTemplate['Business'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('updated') . ' ' . '" . "<a href=\'$siteUrl"."business/" . $value[\'Business\'][\'slug\'] . "\'>" . $value[\'Business\'][\'name\'] . "</a>"';
        $textTemplate['Sighting'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' -> ' . '" . "<a href=\'$siteUrl"."sighting/" . $value[\'Sighting\'][\'id\'] . "\'>" . $value[\'Item\'][\'name\'] . "</a>".  "' . ' ' . __l('@') . ' ' . '" .  "<a href=\'$siteUrl"."place/" . $value[\'Place\'][\'slug\'] . "\'>" . $value[\'Place\'][\'name\'] . "</a>"';
        $textTemplate['BusinessView'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('viewed') . ' ' . '" . "<a href=\'$siteUrl"."business/" . $value[\'Business\'][\'slug\'] . "\'>" . $value[\'Business\'][\'name\'] . "</a>"';
        $textTemplate['UserProfile'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('profile updated'). ' ' . '" . "" . ""';
		$textTemplate['SightingRating'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('rated') . ' ' . '" . $value[\'SightingRatingType\'][\'name\'] . " - ". "<a href=\'$siteUrl"."sighting/" . $value[\'Sighting\'][\'id\'] . "\'>" . $value[\'Sighting\'][\'Item\'][\'name\'] . "</a>".  "' . ' ' . __l('@') . ' ' . '" .  "<a href=\'$siteUrl"."place/" . $value[\'Sighting\'][\'Place\'][\'slug\'] . "\'>" . $value[\'Sighting\'][\'Place\'][\'name\'] . "</a>"';
		$textTemplate['ReviewComment'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('commented') . ' ' . '" . "<a href=\'$siteUrl"."review/" . $value[\'Review\'][\'id\'] . "\'>" . $value[\'ReviewComment\'][\'comment\'] . "</a>"';
		$textTemplate['SightingFlag'] = '"<a href=\'$siteUrl"."user/" . $value[\'User\'][\'username\'] . "\'>" . $value[\'User\'][\'username\'] . "</a>" . "' . ' ' . __l('flagged') . ' ' . '" . "<a href=\'$siteUrl"."sighting/" . $value[\'Sighting\'][\'id\'] . "\'>" . $value[\'Sighting\'][\'Item\'][\'name\'] . "</a>".  "' . ' ' . __l('@') . ' ' . '" .  "<a href=\'$siteUrl"."place/" . $value[\'Sighting\'][\'Place\'][\'slug\'] . "\'>" . $value[\'Sighting\'][\'Place\'][\'name\'] . "</a>"';
		$this->set('activities', $activities);
        $this->set('textTemplate', $textTemplate);
    }
}
?>