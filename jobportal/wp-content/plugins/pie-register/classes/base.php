<?php



class PieReg_Base
{
	var $user_table;		
	var $user_meta_table; 
	var $plugin_dir;
	var	$plugin_url;
	var	$pie_success;
	var	$pie_error;
	var	$pie_error_msg;
	var	$pie_success_msg;
	var $piereg_global_options;
	
	function __construct()
	{
		$this->plugin_dir = dirname(dirname(__FILE__));
		$this->plugin_url = plugins_url() .'/'. basename(dirname(dirname(__FILE__))) .'/';
		global $piereg_global_options;
		$piereg_global_options = get_option("pie_register_2");
	}
	function getPieMeta()
	{
		global $wpdb;
		$this->user_table		= $wpdb->prefix . "users";
		$this->user_meta_table 	= $wpdb->prefix . "usermeta";
		$result	 = $wpdb->get_results("SELECT distinct(meta_key) FROM ".$this->user_meta_table." WHERE `meta_key` like 'pie_%'" );
		if(sizeof($result) > 0)
		{
			return $result;
		}
		return false;
	}
	function replaceMetaKeys($text,$user_id)
	{
		if($result = $this->getPieMeta())
		{
			foreach($result as $meta)
			{
				$key 		= "%".$meta->meta_key."%";
				
				$value		= get_user_meta($user_id, $meta->meta_key );
				$get_value = "";
				if(is_array($value[0])){
					foreach($value[0] as $val){
						if(is_array($val)){
							if(array_filter($val))
							$get_value .= implode(", ",$val)."<br />";
						}else{
							$get_value = (is_array($value[0]))? implode(", ",$value[0]) : $value[0];
						}
					}
				}else{
					$get_value .= (!empty($value[0]))? $value[0] : "";
				}
				//$value 		= (is_array($value[0]))? implode(", ",$value[0]) : $value[0] ;
				
				$text		= str_replace($key,$get_value,$text);
			}
		}
		return $text;
	}
	function getCurrentFields()
	{		
		$data 	= get_option("pie_fields");			
		$data 	= maybe_unserialize($data );				
		
		if(!$data)
		{
			return false;		
		}
		return $data;			
	}
	function install_settings()
	{
		update_option('piereg_plugin_db_version',PIEREG_DB_VERSION);
		
		/*Get old settings from options*/
		$old_options = get_option("pie_register");
		$new_options = get_option("pie_register_2");
		if($new_options == "" and $old_options != "")
		{
			update_option("pie_register_2",$old_options);
			unset($old_options);
			unset($new_options);
		}
		$this->activation_validation();
		
		//Alternate Pages
		
		$get_pie_pages_from_db		= get_option("pie_pages");
		if(is_array($get_pie_pages_from_db))
		{
			$piereg_login 		= (isset($get_pie_pages_from_db[0]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[0]) : "null";
			$piereg_registrtion = (isset($get_pie_pages_from_db[1]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[1]) : "null";
			$piereg_forgot_pass = (isset($get_pie_pages_from_db[2]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[2]) : "null";
			$piereg_profile 	= (isset($get_pie_pages_from_db[3]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[3]) : "null";
		}
		
		$pie_pages = get_option("pie_pages");
		
		if(trim($piereg_login) != "publish")//Login
		{
			$_p = array();
			$_p['post_title'] 		= __("Pie Register - Login","piereg");
			$_p['post_content'] 	= "[pie_register_login]";
			$_p['post_status'] 		= 'publish';
			$_p['post_type'] 		= 'page';
			$_p['comment_status'] 	= 'closed';
			$_p['ping_status'] 		= 'closed';
			$login_page_id 			= wp_insert_post( $_p );
			$pie_pages[0]			= $login_page_id;
		}
		
		if(trim($piereg_registrtion) != "publish")//Registration
		{
			$_p = array();
			$_p['post_title'] 		= __("Pie Register - Registration","piereg");
			$_p['post_content'] 	= "[pie_register_form]";
			$_p['post_status'] 		= 'publish';
			$_p['post_type'] 		= 'page';
			$_p['comment_status'] 	= 'closed';
			$_p['ping_status'] 		= 'closed';
			$reg_page_id 			= wp_insert_post( $_p );
			$pie_pages[1]			= $reg_page_id;
		}
			
		if(trim($piereg_forgot_pass) != "publish")//Forgot Password
		{
			$_p = array();
			$_p['post_title'] 		= __("Pie Register - Forgot Password","piereg");
			$_p['post_content'] 	= "[pie_register_forgot_password]";
			$_p['post_status'] 		= 'publish';
			$_p['post_type'] 		= 'page';
			$_p['comment_status'] 	= 'closed';
			$_p['ping_status'] 		= 'closed';
			$forPas_page_id	 		= wp_insert_post( $_p );
			$pie_pages[2]			= $forPas_page_id;
		}
		
		if(trim($piereg_profile) != "publish")//Profile Page
		{
			$_p = array();
			$_p['post_title'] 		= __("Pie Register - Profile","piereg");
			$_p['post_content'] 	= "[pie_register_profile]";
			$_p['post_status'] 		= 'publish';
			$_p['post_type'] 		= 'page';
			$_p['comment_status'] 	= 'closed';
			$_p['ping_status'] 		= 'closed';
			$Profile_page_id 		= wp_insert_post( $_p );
			$pie_pages[3]			= $Profile_page_id;
			update_option("Profile_page_id",$Profile_page_id);
		}
		update_option("pie_pages",$pie_pages);
		
		//Countries
		$country = array(__("Afghanistan","piereg"),__("Albania","piereg"),__("Algeria","piereg"),__("American Samoa","piereg"),__("Andorra","piereg"),__("Angola","piereg"),__("Antigua and Barbuda","piereg"),__("Argentina","piereg"),__("Armenia","piereg"),__("Australia","piereg"),__("Austria","piereg"),__("Azerbaijan","piereg"),__("Bahamas","piereg"),__("Bahrain","piereg"),__("Bangladesh","piereg"),__("Barbados","piereg"),__("Belarus","piereg"),__("Belgium","piereg"),__("Belize","piereg"),__("Benin","piereg"),__("Bermuda","piereg"),__("Bhutan","piereg"),__("Bolivia","piereg"),__("Bosnia and Herzegovina","piereg"),__("Botswana","piereg"),__("Brazil","piereg"),__("Brunei","piereg"),__("Bulgaria","piereg"),__("Burkina Faso","piereg"),__("Burundi","piereg"),__("Cambodia","piereg"),__("Cameroon","piereg"),__("Canada","piereg"),__("Cape Verde","piereg"),__("Central African Republic","piereg"),__("Chad","piereg"),__("Chile","piereg"),__("China","piereg"),__("Colombia","piereg"),__("Comoros","piereg"),__("Congo","piereg"),__("Costa Rica","piereg"),__("Côte d'Ivoire","piereg"),__("Croatia","piereg"),__("Cuba","piereg"),__("Cyprus","piereg"),__("Czech Republic","piereg"),__("Denmark","piereg"),__("Djibouti","piereg"),__("Dominica","piereg"),__("Dominican Republic","piereg"),__("East Timor","piereg"),__("Ecuador","piereg"),__("Egypt","piereg"),__("El Salvador","piereg"),__("Equatorial Guinea","piereg"),__("Eritrea","piereg"),__("Estonia","piereg"),__("Ethiopia","piereg"),__("Fiji","piereg"),__("Finland","piereg"),__("France","piereg"),__("Gabon","piereg"),__("Gambia","piereg"),__("Georgia","piereg"),__("Germany","piereg"),__("Ghana","piereg"),__("Greece","piereg"),__("Greenland","piereg"),__("Grenada","piereg"),__("Guam","piereg"),__("Guatemala","piereg"),__("Guinea","piereg"),__("Guinea-Bissau","piereg"),__("Guyana","piereg"),__("Haiti","piereg"),__("Honduras","piereg"),__("Hong Kong","piereg"),__("Hungary","piereg"),__("Iceland","piereg"),__("India","piereg"),__("Indonesia","piereg"),__("Iran","piereg"),__("Iraq","piereg"),__("Ireland","piereg"),__("Israel","piereg"),__("Italy","piereg"),__("Jamaica","piereg"),__("Japan","piereg"),__("Jordan","piereg"),__("Kazakhstan","piereg"),__("Kenya","piereg"),__("Kiribati","piereg"),__("North Korea","piereg"),__("South Korea","piereg"),__("Kuwait","piereg"),__("Kyrgyzstan","piereg"),__("Laos","piereg"),__("Latvia","piereg"),__("Lebanon","piereg"),__("Lesotho","piereg"),__("Liberia","piereg"),__("Libya","piereg"),__("Liechtenstein","piereg"),__("Lithuania","piereg"),__("Luxembourg","piereg"),__("Macedonia","piereg"),__("Madagascar","piereg"),__("Malawi","piereg"),__("Malaysia","piereg"),__("Maldives","piereg"),__("Mali","piereg"),__("Malta","piereg"),__("Marshall Islands","piereg"),__("Mauritania","piereg"),__("Mauritius","piereg"),__("Mexico","piereg"),__("Micronesia","piereg"),__("Moldova","piereg"),__("Monaco","piereg"),__("Mongolia","piereg"),__("Montenegro","piereg"),__("Morocco","piereg"),__("Mozambique","piereg"),__("Myanmar","piereg"),__("Namibia","piereg"),__("Nauru","piereg"),__("Nepal","piereg"),__("Netherlands","piereg"),__("New Zealand","piereg"),__("Nicaragua","piereg"),__("Niger","piereg"),__("Nigeria","piereg"),__("Norway","piereg"),__("Northern Mariana Islands","piereg"),__("Oman","piereg"),__("Pakistan","piereg"),__("Palau","piereg"),__("Palestine","piereg"),__("Panama","piereg"),__("Papua New Guinea","piereg"),__("Paraguay","piereg"),__("Peru","piereg"),__("Philippines","piereg"),__("Poland","piereg"),__("Portugal","piereg"),__("Puerto Rico","piereg"),__("Qatar","piereg"),__("Romania","piereg"),__("Russia","piereg"),__("Rwanda","piereg"),__("Saint Kitts and Nevis","piereg"),__("Saint Lucia","piereg"),__("Saint Vincent and the Grenadines","piereg"),__("Samoa","piereg"),__("San Marino","piereg"),__("Sao Tome and Principe","piereg"),__("Saudi Arabia","piereg"),__("Senegal","piereg"),__("Serbia and Montenegro","piereg"),__("Seychelles","piereg"),__("Sierra Leone","piereg"),__("Singapore","piereg"),__("Slovakia","piereg"),__("Slovenia","piereg"),__("Solomon Islands","piereg"),__("Somalia","piereg"),__("South Africa","piereg"),__("Spain","piereg"),__("Sri Lanka","piereg"),__("Sudan","piereg"),__("Sudan, South","piereg"),__("Suriname","piereg"),__("Swaziland","piereg"),__("Sweden","piereg"),__("Switzerland","piereg"),__("Syria","piereg"),__("Taiwan","piereg"),__("Tajikistan","piereg"),__("Tanzania","piereg"),__("Thailand","piereg"),__("Togo","piereg"),__("Tonga","piereg"),__("Trinidad and Tobago","piereg"),__("Tunisia","piereg"),__("Turkey","piereg"),__("Turkmenistan","piereg"),__("Tuvalu","piereg"),__("Uganda","piereg"),__("Ukraine","piereg"),__("United Arab Emirates","piereg"),__("United Kingdom","piereg"),__("United States","piereg"),__("Uruguay","piereg"),__("Uzbekistan","piereg"),__("Vanuatu","piereg"),__("Vatican City","piereg"),__("Venezuela","piereg"),__("Vietnam","piereg"),__("Virgin Islands, British","piereg"),__("Virgin Islands, U.S.","piereg"),__("Yemen","piereg"),__("Zambia","piereg"),__("Zimbabwe","piereg"));
		add_option("pie_countries",$country);	
		
		//USA States
		$us_states = array(__("Alabama","piereg"),__("Alaska","piereg"),__("Arizona","piereg"),__("Arkansas","piereg"),__("California","piereg"),__("Colorado","piereg"),__("Connecticut","piereg"),__("Delaware","piereg"),__("District of Columbia","piereg"),__("Florida","piereg"),__("Georgia","piereg"),__("Hawaii","piereg"),__("Idaho","piereg"),__("Illinois","piereg"),__("Indiana","piereg"),__("Iowa","piereg"),__("Kansas","piereg"),__("Kentucky","piereg"),__("Louisiana","piereg"),__("Maine","piereg"),__("Maryland","piereg"),__("Massachusetts","piereg"),__("Michigan","piereg"),__("Minnesota","piereg"),__("Mississippi","piereg"),__("Missouri","piereg"),__("Montana","piereg"),__("Nebraska","piereg"),__("Nevada","piereg"),__("New Hampshire", "piereg"),__("New Jersey", "piereg"),__("New Mexico", "piereg"),__("New York", "piereg"),__("North Carolina", "piereg"),__("North Dakota", "piereg"),__("Ohio","piereg"),__("Oklahoma","piereg"),__("Oregon","piereg"),__("Pennsylvania","piereg"),__("Rhode Island", "piereg"),__("South Carolina", "piereg"),__("South Dakota", "piereg"),__("Tennessee","piereg"),__("Texas","piereg"),__("Utah","piereg"),__("Vermont","piereg"),__("Virginia","piereg"),__("Washington","piereg"),__("West Virginia", "piereg"),__("Wisconsin","piereg"),__("Wyoming","piereg"),__("Armed Forces Americas","piereg"),__("Armed Forces Europe","piereg"),__("Armed Forces Pacific","piereg"));
		add_option("pie_us_states",$us_states);
				
		//Canada States
		$can_states = array(__("Alberta","piereg"),__("British Columbia","piereg"),__("Manitoba","piereg"),__("New Brunswick","piereg"),__("Newfoundland and Labrador","piereg"),__("Northwest Territories","piereg"),__("Nova Scotia","piereg"),__("Nunavut","piereg"),__("Ontario","piereg"),__("Prince Edward Island","piereg"),__("Quebec","piereg"),__("Saskatchewan","piereg"),__("Yukon","piereg"));
		add_option("pie_can_states",$can_states);
		
		
		//E-Mail TYpes
		$email_type = array(
							"default_template"							=> __("Default User Registration Template","piereg"),
							"admin_verification"						=> __("Admin Verification","piereg"),
							"email_verification"						=> __("E-Mail Verification","piereg"),
							"email_thankyou"							=> __("Thank You Message","piereg"),
							"pending_payment"							=> __("Pending Payment","piereg"),
							"payment_success"							=> __("Payment Success","piereg"),
							"payment_faild"								=> __("Payment Faild","piereg"),
							"pending_payment_reminder"					=> __("Pending Payment Reminder","piereg"),
							"email_verification_reminder"				=> __("E-Mail Verification Reminder","piereg"),
							"forgot_password_notification"				=> __("Forgot Password Notification","piereg")
							);
		//"email_forgotpassword"=>"Forgot Password"
		update_option("pie_user_email_types",$email_type);
		
		$current = get_option( 'pie_register_2' );
		
		$update["paypal_butt_id"] = ($current["paypal_butt_id"])?$current["paypal_butt_id"]:"";
		$update["paypal_pdt"]     = ($current["paypal_pdt"])?$current["paypal_pdt"]:"";
		$update["paypal_sandbox"] = ($current["paypal_sandbox"])?$current["paypal_sandbox"]:"";			
		$update['enable_admin_notifications'] = ($current['enable_admin_notifications'])?$current['enable_admin_notifications']:1;
		$update['enable_paypal'] = ($current['enable_paypal'])?$current['enable_paypal']:0;
		
		
		$update['admin_sendto_email'] 	= ($current['admin_sendto_email'])?$current['admin_sendto_email']:get_option( 'admin_email' );				
		$update['admin_from_name'] 		= ($current['admin_from_name'])?$current['admin_from_name']:"Administrator";
		$update['admin_from_email'] 	= ($current['admin_from_email'])?$current['admin_from_email']:get_option( 'admin_email' );
		$update['admin_to_email'] 		= ($current['admin_to_email'])?$current['admin_to_email']:get_option( 'admin_email' );
		$update['admin_bcc_email'] 		= ($current['admin_bcc_email'])?$current['admin_bcc_email']:get_option( 'admin_email' );
		$update['admin_subject_email'] 	= ($current['admin_subject_email'])?$current['admin_subject_email']:__("New User Registration","piereg");
		$update['admin_message_email_formate'] 	= ($current['admin_message_email_formate'])?$current['admin_message_email_formate']:1;
		$update['admin_message_email'] 	= ($current['admin_message_email'])?$current['admin_message_email']:'<p>Hello Admin,</p><p>A new user has been registered on your Website,. Details are given below:</p><p>Thanks</p><p>Team %blogname%</p>';
		
		
		$update['display_hints']			= ($current['display_hints'])?$current['display_hints']:1;
		$update['redirect_user']			= ($current['redirect_user'])?$current['redirect_user']:1;
		$update['subscriber_login']			= ($current['subscriber_login'])?$current['subscriber_login']:0;
		$update['login_form_in_website']	= ($current['login_form_in_website'])?$current['login_form_in_website']:1;
		$update['registration_in_website']	= ($current['registration_in_website'])?$current['registration_in_website']:1;
		$update['block_WP_profile']			= ($current['block_WP_profile'])?$current['block_WP_profile']:0;
		$update['allow_pr_edit_wplogin']	= ($current['allow_pr_edit_wplogin'])?$current['allow_pr_edit_wplogin']:0;
		
		$update['modify_avatars']			= ($current['modify_avatars'])?$current['modify_avatars']:0;
		$update['show_admin_bar']			= ($current['show_admin_bar'])?$current['show_admin_bar']:1;
		$update['block_wp_login']			= ($current['block_wp_login'])?$current['block_wp_login']:0;
		$update['alternate_login']			= $pie_pages[0];
		$update['alternate_register']		= $pie_pages[1];
		$update['alternate_forgotpass']		= $pie_pages[2];
		$update['alternate_profilepage']	= $pie_pages[3];
		////// Date Starting/Ending Variables////////////
		//////////////// Since 2.0.12 ///////////////////
		$update['piereg_startingDate']		= ($current['piereg_startingDate'])?$current['piereg_startingDate']:'1901';
		$update['piereg_endingDate']		= ($current['piereg_endingDate'])?$current['piereg_endingDate']:date("Y");
		/////////////
		//
		$update['after_login']				= ($current['after_login'])?$current['after_login']:-1;
		$update['alternate_logout']				= ($current['alternate_logout'])?$current['alternate_logout']:-1;
		$update['alternate_logout_url']				= ($current['alternate_logout'])?$current['alternate_logout_url']:"";
		$update['support_license'] 			= ($current['support_license'])?$current['support_license']:"";
		$update['outputcss'] 				= ($current['outputcss'])?$current['outputcss']:1;
		$update['theme_styles']				= ($current['theme_styles'])?$current['theme_styles']:1;
		$update['outputhtml'] 				= ($current['outputhtml'])?$current['outputhtml']:1;
		$update['no_conflict']				= ($current['no_conflict'])?$current['no_conflict']:0;
		/*$update['currency'] 				= ($current['currency'])?$current['currency']:"USD";*/
		$update['verification'] 			= ($current['verification'])?$current['verification']:0;
		$update['grace_period'] 			= ($current['grace_period'])?$current['grace_period']:0;
		$update['captcha_publc'] 			= ($current['captcha_publc'])?$current['captcha_publc']:"";
		$update['captcha_private'] 			= ($current['captcha_private'])?$current['captcha_private']:"";
		$update['paypal_button_id'] 		= ($current['paypal_button_id'])?$current['paypal_button_id']:"";
		$update['paypal_pdt_token'] 		= ($current['paypal_pdt_token'])?$current['paypal_pdt_token']:"";
		$update['custom_css'] 				= ($current['custom_css'])?$current['custom_css']:"";
		$update['tracking_code'] 			= ($current['tracking_code'])?$current['tracking_code']:"";
		$update['enable_invitation_codes'] 	= ($current['enable_invitation_codes'])?$current['enable_invitation_codes']:0;
		$update['invitation_codes'] 		= ($current['invitation_codes'])?$current['invitation_codes']:"";
		// Payment Setting 
		//$update['payment_setting_amount']				= ($current['payment_setting_amount'])?$current['payment_setting_amount']:"10";
		//Role setting
		//Deprecated science 2.0.10
		$update['pie_regis_set_user_role_']				= ($current['pie_regis_set_user_role_'])?$current['pie_regis_set_user_role_']:"subscriber";
		
		/*$update['payment_setting_activation_cycle'] 	= "0";
		$update['payment_setting_expiry_notice_days'] 	= "0";
		$update['payment_setting_remove_user_days'] 	= "0";
		$update['payment_setting_user_block_notice'] 	= "You are temporary block.";*/
		
		$update['custom_logo_url']			= ($current['custom_logo_url'])? $current['custom_logo_url'] : "";
				
		$update['custom_logo_tooltip']		= ($current['custom_logo_tooltip'])? $current['custom_logo_tooltip'] : "";
		
		$update['custom_logo_link']			= ($current['custom_logo_link'])? $current['custom_logo_link'] : "";
		$update['show_custom_logo']			= ($current['show_custom_logo'])? $current['show_custom_logo'] : 1;
		
		//since 2.0.10, Login form username & password label
		// Login form
		$update['login_username_label']			= ($current['login_username_label'])? $current['login_username_label'] : "Username";
		$update['login_username_placeholder']	= ($current['login_username_placeholder'])? $current['login_username_placeholder'] : "";
		$update['login_password_label']			= ($current['login_password_label'])? $current['login_password_label'] : "Password";
		$update['login_password_placeholder']	= ($current['login_password_placeholder'])? $current['login_password_placeholder'] : "";
		$update['capthca_in_login_label']		= ($current['capthca_in_login_label'])? $current['capthca_in_login_label'] : "";
		$update['capthca_in_login']				= ($current['capthca_in_login'])? $current['capthca_in_login'] : "0";
		// Forgot Password form
		$update['forgot_pass_username_label']	= ($current['forgot_pass_username_label'])? $current['forgot_pass_username_label'] : "Username or E-mail:";
		$update['forgot_pass_username_placeholder']	= ($current['forgot_pass_username_placeholder'])? $current['forgot_pass_username_placeholder'] : "";
		$update['forgot_pass_username_placeholder']	= ($current['forgot_pass_username_placeholder'])? $current['forgot_pass_username_placeholder'] : "";
		$update['capthca_in_forgot_pass_label']	= ($current['capthca_in_forgot_pass_label'])? $current['capthca_in_forgot_pass_label'] : "";
		$update['capthca_in_forgot_pass']	= ($current['capthca_in_forgot_pass'])? $current['capthca_in_forgot_pass'] : "0";
		
		//Since 2.0.10
		$update['remove_PR_settings']	= "0";//Remove All PIE-register settings from database
		
		
		$pie_user_email_types 	= get_option( 'pie_user_email_types'); 
				
		foreach ($pie_user_email_types as $val=>$type) 
		{
			$update['enable_user_notifications'] = ($current['enable_user_notifications'])?$current['enable_user_notifications']:0;
			
			$update['user_from_name_'.$val] 	= ($current['user_from_name_'.$val])?$current['user_from_name_'.$val]:"Admin";
			$update['user_from_email_'.$val] 	= ($current['user_from_email_'.$val])?$current['user_from_email_'.$val]:get_option( 'admin_email' );
			$update['user_to_email_'.$val]	 	= ($current['user_to_email_'.$val])?$current['user_to_email_'.$val]:get_option( 'admin_email' );
			$update['user_subject_email_'.$val] = ($current['user_subject_email_'.$val])?$current['user_subject_email_'.$val]:$type;
			
			$update['user_formate_email_'.$val] = ($current['user_formate_email_'.$val])?$current['user_formate_email_'.$val]:1;
		}

		$update['user_message_email_admin_verification']	 					= ($current['user_message_email_admin_verification'])?$current['user_message_email_admin_verification']: '<p>Dear %user_login%,</p><p>Thanks for showing your interest in becoming a registered member of our Wesbite. This is to inform you that your account is under admin moderation and once approved, you will be notified shortly.<br /><br />Best Wishes,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_email_verification']			 			= ($current['user_message_email_email_verification'])?$current['user_message_email_email_verification']:'<p>Dear %user_login%,</p><p>You have successfully created an account to our Website. You can access your account by completing the registration and verification process, kindly use the link below to verify your email address.</p><p>%activationurl%</p><p>Best Wishes,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_email_thankyou'] 							= ($current['user_message_email_email_thankyou'])?$current['user_message_email_email_thankyou']:'<p>Dear %user_login%,</p><p>Thank you for signing-up to our site. We appreciate that you have successfully created an account. If you require any assistance, feel free to contact.</p><p>Kind Regards,</p><p>Team %blogname%</p>';
		
		//$update['user_message_email_email_forgotpassword'] 	= "";
		$update['user_message_email_payment_success'] 							= ($current['user_message_email_payment_success'])?$current['user_message_email_payment_success']:'<p>Dear %user_login%,</p><p>Congratulations, you have completed the payment procedure successfully. Now you can avail our features anytime you want.</p><p>Best Regards,</p><p>Team %blogname%</p><p>&nbsp;</p>';
		
		$update['user_message_email_payment_faild'] 							= ($current['user_message_email_payment_faild'])?$current['user_message_email_payment_faild']:'<p>Dear %user_login%,</p><p>Unfortunately the payment attempt from your side has been failed. We request you to kindly Log-In to your account again to complete the payment process.</p><p>Kind Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_pending_payment'] 							= ($current['user_message_email_pending_payment'])?$current['user_message_email_pending_payment']:'<p>Dear %user_login%,</p><p>This Email is sent to you to remind that your payment is still in pending. To avoid any further delay to grab our amazing features, kindly complete the payment procedure.</p><p>Best Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_default_template'] 							= ($current['user_message_email_default_template'])?$current['user_message_email_default_template']: '<p>Dear %user_login%,</p><p>Thanks for your registration to our Website. Now you can enjoy unlimited benefits of our services and products by just signing in to your personalized account.</p><p>Best Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_pending_payment_reminder'] 					= ($current['user_message_email_pending_payment_reminder'])?$current['user_message_email_pending_payment_reminder']: '<p>Dear %user_login%,</p><p>We have noticed that you have created an account to the site few days ago, but you did not pay for your account yet. For this we would like to remind you that your payment is still in pending. Kindly complete the payment procedure to avoid any further delays.</p><p>%pending_payment_url%</p><p>Best Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_email_verification_reminder']			 	= ($current['user_message_email_email_verification_reminder'])?$current['user_message_email_email_verification_reminder']: '<p>Dear %user_login%,</p><p>We have noticed that you have created an account to the site few days ago, but you did not verify your email address yet. Kindly follow the link (&nbsp;%activationurl% ) below to complete the verification procedure to become the registered member of our Website.</p><p>Best Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_forgot_password_notification']					= ($current['user_message_email_forgot_password_notification'])?$current['user_message_email_forgot_password_notification']: '<p>Dear %user_login%,</p><p>You have just requested for a new password for your account. Please follow the link (&nbsp;%reset_password_url% ) below to reset your password.</p><p>If you have not requested for a new password, kindly ignore this Email.</p><p>Best Regards,</p><p>Team %user_login%</p>';
						
		update_option( 'pie_register_2', $update );
		
		
		$current_fields 	= maybe_unserialize(get_option( 'pie_fields' ));	
		
		
		$fields 					= array();
		
		
		$fields['form']['label'] 				= ($current_fields['form']['label'])?$current_fields['form']['label']:__("Registration Form","piereg");
		$fields['form']['desc'] 				= ($current_fields['form']['desc'])?$current_fields['form']['desc']:__("Please fill the form below to register.","piereg");
		$fields['form']['label_alignment'] 		= ($current_fields['form']['label_alignment'])?$current_fields['form']['label_alignment']:"left";
		$fields['form']['css']					= ($current_fields['form']['css'])?$current_fields['form']['css']:"";
		$fields['form']['type']					= ($current_fields['form']['type'])?$current_fields['form']['type']:"form";
		$fields['form']['meta']					= ($current_fields['form']['meta'])?$current_fields['form']['meta']:0;
		$fields['form']['reset']				= ($current_fields['form']['reset'])?$current_fields['form']['reset']:0;
		
		
		
		$fields[0]['label'] 		= ($current_fields[0]['label'])?$current_fields[0]['label']:__("Username","piereg");
		$fields[0]['type'] 			= ($current_fields[0]['type'])?$current_fields[0]['type']:"username";
		$fields[0]['id'] 			= ($current_fields[0]['id'])?$current_fields[0]['id']:0;
		$fields[0]['remove'] 		= ($current_fields[0]['remove'])?$current_fields[0]['remove']:0;
		$fields[0]['required'] 		= ($current_fields[0]['required'])?$current_fields[0]['required']:1;
		$fields[0]['desc'] 			= ($current_fields[0]['desc'])?$current_fields[0]['desc']:"";
		$fields[0]['length'] 		= ($current_fields[0]['length'])?$current_fields[0]['length']:"";
		$fields[0]['default_value'] = ($current_fields[0]['default_value'])?$current_fields[0]['default_value']:"";
		$fields[0]['placeholder'] 	= ($current_fields[0]['placeholder'])?$current_fields[0]['placeholder']:"";
		$fields[0]['css'] 			= ($current_fields[0]['css'])?$current_fields[0]['css']:""; 
		$fields[0]['meta']			= ($current_fields[0]['meta'])?$current_fields[0]['meta']:0;
		
		$fields[1]['label'] 			= ($current_fields[1]['label'])?$current_fields[1]['label']:__("E-mail","piereg");
		
		$fields[1]['label2'] 			= ($current_fields[1]['label2'])?$current_fields[1]['label2']:__("Confirm E-mail","piereg");
		$fields[1]['type'] 				= ($current_fields[1]['type'])?$current_fields[1]['type']:"email";
		$fields[1]['id'] 				= ($current_fields[1]['id'])?$current_fields[1]['id']:1;
		$fields[1]['remove'] 			= ($current_fields[1]['remove'])?$current_fields[1]['remove']:0;
		$fields[1]['required'] 			= ($current_fields[1]['required'])?$current_fields[1]['required']:1;
		$fields[1]['desc'] 				= ($current_fields[1]['desc'])?$current_fields[1]['desc']:"";
		$fields[1]['length'] 			= ($current_fields[1]['length'])?$current_fields[1]['length']:"";
		$fields[1]['default_value'] 	= ($current_fields[1]['default_value'])?$current_fields[1]['default_value']:"";
		$fields[1]['placeholder'] 		= ($current_fields[1]['placeholder'])?$current_fields[1]['placeholder']:"";
		$fields[1]['css'] 				= ($current_fields[1]['css'])?$current_fields[1]['css']:""; 
		$fields[1]['validation_rule'] 	= ($current_fields[1]['validation_rule'])?$current_fields[1]['validation_rule']:"email";
		$fields[1]['meta']				= ($current_fields[1]['meta'])?$current_fields[1]['meta']:0;
		
		$fields[2]['label'] 			= ($current_fields[2]['label'])?$current_fields[2]['label']:__("Password","piereg");
		
		$fields[2]['label2'] 			= ($current_fields[2]['label2'])?$current_fields[2]['label2']:__("Confirm Password","piereg");
		$fields[2]['type'] 				= ($current_fields[2]['type'])?$current_fields[2]['type']:"password";
		$fields[2]['id'] 				= ($current_fields[2]['id'])?$current_fields[2]['id']:2;
		$fields[2]['remove'] 			= ($current_fields[2]['remove'])?$current_fields[2]['remove']:0;
		$fields[2]['required'] 			= ($current_fields[2]['required'])?$current_fields[2]['required']:1;
		$fields[2]['desc'] 				= ($current_fields[2]['desc'])?$current_fields[2]['desc']:"";
		$fields[2]['length'] 			= ($current_fields[2]['length'])?$current_fields[2]['length']:"";
		$fields[2]['default_value'] 	= ($current_fields[2]['default_value'])?$current_fields[2]['default_value']:"";
		$fields[2]['placeholder'] 		= ($current_fields[2]['placeholder'])?$current_fields[2]['placeholder']:"";
		$fields[2]['css'] 				= ($current_fields[2]['css'])?$current_fields[2]['css']:""; 
		$fields[2]['validation_rule'] 	= ($current_fields[2]['validation_rule'])?$current_fields[2]['validation_rule']:""; 
		$fields[2]['meta']				= ($current_fields[2]['meta'])?$current_fields[2]['meta']:0;
		$fields[2]['show_meter']		= ($current_fields[2]['show_meter'])?$current_fields[2]['show_meter']:1;
		
		
		//Getting data from old plugins
		$num = 3;
		
		if ($current['firstname'] || $current ['lastname'])
		{
			$fields[$num]['type'] 			= "name";
			$fields[$num]['label'] 			= __("First Name","piereg");
			$fields[$num]['label2'] 		= __("Last Name","piereg");
			$fields[$num]['field_name'] 	= "first_name";			
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		
		if ($current['website'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= __("Website","piereg");	
			$fields[$num]['field_name'] 	= "url";		
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		if ($current ['aim'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= __("AIM","piereg");
			$fields[$num]['field_name'] 	= "aim";			
			$fields[$num]['id'] 			= $num;	
			$num++;			
		}
		if ($current['yahoo'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= __("Yahoo IM","piereg");
			$fields[$num]['field_name'] 	= "yim";			
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		if ($current['jabber'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= __("Jabber / Google Talk","piereg");
			$fields[$num]['field_name'] 	= "jabber";			
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		if ($current['about'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= __("About Yourself","piereg");	
			$fields[$num]['field_name'] 	= "description";			
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		
		
		
		$piereg_custom = get_option( 'pie_register_custom' );
		if( is_array($piereg_custom ))
		{
			foreach( $piereg_custom as $k=>$v)
			{	
				
				if($v['fieldtype']=="select" || $v['fieldtype']=="checkbox" || $v['fieldtype']=="radio")//Populating values
				{
					$ops = explode(',',$v['extraoptions']);
					foreach( $ops as $op )
					{
						$fields[$num]['value'][] 	= $op;
						$fields[$num]['display'][] 	= $op;
					}
				}
				else
				{
					$fields[$num]['default_value'] 	= $v['extraoptions'];				
				}
				
				$fields[$num]['type'] 			= $v['fieldtype'];
				$fields[$num]['label'] 			= $v['label'];			
				$fields[$num]['id'] 			= $num;			
				$fields[$num]['required'] 		= $v['required'];
				
				if($fields[$num]['type']=="select")
				{
					$fields[$num]['type'] = "dropdown";	
				}
				
				if($fields[$num]['type']=="date")
				{
					$fields[$num]['date_type'] 	 	= "datepicker";
					$fields[$num]['date_format'] 	= $current["dateformat"];
					$fields[$num]['firstday'] 		= $current["firstday"];
					$fields[$num]['startdate'] 		= $current["startdate"];
					$fields[$num]['calyear'] 		= $current["calyear"];	
					$fields[$num]['calmonth'] 		= $current["calmonth"];				
					
				}
				
				$num++;
			}
		}
		
		$fields['submit']['message'] 			= __("Thank you for your registration","piereg");
		$fields['submit']['confirmation'] 		= "text";
		$fields['submit']['text'] 				= "Submit";
		$fields['submit']['reset']				= 0;
		$fields['submit']['reset_text'] 		= "Reset";
		$fields['submit']['type'] 				= "submit";
		$fields['submit']['meta']				= 0;	
	
			
		//if(!is_array($current_fields ) || sizeof($current_fields ) == 0)
		//{
			add_option( 'pie_fields', $fields  );
		//}	
		
		update_option( 'pie_fields_default', $fields  );
		
		$structure 	= $this->getDefaultMeta();
		
				
		update_option( 'pie_fields_meta', $structure  );
		
		global $wpdb;
		$prefix=$wpdb->prefix."pieregister_";
		$codetable=$prefix."code";
		$wpdb->query("CREATE TABLE ".$codetable."(`id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`created` DATE NOT NULL ,`modified` DATE NOT NULL ,`name` TEXT NOT NULL ,`count` INT( 5 ) NOT NULL ,`status` INT( 2 ) NOT NULL ,`code_usage` INT( 5 ) NOT NULL) ENGINE = MYISAM ;");
		/*$create_ctable_sql = "CREATE TABLE $codetable (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  modified datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
			  name text NOT NULL,
			  count int(11) NOT NULL,
			  code_usage int(11) NOT NULL,
			  status mediumint(2) NOT NULL,
			  UNIQUE KEY id (id)
			);";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $create_ctable_sql );*/
		
		$status = $wpdb->get_results("SHOW COLUMNS FROM ".$codetable."");
		$check = 0;
		foreach($status as $key=>$val)
		{
			if(trim(strtolower($val->Field)) == "code_usage")
			{
				$check = 1;
			}
		}
		if($check == 0)
		{
			$wpdb->query("alter table ".$codetable." add column `code_usage` int(11) NULL");
		}
		
		//Adding active meta to existing users
		 $blogusers = get_users();
   		 foreach ($blogusers as $user) 
		 {
        	add_user_meta( $user->ID, 'active', 1);
    	 }
		 
		 
		 
		 ////////////////// DELETE ///////////////
		$key = "WJ1U4QFJBOU25anWq3t8eAc7A1LZJWFExpKUBw2AFVODnxBpscnCYdRcxWkP8os1gfaC2d4s54f65d4sf56s4dT0waQ~~";
		$key = trim(strip_tags($key));
		$piereg = get_option("pie_register_2");
		$piereg['support_license'] = ($piereg['support_license'])? $piereg['support_license'] : $key;
		update_option("pie_register_2",$piereg);
		update_option("pie_register_2_key",$key);
		update_option("pie_register_2_active","1");
		$error = apply_filters("piereg_Your_version_has_been_registered","Success. Your version has been registered.");
		return $error ;
		 /////////////////////////////////////////
		 
	}
	function getDefaultMeta()
	{
		$structure = array();
		$structure["text"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="length_%d%">'.__("Length","piereg").'</label><input type="text" name="field[%d%][length]" id="length_%d%" class="input_fields character_fields field_length numeric"></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","piereg").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_rule_%d%">'.__("Validation Rule","piereg").'</label><select name="field[%d%][validation_rule]" id="validation_rule_%d%"><option>'.__("None","piereg").'</option><option value="number">'.__("Number","piereg").'</option><option value="alphanumeric">'.__("Alphanumeric","piereg").'</option><option value="email">'.__("E-Mail","piereg").'</option><option value="website">'.__("Website","piereg").'</option><option value="standard">'.__("USA Format","piereg").' (xxx) (xxx-xxxx)</option><option value="international">'.__("Phone International","piereg").' xxx-xxx-xxxx</option></select></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>'; 
		
		/*$structure["username"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][label]"><input type="hidden" name="field[%d%][validation_rule]"><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';*/
		
	
		$structure["username"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][label]"><input type="hidden" name="field[%d%][validation_rule]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" value="Username" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
		
		
		$structure["default"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div></div></div>';
		
		$structure["aim"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" value="AIM" id="label_%d%" class="input_fields field_label"></div></div></div>';
		
		
		$structure["url"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="Website" class="input_fields field_label"></div></div></div>';
		
		
		$structure["yim"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="Yahoo IM" class="input_fields field_label"></div></div></div>';
		
		
		$structure["description"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="About Yourself" class="input_fields field_label"></div></div></div>';
		
		
		$structure["jabber"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" value="Jabber / Google Talk" id="label_%d%" class="input_fields field_label"></div></div></div>';
		
		$structure["password"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][label]"><input type="hidden" name="field[%d%][validation_rule]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" value="Password" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="label2_%d%">'.__("Label2","piereg").'</label><input type="text" name="field[%d%][label2]" value="Confirm Password" id="label2_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_meter_%d%">'.__("Show Strength Meter","piereg").'</label><select class="show_meter" name="field[%d%][show_meter]" id="show_meter_%d%" class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
			$structure['email']	= '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][label]"><input type="hidden" name="field[%d%][validation_rule]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" value="E-mail" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="label2_%d%">'.__("Label2","piereg").'</label><input type="text" name="field[%d%][label2]" value="Confrim E-mail" id="label2_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="confirm_email_%d%">'.__("Confirm E-Mail","piereg").'</label><input name="field[%d%][confirm_email]" id="confirm_email" value="%d%" type="checkbox" class="checkbox_fields"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
		
		
		$structure["textarea"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="rows_%d%">'.__("Rows","piereg").'</label><input type="text" value="8" name="field[%d%][rows]" id="rows_%d%" class="input_fields character_fields field_rows numeric"></div><div class="advance_fields"><label for="cols_%d%">'.__("Columns","piereg").'</label><input type="text" value="73" name="field[%d%][cols]" id="cols_%d%" class="input_fields character_fields field_cols numeric"></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","piereg").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		$structure["dropdown"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields  sel_options_%d%"><label for="display_%d%">'.__("Display Value","piereg").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields select_option_display"><label for="value_%d%">'.__("Value","piereg").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields select_option_value"><label>'.__("Checked","piereg").'</label><input type="radio" value="0" id="check_%d%" name="field[%d%][selected][]" class="select_option_checked"><a style="color:white" href="javascript:;" onclick="addOptions(%d%,\'radio\',jQuery(this));">+</a><!--<a style="color:white;font-size: 13px;margin-left: 2px;" href="javascript:;" onclick="jQuery(this).parent().remove();">x</a>--></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"> <label for="list_type_%d%">'.__("List Type","piereg").'</label><select name="field[%d%][list_type]" id="list_type_%d%"><option>'.__("None","piereg").'</option><option value="country">'.__("Country","piereg").'</option><option value="us_states">'.__("US States","piereg").'</option><option value="can_states">'.__("Canada States","piereg").'</option> </select></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		$structure["multiselect"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields  sel_options_%d%"><label for="display_%d%">'.__("Display Value","piereg").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields select_option_display"><label for="value_%d%">'.__("Value","piereg").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields select_option_value"><label>'.__("Checked","piereg").'</label><input type="checkbox" value="0" id="check_%d%" name="field[%d%][selected][]" class="select_option_checked"><a style="color:white" href="javascript:;" onclick="addOptions(%d%,\'checkbox\',jQuery(this));">+</a><!--<a style="color:white;font-size: 13px;margin-left: 2px;" href="javascript:;" onclick="jQuery(this).parent().remove();">x</a>--></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"> <label for="list_type_%d%">'.__("List Type","piereg").'</label><select name="field[%d%][list_type]" id="list_type_%d%"><option>'.__("None","piereg").'</option><option value="country">'.__("Country","piereg").'</option><option value="us_states">'.__("US States","piereg").'</option><option value="can_states">'.__("Canada States","piereg").'</option></select></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		$structure["number"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="min_%d%">'.__("Min","piereg").'</label><input type="text" name="field[%d%][min]" id="min_%d%" class="input_fields character_fields  numeric"></div><div class="advance_fields"><label for="max_%d%">'.__("Max","piereg").'</label><input type="text" name="field[%d%][max]" id="max_%d%" class="input_fields character_fields  numeric"></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","piereg").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		$structure["checkbox"] = '<div class="fields_main">  <div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div>  <div class="advance_fields  sel_options_%d%"><label for="display_%d%">'.__("Display Value","piereg").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields checkbox_option_display"><label for="value_%d%">'.__("Value","piereg").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields checkbox_option_value"><label>'.__("Checked","piereg").'</label><input type="checkbox" value="0" id="check_%d%" name="field[%d%][selected][]" class="checkbox_option_checked"><a style="color:white" href="javascript:;" onClick="addOptions(%d%,\'checkbox\');">+</a></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div> <div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div> </div></div>';
		
		$structure["radio"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields  sel_options_%d%"><label for="display_%d%">'.__("Display Value","piereg").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields radio_option_display"><label for="value_%d%">'.__("Value","piereg").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields radio_option_value"><label>'.__("Checked","piereg").'</label><input type="radio" value="0" id="check_%d%" name="field[%d%][selected][]" class="radio_option_checked"><a style="color:white" href="javascript:;" onclick="addOptions(%d%,\'radio\');">+</a></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		$structure["html"] 			= '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><textarea rows="8" id="htmlbox_%d%" class="ckeditor" name="field[%d%][html]" cols="16"></textarea></div></div></div>';
		
		/*$structure["html"] 			= '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><textarea rows="8" id="htmlbox_%d%" class="piereg_html_editor_field" name="field[%d%][html]" cols="16"></textarea></div></div></div>';*/
		
		
		$structure["sectionbreak"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div></div></div>';
		
		$structure["pagebreak"] 	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="advance_fields"><label for="next_button_%d%">'.__("Next Button","piereg").'</label><div class="calendar_icon_type">  <input class="next_button" type="radio" id="next_button_%d%_text" name="field[%d%][next_button]" value="text" checked="checked">  <label for="next_button_%d%_text">'.__("Text","piereg").' </label>  <input class="next_button" type="radio" id="next_button_%d%_url" name="field[%d%][next_button]" value="url"><label for="next_button_%d%_url"> '.__("Image","piereg").'</label></div><div id="next_button_url_container_%d%" style="float:left;clear: both;display: none;">  <label for="next_button_%d%_url"> '.__("Image Path","piereg").': </label>  <input type="text" name="field[%d%][next_button_url]" class="input_fields" id="next_button_%d%_url"></div><div id="next_button_text_container_%d%" style="float:left;clear: both;">  <label for="next_button_%d%_text"> '.__("Text","piereg").': </label>  <input type="text" name="field[%d%][next_button_text]" value="Next" class="input_fields" id="next_button_%d%_text"></div></div><div class="advance_fields"><label for="prev_button_%d%">'.__("Previous Button","piereg").'</label><div class="calendar_icon_type">  <input class="prev_button" type="radio" id="prev_button_%d%_text" name="field[%d%][prev_button]" value="text" checked="checked">  <label for="prev_button_%d%_text">'.__("Text","piereg").' </label>  <input class="prev_button" type="radio" id="prev_button_%d%_url" name="field[%d%][prev_button]" value="url">  <label for="prev_button_%d%_url"> '.__("Image","piereg").'</label></div><div id="prev_button_url_container_%d%" style="float:left;clear: both;display: none;">  <label for="prev_button_%d%_url"> '.__("Image Path","piereg").': </label>  <input type="text" name="field[%d%][prev_button_url]" class="input_fields" id="prev_button_%d%_url"></div><div id="prev_button_text_container_%d%" style="float:left;clear: both;">  <label for="prev_button_%d%_text"> '.__("Text","piereg").': </label>  <input type="text" name="field[%d%][prev_button_text]" value="Previous" class="input_fields" id="prev_button_%d%_text"></div></div></div></div>';
		
		
		$structure['name']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" value="First Name" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="label2_%d%">'.__("Label2","piereg").'</label><input type="text" name="field[%d%][label2]" value="Last Name" id="label2_%d%" class="input_fields field_label2"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		

	$structure['time']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"> <label for="time_type_%d%">'.__("List Type","piereg").'</label><select class="time_format" name="field[%d%][time_type]" id="time_type_%d%"><option value="12">'.__("12 hour","piereg").'</option><option value="24">'.__("24 hour","piereg").'</option></select></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		

	
	$structure['website']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
	
	$structure['upload']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="Upload" class="input_fields field_label"></div><div class="advance_fields"><label for="file_types_%d%">'.__("File Types","piereg").'</label><input type="text" name="field[%d%][file_types]" id="file_types_%d%" class="input_fields"><a class="info" href="javascript:;">'.__("Separated with commas","piereg").' (i.e. jpg, gif, png, pdf)</a></div><div clss="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
	
	$structure['profile_pic'] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="Profile Picture" class="input_fields field_label"></div><div clss="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
	
	$structure['address']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"> <label for="address_type_%d%">'.__("List Type","piereg").'</label><select class="address_type" name="field[%d%][address_type]" id="address_type_%d%"><option value="International">'.__("International","piereg").'</option><option value="United States">'.__("United States","piereg").'</option><option value="Canada">'.__("Canada","piereg").'</option></select></div><div id="default_country_div_%d%" class="advance_fields"> <label for="default_country_%d%">'.__("Default Country","piereg").'</label><select class="default_country" name="field[%d%][default_country]" id="default_country_%d%"><option value="" selected="selected"></option><option value="Afghanistan">'.__("Afghanistan","piereg").'</option><option value="Albania">'.__("Albania","piereg").'</option><option value="Algeria">'.__("Algeria","piereg").'</option><option value="American Samoa">'.__("American Samoa","piereg").'</option><option value="Andorra">'.__("Andorra","piereg").'</option><option value="Angola">'.__("Angola","piereg").'</option><option value="Antigua and Barbuda">'.__("Antigua and Barbuda","piereg").'</option><option value="Argentina">'.__("Argentina","piereg").'</option><option value="Armenia">'.__("Armenia","piereg").'</option><option value="Australia">'.__("Australia","piereg").'</option><option value="Austria">'.__("Austria","piereg").'</option><option value="Azerbaijan">'.__("Azerbaijan","piereg").'</option><option value="Bahamas">'.__("Bahamas","piereg").'</option><option value="Bahrain">'.__("Bahrain","piereg").'</option><option value="Bangladesh">'.__("Bangladesh","piereg").'</option><option value="Barbados">'.__("Barbados","piereg").'</option><option value="Belarus">'.__("Belarus","piereg").'</option><option value="Belgium">'.__("Belgium","piereg").'</option><option value="Belize">'.__("Belize","piereg").'</option><option value="Benin">'.__("Benin","piereg").'</option><option value="Bermuda">'.__("Bermuda","piereg").'</option><option value="Bhutan">'.__("Bhutan","piereg").'</option><option value="Bolivia">'.__("Bolivia","piereg").'</option><option value="Bosnia and Herzegovina">'.__("Bosnia and Herzegovina","piereg").'</option><option value="Botswana">'.__("Botswana","piereg").'</option><option value="Brazil">'.__("Brazil","piereg").'</option><option value="Brunei">'.__("Brunei","piereg").'</option><option value="Bulgaria">'.__("Bulgaria","piereg").'</option><option value="Burkina Faso">'.__("Burkina Faso","piereg").'</option><option value="Burundi">'.__("Burundi","piereg").'</option><option value="Cambodia">'.__("Cambodia","piereg").'</option><option value="Cameroon">'.__("Cameroon","piereg").'</option><option value="Canada">'.__("Canada","piereg").'</option><option value="Cape Verde">'.__("Cape Verde","piereg").'</option><option value="Central African Republic">'.__("Central African Republic","piereg").'</option><option value="Chad">'.__("Chad","piereg").'</option><option value="Chile">'.__("Chile","piereg").'</option><option value="China">'.__("China","piereg").'</option><option value="Colombia">'.__("Colombia","piereg").'</option><option value="Comoros">'.__("Comoros","piereg").'</option><option value="Congo, Democratic Republic of the">'.__("Congo, Democratic Republic of the","piereg").'</option><option value="Congo, Republic of the">'.__("Congo, Republic of the","piereg").'</option><option value="Costa Rica">'.__("Costa Rica","piereg").'</option><option value="Côte d\'Ivoire">'.__("Côte d\'Ivoire","piereg").'</option><option value="Croatia">'.__("Croatia","piereg").'</option><option value="Cuba">'.__("Cuba","piereg").'</option><option value="Cyprus">'.__("Cyprus","piereg").'</option><option value="Czech Republic">'.__("Czech Republic","piereg").'</option><option value="Denmark">'.__("Denmark","piereg").'</option><option value="Djibouti">'.__("Djibouti","piereg").'</option><option value="Dominica">'.__("Dominica","piereg").'</option><option value="Dominican Republic">'.__("Dominican Republic","piereg").'</option><option value="East Timor">'.__("East Timor","piereg").'</option><option value="Ecuador">'.__("Ecuador","piereg").'</option><option value="Egypt">'.__("Egypt","piereg").'</option><option value="El Salvador">'.__("El Salvador","piereg").'</option><option value="Equatorial Guinea">'.__("Equatorial Guinea","piereg").'</option><option value="Eritrea">'.__("Eritrea","piereg").'</option><option value="Estonia">'.__("Estonia","piereg").'</option><option value="Ethiopia">'.__("Ethiopia","piereg").'</option><option value="Fiji">'.__("Fiji","piereg").'</option><option value="Finland">'.__("Finland","piereg").'</option><option value="France">'.__("France","piereg").'</option><option value="Gabon">'.__("Gabon","piereg").'</option><option value="Gambia">'.__("Gambia","piereg").'</option><option value="Georgia">'.__("Georgia","piereg").'</option><option value="Germany">'.__("Germany","piereg").'</option><option value="Ghana">'.__("Ghana","piereg").'</option><option value="Greece">'.__("Greece","piereg").'</option><option value="Greenland">'.__("Greenland","piereg").'</option><option value="Grenada">'.__("Grenada","piereg").'</option><option value="Guam">'.__("Guam","piereg").'</option><option value="Guatemala">'.__("Guatemala","piereg").'</option><option value="Guinea">'.__("Guinea","piereg").'</option><option value="Guinea-Bissau">'.__("Guinea-Bissau","piereg").'</option><option value="Guyana">'.__("Guyana","piereg").'</option><option value="Haiti">'.__("Haiti","piereg").'</option><option value="Honduras">'.__("Honduras","piereg").'</option><option value="Hong Kong">'.__("Hong Kong","piereg").'</option><option value="Hungary">'.__("Hungary","piereg").'</option><option value="Iceland">'.__("Iceland","piereg").'</option><option value="India">'.__("India","piereg").'</option><option value="Indonesia">'.__("Indonesia","piereg").'</option><option value="Iran">'.__("Iran","piereg").'</option><option value="Iraq">'.__("Iraq","piereg").'</option><option value="Ireland">'.__("Ireland","piereg").'</option><option value="Israel">'.__("Israel","piereg").'</option><option value="Italy">'.__("Italy","piereg").'</option><option value="Jamaica">'.__("Jamaica","piereg").'</option><option value="Japan">'.__("Japan","piereg").'</option><option value="Jordan">'.__("Jordan","piereg").'</option><option value="Kazakhstan">'.__("Kazakhstan","piereg").'</option><option value="Kenya">'.__("Kenya","piereg").'</option><option value="Kiribati">'.__("Kiribati","piereg").'</option><option value="North Korea">'.__("North Korea","piereg").'</option><option value="South Korea">'.__("South Korea","piereg").'</option><option value="Kuwait">'.__("Kuwait","piereg").'</option><option value="Kyrgyzstan">'.__("Kyrgyzstan","piereg").'</option><option value="Laos">'.__("Laos","piereg").'</option><option value="Latvia">'.__("Latvia","piereg").'</option><option value="Lebanon">'.__("Lebanon","piereg").'</option><option value="Lesotho">'.__("Lesotho","piereg").'</option><option value="Liberia">'.__("Liberia","piereg").'</option><option value="Libya">'.__("Libya","piereg").'</option><option value="Liechtenstein">'.__("Liechtenstein","piereg").'</option><option value="Lithuania">'.__("Lithuania","piereg").'</option><option value="Luxembourg">'.__("Luxembourg","piereg").'</option><option value="Macedonia">'.__("Macedonia","piereg").'</option><option value="Madagascar">'.__("Madagascar","piereg").'</option><option value="Malawi">'.__("Malawi","piereg").'</option><option value="Malaysia">'.__("Malaysia","piereg").'</option><option value="Maldives">'.__("Maldives","piereg").'</option><option value="Mali">'.__("Mali","piereg").'</option><option value="Malta">'.__("Malta","piereg").'</option><option value="Marshall Islands">'.__("Marshall Islands","piereg").'</option><option value="Mauritania">'.__("Mauritania","piereg").'</option><option value="Mauritius">'.__("Mauritius","piereg").'</option><option value="Mexico">'.__("Mexico","piereg").'</option><option value="Micronesia">'.__("Micronesia","piereg").'</option><option value="Moldova">'.__("Moldova","piereg").'</option><option value="Monaco">'.__("Monaco","piereg").'</option><option value="Mongolia">'.__("Mongolia","piereg").'</option><option value="Montenegro">'.__("Montenegro","piereg").'</option><option value="Morocco">'.__("Morocco","piereg").'</option><option value="Mozambique">'.__("Mozambique","piereg").'</option><option value="Myanmar">'.__("Myanmar","piereg").'</option><option value="Namibia">'.__("Namibia","piereg").'</option><option value="Nauru">'.__("Nauru","piereg").'</option><option value="Nepal">'.__("Nepal","piereg").'</option><option value="Netherlands">'.__("Netherlands","piereg").'</option><option value="New Zealand">'.__("New Zealand","piereg").'</option><option value="Nicaragua">'.__("Nicaragua","piereg").'</option><option value="Niger">'.__("Niger","piereg").'</option><option value="Nigeria">'.__("Nigeria","piereg").'</option><option value="Norway">'.__("Norway","piereg").'</option><option value="Northern Mariana Islands">'.__("Northern Mariana Islands","piereg").'</option><option value="Oman">'.__("Oman","piereg").'</option><option value="Pakistan">'.__("Pakistan","piereg").'</option><option value="Palau">'.__("Palau","piereg").'</option><option value="Palestine">'.__("Palestine","piereg").'</option><option value="Panama">'.__("Panama","piereg").'</option><option value="Papua New Guinea">'.__("Papua New Guinea","piereg").'</option><option value="Paraguay">'.__("Paraguay","piereg").'</option><option value="Peru">'.__("Peru","piereg").'</option><option value="Philippines">'.__("Philippines","piereg").'</option><option value="Poland">'.__("Poland","piereg").'</option><option value="Portugal">'.__("Portugal","piereg").'</option><option value="Puerto Rico">'.__("Puerto Rico","piereg").'</option><option value="Qatar">'.__("Qatar","piereg").'</option><option value="Romania">'.__("Romania","piereg").'</option><option value="Russia">'.__("Russia","piereg").'</option><option value="Rwanda">'.__("Rwanda","piereg").'</option><option value="Saint Kitts and Nevis">'.__("Saint Kitts and Nevis","piereg").'</option><option value="Saint Lucia">'.__("Saint Lucia","piereg").'</option><option value="Saint Vincent and the Grenadines">'.__("Saint Vincent and the Grenadines","piereg").'</option><option value="Samoa">'.__("Samoa","piereg").'</option><option value="San Marino">'.__("San Marino","piereg").'</option><option value="Sao Tome and Principe">'.__("Sao Tome and Principe","piereg").'</option><option value="Saudi Arabia">'.__("Saudi Arabia","piereg").'</option><option value="Senegal">'.__("Senegal","piereg").'</option><option value="Serbia and Montenegro">'.__("Serbia and Montenegro","piereg").'</option><option value="Seychelles">'.__("Seychelles","piereg").'</option><option value="Sierra Leone">'.__("Sierra Leone","piereg").'</option><option value="Singapore">'.__("Singapore","piereg").'</option><option value="Slovakia">'.__("Slovakia","piereg").'</option><option value="Slovenia">'.__("Slovenia","piereg").'</option><option value="Solomon Islands">'.__("Solomon Islands","piereg").'</option><option value="Somalia">'.__("Somalia","piereg").'</option><option value="South Africa">'.__("South Africa","piereg").'</option><option value="Spain">'.__("Spain","piereg").'</option><option value="Sri Lanka">'.__("Sri Lanka","piereg").'</option><option value="Sudan">'.__("Sudan","piereg").'</option><option value="Sudan, South">'.__("Sudan, South","piereg").'</option><option value="Suriname">'.__("Suriname","piereg").'</option><option value="Swaziland">'.__("Swaziland","piereg").'</option><option value="Sweden">'.__("Sweden","piereg").'</option><option value="Switzerland">'.__("Switzerland","piereg").'</option><option value="Syria">'.__("Syria","piereg").'</option><option value="Taiwan">'.__("Taiwan","piereg").'</option><option value="Tajikistan">'.__("Tajikistan","piereg").'</option><option value="Tanzania">'.__("Tanzania","piereg").'</option><option value="Thailand">'.__("Thailand","piereg").'</option><option value="Togo">'.__("Togo","piereg").'</option><option value="Tonga">'.__("Tonga","piereg").'</option><option value="Trinidad and Tobago">'.__("Trinidad and Tobago","piereg").'</option><option value="Tunisia">'.__("Tunisia","piereg").'</option><option value="Turkey">'.__("Turkey","piereg").'</option><option value="Turkmenistan">'.__("Turkmenistan","piereg").'</option><option value="Tuvalu">'.__("Tuvalu","piereg").'</option><option value="Uganda">'.__("Uganda","piereg").'</option><option value="Ukraine">'.__("Ukraine","piereg").'</option><option value="United Arab Emirates">'.__("United Arab Emirates","piereg").'</option><option value="United Kingdom">'.__("United Kingdom","piereg").'</option><option value="United States">'.__("United States","piereg").'</option><option value="Uruguay">'.__("Uruguay","piereg").'</option><option value="Uzbekistan">'.__("Uzbekistan","piereg").'</option><option value="Vanuatu">'.__("Vanuatu","piereg").'</option><option value="Vatican City">'.__("Vatican City","piereg").'</option><option value="Venezuela">'.__("Venezuela","piereg").'</option><option value="Vietnam">'.__("Vietnam","piereg").'</option><option value="Virgin Islands, British">'.__("Virgin Islands, British","piereg").'</option><option value="Virgin Islands, U.S.">'.__("Virgin Islands, U.S.","piereg").'</option><option value="Yemen">'.__("Yemen","piereg").'</option><option value="Zambia">'.__("Zambia","piereg").'</option><option value="Zimbabwe">'.__("Zimbabwe","piereg").'</option></select></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="hide_address2_%d%">'.__("Hide Address 2","piereg").'</label><input onchange="checkEvents(this,\'address_address2_%d%\')" name="field[%d%][hide_address2]" id="hide_address2_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="hide_address2_%d%" class="required"></label></div><div class="advance_fields"><label for="hide_state_%d%">'.__("Hide State","piereg").'</label><input class="hide_state" name="field[%d%][hide_state]" id="hide_state_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="hide_state_%d%" class="required"></label></div><div style="display:none;" id="default_state_div_%d%" class="advance_fields"><label for="default_state_%d%">'.__("Default State","piereg").'</label><select id="us_states_%d%" style="display:none;" class="default_state us_states_%d%" name="field[%d%][us_default_state]"><option value="" selected="selected"></option><option value="Alabama">'.__("Alabama","piereg").'</option><option value="Alaska">'.__("Alaska","piereg").'</option><option value="Arizona">'.__("Arizona","piereg").'</option><option value="Arkansas">'.__("Arkansas","piereg").'</option><option value="California">'.__("California","piereg").'</option><option value="Colorado">'.__("Colorado","piereg").'</option><option value="Connecticut">'.__("Connecticut","piereg").'</option><option value="Delaware">'.__("Delaware","piereg").'</option><option value="District of Columbia">'.__("District of Columbia","piereg").'</option><option value="Florida">'.__("Florida","piereg").'</option><option value="Georgia">'.__("Georgia","piereg").'</option><option value="Hawaii">'.__("Hawaii","piereg").'</option><option value="Idaho">'.__("Idaho","piereg").'</option><option value="Illinois">'.__("Illinois","piereg").'</option><option value="Indiana">'.__("Indiana","piereg").'</option><option value="Iowa">'.__("Iowa","piereg").'</option><option value="Kansas">'.__("Kansas","piereg").'</option><option value="Kentucky">'.__("Kentucky","piereg").'</option><option value="Louisiana">'.__("Louisiana","piereg").'</option><option value="Maine">'.__("Maine","piereg").'</option><option value="Maryland">'.__("Maryland","piereg").'</option><option value="Massachusetts">'.__("Massachusetts","piereg").'</option><option value="Michigan">'.__("Michigan","piereg").'</option><option value="Minnesota">'.__("Minnesota","piereg").'</option><option value="Mississippi">'.__("Mississippi","piereg").'</option><option value="Missouri">'.__("Missouri","piereg").'</option><option value="Montana">'.__("Montana","piereg").'</option><option value="Nebraska">'.__("Nebraska","piereg").'</option><option value="Nevada">'.__("Nevada","piereg").'</option><option value="New Hampshire">'.__("New Hampshire","piereg").'</option><option value="New Jersey">'.__("New Jersey","piereg").'</option><option value="New Mexico">'.__("New Mexico","piereg").'</option><option value="New York">'.__("New York","piereg").'</option><option value="North Carolina">'.__("North Carolina","piereg").'</option><option value="North Dakota">'.__("North Dakota","piereg").'</option><option value="Ohio">'.__("Ohio","piereg").'</option><option value="Oklahoma">'.__("Oklahoma","piereg").'</option><option value="Oregon">'.__("Oregon","piereg").'</option><option value="Pennsylvania">'.__("Pennsylvania","piereg").'</option><option value="Rhode Island">'.__("Rhode Island","piereg").'</option><option value="South Carolina">'.__("South Carolina","piereg").'</option><option value="South Dakota">'.__("South Dakota","piereg").'</option><option value="Tennessee">'.__("Tennessee","piereg").'</option><option value="Texas">'.__("Texas","piereg").'</option><option value="Utah">'.__("Utah","piereg").'</option><option value="Vermont">'.__("Vermont","piereg").'</option><option value="Virginia">'.__("Virginia","piereg").'</option><option value="Washington">'.__("Washington","piereg").'</option><option value="West Virginia">'.__("West Virginia","piereg").'</option><option value="Wisconsin">'.__("Wisconsin","piereg").'</option><option value="Wyoming">'.__("Wyoming","piereg").'</option><option value="Armed Forces Americas">'.__("Armed Forces Americas","piereg").'</option><option value="Armed Forces Europe">'.__("Armed Forces Europe","piereg").'</option><option value="Armed Forces Pacific">'.__("Armed Forces Pacific","piereg").'</option></select><select id="can_states_%d%" style="display:none;" class="default_state can_states_%d%" name="field[%d%][canada_default_state]"><option value="" selected="selected"></option><option value="Alberta">'.__("Alberta","piereg").'</option><option value="British Columbia">'.__("British Columbia","piereg").'</option><option value="Manitoba">'.__("Manitoba","piereg").'</option><option value="New Brunswick">'.__("New Brunswick","piereg").'</option><option value="Newfoundland &amp; Labrador">'.__("Newfoundland and Labrador","piereg").'</option><option value="Northwest Territories">'.__("Northwest Territories","piereg").'</option><option value="Nova Scotia">'.__("Nova Scotia","piereg").'</option><option value="Nunavut">'.__("Nunavut","piereg").'</option><option value="Ontario">'.__("Ontario","piereg").'</option><option value="Prince Edward Island">'.__("Prince Edward Island","piereg").'</option><option value="Quebec">'.__("Quebec","piereg").'</option><option value="Saskatchewan">'.__("Saskatchewan","piereg").'</option><option value="Yukon">'.__("Yukon","piereg").'</option></select></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
				
		/*$structure['captcha']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div></div></div>';*/
				
		$structure['captcha']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="Re-Captcha" class="input_fields field_label"></div><div class="advance_fields"><label for="recaptcha_skin_%d%">'.__("Captcha Skin","piereg").'</label><select class="show_in_profile" name="field[%d%][recaptcha_skin]" id="recaptcha_skin_%d%"  class="checkbox_fields"><option value="red" selected="selected">'.__("Red","piereg").'</option><option value="white">'.__("White","piereg").'</option><option value="clean">'.__("Clean","piereg").'</option><option value="blackglass">'.__("Blackglass","piereg").'</option></select></div></div></div>';
				
		$structure['math_captcha']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" value="Math Captcha" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
		
		$structure['phone']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","piereg").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"> <label for="phone_format_%d%">'.__("Phone Format","piereg").'</label><select class="phone_format" name="field[%d%][phone_format]" id="phone_format_%d%"><option value="standard">'.__("USA Format","piereg").' (###) ###-####</option><option value="international">'.__("International","piereg").'</option></select></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		$structure['date']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="date_type_%d%">'.__("Date Format","piereg").'</label><select class="date_format" name="field[%d%][date_format]" id="date_format_%d%"><option value="mm/dd/yy">mm/dd/yy</option><option value="dd/mm/yy">dd/mm/yy</option><option value="dd-mm-yy">dd-mm-yy</option><option value="dd.mm.yy">dd.mm.yy</option><option value="yy/mm/dd">yy/mm/dd</option><option value="yy.mm.dd">yy.mm.dd</option></select></div><div class="advance_fields"><label for="date_type_%d%">'.__("Date Input Type","piereg").'</label><select class="date_type" name="field[%d%][date_type]" id="date_type_%d%"><option value="datefield">'.__("Date Field","piereg").'</option><option value="datepicker">'.__("Date Picker","piereg").'</option><option value="datedropdown">'.__("Date Drop Down","piereg").'</option></select></div><div class="advance_fields"><label for="startingDate_%d%">'.__("Starting Date","piereg").'</label><input name="field[%d%][startingDate]" id="startingDate_%d%" type="text" value="1901" class="input_fields"><a href="javascript:;" class="info">e.g : 1901</a></div><div class="advance_fields"><label for="endingDate_%d%">'.__("Ending Date","piereg").'</label><input name="field[%d%][endingDate]" id="endingDate_%d%" type="text" class="input_fields" value="'.(date("Y")).'"><a href="javascript:;" class="info">e.g : 2014</a></div><div style="display:none;" id="icon_div_%d%" class="advance_fields"><label for="date_type_%d%">&nbsp;</label><div class="calendar_icon_type"><input class="calendar_icon" type="radio" id="calendar_icon_%d%_none" name="field[%d%][calendar_icon]" value="none" checked="checked"><label for="calendar_icon_%d%_none"> '.__("No Icon","piereg").' </label>&nbsp;&nbsp;<input class="calendar_icon" type="radio" id="calendar_icon_%d%_calendar" name="field[%d%][calendar_icon]" value="calendar"><label for="calendar_icon_%d%_calendar"> '.__("Calendar Icon","piereg").' </label>&nbsp;&nbsp;<input class="calendar_icon" type="radio" id="calendar_icon_%d%_custom" name="field[%d%][calendar_icon]" value="custom"><label for="calendar_icon_%d%_custom"> '.__("Custom Icon","piereg").' </label></div><div id="icon_url_container_%d%" style="display: none;float:left;clear: both;"><label for="cfield_calendar_icon_%d%_url"> '.__("Image Path","piereg").': </label><input type="text" class="input_fields" name="field[%d%][calendar_icon_url]" id="cfield_calendar_icon_%d%_url"></div></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		$structure['list'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="rows_%d%">'.__("Rows","piereg").'</label><input type="text" value="1" name="field[%d%][rows]" id="rows_%d%" class="input_fields character_fields list_rows numeric greaterzero"></div><div class="advance_fields"><label for="cols_%d%">'.__("Columns","piereg").'</label><input type="text" value="1" name="field[%d%][cols]" id="cols_%d%" class="input_fields character_fields list_cols numeric greaterzero"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","piereg").'</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">'.__("Yes","piereg").'</option><option value="0">'.__("No","piereg").'</option></select></div></div></div>';
		
		$structure['hidden'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","piereg").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div></div></div>';
		
		$structure['invitation']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","piereg").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","piereg").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","piereg").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","piereg").'</label></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","piereg").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","piereg").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","piereg").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
		
		return $structure;	
	}
	function uninstall_settings()
	{
		global $wpdb;
		$option = get_option("pie_register_2");
		
		if($option['remove_PR_settings'] == 1)
		{
			$pie_pages = get_option("pie_pages",$pie_pages);
			
			if(is_array($pie_pages ))
			{
				foreach ($pie_pages as $page)	
				{
					wp_delete_post($page);	
				}
			}
			
			delete_option('piereg_math_cpatcha_enable');
			delete_option('piereg_plugin_db_version');
			delete_option('pie_can_states');
			delete_option('pie_countries');
			delete_option('pie_fields');
			delete_option('pie_fields_default');
			delete_option('pie_fields_meta');
			delete_option('pie_register_2');
			delete_option('pie_register_2_active');
			delete_option('pie_register_2_key');
			delete_option('pie_user_email_types');
			delete_option('pie_us_states');
			delete_option('pie_pages');
			
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			$wpdb->query("DROP TABLE IF EXISTS $codetable");
		}
	}
	function pluginURL($add = "")
	{
		return plugins_url()."/pie-register/".$add;	
	}
	function getMetaKey($text)
	{
		return str_replace("-","_",sanitize_title($text));	
	}
	function filterEmail($text,$user,$user_pass="",$key=false)
	{
		if(!is_object($user))
		{
			global $wpdb;
			/*$user_data = $wpdb->get_results("SELECT `ID`, `user_login`, `user_nicename`, `user_email`, `user_registered`,`user_activation_key` FROM `wp_users` WHERE `user_email` = '".$wpdb->escape( $user )."' || `user_login` = '".$wpdb->escape( $user )."'");*/
			
			$user_table = $wpdb->prefix."users";
		
			$user_data = $wpdb->get_results("SELECT `ID`, `user_login`, `user_nicename`, `user_email`, `user_registered`,`user_activation_key` FROM `".$user_table."` WHERE `user_email` = '".$wpdb->escape( $user )."' || `user_login` = '".$wpdb->escape( $user )."' || `ID` = '".$wpdb->escape( $user )."'");
			$user = $user_data[0];
		}
		if(!$user) return false;
		$text					= $this->replaceMetaKeys($text,$user->ID);
		$user_login 			= stripslashes($user->user_login);
		$user_email 			= stripslashes($user->user_email);
		$blog_name 				= get_option("blogname"); 
		$site_url 				= get_option("siteurl");
		
		$blogname_url			= '<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>';
		$first_name				= get_user_meta( $user->ID, 'first_name' );
		$last_name				= get_user_meta( $user->ID, 'last_name' );
		
		$user_url				= $user->user_url;
		
		$user_aim				= get_user_meta( $user->ID, 'aim' );
		$user_yim				= get_user_meta( $user->ID, 'yim' );
		$user_jabber			= get_user_meta( $user->ID, 'jabber' );
		$user_biographical_nfo	= get_user_meta( $user->ID, 'description' );
		$invitation_code		= get_user_meta( $user->ID, 'invite_code' );
		
		$invitation_code		= (is_array($invitation_code))? $invitation_code[0] : "";
		
		$hash 			= get_user_meta( $user->ID, 'hash' );
		$user_ip		= $_SERVER['REMOTE_ADDR'];
		//$activationurl	= $this->pie_login_url().'?action=activate&id='.$user_login.'&activation_key='.$hash[0];
		
		$activationurl	= $this->pie_modify_custom_url($this->pie_login_url(),"action=activate").'&id='.$user_login.'&activation_key='.$hash[0];
		$activationurl	= '<a href="'.$activationurl.'" target="_blank">'.$activationurl.'</a>';
																																		  
		$all_field = $this->get_all_field($user->user_email);
		
		$password_reset_key = $key;
		$user_registration_date = $user->user_registered;
		//$reset_password_url = $this->pie_modify_custom_url($this->pie_login_url())."?action=rp&key={$password_reset_key}&login=" . $user_login;
		
		$reset_password_url = $this->pie_modify_custom_url($this->pie_login_url(),"action=rp")."&key={$password_reset_key}&login=" . $user_login;
		$reset_password_url = '<a href="'.$reset_password_url.'" target="_blank">'.$reset_password_url.'</a>';
		
		/////////////// PAYMENT LINK ///////////
		$option = get_option("pie_register_2");
		$pending_payment_url = "";
		$register_type = get_user_meta($user->ID, 'register_type', true);
		if($register_type == "payment_verify"){
			$hash = md5( time() );
			update_user_meta( $user_id, 'hash', $hash );
			if($option['paypal_sandbox'] == "yes")
				$pending_payment_url = SSL_SAND_URL."?cmd=_s-xclick&hosted_button_id=".$option['paypal_butt_id']."&custom=".$hash."|".$user->ID;
			else
				$pending_payment_url = SSL_P_URL."?cmd=_s-xclick&hosted_button_id=".$option['paypal_butt_id']."&custom=".$hash."|".$user->ID;
				
				
			$pending_payment_url = '<a href="'.$pending_payment_url.'">'.$pending_payment_url.'</a>';
		}
		$user_pass = "********";
		//////////////////////////////////////
		
		$keys 	= array("%user_login%","%user_email%","%blogname%","%siteurl%","%activationurl%","%firstname%","%lastname%","%user_url%","%user_aim%","%user_yim%","%user_jabber%","%user_biographical_nfo%","%all_field%","%user_registration_date%","%reset_password_url%" ,"%invitation_code%","%pending_payment_url%","%blogname_url%","%user_ip%","%user_pass%");
		$values = array($user_login ,$user_email,$blog_name, $site_url,$activationurl,$first_name[0],$last_name[0],$user_url,$user_aim[0],$user_yim[0],$user_jabber[0],$user_biographical_nfo[0], $all_field,$user_registration_date,$reset_password_url,$invitation_code,$pending_payment_url,$blogname_url,$user_ip,$user_pass);
		$return_text = str_replace($keys,$values,$text);
		return $return_text;
	}
	function get_all_field($user)
	{
		if(!is_object($user))
		{
			global $wpdb;
			$user_table = $wpdb->prefix."users";
			$user = $wpdb->get_results("SELECT `ID`, `user_login`, `user_nicename`, `user_email`, `user_registered` FROM `".$user_table."` WHERE `user_email` = '".stripslashes(mysql_real_escape_string( $user ) )."'");
			$user = $user[0];
		}
		if($user)
		{
			$val = "<table>";
				foreach($user as $key=>$value)
				{
					if($key != "ID")
					{
						$val .= "<tr>
									<td>".$this->chnge_case($key)."</td>
									<td>".$value."</td>
								</tr>";
					}
				}
			$val .= "</table>";
		}
		else{
			$val = "";
		}
		return $val;
	}
	function chnge_case($key = "")
	{
		return @ucwords(strtolower(str_replace("_"," ",$key)));
		//return ucwords("baqar hassan");
	}
	function createDropdown($options,$sel = "")
	{
		$html = "";
		for($a = 0 ;$a < sizeof($options);$a++)
		{
			$selected = "";
			if($options[$a]==$sel)
			$selected = 'selected="selected"';
			$html .= '<option '.$selected.' value="'.$options[$a].'">'.$options[$a].'</option>';	
		}
		return $html;
	}
	function codeTable()
	{
		global $wpdb;		
		return $wpdb->prefix."pieregister_code";			
	}
	function checkLicense($key="")
	{
		if(empty($key))	
		{
			return;
		}
		/*$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://pieregister.genetechsolutions.com/license.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"domain=".get_bloginfo("url")."&key=".rawurlencode($key));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch); 
		curl_close ($ch);	*/
		
		$error = $this->Check_license_key_form_API($key);
		
		//if(strip_tags($server_output)=="True")
		if($error == "")
		{
			add_option("pie_register_2_key",$key);
			add_option("pie_register_2_active",1);
			return $key; 	
		}
		return	""; 
	}
	
	function Check_license_key_form_API($key)
	{
		//die("<br />Check_license_key_form_API");
		/*$post_url = "http://achnawachna.com/PieRegisterService_new/requesthandler.ashx";
		$domain_name = get_bloginfo("url");
		$post_string_url	= "type=checkdomainkey&domainname=".$domain_name."&key=".trim($key);

		$post_response = "";
		if(function_exists('curl_version')){
			$request = curl_init($post_url);
			curl_setopt($request, CURLOPT_HEADER, 0);
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($request, CURLOPT_POSTFIELDS, $post_string_url);
			curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
			$post_response = curl_exec($request);
			curl_close ($request);
		}else{
			$url = $post_url."?".$post_string_url;
			$post_response = @file_get_contents($url);
		}*/
		/*$rresponce_code = trim(strip_tags($post_response));// get response code from http://pieregister.genetechsolutions.com/
		/*$error = "";
		if($rresponce_code == "1001")
			{$error = __("Both key and domain doesn't exist. Please try gain later","piereg");}
		else if($rresponce_code == "1002")
			{$error = __("This Domain doesn't exist. Please try gain later","piereg");}
		else if($rresponce_code == "1003")
			{$error = __("This key doesn't exist. Please try gain later","piereg");}
		else if($rresponce_code == "1004")
			{$error = __("Key and domain exist but a cross match (not related to each other). Please try gain later","piereg");}
		else if($rresponce_code == "")
			{$error = __("Server is down please try again later.","piereg");}
		else if($rresponce_code == "1000")
		{
			$error = array('key'=>$key);
		}
		else if($rresponce_code != "")
		{
			$error = array('key'=>$key);
		}*/
		
		$key = md5(rand(123,321))."4QFJBOU25anWq3t8eAc7A1LZJWFExpKUBw2AFVODn".md5(time())."gfa~~";
		$error = array('key'=>$key);
		return $error;
	}
	
	
	function warnings()
	{ //Show warning if plugin is installed on a WordPress lower than 3.2
		global $wp_version;			
		//VERSION CONTROL
		if( $wp_version < 3.5 )			
		echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('Pie-Register is only compatible with WordPress v3.5 and up. You are currently using WordPress.', 'piereg').$wp_version.". ".__("The plugin may not work as expected.","piereg")."</strong> </p></div>";
		
		$key 	=  get_option("pie_register_2_key");
		$active =  get_option("pie_register_2_active");
		$notice_PR_License_Key = PieRegister::print_Rpr_licenseKey_errors();
		$print_PR_notice = "";
		
		if((empty($key) ||  $active != 1) and 1==2)
		{
			/*echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('Your are using the unregistered version of Pie Register. ','piereg').'<a href="http://pieregister.genetechsolutions.com/get-your-license-key?wp_home_url='.urlencode(get_bloginfo("url")).'" target="_blank">'.__('Click here','piereg').'</a>'.__(' to get your key. ', 'piereg')."</strong></p></div>";*/
			
			$print_PR_notice = "<p>
									<strong>".__('You are using the unregistered version of Pie Register. ','piereg').'
										<a href="'.admin_url("admin.php?page=pie-general-settings").'" >
											'.__('Click here','piereg').'
										</a>'.__(' to get your free license key. ', 'piereg')."
									</strong>
								</p>";
		}
		if(trim(strip_tags($notice_PR_License_Key)) != "")
		{
			$print_PR_notice .= "<p>
									<strong>".__($notice_PR_License_Key,'piereg')."
									</strong>
								</p>";
				
		}
		if( trim($print_PR_notice) != "" )
		{
			echo "<div id='piereg-warning' class='updated fade-ff0000'>
					".$print_PR_notice."
				</div>";			
		}
		/*else
		{
			$responce = $this->Check_license_key_form_API(trim($key));
			if(trim($responce) == "Server is down please try again later.")
			{
				$notice = __($responce,"piereg");
			}
			else if(trim($responce) != "")
			{
				$piereg = get_option("pie_register_2");
				$piereg['support_license'] = "";
				update_option("pie_register_2",$piereg);
				delete_option("pie_register_key");
				delete_option("pie_register_active");
				$notice = __($responce, 'piereg');
			}
		}*/
		/*if(trim($notice) != "")
		{
			echo "<div id='piereg-warning' class='updated fade-ff0000'>
					<p>
						<strong>notice ::  ".__($notice, 'piereg')."
						</strong>
					</p>
				</div>";
		}*/
		/**
			* add snipt since 2.0.12
		**/
		$piereg_notices = @file_get_contents('http://www.genetechsolutions.com/pie_register_help_content/piereg_notices.json');
		$regular_notice = "";
		$dismiss_notice = "";
		if(!empty($piereg_notices))
		{
			$piereg_notices_json = json_decode($piereg_notices);// decode the JSON into an object
			if(is_object($piereg_notices_json))
			{
				if(trim($piereg_notices_json->notices->regular_notice) != "")//for regular notice
					$regular_notice = html_entity_decode($piereg_notices_json->notices->regular_notice);
				
				if(trim($piereg_notices_json->notices->dismiss_notice) != "")//for dismiss notice
					$dismiss_notice = html_entity_decode($piereg_notices_json->notices->dismiss_notice);
			}
		}
		//$regular_notice = @file_get_contents("http://genetechsolutions.com/pie_register_help_contain/regular_notice.txt");
		if(trim($regular_notice) != "")
		{
			echo "<div id='piereg-warning' class='updated fade-ff0000'>
					<p>".$regular_notice."</p>
				  </div>";
		}
		
		//$desmiss_notice = @file_get_contents("http://genetechsolutions.com/pie_register_help_contain/desmiss_notice.txt");
		
		if(trim($dismiss_notice) != "")
		{
			?>
			<script tyle="text/javascript">
				var piereg = jQuery.noConflict();
                piereg(document).ready(function(){
                    if(sessionStorage.getItem("dissmiss_notice") != "abc")
					{
						var dismiss_notice = '<div class="error updated" id="pie_dismiss_error"><a style="float:right;cursor:pointer;" id="pie_dismiss_close_btn">X</a><p><?php echo $dismiss_notice; ?></p></div>';
						piereg("#pie_dismiss_error_show").html(dismiss_notice);
						sessionStorage.setItem("dissmiss_notice","false");
					}
					
                    piereg("#pie_dismiss_close_btn").click(function(){
                        sessionStorage.setItem("dissmiss_notice","abc");
                        piereg("#pie_dismiss_error").fadeOut();
                    });
                });
            </script>
            <?php
            echo '<div id="pie_dismiss_error_show"></div>';
		}
	}
	function ignoreHeader($curl, $headerStr)
	{
	  return strlen($headerStr);
	}
	public function check_enable_social_site_method()// only check any Social Site method enable or not.
	{
		$pie_reg = get_option('pie_register_2');
		if(
		(isset($pie_reg['piereg_enable_facebook']) and $pie_reg['piereg_enable_facebook'] == 1 and trim($pie_reg['piereg_facebook_app_id']) != "") or
		(isset($pie_reg['piereg_enable_linkedin']) and $pie_reg['piereg_enable_linkedin'] == 1 and trim($pie_reg['piereg_linkedin_app_id']) != "")	or
		(isset($pie_reg['piereg_enable_google']) and $pie_reg['piereg_enable_google'] 	  == 1 )	or
		(isset($pie_reg['piereg_enable_yahoo']) and $pie_reg['piereg_enable_yahoo'] 	  == 1 )	or
		(isset($pie_reg['piereg_enable_twitter']) and $pie_reg['piereg_enable_twitter']   == 1 and trim($pie_reg['piereg_twitter_app_id'] ) != "")
		  )
		{
			return "true";
		}
		else
		{
			return "false";
		}
		
	}
	public function check_enable_payment_method()// only check any payment method enable or not.
	{
		$pie_reg = get_option('pie_register_2');
		if(
			(isset($pie_reg['enable_authorize_net']) && $pie_reg['enable_authorize_net']	 	== 1 and trim($pie_reg['piereg_authorize_net_api_id'])	 	!= "") or
		(isset($pie_reg['enable_2checkout']) && $pie_reg['enable_2checkout'] == 1 and trim($pie_reg['piereg_2checkout_api_id']) != "")or
		(isset($pie_reg['enable_PaypalExpress']) && $pie_reg['enable_PaypalExpress'] == 1 and trim($pie_reg['PaypalExpress_Username']) != "") or
		(isset($pie_reg['enable_Skrill']) && $pie_reg['enable_Skrill'] == 1 and trim($pie_reg['Skrill_Username']) != "") /*or
			($pie_reg['enable_paypal'] 			== 1 and trim($pie_reg['paypal_butt_id'])				!= "")*/
		  )
		{
			return "true";
		}
		else
		{
			return "false";
		}
	}
	function check_plugin_activation()
	{
		if(
		   	is_plugin_active("pie-rigister-2checkout/pie-rigister-2checkout.php")								or
		   	is_plugin_active("pie-rigister-authorize-dot-net/pie-rigister-authorize-dot-net.php")				or
		   	is_plugin_active("pie-register_paypal_express_checkout/pie-register_paypal_express_checkout.php")	or
		   	is_plugin_active("pie-register_skrill/pie-register_skrill.php")
		  )
		{
			return "true";
		}
		else{
			return "false";
		}
	}
	function activation_validation()
	{}
	
	function piereg_default_settings()
	{
		$pie_pages_id = get_option("pie_pages");
		
		$update = get_option( 'pie_register_2' );
		//E-Mail TYpes
		$email_type = array(
							"default_template"							=> __("Default Template","piereg"),
							"admin_verification"						=> __("Admin Verification","piereg"),
							"email_verification"						=> __("E-Mail Verification","piereg"),
							"email_thankyou"							=> __("Thank You Message","piereg"),
							"pending_payment"							=> __("Pending Payment","piereg"),
							"payment_success"							=> __("Payment Success","piereg"),
							"payment_faild"								=> __("Payment Faild","piereg"),
							"pending_payment_reminder"					=> __("Pending Payment Reminder","piereg"),
							"email_verification_reminder"				=> __("E-Mail Verification Reminder","piereg"),
							"forgot_password_notification"				=> __("Forgot Password Notification","piereg")
							);
		//"email_forgotpassword"=>"Forgot Password"
		add_option("pie_user_email_types",$email_type);
		
		$update = get_option( 'pie_register_2' );
		
		$update["paypal_butt_id"] = "";
		$update["paypal_pdt"]     = "";
		$update["paypal_sandbox"] = "";
		$update['enable_admin_notifications'] = 1;
		$update['enable_paypal'] = 0;
		$update['admin_sendto_email'] 	= get_option( 'admin_email' );				
		$update['admin_from_name'] 		= "Administrator";
		$update['admin_from_email'] 	= get_option( 'admin_email' );
		$update['admin_to_email'] 		= get_option( 'admin_email' );
		$update['admin_bcc_email'] 		= get_option( 'admin_email' );
		$update['admin_subject_email'] 	= __("New User Registration","piereg");
		$update['admin_message_email_formate'] 	= 1;
		$update['admin_message_email'] 	= '<p>Hello Admin,</p><p>A new user has been registered on your Website,. Details are given below:</p><p>Thanks</p><p>Team %blogname%</p>';
		
		
		$update['display_hints']			= 1;
		$update['redirect_user']			= 0;
		$update['subscriber_login']			= 0;
		$update['allow_pr_edit_wplogin']	= 0;
		$update['block_WP_profile']			= 0;
		$update['modify_avatars']			= 0;
		$update['show_admin_bar']			= 1;
		$update['block_wp_login']			= 0;
		$update['alternate_login']			= $pie_pages_id[0];
		$update['alternate_register']		= $pie_pages_id[1];
		$update['alternate_forgotpass']		= $pie_pages_id[2];
		$update['alternate_profilepage']	= $pie_pages_id[3];
		
		$update['after_login']				= -1;
		
		$update['alternate_logout']			= -1;
		
		$update['alternate_logout_url']			= -1;
		$update['support_license'] 			= "";
		$update['outputcss'] 				= 1;
		$update['theme_styles']				= 1;
		$update['outputhtml'] 				= 1;
		$update['no_conflict']				= 0;
		/*$update['currency'] 				= "USD";*/
		$update['verification'] 			= 0;
		$update['grace_period'] 			= 0;
		$update['captcha_publc'] 			= "";
		$update['captcha_private'] 			= "";
		$update['paypal_button_id'] 		= "";
		$update['paypal_pdt_token'] 		= "";
		$update['custom_css'] 				= "";
		$update['tracking_code'] 			= "";
		$update['enable_invitation_codes'] 	= 0;
		$update['invitation_codes'] 		= "";
		
		$update['custom_logo_url']			= "";
				
		$update['custom_logo_tooltip']		= "";
		
		$update['custom_logo_link']			= "";
		$update['show_custom_logo']			= 1;
		//$update['payment_setting_amount']	= "10";
		//Deprecated Since 2.0.10
		$update['pie_regis_set_user_role_']	= "subscriber";
		//since 2.0.10, Login form username & password label
		// Login form
		$update['login_username_label']			= "Username";
		$update['login_username_placeholder']	= "";
		$update['login_password_label']			= "Password";
		$update['login_password_placeholder']	= "";
		$update['capthca_in_login_label']		= "";
		$update['capthca_in_login']				= "0";
		// Forgot Password form
		$update['forgot_pass_username_label']		= "Username or E-mail:";
		$update['forgot_pass_username_placeholder']	= "";
		$update['forgot_pass_username_placeholder']	= "";
		$update['capthca_in_forgot_pass_label']		= "";
		$update['capthca_in_forgot_pass']			= "0";
		//Since 2.0.10
		$update['remove_PR_settings']	= "0";//Remove All PIE-register settings from database
		
		////// Date Starting/Ending Variables////////////
		//////////////// Since 2.0.12 ///////////////////
		$update['piereg_startingDate']		= '1901';
		$update['piereg_endingDate']		= date("Y");
		/////////////
		
		$pie_user_email_types 	= get_option( 'pie_user_email_types'); 
				
		foreach ($pie_user_email_types as $val=>$type) 
		{
			$update['enable_user_notifications'] = 0;
			
			$update['user_from_name_'.$val] 	= "Admin";
			$update['user_from_email_'.$val] 	= get_option( 'admin_email' );
			$update['user_to_email_'.$val]	 	= get_option( 'admin_email' );
			$update['user_subject_email_'.$val] = $type;
			$update['user_formate_email_'.$val] = 1;
		}

		$update['user_message_email_admin_verification']	 					= '<p>Dear %user_login%,</p><p>Thanks for showing your interest in becoming a registered member of our Wesbite. This is to inform you that your account is under admin moderation and once approved, you will be notified shortly.<br /><br />Best Wishes,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_email_verification']			 			= '<p>Dear %user_login%,</p><p>You have successfully created an account to our Website. You can access your account by completing the registration and verification process, kindly use the link below to verify your email address.</p><p>%activationurl%</p><p>Best Wishes,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_email_thankyou'] 							= '<p>Dear %user_login%,</p><p>Thank you for signing-up to our site. We appreciate that you have successfully created an account. If you require any assistance, feel free to contact.</p><p>Kind Regards,</p><p>Team %blogname%</p>';
		
		//$update['user_message_email_email_forgotpassword'] 	= "";
		$update['user_message_email_payment_success'] 							= '<p>Dear %user_login%,</p><p>Congratulations, you have completed the payment procedure successfully. Now you can avail our features anytime you want.</p><p>Best Regards,</p><p>Team %blogname%</p><p>&nbsp;</p>';
		
		$update['user_message_email_payment_faild'] 							= '<p>Dear %user_login%,</p><p>Unfortunately the payment attempt from your side has been failed. We request you to kindly Log-In to your account again to complete the payment process.</p><p>Kind Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_pending_payment'] 							= '<p>Dear %user_login%,</p><p>This Email is sent to you to remind that your payment is still in pending. To avoid any further delay to grab our amazing features, kindly complete the payment procedure.</p><p>Best Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_default_template'] 							= '<p>Dear %user_login%,</p><p>Thanks for your registration to our Website. Now you can enjoy unlimited benefits of our services and products by just signing in to your personalized account.</p><p>Best Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_pending_payment_reminder'] 					='<p>Dear %user_login%,</p><p>We have noticed that you have created an account to the site few days ago, but you did not pay for your account yet. For this we would like to remind you that your payment is still in pending. Kindly complete the payment procedure to avoid any further delays.</p><p>%pending_payment_url%</p><p>Best Regards,</p><p>Team %blogname%</p>';
		
		$update['user_message_email_email_verification_reminder']			 	= '<p>Dear %user_login%,</p><p>We have noticed that you have created an account to the site few days ago, but you did not verify your email address yet. Kindly follow the link (&nbsp;%activationurl% ) below to complete the verification procedure to become the registered member of our Website.</p><p>Best Regards,</p><p>Team %blogname%</p>';

		$update['user_message_email_forgot_password_notification']				= '<p>Dear %user_login%,</p><p>You have just requested for a new password for your account. Please follow the link (&nbsp;%reset_password_url% ) below to reset your password.</p><p>If you have not requested for a new password, kindly ignore this Email.</p><p>Best Regards,</p><p>Team %user_login%</p>';
		

		update_option( 'pie_register_2', $update );
	}
	function pie_registration_url($url=false)
	{
		//$options = get_option("pie_register_2");
		global $piereg_global_options;
		$options = $piereg_global_options;
		$pie_registration_url = get_permalink($options['alternate_register']);
		return ($pie_registration_url)? $pie_registration_url : wp_registration_url();
	}
	function pie_login_url($url=false)
	{
		//$options = get_option("pie_register_2");
		global $piereg_global_options;
		$options = $piereg_global_options;
		$pie_login_url = get_permalink($options['alternate_login']);
		return ($pie_login_url)? $pie_login_url : wp_login_url();
	}
	function pie_lostpassword_url($url=false)
	{
		//$options = get_option("pie_register_2");
		global $piereg_global_options;
		$options = $piereg_global_options;
		$pie_lostpass_url = get_permalink($options['alternate_forgotpass']);
		return ($pie_lostpass_url)? $pie_lostpass_url : wp_lostpassword_url();
	}
	function piereg_logout_url($url,$redirect)
	{
		//$options = get_option("pie_register_2");
		global $piereg_global_options;
		$options = $piereg_global_options;
		if($options['alternate_logout_url'] != ""){
			$redirect = $options['alternate_logout_url'];
		}
		else{
			if(intval($options['alternate_logout']) > 0 ){
				$redirect = get_permalink($options['alternate_logout']);
			}else{
				$redirect = home_url();
			}
		}
		
		if(empty($redirect))
			$redirect = home_url();
		
		$redirect = urlencode($redirect);
		$new_logout_url = home_url() . '/?piereg_logout_url=true&redirect_to=' . $redirect;
    	return $new_logout_url;
	}
	function pie_modify_custom_url($get_url,$query_string=false){
		$get_url = trim($get_url);
		if(!$get_url) return false;
		
		if(strpos($get_url,"?"))
			$url = $get_url."&".$query_string;
		else
			$url = $get_url."?".$query_string;
			
		return $url;
	}
	function piereg_validate_files($file_info,$extantion_array = array())
	{
		$result = false;
		$extantion_array = array_map("trim",$extantion_array);
		$result = in_array(pathinfo($file_info,PATHINFO_EXTENSION),$extantion_array);
		$result = (trim($result))? $result : false;
		return $result;
	}
	
	function pie_profile_pictures_upload($user_id,$field,$field_slug){
		global $errors;
		$errors = new WP_Error();
		if($_FILES[$field_slug]['name'] != ''){
			//echo $_FILES[$field_slug]['name'];
			//die('pie_profile_pictures_upload');
			////////////////////////////UPLOAD PROFILE PICTURE//////////////////////////////
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$result = $this->piereg_validate_files($_FILES[$field_slug]['name'],array("gif","jpeg","jpg","png","bmp"));
			if($result)
			{
				$temp = explode(".", $_FILES[$field_slug]["name"]);
				$extension = end($temp);
				$upload_dir = wp_upload_dir();
				$temp_dir = $upload_dir['basedir']."/piereg_users_files/".$user_id;
				wp_mkdir_p($temp_dir);
				$temp_file_name = "profile_pic_".crc32($user_id."_".$extension."_".time()).".".$extension;
				$temp_file_url = $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
				if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name)){
					$errors->add( $field_slug , '<strong>'.ucwords(__('error','piereg')).'</strong>: '.apply_filters("piereg_fail_to_upload_profile picture",__('Fail to upload profile picture.','piereg' )));
					/*$_POST['error'] = ('<strong>'.ucwords(__('error','piereg')).'</strong>: '.apply_filters("piereg_fail_to_upload_profile picture",__('Fail to upload profile picture.','piereg' )));*/
				}else{
					//$_POST[$field_slug] = $temp_file_url;
					update_user_meta($user_id,"pie_".$field_slug, $temp_file_url);
				}
				
			}else{
				$errors->add( $slug , '<strong>'.ucwords(__('error','piereg')).'</strong>: '.apply_filters("piereg_invalid_file_type_in_profile_picture",__('Invalid File Type In Profile Picture.','piereg' )));
				/*$_POST['error'] = ('<strong>'.ucwords(__('error','piereg')).'</strong>: '.apply_filters("piereg_invalid_file_type_in_profile_picture",__('Invalid File Type In Profile Picture.','piereg' )));*/
			}
		}else{
			update_user_meta($user_id,"pie_".$field_slug, "");
		}
	}
	function pie_upload_files($user_id,$field,$field_slug){
		global $errors;
		$errors = new WP_Error();
		$result = false;
		if($_FILES[$field_slug]['name'] != ''){
			///////////////////UPLOAD ALL FILES//////////////////////////
			
			if($field['file_types'] != ""){
				$filter_string = stripcslashes($field['file_types']);
				$filter_array = explode(",",$filter_string);
				$result = $this->piereg_validate_files($_FILES[$field_slug]['name'],$filter_array);
				
				if($result){
					$temp = explode(".", $_FILES[$field_slug]["name"]);
					$extension = end($temp);
					$upload_dir = wp_upload_dir();
					$temp_dir = $upload_dir['basedir']."/piereg_users_files/".$user_id;
					wp_mkdir_p($temp_dir);
					$temp_file_name = "file_".crc32($user_id."_".$extension."_".time()).".".$extension;
					$temp_file_url = $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
					if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name) && $required){
						$errors->add( $field_slug , '<strong>'.ucwords(__('error','piereg')).'</strong>: '.apply_filters("piereg_Fail_to_upload_profile_picture",__('Fail to upload profile picture.','piereg' )));
					}else{
						//$_POST[$field_slug] = $temp_file_url;
						update_user_meta($user_id,"pie_".$field_slug, $temp_file_url);
					}
				}else{
					$errors->add( $field_slug , '<strong>'.ucwords(__('error','piereg')).'</strong>: '.apply_filters("piereg_invalid_file",__('Invalid File.','piereg' )));
					/*$_POST['error'] = ('<strong>'.ucwords(__('error','piereg')).'</strong>: '.apply_filters("piereg_invalid_file",__('Invalid File.','piereg' )));*/
				}
			}
			elseif($field['file_types'] == ""){
				$temp = explode(".", $_FILES[$field_slug]["name"]);
				$extension = end($temp);
				$upload_dir = wp_upload_dir();
				$temp_dir = $upload_dir['basedir']."/piereg_users_files/".$user_id;
				wp_mkdir_p($temp_dir);
				$temp_file_name = "file_".crc32($user_id."_".$extension."_".time()).".".$extension;
				$temp_file_url = $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
				if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name) && $required){
					$errors->add( $field_slug , '<strong>'.ucwords(__('error','piereg')).'</strong>: '.apply_filters("piereg_fail_to_upload_profile_picture",__('Fail to upload profile picture.','piereg' )));
				}else{
					//$_POST[$field_slug] = $temp_file_url;
					update_user_meta($user_id,"pie_".$field_slug, $temp_file_url);
				}
			}
		}else{
			update_user_meta($user_id,"pie_".$field_slug, "");
		}
	}
	
	function piereg_get_cookie($c_name){
		$pieregcookie = $_COOKIE[$c_name];
		$pieregcookie = stripslashes($pieregcookie);
		$pieregcookie = json_decode($pieregcookie, true);
		echo '<pre>';
		print_r($pieregcookie);
		echo '</pre>';
		return $pieregcookie;
	}
	
	
	
	
	
}