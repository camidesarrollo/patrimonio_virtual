<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "-confhp"php and fill in the values. bastian was here
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

// ** MySQL settings - You can get this info from your web host ** //
//define( 'DB_NAME', 'dev5_patvirtual_qa' );
//define( 'DB_USER', 'dev5_patvirtual_qa' );
//define( 'DB_PASSWORD', 'dev_ppvDB2022' );
//define( 'DB_HOST', '10.0.1.109' );

define( 'DB_NAME', 'pro_patvirtual' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );

/** The name of the database for WordPress */
//define( 'DB_NAME', 'ab20067_biblioredes' );

/** MySQL database username */
//define( 'DB_USER', 'ab20067_biblio' );

/** MySQL database password */
//define( 'DB_PASSWORD', 'xYZbU@qXMD24' );

/** MySQL hostname */
//define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define('CLAVE_SECRETA_ENCRIPTACION', 'K7#p9E2z!R5vX@8qY6sD3gFwL1mN4cHnB');
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

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

define('WP_CACHE', false);

// define('WP_DEBUG_LOG', true);
// define('WP_DEBUG_DISPLAY', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

if ( ! defined( 'COOKIE_DOMAIN' ) ) {
	define('COOKIE_DOMAIN','.localhost/patvirtual/');
}

#define('COOKIE_DOMAIN','.biblioredes.gob.cl');
define( 'WP_HOME', 'http://localhost/patvirtual/' );
define( 'WP_SITEURL', 'http://localhost/patvirtual/' );
define( 'WP_ENV', 'development' );

#define('FORCE_SSL_ADMIN', true);
#define('RELOCATE', TRUE);
#$_SERVER['HTTPS'] = 'on';
header("Content-Security-Policy: frame-ancestors 'self' *.gob.cl;");
