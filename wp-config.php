<?php
define('WP_CACHE', true); // WP-Optimize Cache
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
define('DB_NAME', 'kd');
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
define('AUTH_KEY',         'x#7 IOMr|lSqWHKSZ6jz27krtV}RVs3RGRnAwo9/9%DZl4+)k+CN;5bM}LWHkyGs');
define('SECURE_AUTH_KEY',  '?xu%k8u,Psh]nBm:rcDM^$9ToF6~gzs)lg*$sLSe-xX +A8g d8&e:</u1^tV&~:');
define('LOGGED_IN_KEY',    'r()$,wz>pq@~;ifIS1@.<,Rf6+4PFds;3umzR:zW`KH/c=T^E8#;|f^$&Gq*53(4');
define('NONCE_KEY',        'U>ZE;xI^SJy$hk{7$m0$N]n&JKtteHQ4V<LQKOClr0L]&1# JY7[]Fv+TB;@D*f:');
define('AUTH_SALT',        'lJ0ozm*Kao<tkYIu(EN%fQv^B&PK&Xynqu.JO=?KHB7LNl?V J~tx *>52V(jnbu');
define('SECURE_AUTH_SALT', 'Pj- 161a^fJDMF]MVaY67bthqhjEb$65^(T%EN:{;[ t9;mrW[D6E!n&b3qqE[J@');
define('LOGGED_IN_SALT',   '  dQ5_jbo^eGiQ8#R`Q%G}1mT}6XLCf:}O=g)U+DM|eJjx9e#bM$bS{/%ZLDGg5R');
define('NONCE_SALT',       '{3TciGGiFJHE *gOYR.8G#=8!G7W+Vi1+fFj^a6kG(Q_{ZjktN^IZo9>jM})1?H~');
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
define('WP_DEBUG', false);
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');