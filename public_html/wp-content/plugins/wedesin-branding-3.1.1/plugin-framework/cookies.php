<?php
/**
 * PHP Cookie manipulation cclass.
 *
 * @author   Malik Umer Farooq <lablnet01@gmail.com>
 * @author-profile https://www.facebook.com/malikumerfarooq01/
 *
 * @license MIT
 *
 * @link    https://github.com/Lablnet/PHP-Cookie-manipulation-Class
 */

if( ! class_exists( 'd1g1digibrandCookies' ) )
{

    class d1g1digibrandCookies
    {
        private $name; // name of cookie
        private $value; // value of cookie
        private $expire; // expire of cookie
        private $domain; // domain of cookie
        private $path; // path of cookie
        private $secure; // secure of cookie
        private $httponly; // httponly of cookie

        /**
         * __Construct set the default values.
         *
         * @return void
         */
        public function __construct()
        {
            $this->expire = 0;

            $this->domain = '/';

            $this->path = '/';

            $this->secure = true;

            $this->httponly = false;
        }

        /**
         * Set the cookie value.
         *
         * @param
         * $name of cookie
         * $value of cookie
         * $expire of cookie
         * $domain of cookie
         * $secure of cookie
         * $httponly of cookie
         *
         * @return bool
         */
        public function Set($params)
        {
            if (is_array($params)) {
                if (preg_match("/[=,; \t\r\n\013\014]/", $params['name'])) {
                    $this->name = rand(1, 25);
                } else {
                    $this->name = $params['name'];
                }

                if ($params['expire'] instanceof \DateTime) {
                    $expire = $expire->format('U');
                } elseif (!is_numeric($params['expire'])) {
                    $expire = strtotime($params['expire']);
                } else {
                    $this->expire = $params['expire'];
                }

                $this->value = $params['value'];

                //$this->domain = empty($params['domain']) ? $this->domain : $params['domain']; 

                $this->path = empty($path) ? $this->path : $params['path'];

                $this->secure = empty($params['secure']) ? $this->secure : $params['secure']; 

                //$this->httponly  = empty($params['httponly']) ? $this->httponly : $params['httponly']; 

                //set the cookie
                setcookie($this->name,  $this->value, $this->expire , $this->path /*, $this->domain,  $this->httponly*/);

                return true;
            } else {
                return false;
            }
        }

        /**
         * Check if cookie set or not.
         *
         * @param  $name of cookie
         *
         * @return bool
         */
        public function IsCookie($name)
        {
            if (isset($name) && !empty($name)) {
                if (isset($_COOKIE[$name])) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        /**
         * Get the cookie value.
         *
         * @param  $name of cookie
         *
         * @return string | boolean
         */
        public function Get($name)
        {
            if (isset($name) && !empty($name)) {
                if (isset($_COOKIE[$name])) {
                    return $_COOKIE[$name];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        /**
         * Delete the cookie.
         *
         * @param  $name of cookie
         *
         * @return bool
         */
        public function Delete($name)
        {
            if (isset($name) && !empty($name)) {
                if (self::IsCookie($name)) {
                    $this->name = $name;

                    $this->value = self::Get($name);

                    setcookie($this->name, $this->value, time() - 3600, $this->path );

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}