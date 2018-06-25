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
App::import('Core', 'Helper');

class AppHelper extends Helper
{
    public function getUserAvatar($user_id)
    {
        App::import('Model', 'User');
        $modelObj = new User();
        $user = $modelObj->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
            ) ,
            'fields' => array(
                'UserAvatar.id',
                'UserAvatar.dir',
                'UserAvatar.filename'
            ) ,
            'recursive' => 0
        ));
        return $user['UserAvatar'];
    }
    public function checkForPrivacy($type = null, $field_to_check = null, $logged_in_user = null, $username = null, $is_boolean = false)
    {
        App::import('Model', 'UserPermissionPreference');
        $privacy_model_obj = new UserPermissionPreference();
        // check is logged in user is admin
        if ($logged_in_user) {
            $logged_in_user_type = $privacy_model_obj->User->find('first', array(
                'conditions' => array(
                    'User.id' => $logged_in_user,
                ) ,
                'fields' => array(
                    'User.user_type_id'
                ) ,
                'recursive' => -1
            ));
            // no restrictions for admin
            if ($logged_in_user_type['User']['user_type_id'] == ConstUserTypes::Admin) return true;
        }
        $privacy = $privacy_model_obj->getUserPrivacySettings($username);
        $is_show = true;
        if (Configure::read($type . '-' . $field_to_check)) {
            if ($privacy['UserPermissionPreference'][$type . '-' . $field_to_check] == ConstPrivacySetting::Users and !$logged_in_user) {
                $is_show = false;
            } else if ($privacy['UserPermissionPreference'][$type . '-' . $field_to_check] == ConstPrivacySetting::Nobody) {
                $is_show = false;
            } else if ($privacy['UserPermissionPreference'][$type . '-' . $field_to_check] == ConstPrivacySetting::Friends) {
                // To write user friends lists in config
                App::import('Model', 'UserFriend');
                $user_friend_obj = new UserFriend();
                $is_show = $user_friend_obj->checkIsFriend($logged_in_user, $username);
            } else if ($is_boolean) {
                $is_show = $privacy['UserPermissionPreference'][$type . '-' . $field_to_check];
            }
        } else {
            $is_show = false;
        }
        return $is_show;
    }
    public function checkForVideoPrivacy($type = null, $field_value = null, $logged_in_user = null, $username = null, $is_boolean = false)
    {
        $is_show = true;
        if ($field_value == ConstPrivacySetting::Users and !$logged_in_user) {
            $is_show = false;
        } else if ($field_value == ConstPrivacySetting::Nobody) {
            $is_show = false;
        } else if ($field_value == ConstPrivacySetting::Friends) {
            // To write user friends lists in config
            App::import('Model', 'UserFriend');
            $user_friend_obj = new UserFriend();
            $is_show = $user_friend_obj->checkIsFriend($logged_in_user, $username);
        } else if ($is_boolean) {
            $is_show = $privacy['UserPermissionPreference'][$type . '-' . $field_to_check];
        }
        return $is_show;
    }
    function getFacebookAvatar($fbuser_id, $height = 35, $width = 35)
    {
        return $this->image("http://graph.facebook.com/{$fbuser_id}/picture", array(
            'height' => $height,
            'width' => $width
        ));
    }
    function getUserAvatarLink($user_details, $dimension = 'medium_thumb', $is_link = true)
    {
        App::import('Model', 'Setting');
        $this->Setting = new Setting();
        App::import('Model', 'User');
        $modelObj = new User();
        $user = $modelObj->find('first', array(
            'conditions' => array(
                'User.id' => $user_details['id'],
            ) ,
            'fields' => array(
                'UserAvatar.id',
                'UserAvatar.dir',
                'UserAvatar.filename',
                'UserAvatar.height',
                'UserAvatar.width',
                'User.profile_image_id',
                'User.twitter_avatar_url',
                'User.fb_user_id',
                'User.username',
                'User.id',
            ) ,
            'recursive' => 0
        ));
        if ($user_details['user_type_id'] == ConstUserTypes::Admin || $user_details['user_type_id'] == ConstUserTypes::User) {
            $user_image = '';
            // Setting Default Profile Image //
            $width = $this->Setting->find('first', array(
                'conditions' => array(
                    'Setting.name' => 'thumb_size.' . $dimension . '.width'
                ) ,
                'recursive' => -1
            ));
            $height = $this->Setting->find('first', array(
                'conditions' => array(
                    'Setting.name' => 'thumb_size.' . $dimension . '.height'
                ) ,
                'recursive' => -1
            ));
            if (!empty($user['User']['fb_user_id'])) {
                $user_image = $this->getFacebookAvatar($user['User']['fb_user_id'], $height['Setting']['value'], $width['Setting']['value']);
            } elseif (!empty($user['User']['twitter_avatar_url'])) {
                $user_image = $this->image($user['User']['twitter_avatar_url'], array(
                    'title' => $this->cText($user['User']['username'], false) ,
                    'width' => $width['Setting']['value'],
                    'height' => $height['Setting']['value']
                ));
            }
            // Setting Profile Image based on settings choosed by user //
            if ($user['User']['profile_image_id'] == ConstProfileImage::Twitter) {
                $user_image = $this->image($user['User']['twitter_avatar_url'], array(
                    'title' => $this->cText($user['User']['username'], false) ,
                    'width' => $width['Setting']['value'],
                    'height' => $height['Setting']['value']
                ));
            } elseif ($user['User']['profile_image_id'] == ConstProfileImage::Facebook) {
                $width = $this->Setting->find('first', array(
                    'conditions' => array(
                        'Setting.name' => 'thumb_size.' . $dimension . '.width'
                    ) ,
                    'recursive' => -1
                ));
                $height = $this->Setting->find('first', array(
                    'conditions' => array(
                        'Setting.name' => 'thumb_size.' . $dimension . '.height'
                    ) ,
                    'recursive' => -1
                ));
                $user_image = $this->getFacebookAvatar($user['User']['fb_user_id'], $height['Setting']['value'], $width['Setting']['value']);
            } elseif ($user['User']['profile_image_id'] == ConstProfileImage::Upload || empty($user_image)) {
                //get user image
                $user_image = $this->showImage('UserAvatar', (!empty($user_details['UserAvatar'])) ? $user_details['UserAvatar'] : '', array(
                    'dimension' => $dimension,
                    'alt' => sprintf('[Image: %s]', $user_details['username']) ,
                    'title' => $user_details['username']
                ));
            }
            //return image to user
            return (!$is_link) ? $user_image : $this->link($user_image, array(
                'controller' => 'users',
                'action' => 'view',
                $user_details['username'],
                'admin' => false
            ) , array(
                'title' => $this->cText($user_details['username'], false) ,
                'escape' => false
            ));
        }
    }
    public function get_links($tag)
    {
        App::import('Model', 'PageTag');
        $pageTagObj = new PageTag();
        $pagesTag = $pageTagObj->find('first', array(
            'conditions' => array(
                'PageTag.name' => $tag
            ) ,
            'fields' => array(
                'PageTag.name'
            ) ,
            'contain' => array(
                'Page' => array(
                    'fields' => array(
                        'Page.title',
                        'Page.slug'
                    ) ,
                    'order' => array(
                        'PagesPageTag.display_order' => 'asc'
                    )
                )
            )
        ));
        $pages = array();
        $str = '';
        if (!empty($pagesTag['Page'])) {
            foreach($pagesTag['Page'] as $page):
                $str.= '<li>' . $this->link($this->cText($page['title'], false) , array(
                    'controller' => 'pages',
                    'action' => 'view',
                    $page['slug']
                ) , array(
                    'title' => $page['title']
                )) . '</li>';
            endforeach;
        }
        return $str;
    }
    public function getLanguage()
    {
        App::import('Model', 'Translation');
        $this->Translation = new Translation();
        $languages = $this->Translation->find('all', array(
            'conditions' => array(
                'Language.id !=' => 0
            ) ,
            'fields' => array(
                'DISTINCT(Translation.language_id)',
                'Language.name',
                'Language.iso2'
            ) ,
            'order' => array(
                'Language.name' => 'ASC'
            )
        ));
        $languageList = array();
        if (!empty($languages)) {
            foreach($languages as $language) {
                $languageList[$language['Language']['iso2']] = $language['Language']['name'];
            }
        }
        return $languageList;
    }
    public function isAutoSuspendEnabled($model)
    {
        if (Configure::read('suspicious_detector.is_enabled') && Configure::read('suspicious_detector.auto_suspend_' . $model . '_on_system_flag')) {
            return 1;
        } else {
            return 0;
        }
    }
    public function formGooglemap($placedetails = array() , $size = '320x320', $type = 'static')
    {
        if ($type == 'static') {
            if ((!(is_array($placedetails))) || empty($placedetails)) {
                return false;
            }
            $mapurl = 'http://maps.google.com/maps/api/staticmap?center=';
            $mapcenter[] = str_replace(' ', '+', $placedetails['latitude']) . ',' . $placedetails['longitude'];
            $mapcenter[] = 'zoom=' . (!empty($placedetails['zoom_level']) ? $placedetails['zoom_level'] : 8);
            $mapcenter[] = 'size=' . $size;
            $mapcenter[] = 'markers=color:pink|label:M|' . $placedetails['latitude'] . ',' . $placedetails['longitude'];
            $mapcenter[] = 'sensor=false';
            return $mapurl . implode('&amp;', $mapcenter);
        } else {
            $map_size = explode('x', $size);
            $embeddmapurl[] = 'http://maps.google.com/maps?f=q&amp;hl=en&amp;geocode=;';
            $address = !empty($placedetails['address2']) ? $placedetails['address2'] : '';
            //$address.= !empty($placedetails['city']) ? $placedetails['city'] . '+' : '';
            //$address.= !empty($placedetails['state']) ? $placedetails['state'] . '+' : '';
            //$address.= !empty($placedetails['country']) ? $placedetails['country'] . '+' : '';
            $embeddmapurl[] = 'q=' . $address;
            $embeddmapurl[] = 'll=' . str_replace(' ', '+', $placedetails['latitude']) . ',' . $placedetails['longitude'];
            $embeddmapurl[] = 'z=' . (!empty($placedetails['map_zoom_level']) ? $placedetails['map_zoom_level'] : 8);
            $embeddmapurl[] = 'output=embed';
            $embeddmapurl = implode('&amp;', $embeddmapurl);
            $embbedd = "<iframe width='" . $map_size['0'] . "' height='" . $map_size['1'] . "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='" . $embeddmapurl . "'></iframe>";
            return $embbedd;
        }
    }
    function notificationDescription($userPoint)
    {
        $str = '';
        if ($userPoint['UserPoint']['model'] == 'ReviewRating') {
            $str = ' ' . __l('says,') . ' ' . '"' . $userPoint['ReviewRating']['ReviewRatingType']['name'] . '!" ' . __l('about') . ' ' . $this->link($this->cText($userPoint['ReviewRating']['Review']['Sighting']['Item']['name'], false) . " @ " . $this->cText($userPoint['ReviewRating']['Review']['Sighting']['Place']['name'], false) , array(
                'controller' => 'reviews',
                'action' => 'view',
                $userPoint['ReviewRating']['Review']['id']
            ) , array(
                'title' => $this->cText($userPoint['ReviewRating']['Review']['Sighting']['Item']['name'], false) . " @ " . $this->cText($userPoint['ReviewRating']['Review']['Sighting']['Place']['name'], false)
            ));
        } else if ($userPoint['UserPoint']['model'] == 'SightingRating') {
            $str = $userPoint['SightingRating']['SightingRatingType']['name'] . ' ' . $this->link($this->cText($userPoint['SightingRating']['Sighting']['Item']['name'], false) . " @ " . $this->cText($userPoint['SightingRating']['Sighting']['Place']['name'], false) , array(
                'controller' => 'sightings',
                'action' => 'view',
                $userPoint['SightingRating']['Sighting']['id']
            ) , array(
                'title' => $this->cText($userPoint['SightingRating']['Sighting']['Item']['name'], false) . " @ " . $this->cText($userPoint['SightingRating']['Sighting']['Place']['name'], false)
            )) . ' ' . __l('based on your sighting');
        } else if ($userPoint['UserPoint']['model'] == 'ReviewComment') {
            $str = ' ' . __l('commented on') . ' ' . $this->link($this->cText($userPoint['ReviewComment']['Review']['Sighting']['Item']['name'], false) . " @ " . $this->cText($userPoint['ReviewComment']['Review']['Sighting']['Place']['name'], false) , array(
                'controller' => 'reviews',
                'action' => 'view',
                $userPoint['ReviewComment']['Review']['id']
            ) , array(
                'title' => $this->cText($userPoint['ReviewComment']['Review']['Sighting']['Item']['name'], false) . " @ " . $this->cText($userPoint['ReviewComment']['Review']['Sighting']['Place']['name'], false)
            ));
        } else if ($userPoint['UserPoint']['model'] == 'UserFollower') {
            $str = ' ' . __l('started following you!');
        }  else if ($userPoint['UserPoint']['model'] == 'Sighting') {
            $str = ' ' . __l('New sighting has been added!');
		}
        return $str;
    }
}
?>