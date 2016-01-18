<?php
/** Enable W3 Total Cache Edge Mode */
define('W3TC_EDGE_MODE', true); // Added by W3 Total Cache

/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'wp_qualitair_ecommerce');

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
define('AUTH_KEY',         'v}$Es+C5clO3AowLZp}R`0m7jNF<*DO>}u3p!x1=E-RoAD2jCF7|gKQx9D)HdG!@');
define('SECURE_AUTH_KEY',  'ly3j|KjfqEnQ(6w=;6C(FFm.xGO,? |mbH9;I5W~ZLc+_m%HIE<9/{K:g=;e-(,$');
define('LOGGED_IN_KEY',    'SyaYC@TE>@6^4vjfVaZN0k)KvutS-`pnL5tG(H-,?ZJPKPw3EDhMG?LwurA#>797');
define('NONCE_KEY',        'vE[:EvU!c`Je~Zl:Ci[4{.l;ZZRS!N+AZ-EU?rqHh0k7HeFxh~lI[#jC#N}wvF)m');
define('AUTH_SALT',        'ylT~-N!PCU>*|4VY;o+^Rz,?lr1V  ktv[sv{88|S.|Z8S-w3)r^tyR:R|VV d+F');
define('SECURE_AUTH_SALT', 'T<>|y 6S @%UQ+P>fg([q`3FE[jnX!T&0Ds+Aj%F2R|*2;R+l)>KthgQLy;3bh1~');
define('LOGGED_IN_SALT',   '-lQlt-|L98?rwruOrD7lI`i<ykl6|+)o|!YnYA6u_Xjf&5U+UIMRk;j^igs,+oxW');
define('NONCE_SALT',       '!oJ~|EbHrS}*4+S_-:VSc+x*1rV$sPK{p+~;t%_9|.z2d${Fm@xpDE_9:;2/CO])');

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

define('WP_ALLOW_REPAIR', true);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
