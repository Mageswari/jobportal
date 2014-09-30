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
define('DB_NAME', 'jobportal');

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
define('AUTH_KEY',         'f~*gM-p6 `~P ^Vfe(P80::-s+%m$?)3[?aGPX&E[<~H84_D.<mbi,q]{@<r/p6:');
define('SECURE_AUTH_KEY',  'Y.=db6W!ct(3(iNnf8Oxl7UVQ#uxDQj)W|Be!$Fs-%O+E0?[E(5,2r$Rl+;>5-|a');
define('LOGGED_IN_KEY',    '}a]R~.> <:>1W:C/V7Mz1r k?8;DxdG-bVB.C~y}QTZpaL;+#Qp9 5/#_2UyzH.d');
define('NONCE_KEY',        '67JXN]5k)`j$?Y39n+C=C-nyIu!l+yMMr#IPvk?M|,4 n;h>K^ge-1tp9~b^B~SQ');
define('AUTH_SALT',        'V2QIa>},z(0CgR.cvd8XtR=H*]Tbg[uI2u0`>@=e;KI?zT]/N/u?TwVSCaW|B,$t');
define('SECURE_AUTH_SALT', 'zn.(=9@PrmntLIb2|Vyv*rzBsrNz[Sr3+a iVEXw2U4G*.?<-CX ^~v&=6J-TqZ^');
define('LOGGED_IN_SALT',   '5R.#1L-cI3fA~N/bf#?nOFp!)Og[n(_v| J0PP}HiWN;*!FeTUwU^[d]oD2|G@2U');
define('NONCE_SALT',       ' ZW1:-vCnvBtcUC?xMiTwm3~3!{fu&npiX8U$8TNs}PA[pzIo#[MlWiEA~XLMwcr');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
