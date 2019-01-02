<?php
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'stellatravel');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'S; w%ShZ#UfK)_3LsJV5I$1$0*.?5FHyJgiyM;JU@3z|-FrnR}(xTx,d8IDQBt^*');
define('SECURE_AUTH_KEY',  'APSxjEy={^7Q-gZ9n-;M>1Bu%xuBDj<U{1HGTN>~V?y;]J6b;[xA89|DzNZzWk3a');
define('LOGGED_IN_KEY',    'eAY.G2YJ!v#$TuPTL2y}&e5x[<zG5xF)jP^qSQI&-yaa:M!ls*7k]%(VIduvJ$&o');
define('NONCE_KEY',        'dkI[Arp{aHFxoa_c?pmV%lkN]oE0T=4{*`eN)~0_h?HE}ZqR 9Lbe%(!lX+TFx7s');
define('AUTH_SALT',        '61cdfG!T{;5r@Gt%9e8UL5^[C~/WL.%MSX?I}(oduq?eDgO-2C!ysJkgyHubJS&y');
define('SECURE_AUTH_SALT', '+HU_Y0W0!)[Zk[sU+?HlB, 7[)gqjWj(=Smc&Uf`lCB2l)KWSpgASEgec|Vx;.Jj');
define('LOGGED_IN_SALT',   'LYpj+}zvA(+zkwNJ f[3kK}=j-~6voqB$ZHBqh@9c?rC`rB-XD?Pqx/(/ jzn{ /');
define('NONCE_SALT',       '[p+>.vrF4,MQev<l,DF0zrveO:3=q/1YjL.x.^s4i^~-lz,idyNHHml`Y (R%~pg');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );
@ini_set( 'display_errors', 0 );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
