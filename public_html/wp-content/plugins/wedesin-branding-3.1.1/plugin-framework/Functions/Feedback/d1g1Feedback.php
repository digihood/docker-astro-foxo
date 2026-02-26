<?php 
namespace digibrand\framework\Functions\feedback;
use digibrand\framework\d1g1Session;
use digibrand\framework\monolog\d1g1MonologFunction;
use digibrand\framework\Globals;

/**
 * feedback
 *
 * 
 * 
 * 
 * @author Digihood
 */ 

 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1Feedback' ) ){

	class d1g1Feedback
	{
        private $formID = 'feedback';
        private $fields;
        private $session;
      
		public function __construct($field_list = [])
		{
           
            if(isset($_GET['tab']) && $_GET['tab'] == 'feedback'){
                add_filter( 'd1g1_button_form-'.Globals::$FWDIGI_PLUGINID.'_feedback', [$this,'button_feedback'], 10, 1 );
            }
            
            add_action(Globals::$FWDIGI_PLUGINID.'_before_save' , [$this,'feedback_send'],10 , 1 );

        }

        public function feedback_send($rules) {
            
            
             
                if($rules->get_action() == 'send_feedback'){
                   
                    $plugin = get_plugin_data(Globals::$FWDIGI_PATHSLUG . '/plugin.php');
                   
                    if(isset($rules->values['feedback_usermail']) && $rules->values['feedback_usermail']){
                        $replymail = $rules->values['feedback_usermail'];
                    }else {
                        $replymail = '';
                    }
                 
                    $result = $this->send_feedback_email($rules->values, $this->formID,$plugin,$replymail);    
                    
                    if ($result) {
                      
                        \D1g1Notice::success(__('Feedback byl úspěšně odeslán', Globals::$FWDIGI_PLUGINID),true);                                             
                    } else {
                        \D1g1Notice::error(__('Feedback nebyl odeslán', Globals::$FWDIGI_PLUGINID),true);     
                       
                    }
                   
                  
                }
            
        }
       private function send_feedback_email($inputs, $formID, $plugin , $replymail){
            $plugin_mail = 'plugins@digihood.cz';
            $settingscss = [];
            $content = $this->feedback_content($inputs,$plugin);
            $footer = __('', Globals::$FWDIGI_PLUGINID);
           
            if ($inputs['feedback_feedback_subject']) {
                $subject = $inputs['feedback_feedback_subject'];
            }else{
                $subject = __('Feedback na plugin  ' . $plugin['Name'] . '', Globals::$FWDIGI_PLUGINID);
            }
            
          //  d1g1Session::start_session();
            $email = new \D1G1SendEmailNew($replymail,$settingscss);
            //zde zprovoznit emaily
            
            $text = $email->email_content('Feedback', array($content),$footer );
           
            //uložení aktualizace
           
           
           
            if ($inputs && $subject && $inputs['feedback_feedback_content']) {
                
                $array = [];
               
               
                return $email->send_client_emails($plugin_mail, $subject ,$text);
               // d1g1Session::remove_session($formID . '_validation');
               // $T = d1g1Session::add_session($formID . '_save', 'success');
              
            
            } 
           
        }

        public function feedback_content($inputs,$plugin) {
            
            $content = __('Feedback z webu <a href="'.get_home_url().'">'.get_home_url().'</a> na plugin <b>'. $plugin['Name'] . '</b>'  , Globals::$FWDIGI_PLUGINID).'<br><br>';
            $content .= __('Od uživatele: '. $inputs['feedback_username'], Globals::$FWDIGI_PLUGINID).'<br><br>';
            $content .= __('Uživatelský email: '. $inputs['feedback_usermail'], Globals::$FWDIGI_PLUGINID).'<br><br>';
            if($inputs['feedback_userphone']){
                $content .= __('Telefon uživatele: '. $inputs['feedback_userphone'], Globals::$FWDIGI_PLUGINID).'<br><br>';
            }
            $content .= __('Uživatel se registroval: '. $inputs['feedback_dateregister'], Globals::$FWDIGI_PLUGINID).'<br><br>';
            $content .= __('Role uživatele: '. $inputs['feedback_userroles'], Globals::$FWDIGI_PLUGINID).'<br><br>';
            $content .= __('digibrand: '. $plugin['Name'], Globals::$FWDIGI_PLUGINID).'<br><br>';
            $content .= __('digibrand Version: '. $plugin['Version'], Globals::$FWDIGI_PLUGINID).'<br><br>';
            if(isset($inputs['feedback_licence']) && $inputs['feedback_licence']){
                $content .= __('digibrand Licence: '. $inputs['feedback_licence'], Globals::$FWDIGI_PLUGINID).'<br><br>';
            }
           
            $content .= '<h3>'.__('feedback: ', Globals::$FWDIGI_PLUGINID).'</h3>';
            $content .= $inputs['feedback_feedback_content'];
            return $content;

        }

        public static function feedback_session(){
            $sessins_list = [
                'feedback_save' => [
                    'fail' => __("Při odesílaní Zpětné vazby došlo k chybě, vyplnili jste obsah ??", Globals::$FWDIGI_PLUGINID), 
                    'success' => __("Zpětná vazba odeslána", Globals::$FWDIGI_PLUGINID)
                ],
            ];
            if(isset($_SESSION['feedback_save']) && $_SESSION['feedback_save'] ){
                d1g1Session::display_notice($sessins_list);
                settings_errors();
                d1g1Session::remove_session('feedback_save');
                
             }  
        }

        public function button_feedback($button){
          
        $button = __('Odeslat zpětnou vazbu', Globals::$FWDIGI_PLUGINID);
          return $button;
        
        }

        public static function field_feedback(){
            $userdata = get_userdata(get_current_user_id());
            $username = $userdata->user_login;
            $useremail = $userdata->user_email;
            $dateregister = $userdata->user_registered;
            $userroles = $userdata->roles[0];
            $userphone = get_user_meta(get_current_user_id(),'billing_phone',true);
            $fields = [
                'headline' => 'Nadpis formuláře',
                'description' => 'Popisek formuláře',
                'enctype' => '',
                'action' => 'send_feedback',
                'sections' => 
                    [
                    'section111' => [
                        'headline' => 'Zpětná vazba ',
                        'description' => 'Váš názor je pro nás důležitý. Chtěli bychom vás poprosit o zpětnou reakci.',
                        'fields' => [  
                            [
                                'type' => 'text',
                                'name' => 'feedback_subject',

                                'label' => 'Předmět',
                            ],
                            [
                                'type' => 'editor',
                                'name' => 'feedback_content',
                                'rules' => 'required',
                                'label' => 'Obsah',
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'username',
                                'required' => false,
                                'label' => 'Uživatel',
                                'value' => $username
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'usermail',
                                'label' => 'Email uživatele',
                                'required' => false,
                                'value' => $useremail
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'dateregister',
                                'label' => 'Datum registrace',
                                'required' => false,
                                'value' => $dateregister
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'userroles',
                                'label' => 'Role uživatele',
                                'required' => false,
                                'value' => $userroles
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'userphone',
                                'label' => 'Telefon uživatele',
                                'required' => false,
                                'value' => $userphone
                            ],
                            [
                                'type' => 'hidden',
                                'name' => 'licence',
                                'label' => 'Licence',
                                'required' => false,
                                'value' => 'In construct'
                            ],
                           
                        ]
                    ],
                    
                ]
            ];
            return $fields;
        }

    }

 new d1g1Feedback;
}