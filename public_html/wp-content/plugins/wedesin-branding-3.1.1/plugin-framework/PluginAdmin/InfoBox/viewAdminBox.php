<?php 
namespace digibrand\framework\pluginAdmin\InfoBox;
use digibrand\framework\pluginAdmin\InfoBox\getRestApiRequest;
use digibrand\framework\d1g1UTM;
use digibrand\framework\Globals;

/**
 * Zobrazení Rest API dat v adminu
 * 
 * @author digihood
 */ 


if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
if( ! class_exists( 'viewAdminBox' ) )
{
    
	class viewAdminBox
	{
		
		

		public function __construct()
		{
			add_action( 'wp_dashboard_setup', [$this, 'register_dashboard_widget'] );
		}
		/**
		 *  zobrazeni boxu
		 *
		 * @param none
		 * 
		 * @author digihood
		 * @return string
		 */
		

		        /**
         * Registrace widgetu na dashboard
         */
        public function register_dashboard_widget() {
            wp_add_dashboard_widget(
                'digihood_dashboard_widget', // ID widgetu
                __('Digihood Dashboard Box', 'textdomain'), // Název
                [$this, 'render_dashboard_widget'] // Callback pro obsah widgetu
            );
        }

		
		
		/**
         * Zobrazení obsahu widgetu
         */
        public function render_dashboard_widget() {
            $this->display_box('pluginsnews'); // Typ může být přizpůsoben
        }
		
        public function d1g1_dashboard_widget_display($type){
			$this->display_box($type);
		}
		/**
		 *  box nastenka 
		 *
		 * @param type (default: plugins)
		 * @author digihood
		 * @return string
		 */

		private function display_box($type="plugins") { 
			$lang = get_bloginfo('language');
		
			if(in_array($lang,(D1G1_SITE_BOX_LANG_SHOW))){
				$rApi = new getRestApiRequest;
				$data = $rApi->get_data($lang, $type);
				
				if($data) {
					echo '<div class="card'.($type == 'plugins' ? ' info' : '').'">';

						if ($type == 'pluginsnews'){ 
							echo 	'<h2">' .__("Novinky na webu digibrands DigiHood", Globals::$FWDIGI_PLUGSLUG).'</h2><br>';
						}
						?>

						<div class="d1g1-widget">
						<?php 
							foreach ($data as $post) {
								if($post){
									$this->get_widget($post,$type);
								}
							}
						?>
						</div>
						<?php
					echo '</div>';
				}
			}
		}
		/**
		 *  zobrazí obsah postu
		 *
		 * @param post
		 * @param plugin
		 * 
		 * @author digihood
		 * @return string
		 */
		private function get_widget($post, $type){
			if ( !isset($post['title']) || !isset($post['content']) || empty($post['title']) || empty($post['content']) ) return;
			$level = ($type == 'plugins' ? '2' : '3');
			echo '<h'.$level.' '.($type == 'plugins' ? '>' : 'style="font-size:18px">') .' '.$post['title'].'</h'.$level. '>';
			echo $post['content'];

			echo (isset($post['thumbnail']) && !empty($post['thumbnail']) ? '<img src="'.$post['thumbnail'].'" style="width:100%;height:auto;margin:5px 10px 10px 0;">' : '');
			
			if (!isset($post['button']['url']) || empty($post['button']['url']) || !isset($post['button']['text']) || empty($post['button']['text'])) return;
			$utm = new d1g1UTM('',$post['button']['url']);
			$url = $utm->base_url();
			echo '<a href="'.$url.'" target="_blank" class="button button-primary watermelon">'.$post['button']['text'].'</a>';
		}
	}
}

if( is_admin() )
	new viewAdminBox;


