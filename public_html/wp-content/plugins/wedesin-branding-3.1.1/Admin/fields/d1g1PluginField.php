<?php 
namespace digibrand\admin\fields;
use digibrand\framework\Functions\feedback\d1g1Feedback;
use digibrand\framework\Globals;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }
  
if( ! class_exists( 'd1g1PluginField' ) )
{
    class d1g1PluginField
    {
        public static function get_fields_form($formID){
			
            $fields= [];
			if($formID == 'brandEmailsSettings'){
				$fields = [
					'headline' => 'Nastavení emailů',
					'description' => 'Pro správné používání emailů je potřeba přizpůsobit jejich vzhled a nastavení.',
					'enctype' => '',
					'sections' => [
						'section1' => [
							'headline' => 'Nastavení vzhledu emailu',
							'fields' => [  
									[
										'type' => 'box',
										'name' => 'footer',
										'label' => '',
										'saveAs' => 'options',
									],
									[
										'type' => 'color',
										'name' => 'footer_bg_color',
										'label' => 'Barva pozadí zápatí',
										'help_text' => 'Vyberte si barvu, kterou bude mít zápatí emailů',
										'value' => '#0a0a0a', // rgba(30,30,30,0.64)
										'saveAs' => 'options',
										
										'options' => [
											'width' => 'full',
										],
									],
									[
										'type' => 'color',
										'name' => 'footer_color',
										'label' => 'Barva textu zápatí',
										'help_text' => 'Vyberte si barvu, kterou bude mít text  v zápatí emailů',
										'value' => '#ff00dd', // rgba(30,30,30,0.64)
										'saveAs' => 'options',
										'options' => [
											'width' => 'half',
										],
									],
									[
										'type' => 'color',
										'name' => 'Links_color',
										'label' => 'Barva textu odkazů',
										'help_text' => 'Vyberte si barvu, kterou bude mít odkaz  v zápatí emailů',
										'value' => '#2199e8', 
										'saveAs' => 'options',
										'options' => [
											'width' => 'half',
										],
									],
									[
										'type' => 'image',
										'name' => 'header_logo',
										'label' => 'Logo do hlavičky emailu',
										'description' => 'Chcete-li vložit do emailu své logo, zde ho nahrajte',
										'help_text' => 'Chcete-li vložit do emailu své logo, zde ho nahrajte. Nahrávejte ve formátu jpg, nebo png o šířce alespoň 200px',
										'value' => '',
										'floating_label' => true,
										'saveAs' => 'options',
										'options' => [
											'width' => 'half',
										],
									],
									[
										'type' => 'text',
										'name' => 'reply_to_email',
										'label' => 'Email pro odpověď',
										'help_text' => 'Vyplňte email, na který má přijít odpověď',
										'saveAs' => 'options',
										'options' => [
											'width' => 'full',
										],
									]    

								]
							],
							'section2' => [
								'headline' => "Nastaveni emailu o aktualizacích", 
								'fields' => [
									[
										'type' => 'editor',
										'name' => 'mail_text_license_d1g1',
										'label' => 'Text emailu',
										'description' => 'Text emailu',
										'help_text' => 'Pomocný text pro editor',
										'floating_label' => true,
										'saveAs' => 'options',
									],
									[
										'type' => 'text',
										'name' => 'license_email_subject',
										'saveAs' => 'options',
										'label' => 'Předmět emailu',
										'help_text' => 'Vyplňte předmět, který má být uvedem v emailu s odeslanou licencí',
									],
									[
										'type' => 'text',
										'name' => 'send_to_email',
										'saveAs' => 'options',
										'label' => 'Email odeslat na',
										'help_text' => 'Vyplňte email, na který má být odesláno hlášení o aktualizaci',
									]
								]
					
							],
						]
					];
			
			}elseif($formID == 'updateReport'){
				
				add_filter('d1g1_button_form-'.Globals::$FWDIGI_PLUGINID.'_'.$formID,function ($text) { $text = 'Odeslat hlášení'; return $text;} );
				$plugins = [];
				$plugins_list = get_plugins();
				foreach ($plugins_list as $plugin => $value) {
					if ($plugin && isset($value['Name']))
						$plugins[$plugin] = $value['Name'];
				}

				
				$fields = [
					'headline' => 'Aktualizace',
					'description' => '',
					'enctype' => '',
					'action' => 'send_mail',
					'sections' => [
						'section1' => [
							'headline' => 'Jádro WP',
							'description' => 'Co z WP bylo aktualizováno?',
							'fields' => [
								[
									'type' => 'checkbox',
									'name' => 'up_wp',
									'label' => 'Aktualizace Wordpressu',
									'saveAs' => 'options',
								],
								[
									'type' => 'checkbox',
									'name' => 'up_theme',
									'label' => 'Aktualizace Šablony',
									'saveAs' => 'options',
								],
								[
									'type' => 'checkbox',
									'name' => 'up_settings',
									'label' => 'Aktualizace Nastavení důležitých fcí (zabezpečení, zálohy a pod.)',
									'saveAs' => 'options',
								]  
							]
						],
						'section2' => [
							'headline' => 'Pluginy',
							'description' => 'Jaké pluginy byly aktualizovány',
							'fields' => [
								[
									'type' => 'checkbox',
									'name' => 'up_plugins',
									'label' => 'Aktualizace Nastavení důležitých fcí (zabezpečení, zálohy a pod.)',
									'saveAs' => 'options',
									'options' => [
										'checkboxs' => $plugins
									],
								],
							]
							],
							'section3' => [
								'headline' => 'Připojit ke zprávě poznámku',
								'description' => '',
								'fields' => [
									[
										'type' => 'editor',
										'name' => 'up_content',
										'label' => 'Poznámka',
										'description' => 'Text emailu navíc',
										'floating_label' => true,
										'saveAs' => 'options',
									],
								]
							]
					]
				];
			}else if($formID == 'feedback'){
				$field = d1g1Feedback::field_feedback();
                $fields = $field;
			}
			return $fields;
        
        }
        public static function get_fields_cpt_form($post_type){
            $fields= [];
            if ($post_type == 'feedback') {
                $fields=[

                ];
            }
            return $fields;
        }
    }
}			