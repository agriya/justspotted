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
class ConstUserTypes
{
    const Admin = 1;
    const User = 2;
}
class ConstThrough
{
    const Site = 1;
    const iPhone = 2;
	const Android = 3;
}

class ConstAttachment
{
    const UserAvatar = 1;
    const PhotoAlbum = 2;
    const Photo = 3;
    const Guide = 23;
    const Business = 85;
}
class ConstFriendRequestStatus
{
    const Pending = 1;
    const Approved = 2;
    const Reject = 3;
}
class ConstMessageFolder
{
    const Inbox = 1;
    const SentMail = 2;
    const Drafts = 3;
    const Spam = 4;
    const Trash = 5;
}
class ConstUserFriendStatus
{
    const Pending = 1;
    const Approved = 2;
    const Rejected = 3;
}
// setting for one way and two way friendships
class ConstUserFriendType
{
    const IsTwoWay = true;
}
// Setting for privacy settings
class ConstPrivacySetting
{
    const EveryOne = 1;
    const Users = 2;
    const Friends = 3;
    const Nobody = 4;
}
class ConstMoreAction
{
    const Inactive = 1;
    const Active = 2;
    const Delete = 3;
    const OpenID = 4;
    const Export = 5;
	const Approved = 6;
    const Disapproved = 7;
    const Featured = 8;
    const Notfeatured = 9;
    const Site = 10;
    const Twitter = 11;
    const Facebook = 12;
	const Gmail = 13;
	const Yahoo = 14;
    const Suspend = 15;
	const Unsuspend = 16;
	const Flagged = 17;
	const Unflagged = 18;
	const Normal = 19;
	const Checked = 20;
	const Unchecked = 21;
	const Published = 22;
	const Unpublished = 23;
	const Business = 24;
	const UserFlagged = 25;
	const Foursquare = 26;
	
}
// Banned ips types
class ConstBannedTypes
{
    const SingleIPOrHostName = 1;
    const IPRange = 2;
    const RefererBlock = 3;
}
// Banned ips durations
class ConstBannedDurations
{
    const Permanent = 1;
    const Days = 2;
    const Weeks = 3;
}
class ConstURLFilter
{
    const Commented = 'commented';
    const Flagged = 'flagged';
    const Viewed = 'viewed';
    const Favorited = 'favorited';
    const Rated = 'rated';
    const Downloaded = 'downloaded';
}
class ConstBusinessRequests
{
   const Pending = 0;
   const Accepted = 1;
   const Rejected = 2;
}
class ConstPlaceClaimRequests
{
   const Pending = 0;
   const Approved = 1;
   const Rejected = 2;
}
class ConstFileExt
{
    const Flv = 'flv';
    const Jpeg = 'jpeg';
    const Gif = 'gif';
    const Bmp = 'bmp';
    const Png = 'png';
}
class ConstUploadedVia
{
    const File = 1;
    const Record = 2;
    const Embed = 3;
}
class ConstLanguageID
{
    const English = 42;
}
class ConstVideoViewType
{
	const NormalView = 1;
	const EmbedView = 2;
	const EmbedAutoPlayView = 3;
}
class ConstGenders
{
    const Male = 1;
    const Female = 2;
}
class ConstProfileImage
{
    const Twitter = 1;
	const Facebook = 2;
	const Upload = 3;
}
class ConstMailNotification{
	const Comment = 1;
	const Follow = 2;
	const ReviewRating = 3;
}
class ConstSettingsSubCategory
{
    const Regional = 4;    
}
?>