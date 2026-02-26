<?php

define( 'ITSEC_ENCRYPTION_KEY', 'bkN3Vl5yOyl+ODB+YDk2czBNe35RW30+Jjp+LnleOXUvU3VKaGZ7RGR7UXNMfnIsbT40RzVAMUVrNWlVNHJyeg==' );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */


define('VITE_DEVELOPMENT', true); //false pro produkci

define('FS_METHOD', 'direct');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'database' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'pwRadX^{dPEO)k3.k9pJz(bGY cR4,Gg^[ gJ.733+t6cjzI1XA9HuUflrCO.f/l' );
define( 'SECURE_AUTH_KEY',  '*3;2gzdIMow@PQ,fhNT0IQWOD|a@/_dG&EO:U&C5d!2GF$hT/FESzo>K)bxY}W97' );
define( 'LOGGED_IN_KEY',    'X.TMni`[P7)3h&Pq`QofY^?B$-=8GY3z1x(zc]k~n69[WRtN,bk9wac7wL.GThq.' );
define( 'NONCE_KEY',        'f;*+u!f#o$FK;v5PV{gD)T`}i{:tKQx!pHZ_)W`_|R-_l2*;$yIr`K2L6+K<.cKK' );
define( 'AUTH_SALT',        'E%@];ZCCeS>x-Ig[a1;t[<A!_ZBPX^&Qd-m2Z}Sl&EX={UlJr0MszuTMtSN=+N~V' );
define( 'SECURE_AUTH_SALT', 'T;rULOC=7f]1`Blfz=!{{QE|LE9Q)D09seSawM+v|_a=i1D0.;X,XA(}Hd;9%@71' );
define( 'LOGGED_IN_SALT',   'v(e=!R1uT<{m*,|]jl5Q:a4fX:+%n_qP%zYA(*lh&@VGq$As9wct=k1!O}fj%ANh' );
define( 'NONCE_SALT',       '73a?0=t;l0<2+`cD>fI5Jaf!HD@tWLZC:l|Y32+]C,0*?;!BP>>f.vB~C`nQ4kH$' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
