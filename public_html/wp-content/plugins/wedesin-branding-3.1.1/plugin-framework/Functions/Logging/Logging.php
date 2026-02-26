<?php

/**
 * WP_Logger Class
 * 
 * Třída pro logování událostí v rámci WordPressu.
 * Umožňuje logování různých úrovní zpráv do souborů podle kanálu.
 *  	// \DigiLog::log('santa', 'INFO', 'D1G1_SITE_BOX_LANG_SHOW: '.print_r(D1G1_SITE_BOX_LANG_SHOW,true),['array' => 'value']);
 * @author Vaše jméno
 * @version 1.0
 */
 
 if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if(!class_exists('DigiLog')){
    class DigiLog {
        private static $log_dir;
        private static $initialized = false;

        const LEVELS = [
            'DEBUG',
            'INFO',
            'WARNING',
            'ERROR',
        ];

        /**
         * Inicializuje složku pro logy.
         */
        private static function initialize() {
            if (self::$initialized) return;

            $upload_dir = wp_upload_dir(); 
            
            self::$log_dir =  $upload_dir['basedir']. '/digi-logs/';


            if (!file_exists(self::$log_dir)) {
                mkdir(self::$log_dir, 0755, true);
            }

            self::$initialized = true;
        }

        /**
         * Zaznamenává zprávu do logu.
         * 
         * @param string $channel Kanál pro logování.
         * @param string $level Úroveň logování.
         * @param string $message Zpráva k logování.
         * @param array $data Dodatečná data k logování.
         */
        public static function log($channel, $level, $message, $data = []) {
            self::initialize();

            if (!in_array($level, self::LEVELS)) {
                throw new Exception('Invalid log level.');
            }

            $filename = self::$log_dir . $channel . '.json';
            $log_entry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'level' => $level,
                'message' => $message,
                'data' => $data,
                'called_from' => self::get_called_from(),
            ];

            $current_logs = [];
            if (file_exists($filename)) {
                $current_logs = json_decode(file_get_contents($filename), true);
            }

            $current_logs[] = $log_entry;
            file_put_contents($filename, json_encode($current_logs, JSON_PRETTY_PRINT));
        }

        /**
         * Získává informace o místě volání metody.
         * 
         * @return string Informace o souboru a řádku volání.
         */
        private static function get_called_from() {
            $trace = debug_backtrace();
            $caller = $trace[1];
            return $caller['file'] . ':' . $caller['line'];
        }

        /**
         * Získává logy pro daný kanál.
         * 
         * @param string $channel Kanál pro získání logů.
         * @return array Pole logovaných zpráv.
         */
        public static function get_logs($channel) {
            self::initialize();

            $filename = self::$log_dir . $channel . '.json';
            if (file_exists($filename)) {
                return json_decode(file_get_contents($filename), true);
            }
            return [];
        }
    }
}
