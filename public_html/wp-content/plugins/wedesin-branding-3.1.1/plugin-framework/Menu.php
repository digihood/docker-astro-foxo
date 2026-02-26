<?php
namespace digibrand\Framework;

class Menu
{
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'my_admin_menu' ) );
    }

    function my_admin_menu() {
        add_menu_page( 'My Top Level Menu Example', 'Top Level Menu 11', 'manage_options', 'example11', [$this,'display_text'] );
     }
     
     
     function display_text(){
        // Vložení záhlaví
        echo '<div class="wrap"><h1 class="wp-heading-inline">Můj plugin</h1></div>';
        // Obsah stránky
        echo '<div class="container">';
        echo '<h2> Nastavení pluginu </h2>';
        echo '<form action="" method="post">';
        echo '<div class="form-group">';
        echo '<label for="my_plugin_option">Možnost 1:</label>';
        echo '<input type="text" class="form-control" name="my_plugin_option" id="my_plugin_option" placeholder="Zadejte možnost 1">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="my_plugin_option2">Možnost 2:</label>';
        echo '<input type="text" class="form-control" name="my_plugin_option2" id="my_plugin_option2" placeholder="Zadejte možnost 2">';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary">Uložit změny</button>';
        echo '</form>';
        echo '</div>'; 
     }


}  

