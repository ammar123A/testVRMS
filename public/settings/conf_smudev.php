<?php
/* Server Settings */
$db_port = "1521";
$db_user = "AFM";
//$db_pwd = "afm";
//$db_host = "nb-fendy"; $db_sid = "xe";
//$db_host = "161.139.21.21"; $db_sid = "smuhrftaf"; Unused Oracle 10g
$db_host = "161.139.21.21"; $db_sid = "SMUDEV"; //as of 18 August 2014
$is_utm = true;

$db_type = "oci8";
$cstr = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=" . $db_host . ")(PORT=" . $db_port . "))(CONNECT_DATA=(SID=" . $db_sid . ")))"; //Unused Oracle 10g
//$cstr = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=" . $db_host . ")(PORT=" . $db_port . "))(CONNECT_DATA=(SERVICE_NAME=" . $db_sid . ")))";

/* Email Settings 
$email_param ['smtp'] = "mel.utm.my";
$email_param ['port'] = 25;
*/
$email_param ['smtp'] = "smtp-relay.gmail.com";
$email_param ['port'] = 587; 
$email_param ['user_name'] = "ifammsadmin@utm.my";
$email_param ['user_pwd'] = "i7@mm5^^master";


/* Folder Settings */
/*
$view_upload_folder = "fleet-attachment";
$upload_folder = "D:/fleet-attachment";
$supported_folder = "supported_doc";
$passenger_folder = "passenger_doc";
$passenger_list_folder = "passenger";
$vehicle_folder = "vehicle";
$driver_folder = "driver";
$maint_folder = "maint-wo";
*/
$view_upload_folder = "/vrms/fleet-attachment";
$upload_folder = "D:/fleet-attachment";
$supported_folder = "supported_doc";
$passenger_folder = "passenger_doc";
$passenger_list_folder = "passenger";
$vehicle_folder = "vehicle";
$driver_folder = "driver";
$maint_folder = "maint-wo";

/* Alert Settings */
$is_email_on = true;
$is_sms_on = true;
$reservation_day_alert = 0; // day   27-Mac-2017 Batalkan 24 hours
$reservation_cancel_alert = 1; // day
$road_tax_alert = 3; // month;
$puspakom_alert = 3; // month;
$permit_alert = 3; // month;
$license_alert = 30; // day;
$roadtax_alert = 30; // day;

// sms setting updated 6 Nov 2012 (testing url)
//$sms ['url'] = "http://202.171.41.169:8080/15888v3/smsgateway/sendsms.aspx?username=dapat15888&password=ZGFwYXQxMjM=&keyword=UTM&shortcode=15888&smsid=0&sender=%2B60194788894&servicetype=BULK&details=Testing+From+DAPAT+FOR+UTM&telco=CELCOM&guid=0";

// sms setting updated 20 Sept 2012
$sms ['url'] = "http://202.171.41.169:8080/15888v3/smsgateway/sendsms.aspx";
//$sms ['username'] = "J0505STK";
//$sms ['password'] = "rohanastk";
$sms ['username'] = "dapat15888";
$sms ['password'] = "ZGFwYXQxMjM=";
$sms ['shortcode'] = "15888";
$sms ['keyword'] = "UTM";
$sms ['smsid'] = 0;
$sms ['service_type'] = "BULK";
$sms ['guid'] = 0;

$p = trim($_GET ['p']);
if(strlen($p) == 0) $p = trim($_POST ['p']);
if(!is_file($p)) $p = "info.php";
$content_page = $p;

function send_email($receiverEmail, $subject, $content, $emailFlag = true){
	global $mail_smtp, $mail_port, $mail_username, $mail_password, $admin_email;
	
	if($emailFlag){
		$m = new MAIL;
		$m->From($admin_email);
		$m->AddTo($receiverEmail);
		$m->Subject($subject);
		$m->Text($content);
		
		if($c = $m->Connect($mail_smtp, $mail_port)) {
			if($m->Send($c)) {
				return true;
			}
			else {
				return false;
			}
		}
		
	}
}
?>