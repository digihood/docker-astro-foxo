<?php 
namespace digibrand\framework\pluginSystem;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }

/**
 * A base class for WordPress plugins with activation-time prerequisites.
 *
 * The method check_plugin_requirements() must be extended in a child class.
 * This function must return a string or an array of strings containing the
 * descriptions of unmet requirements for the plugin activation. If the
 * requirements are met, the method must return an empty value (e.g. an empty
 * array or false).
 *
 * The child class must call the add_activation_hooks() method. The filepath
 * of the plugin main file must be given as a parameter. Typically, this would
 * be the __FILE__ magic constant of the child class.
 *
 * For the benefit of pretty messages, the child class should define a
 * PLUGIN_NAME constant (for PHP > 5.3) or call the add_activation_hooks()
 * method with the second $plugin_name parameter set to the same effect.
 */

    abstract class d1g1PluginRequirements {
    
        private $_plugin_main_filepath;
        private $_req_check_option;
        private $_plugin_name = '';
    
        /**
         * Register the necessary activation hooks.
         * This method should be called in the main plugin constructor.
         * @param   string  $filepath       The path to the plugin main file
         * @param   string  $plugin_name    (Optional) Human readable plugin name
         */
        public function add_activation_hooks( $filepath, $plugin_name = '' ) {
            if ( ! file_exists( $filepath ) )
                throw new InvalidArgumentException( 'Invalid filepath' );
            $this->_plugin_main_filepath = $filepath;
            $this->_req_check_option = basename( $filepath ) . '-reqs-failed';
            if ( $plugin_name )
                $this->_plugin_name = $plugin_name;
            register_activation_hook(
                $this->_plugin_main_filepath,
                array( $this, 'activate_plugin_callback' )
            );
            register_deactivation_hook(
                $this->_plugin_main_filepath,
                array( $this, 'deactivate_plugin_callback' )
            );
            if ( get_site_option( $this->_req_check_option ) ) {
                add_action(
                    'admin_notices',
                    array( $this, 'show_activation_error' )
                );
                add_action(
                    'admin_init',
                    array( $this, 'force_deactivate' )
                );
            }
        }
    
        /**
         * Callback for the plugin activation.
         * Calls the check_plugin_requirements() method. You may extend this method
         * in a child class, but remember to call parent::activate_plugin_callback()
         */
        public function activate_plugin_callback() {
            $reqs_failed = $this->check_plugin_requirements();
            if ( !empty( $reqs_failed ) )
                update_site_option( $this->_req_check_option, $reqs_failed );
            else
                delete_site_option( $this->_req_check_option );
        }
    
        /**
         * Callback for plugin deactivation.
         * Included here for the sake of completeness.
         */
        public function deactivate_plugin_callback() {
        }
    
        /**
         * Deactivate plugin callback.
         * For the admin_init hook.
         */
        public function force_deactivate() {
            deactivate_plugins( $this->_plugin_main_filepath );
        }
    
        /**
         * Check the plugin requirements
         *
         * This method must be overridden in a child class. This is where
         * the plugin activation requirements are checked. The method must
         * return either a string or an array of strings containing the
         * descriptions of the FAILED requirements, e.g. "PHP version must
         * be greater than 5.3". On success, return an empty array or an
         * empty string.
         *
         * @return  mixed   A string or an array of strings containing
         *                  descriptions of failed requirements. Return empty
         *                  if everything is OK to proceed with activation.
         */
        abstract protected function check_plugin_requirements();
    
        /**
         * Print the activation error.
         */
        public function show_activation_error() {
            $error_list = (array) get_site_option( $this->_req_check_option );
            if ( ! $this->_plugin_name && function_exists( 'get_called_class' ) ) {
                $called_class = get_called_class();
                if ( defined( $called_class . '::PLUGIN_NAME' ) )
                    $this->_plugin_name = $called_class::PLUGIN_NAME;
            }
            echo "<div class='error'><p>";
            if ( $this->_plugin_name ) {
                printf(
                    "The plugin <strong>%s</strong> could not be activated.",
                    $this->_plugin_name
                );
            } else {
                printf(
                    "Plugin <strong>activation failed</strong> in <code>%s</code>.",
                    basename( $this->_plugin_main_filepath )
                );
            }
            echo "</p><ul class='ul-disc'>\n";
            foreach ( $error_list as $error ) {
                echo "<li>$error</li>\n";
            }
            echo "</ul></div>";
            remove_action( 'admin_notices', array( $this, 'show_activation_error' ) );
            delete_site_option( $this->_req_check_option );
            unset( $_GET['activate'] ); // Prevents printing the "Plugin activated" message.
        }
    
    }
