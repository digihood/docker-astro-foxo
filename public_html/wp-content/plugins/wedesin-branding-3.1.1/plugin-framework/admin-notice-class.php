<?php

/**
 * Helper to work with Wordpress admin notices
 *
 * @example
 * D1g1Notice::success('All is good!');
 *
 * @example
 * D1g1Notice::warning('Do something please.', true);
 *
 * @example
 * D1g1Notice::info('Are you ok?', function ($id) {
 *     return 'jQuery(document).on("click", "#' . $id . ' .notice-dismiss", function () {
 *         // do something on dismiss...
 *     });';
 * });
 */
if(!class_exists('D1g1Notice')){

    class D1g1Notice {
        private static $_instance = null;
        public $notices = [];
        public $scripts = [];

        public function __construct()
        {
            // @see https://codex.wordpress.org/digibrand_API/Action_Reference/admin_notices
            add_action('admin_notices', [&$this, 'display']);
            // @see https://codex.wordpress.org/digibrand_API/Action_Reference/admin_footer
            add_action('admin_footer', [&$this, 'scripts']);
        }

        public function display()
        {
            echo implode(' ', $this->notices);
        }

        public function scripts()
        {
            echo implode(' ', $this->scripts);
        }

        public static function getInstance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new D1g1Notice();
            }

            return self::$_instance;
        }

        /**
         * @param $message - Message to display
         * @param string $type - Type of the notice (default: '')
         * @param mixed $isDismissible - If true, allow to dismiss. Could be a function that return javascript.
         */
        public static function add($message, $type = '', $isDismissible = null)
        {
            $instance = self::getInstance();

            $identifier = 'notice-' . (count($instance->notices) + 1);
            $notice = '<div class="notice' . (empty($type) ? '' : ' notice-' . $type) . (is_null($isDismissible) ? '' : ' is-dismissible') . ' d1g1-notice" id="' . $identifier . '" style="padding: 15px;">' . $message . '</div>';

            if (is_callable($isDismissible)) {
                $instance->scripts[] = '<script>' . $isDismissible($identifier) . '</script>';
            }

            $instance->notices[] = $notice;
        }

        /**
         * Methoda pro více zprav v array
         * 
         * @param array $messages - array zprav
         * @param string $type - Type of the notice (default: '')
         */
        static function add_array($messages, $type = '', $isDismissible = null)
        {
            $instance = self::getInstance();
            $identifier = 'notice-' . (count($instance->notices) + 1);
            $notice = '<div class="notice' . (empty($type) ? '' : ' notice-' . $type) . (is_null($isDismissible) ? '' : ' is-dismissible') . ' d1g1-notice" id="' . $identifier . '" style="padding: 15px;">';
        
            foreach ($messages as $message) {
                if(is_array($message)){
                    foreach ($message as $msg) {
                        $notice .= '<p>' . $msg . '</p>';
                    }
                }else{
                    $notice .= '<p>' . $message . '</p>';
                }
            
            }
            $notice .= '</div>';
            $instance->notices[] = $notice;
        }

        /**
         * @param string $message - Message to display
         * @param mixed $isDismissible - If true, allow to dismiss. Could be a function that return javascript.
         */
        public static function info($message, $isDismissible = null)
        {
        if(is_array($message)){
                self::add_array($message, 'info', $isDismissible);
            }else{
                self::add($message, 'info', $isDismissible);
            }
        }

        /**
         * @param string $message - Message to display
         * @param mixed $isDismissible - If true, allow to dismiss. Could be a function that return javascript.
         */
        public static function error($message, $isDismissible = null)
        {
        if(is_array($message)){
                self::add_array($message, 'error', $isDismissible);
            }else{
                self::add($message, 'error', $isDismissible);
            }
        }

        /**
         * @param string $message - Message to display
         * @param mixed $isDismissible - If true, allow to dismiss. Could be a function that return javascript.
         */
        public static function success($message, $isDismissible = null)
        {
        if(is_array($message)){
                self::add_array($message, 'success', $isDismissible);
            }else{
                self::add($message, 'success', $isDismissible);
            }
        }

        /**
         * @param string $message - Message to display
         * @param mixed $isDismissible - If true, allow to dismiss. Could be a function that return javascript.
         */
        public static function warning($message, $isDismissible = null)
        {
            if(is_array($message)){
            
                self::add_array($message, 'warning', $isDismissible);
            }else{
                self::add($message, 'warning', $isDismissible);
            }
        }

        /**
         * Method for html box with title and message without notice
         * 
         * @param string $title - Title of the box
         * @param string $message - Message to display
         * @param string $type - Type of the notice (default: '')
         */
        static function Box($title, $text) {
            $box = '<div class="box">';
            $box .= '<div class="title">' . $title . '</div>';
            $box .= '<div>' . $text . '</div>';
            $box .= '</div>';
        
            return $box;
        }
    }

}