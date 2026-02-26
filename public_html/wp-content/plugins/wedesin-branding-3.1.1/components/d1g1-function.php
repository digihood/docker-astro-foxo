<?php 
namespace digibrand\components;


use digibrand\framework\Globals;
use digibrand\framework\d1g1Session;
/**
 * Popis třídy
 *
 * 
 * @author digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

   



if( ! class_exists( 'D1G1FormFunction' ) )
{
	class D1G1FormFunction 
	{
       private $fields;
       private $session;
       private $type_notices;
      
		public function __construct($field_list = [])
		{
            $this->fields = $field_list;
            
         //   add_action( 'd1g1_test', [$this,''] );
        
         add_action( Globals::$FWDIGI_PLUGINID.'_before_save', [$this,'d1g1_form_save_option'],10,3);
        
         
        }

         /**
         * Odeslat email s aktualizací
         *
         * @author digihood
         * @return true/false
         */ 

 
            // add_action('digi_test','d1g1_testfefe');

            public function d1g1_form_save_option( $rules) {
              
               
                switch ($rules->get_action()) {
                    case 'send_mail':
                        $this->session = new d1g1Session;       
                       
                        $this->send_update_email($rules->values, $rules->form_id); 
                        break;
                    case 'send_mail_anthor':
                        $this->session = new d1g1Session;       
                        $result = $this->send_update_email($rules->values, $rules->form_id,true); 
                        break;
                    
                    }
                
                
                
                
             }

          
        function d1g1_settings_email($settings = []){
            $settings = [
                'footer_bg_color'        => Globals::d1g1_get_option('brandEmailsSettings','footer_bg_color'),
                'footer_color'           => Globals::d1g1_get_option('brandEmailsSettings','footer_color'), 
                'header_logo'            => Globals::d1g1_get_option('brandEmailsSettings','header_logo'), 
                'reply_to_email'         => Globals::d1g1_get_option('brandEmailsSettings','reply_to_email'), 
                'license_email_subject'  => Globals::d1g1_get_option('brandEmailsSettings','license_email_subject'), 
                'send_to_email'          => Globals::d1g1_get_option('brandEmailsSettings','send_to_email'), 
                'Links_color'            => Globals::d1g1_get_option('brandEmailsSettings','Links_color'),
            ];
            return $settings;

        }
         

        function send_update_email($inputs, $formID,$other = false){
            $mail = Globals::d1g1_get_option('brandEmailsSettings', 'send_to_email' );           
            if (empty($mail)) {
                $mail = get_option( 'admin_email' );
            }
            global $global;
            $colors = $this->d1g1_settings_email();
            $content = $this->update_email_content($inputs,$other);
            $footer = __('Děkujeme, že využíváte naše služby. V případě problémů s aktualizací se na nás neprodleně obraťte na emailu <a href="mailto:jan@digihood.cz" style="color:'.$colors['Links_color'].';">jan@digihood.cz</a>', D1G1_BRANDING);
            $subject = Globals::d1g1_get_option('brandEmailsSettings','license_email_subject');
            if (empty($subject)) $subject = __('Aktualizovali jsme váš web', D1G1_BRANDING);
           
            $email = new \D1G1SendEmailNew($mail,$this->d1g1_settings_email());
           
            //zde zprovoznit emaily
            $text = $email->email_content('', array($content),$footer );
           
            //uložení aktualizace
            $this->session = new d1g1Session;
          
        
            if ($this->save_update_data($inputs)) {
               $t =  $email->send_client_emails($mail, $subject ,$text);
         
               $this->type_notices = 'success';
               add_action('admin_notices', [$this, 'send_mail_notices']);
                
                
               
            } else {
                $this->type_notices = 'error';
                add_action('admin_notices', [$this, 'send_mail_notices']);
               
            }
          
            
           
        }

        public function save_update_data($inputs){
            $update_list = get_option('_d1g1_list', []);
            
            $new = $this->update_list($inputs);
            
            if($new) {
                $update_list[strtotime('now')] = array(
                    'user'=> get_current_user_id(),
                    'update'=> $new
                );
               
                update_option('_d1g1_list',$update_list);
                return true;
            } else{
                return false;
            }



        }
        
        public function update_list($inputs) {
            global $wp_version;
            $content = '';
            if(isset($inputs['updateReport_up_wp']) && !empty($inputs['updateReport_up_wp'])) {
                if ($wp_version){
                    $content .= __('Wordpress aktualizován na verzi', 'D1G1BRAND').' '. $wp_version.'<br>';
                }else {
                    $content .= __('Wordpress aktualizován na nejnovější verzi', 'D1G1BRAND').'<br>';
                } 
            }
            if(isset($inputs['updateReport_up_theme']) && !empty($inputs['updateReport_up_theme'])) {
                $content .= __('Šablona byla aktualizován na nejnovější verzi', 'D1G1BRAND').'<br>';
            }
            if(isset($inputs['updateReport_up_settings']) && !empty($inputs['updateReport_up_settings'])) {
                $content .= __('Na webu jsme aktualizovali nastavení webu (zabezpečení, zálohy, php, nastavení eshopu)', 'D1G1BRAND').'<br>';
            }
            if(isset($inputs['updateReport_up_plugins']) && !empty($inputs['updateReport_up_plugins'])) {
                $content .= '<p><b>'. __('Aktualizované pluginy', 'D1G1BRAND').':</b></p>';
                $plugins_list = get_plugins();
                foreach ($inputs['updateReport_up_plugins'] as $key => $value) {
             
                    if( $value && array_key_exists($key, $plugins_list )){
                       
                        if (isset($plugins_list[$key])){
                          
                            $name = (isset($plugins_list[$key]['Name'])? $plugins_list[$key]['Name'] : '');
                            $version = (isset($plugins_list[$key]['Version'])? $plugins_list[$key]['Version'] : '');
                     
                            if ($name && $version) {
                                $content .= $name . ' '. __('Aktualizováno na verzi', 'D1G1BRAND'). ' '. $version. '<br>';
                            }
                        }
                    }
                }
            }
            if(isset($inputs['updateReport_up_content']) && !empty($inputs['updateReport_up_content'])) {
                $content .= '<p><b>'.__('Poznámka k aktualizaci', 'D1G1BRAND').'</b></p>';
                $content .= '<div>'.$inputs['updateReport_up_content'].'</div>';
            }
     
            return $content;

        }
        public function url_without_http() {
            $http = substr(get_home_url(), 0, 5);
                if ($http == 'https'){
                    return str_replace('https://','',''.get_home_url().'');
                }else {
                    return str_replace('http://','',''.get_home_url().'');
                }

        }

        public function update_email_content($inputs,$other) {
         $colors = $this->d1g1_settings_email();
            $text = Globals::d1g1_get_option('brandEmailsSettings','mail_text_license_d1g1');
            
            $content = "";
            if ($text) {
                $content = $text;
            } else {
                $content .=__('Dobrý den', 'D1G1BRAND').',<br><br>';
                $content .= __('právě jsme aktualizovali Váš web', 'D1G1BRAND').' <a href="'.get_home_url().'" style="color:'.$colors['Links_color'].';">'.$this->url_without_http().'</a>.<br><br>';
            }

            if(!$other){
                $content .= '<h3>'.__('Přehled aktualizací:', 'D1G1BRAND').'</h3>';
                $content .= $this->update_list($inputs);
            }
            return $content;

        }
      
        function send_mail_notices($type_notices){
            ?>
                <div class="notice notice-<?= $this->type_notices; ?> d1g1-notice is-dismissible">
                    <?php 
                        switch ($this->type_notices) {
                            case 'error':
                                echo '<p>'. __('Hlášení neodesláno', 'digi-framework'). '</p>';
                                break;
                            
                            case 'success':
                                echo '<p>'. __('Hlášení odesláno', 'digi-framework'). '</p>';
                                break;
                        }
                    ?>
                    
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Skrýt toto upozornění.</span></button>
                </div>
            <?php
        }

       
    }
    new D1G1FormFunction;
}