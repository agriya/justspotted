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
class AppModel extends Model
{
    public $actsAs = array(
        'Containable',
    );
    public function beforeSave($options = array())
    {
        $this->useDbConfig = 'master';
        return true;
    }
    public function afterSave($created)
    {
        $this->useDbConfig = 'default';
        return true;
    }
    public function beforeDelete($cascade = true)
    {
        $this->useDbConfig = 'master';
        return true;
    }
    public function afterDelete()
    {
        $this->useDbConfig = 'default';
        return true;
    }
    public function findOrSaveAndGetId($data)
    {
        $findExist = $this->find('first', array(
            'conditions' => array(
                'name' => $data
            ) ,
            'fields' => array(
                'id'
            ) ,
            'recursive' => -1
        ));
        if (!empty($findExist)) {
            return $findExist[$this->name]['id'];
        } else {
			$this->create();
			if($this->name == 'Item' || $this->name == 'Place'){
                $this->data[$this->name]['user_id']= $_SESSION['Auth']['User']['id'];
            }
            $this->data[$this->name]['name'] = $data;
            $this->save($this->data[$this->name]);
            return $this->id;
        }
    }
    public function _isValidCaptcha()
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new Securimage();
        return $img->check($this->data[$this->name]['captcha']);
    }
    public function toSaveIp()
    {
		App::import('Model', 'Ip');
		$this->Ip = new Ip();
		$this->data['Ip']['ip'] = RequestHandlerComponent::getClientIP();
		$this->data['Ip']['host'] = RequestHandlerComponent::getReferer();
        $ip = $this->Ip->find('first', array(
            'conditions' => array(
                'Ip.ip' => $this->data['Ip']['ip']
            ) ,
            'fields' => array(
                'Ip.id'
            ) ,
            'recursive' => -1
        ));
		if (empty($ip)) {
			if (!empty($_COOKIE['_geo'])) {
				$_geo = explode('|', $_COOKIE['_geo']);
				$country = $this->Ip->Country->find('first', array(
					'conditions' => array(
						'Country.iso2' => $_geo[0]
					) ,
					'fields' => array(
						'Country.id'
					) ,
					'recursive' => -1
				));
				if (empty($country)) {
					$this->data['Ip']['country_id'] = 0;
				} else {
					$this->data['Ip']['country_id'] = $country['Country']['id'];
				}
				$state = $this->Ip->State->find('first', array(
					'conditions' => array(
						'State.name' => $_geo[1]
					) ,
					'fields' => array(
						'State.id'
					) ,
					'recursive' => -1
				));
				if (empty($state)) {
					$this->data['State']['name'] = $_geo[1];
					$this->Ip->State->create();
					$this->Ip->State->save($this->data['State']);
					$this->data['Ip']['state_id'] = $this->Ip->getLastInsertId();
				} else {
					$this->data['Ip']['state_id'] = $state['State']['id'];
				}
				$city = $this->Ip->City->find('first', array(
					'conditions' => array(
						'City.name' => $_geo[2]
					) ,
					'fields' => array(
						'City.id'
					) ,
					'recursive' => -1
				));
				if (empty($city)) {
					$this->data['City']['name'] = $_geo[2];
					$this->Ip->City->create();
					$this->Ip->City->save($this->data['City']);
					$this->data['Ip']['city_id'] = $this->Ip->City->getLastInsertId();
				} else {
					$this->data['Ip']['city_id'] = $city['City']['id'];
				}
				$this->data['Ip']['latitude'] = $_geo[3];
				$this->data['Ip']['longitude'] = $_geo[4];
			}
			$this->Ip->create();
			$this->Ip->save($this->data['Ip']);
			return $this->Ip->getLastInsertId();
		} else {
	        return $ip['Ip']['id'];
		}
	}
	public function changeFromEmail($from_address = null)
    {
        if (!empty($from_address)) {
            if (preg_match('|<(.*)>|', $from_address, $matches)) {
                return $matches[1];
            } else {
                return $from_address;
            }
        }
    }
    public function formatToAddress($user = null)
    {
        if (!empty($user['UserProfile']['first_name']) && !empty($user['UserProfile']['last_name'])) {
            return $user['UserProfile']['first_name'] . ' ' . $user['UserProfile']['first_name'] . ' <' . $user['User']['email'] . '>';
        } elseif (!empty($user['UserProfile']['first_name'])) {
            return $user['UserProfile']['first_name'] . ' <' . $user['User']['email'] . '>';
        } else {
            return $user['User']['email'];
        }
    }
    public function getImageUrl($model, $attachment, $options, $path = 'absolute')
    {
        $default_options = array(
            'dimension' => 'original',
            'class' => '',
            'alt' => 'alt',
            'title' => 'title',
            'type' => 'jpg'
        );
        $options = array_merge($default_options, $options);
        $image_hash = $options['dimension'] . '/' . $model . '/' . $attachment['id'] . '.' . md5(Configure::read('Security.salt') . $model . $attachment['id'] . $options['type'] . $options['dimension'] . Configure::read('site.name')) . '.' . $options['type'];
        if ($path == 'absolute') return Cache::read('site_url_for_shell', 'long') . 'img/' . $image_hash;
        else return 'img/' . $image_hash;
    }
	public function _readyMailSend($template, $data){
		App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
		App::import('Core', 'ComponentCollection');
		$collection = new ComponentCollection();
		App::import('Component', 'Email');
		$this->Email = new EmailComponent($collection);		
		$check_mail_send = $this->_checkUserNotifications($data['to_userid'], $data['mail_notification_id']); // Checking whether user willing to receive mail or not //		
		if(!empty($check_mail_send)){
			$template = $this->EmailTemplate->selectTemplate($template);
			$emailFindReplace = array(
				'##USERNAME##' => $data['to_username'],
				'##OTHER_USER##' => $data['other_username'],			
				'##ITEM_NAME##' => (!empty($data['item_name']) ? $data['item_name'] : ''),
				'##PLACE_NAME##' => (!empty($data['place_name']) ? $data['place_name'] : ''),			
				'##COMMENT##' => (!empty($data['comment_message']) ? $data['comment_message'] : '') ,
				'##RATING_TYPE##' => (!empty($data['rating_type']) ? $data['rating_type'] : '') ,
				'##SITE_NAME##' => Configure::read('site.name') ,
				'##SITE_URL##' => Router::url('/', true) ,
				'##FOLLOW_LINK##' => Router::url('/', true) ,
			);
			if(!empty($data['review_id'])){
				$emailFindReplace['##REVIEW_LINK##'] = Router::url(array(
					'controller' => 'reviews',
					'action' => 'view',
					'admin' => false,
					$data['review_id'],
				) , true);
			}
			if(!empty($data['follow_user'])){
				$emailFindReplace['##FOLLOW_LINK##'] = Router::url(array(
					'controller' => 'user_followers',
					'action' => 'add',
					'admin' => false,
					'user' => $data['follow_user'],
				) , true);
			}
			$message = strtr($template['email_content'], $emailFindReplace);
			$subject = strtr($template['subject'], $emailFindReplace);		
			
			// Send e-mail to users
			$this->Email->from = (!empty($template['from']) && ($template['from'] == '##FROM_EMAIL##')) ? Configure::read('EmailTemplate.from_email') : $template['from'];
			$this->Email->replyTo = (!empty($template['from']) && $template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
			$this->Email->to = $data['to_email'];
			$this->Email->subject = $subject;
			$this->Email->send($message);
		}
	}
	function _checkUserNotifications($to_user_id, $mail_notification_id)
    {
        App::import('Model', 'UserNotification');
        $this->UserNotification = new UserNotification();
        $conditions = array();
        $notification_check_array = array(
            '1' => 'is_receive_comment',
            '2' => 'is_receive_followers',
            '3' => 'is_receive_compliment',
        );        
        if (!empty($to_user_id)) {
            $check_notifications = $this->UserNotification->find('first', array(
                'conditions' => array(
                    'UserNotification.user_id' => $to_user_id
                ) ,
                'recursive' => -1
            ));
            if (!empty($check_notifications)) {
                $conditions['UserNotification.user_id'] = $to_user_id;
				if (isset($notification_check_array[$mail_notification_id])) {
					$conditions["UserNotification.$notification_check_array[$mail_notification_id]"] = '1';
				}                
                $check_send_mail = $this->UserNotification->find('first', array(
                    'conditions' => $conditions,
                    'recursive' => -1
                ));
                if (!empty($check_send_mail)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    }
    function loginType($user_agent)
    {
        $user_login_type_id = ConstThrough::Site;
        if (stripos($user_agent, 'iPhone') === true) {
            $user_login_type_id = ConstThrough::iPhone;
        } elseif (stripos($user_agent, 'Android') === true) {
            $user_login_type_id = ConstThrough::Android;
        }
        return $user_login_type_id;
    }
}
?>