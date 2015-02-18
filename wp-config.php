<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'kuas_db_v1');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'H*tT&M;60_74ANUbbP[ <6/ESdPDM5a2I5*P}~iyO>zObt/^U|<SVoG4+0X&+49J');
define('SECURE_AUTH_KEY',  '`cURis|WC+c,$Lj1b;1~-Iw?l+NA!B<|U}z)(0pcn-Kl!j_cgfccnjsa<^8lkIek');
define('LOGGED_IN_KEY',    'b*|hV NVB+o_=Fk]+Z/_whJoB&rQ_!s6qhTM19|Qi)@<IFju1{lg+Ey:p#iUPI#R');
define('NONCE_KEY',        ':jCNXwk8tgs9S#Mj|-t[sOw4e]Z|zB{CS%w<,L#y=H+UX},){-`c]{S_L1w[n6?A');
define('AUTH_SALT',        'p50/.&eGZ)os/):m1pz|J|{tog;W~0*n=@E`e0|veD&zD{K=.-jL qp}|;tq|d6,');
define('SECURE_AUTH_SALT', 'A5EKUC-hPPGwhZ|62N1+ra3_flRZy~[&3Sk4,q(a~P8)S;#F+5P*Wd$bJW8w6Q:7');
define('LOGGED_IN_SALT',   'O y*2,>}?R++7{64NEKJ|P~IhQDJ+D$QYGC-;W<|(|<3#S9$jaw6yFzz$t.rTbx?');
define('NONCE_SALT',       'c[a><$5ixtnV[6O*O]%$MZ?8U+1M<r?(O}I)pzq|KQF|@(F4v7w-0`(%S&Z75&bx');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'kuas_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'id_ID');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('AUTOSAVE_INTERVAL', 300 ); // seconds
define('WP_POST_REVISIONS', false );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');