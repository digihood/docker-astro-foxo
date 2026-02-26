<?php 
/**
 * Stažení dat z webu
 * 
 * @author digihood
 */ 
namespace digibrand\framework\pluginAdmin\InfoBox;
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'getRestApiRequest' ) )
{
    
	class getRestApiRequest
	{
		private $api_url;
		private $taxpluginsnews_id = '6';
		private $taxplugins_id = '8';
  
		public function __construct()
		{ 
			// URl pro získaní dat
			$this->api_url = 'https://plugins.digihood.cz/wp-json/wp/v2/';
		}

		/**
		 *  ziskaní url 
		 *
		 * @param type (CPT)
		 * 
		 * @author digihood
		 * @return string
		 */ 

		private function get_url($type, $img_id='') {
			if ($type == 'media' && $img_id) {
				return $this->api_url . 'media/' . $img_id;
			} else {
				if($type == 'pluginsnews') { 
				return $this->api_url . $type . '/?'.$type.'_type='.$this->taxpluginsnews_id.'&per_page=1';
				} else {
				return $this->api_url . $type . '/?'.$type.'_type='.$this->taxplugins_id.'&per_page=1';
				  }
			}
			
		}

		/**
		 * odesladni jazyku  a typu
		 *
		 * @param lang,type (jazyk, a typ přispěvku)
		 * 
		 * @author digihood
		 * @return string
		 */ 

		public function get_data($lang, $type) {
			$data = $this->rest_api_post($lang, $type);
			return $data;
		}
	
		/**
		 *  Rest API přispěvků
		 *
		 * @param type,lang 
		 * 
		 * @author digihood
		 * @return string
		 */

		private function rest_api_post($lang, $type){
			try {
			// Přimé napojení Rest API na ziskanou URL 
			    $response = wp_remote_get( $this->get_url($type) );
            } catch (Error $e) {
                echo "Při stažení posledního příspěvku došlo k chybě " . $e->getMessage() . '.';
            }

			// Ukončí pokud se vyskytne error vrátí prazdno
			if ( !$response || is_wp_error( $response ) || !is_array( $response ) ) {
				
				return '';
			}

			// Získá Data
			$posts = json_decode( wp_remote_retrieve_body( $response ) );

			$array = [];
	        // Pokud je $posts prazdný vrátí prazdný pole
			if ( !empty( $posts ) ) {	
			// Data Pro kazdý Posts 
				foreach ( $posts as $post ) {
					$array[] = array (
						'title'      => ( isset($post->title->rendered) && !empty($post->title->rendered) ? $post->title->rendered : '') , 
						'content'    => ( isset($post->content->rendered) && !empty($post->content->rendered) ? $post->content->rendered : '') ,
						'thumbnail'  => $this->rest_api_img($post->featured_media),
						'button'     => 
							array( 
								'text'   => (isset ($post->acf->green_box_button->title) && !empty($post->acf->green_box_button->title) ? $post->acf->green_box_button->title : ''), 
								'url'    => (isset ($post->acf->green_box_button->url) && !empty($post->acf->green_box_button->url) ? $post->acf->green_box_button->url : '')
							)
					);

				}
			} 
			return $array;	
		}
		/**
		 *  Rest API Obrazků
		 *
		 * @param img_id
		 * 
		 * @author digihood
		 * @return string
		 */

		private function rest_api_img( $img_id ){
			// defautní type pro media
			$type = 'media';
			try {
			// Přimé napojení Rest API na ziskanou URL 
				$img_response = wp_remote_get( $this->get_url($type,$img_id) );
			} catch (Error $e) {
				echo "Při stažení posledního příspěvku došlo k chybě " . $e->getMessage() . '.';
			}
	 		// Ukončí pokud se vyskytne error a vrátí prazdno
			if ( !$img_response || is_wp_error( $img_response ) || !is_array( $img_response ) ) {
				return '';
			}
			// Pokud $img_response['body'] je vyplnený a není prazdný poté se decoduje a vratí URL obrazku 
				if (isset($img_response['body']) && !empty($img_response['body']) ) { 
					$api_img = json_decode( $img_response['body'] ); 
					if (isset($api_img->media_details->sizes->medium->source_url) && $api_img->media_details->sizes->medium->source_url){ 		
						return $api_img->media_details->sizes->medium->source_url;
					} else {
								
						return '';
					}

				}
				
			return '';
				
		}

	}

}



