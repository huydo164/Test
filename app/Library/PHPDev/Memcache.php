<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Library\PHPDev;

class Memcache{

	const CACHE_ON = 1 ; // 0:Not cache, 1:Cache
	const CACHE_TIME_TO_LIVE_15 = 900; //Time cache 15 minute
	const CACHE_TIME_TO_LIVE_30 = 1800; //Time cache 30 minute
	const CACHE_TIME_TO_LIVE_60 = 3600; //Time cache 60 minute
	const CACHE_TIME_TO_LIVE_ONE_DAY = 86400; //Time cache 1 day
	const CACHE_TIME_TO_LIVE_ONE_WEEK = 604800; //Time cache 7 day
	const CACHE_TIME_TO_LIVE_ONE_MONTH = 2419200; //Time cache 1 month
	const CACHE_TIME_TO_LIVE_ONE_YEAR =  29030400; //Time cache 1 year

	//Role
	const CACHE_ROLE_ID    = 'cache_role_id_';
	const CACHE_ROLE_GROUP_ID    = 'cache_role_group_id_';
	const CACHE_ROLE_GROUP_ALL    = 'cache_role_group_all_';
	const CACHE_PERMISSION_BY_ROLE    = 'cache_permission_by_role_';
	const CACHE_USER_ID    = 'cache_user_id_';

	//Type
	const CACHE_TYPE_ID    = 'cache_type_id_';
	const CACHE_TYPE_KEYWORD    = 'cache_type_keyword_';
	const CACHE_TYPE_ALL    = 'cache_type_all';
	//Category
	const CACHE_CATEGORY_ID    = 'cache_category_id_';
	const CACHE_ALL_CATEGORY    = 'cache_all_category';
	const CACHE_ALL_CATEGORY_BY_TYPE    = 'cache_all_category_by_type_';
	const CACHE_SUB_CATEGORY    = 'cache_sub_category_';
	//Info
	const CACHE_INFO_ID    = 'cache_info_id_';
	const CACHE_INFO_KEYWORD    = 'cache_info_keyword_';
	//Banner
	const CACHE_BANNER_ID    = 'cache_banner_id_';
	const CACHE_BANNER_SITE    = 'cache_banner_site_';
	//Trash
	const CACHE_TRASH_ID    = 'cache_trash_id_';
	//product
	const CACHE_PRODUCT_ID = 'cache_product_id_';

	//Photo
    const CACHE_PHOTO_ID = 'cache_photo_id_';

    //Card
    const CACHE_CARD_ID = 'cache_card_id_';

    //Phone
    const CACHE_PHONE_ID = 'cache_phone_id_';

    //Demo
    const CACHE_DEMO_ID  = 'cache_demo_id_';

    //Statics
    const CACHE_STATICS_ID = 'cache_statics_id_';

	const CACHE_TRAINING_ID = 'cache_training_id_';
	const CACHE_CONTACT_ID = 'cache_contact_id_';
	const CACHE_ORDER_ID = 'cache_order_id_';
}
