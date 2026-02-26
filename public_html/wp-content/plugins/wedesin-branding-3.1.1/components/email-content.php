<?php 

if ( ! defined( 'ABSPATH' ) ) {

  exit;

}



if( ! class_exists( 'sendEmailContentD1G1' ) )
 
{

	class sendEmailContentD1G1
	{
		
		public function __construct(){

		}

		/*============================================

			Email - new registration (contact seller) 
			
			HOTOVO

		============================================*/
		public function send_license_mail( $mail, $license ) {
			$class_email = new D1G1SendEmailNew();
			$subject = __('Licence od Antihackera', WDSLS);
			$title = get_option('_d1g1_d1g1licsys_license_plugin_d1g1_license_email_title',false);
			$message =  $class_email->email_content( $title, array('test'), 'test'
			);
			/*preprint($message);
			die();*/
			$class_email->send_client_emails( $mail, $subject, $message );
			return true;
		}
	}
	
}
