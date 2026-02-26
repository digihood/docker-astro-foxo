<?php 

//return english 
function is_eng_d1g1( ) {
    $lang = get_bloginfo('language');
    if ( $lang == "en-GB" || $lang == "en-US" || $lang == "en-AU" || $lang == "en-CA" || $lang == "en-NZ" || $lang == "en-ZA" ) {
        return true;
    } else {
        return false;
    }
}
if( ! function_exists('d1g1_get_svg') ){ 
    function d1g1_get_svg($url) {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        
        $response = file_get_contents($url , false, stream_context_create($arrContextOptions));
        return $response;
    }
}