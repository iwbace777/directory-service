<?php
    if (App::environment('local')) {
        define('HTTP_HOST',                 "http://directoryservice.loc");
        
        define('PAYPAL_SERVER',		    'www.sandbox.paypal.com');
        define('PAYPAL_BUSINESS',	    'jeni.star90@yahoo.com');
        
        define('STRIPE_SECRET_KEY',		    'sk_test_xiBipAcqPYOnXWWDUop1Pveg');
        define('STRIPE_PUBLISH_KEY',	    'pk_test_LsObZosxYmpM5VX9eBqVh4SP');
               
        define("PHONE_PREFIX", '+855');  

        define('SITE_NAME',                 'Local Directory Service');
        define('REPLY_EMAIL',               'noreply@local.rew.io');
        define('REPLY_NAME',                'Local.Rew.IO');
        
        define('FACEBOOK_APP_ID',           '598956920208023');
        define('FACEBOOK_APP_SECRET',       '938d505f38338e66401b3de9e432c849');
                
    } elseif (App::environment('stage')) {
        define('HTTP_HOST',                 "http://stage.rew.io");
        
        define('PAYPAL_SERVER',		    'www.sandbox.paypal.com');
        define('PAYPAL_BUSINESS',	    'jeni.star90@yahoo.com');
        
        define('STRIPE_SECRET_KEY',		    'sk_test_xiBipAcqPYOnXWWDUop1Pveg');
        define('STRIPE_PUBLISH_KEY',	    'pk_test_LsObZosxYmpM5VX9eBqVh4SP');
        
        define("PHONE_PREFIX", '+358');
        
        define('SITE_NAME',                 'Stage Rew IO');
        define('REPLY_EMAIL',               'noreply@stage.rew.io');
        define('REPLY_NAME',                'Stage.Rew.IO');
        
        define('FACEBOOK_APP_ID',           '598956920208023');
        define('FACEBOOK_APP_SECRET',       '938d505f38338e66401b3de9e432c849');
    } else {
        define('HTTP_HOST',             "http://rew.io");
        
        define('PAYPAL_SERVER',		    'www.paypal.com');
        define('PAYPAL_BUSINESS',	    'support@xamando.com');
        
        define('STRIPE_SECRET_KEY',		    'sk_test_xiBipAcqPYOnXWWDUop1Pveg');
        define('STRIPE_PUBLISH_KEY',	    'pk_test_LsObZosxYmpM5VX9eBqVh4SP');
        
        define("PHONE_PREFIX", '+358');
        
        define('SITE_NAME',                 'Rew IO');
        define('REPLY_EMAIL',               'noreply@rew.io');
        define('REPLY_NAME',                'Rew.IO');
        
        define('FACEBOOK_APP_ID',           '804486739647063');
        define('FACEBOOK_APP_SECRET',       '2018a9685f5ddbf94b1eb5268a19dbbd');
    }
    
    define("INFOBIP_USERNAME", 'varaa6');
    define("INFOBIP_PASSWORD", 'varaa12');    
        
    define('PAGINATION_SIZE',           10);    
    define('DEFAULT_ICON',              'fa fa-star');
    define('DEFAULT_PHOTO',             'default.jpg');
        
    define('DEFAULT_WIDGET_COLOR',      '#e6400c');
    define('DEFAULT_WIDGET_HEADER',     '#e6400c');
    define('DEFAULT_WIDGET_BACKGROUND', '#ffffff');
    
    define('DEFAULT_START_TIME',        '09:00');
    define('DEFAULT_END_TIME',          '18:00');
    
    define('DEFAULT_LAT',               60.1708);
    define('DEFAULT_LNG',               24.9375);
    
    define('DATE_FORMAT', "d M Y");
    define('TIME_FORMAT', "d/m/Y H:i:s");        
    
    define('HTTP_USER_PATH',            HTTP_HOST.'/upload/user/');
    define('ABS_USER_PATH',             $_SERVER['DOCUMENT_ROOT'].'/upload/user/');
    
    define('HTTP_COMPANY_PATH',         HTTP_HOST.'/upload/company/');
    define('ABS_COMPANY_PATH',          $_SERVER['DOCUMENT_ROOT'].'/upload/company/');
    
    define('HTTP_STORE_PATH',         HTTP_HOST.'/upload/store/');
    define('ABS_STORE_PATH',          $_SERVER['DOCUMENT_ROOT'].'/upload/store/');    
    
    define('HTTP_COVER_PATH',         HTTP_HOST.'/upload/cover/');
    define('ABS_COVER_PATH',          $_SERVER['DOCUMENT_ROOT'].'/upload/cover/');    

    define('HTTP_REVIEW_PATH',      HTTP_HOST.'/upload/review/');
    define('ABS_REVIEW_PATH',       $_SERVER['DOCUMENT_ROOT'].'/upload/review/');    
    
    define('HTTP_OFFER_PATH',      HTTP_HOST.'/upload/offer/');
    define('ABS_OFFER_PATH',       $_SERVER['DOCUMENT_ROOT'].'/upload/offer/');

    define('HTTP_LOYALTY_PATH',      HTTP_HOST.'/upload/loyalty/');
    define('ABS_LOYALTY_PATH',       $_SERVER['DOCUMENT_ROOT'].'/upload/loyalty/');
    
    define('HTTP_LOGO_PATH',      HTTP_HOST.'/upload/logo/');
    define('ABS_LOGO_PATH',       $_SERVER['DOCUMENT_ROOT'].'/upload/logo/');    
    