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
define('DB_NAME', 'hipchyk');

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
define('AUTH_KEY',         '#ccLLXgv)@/zF]d]T5b3fxiJ)0)X-N(lqRZ`fb:oLITL/E_JMJVi^V`[n@>>gk9%');
define('SECURE_AUTH_KEY',  'vL>>%]5_z~UTB)n98+]1Sk,&N6Nxy99,6ad50MmMc3g%Yy-p2zGqRh~n.5Eo$k]<');
define('LOGGED_IN_KEY',    'ThPRPq5jBbezXg**pEyjG~?R&NU`kW`1_oCf. g|1uO+W^(r//E2!UK_0q#4Oq10');
define('NONCE_KEY',        'vaqzz9F Lv_dBDqrx4!H^VyU]2:~Ggh{=TmBT)#nmrwh:(Sa`Y>E{Xj!i(!P?x{N');
define('AUTH_SALT',        '@-V0TBCqd~iII]zcdv+/:5F`h8j+5B.m~U;|xN+TCe}u(]x&yE0pocqTvUX<9[c3');
define('SECURE_AUTH_SALT', '3rW^?Tn#@oXx0lIr^!n:v[R}r!75},+%Yr](ua^?SP%mo8dS5~clD=78{1)m# T/');
define('LOGGED_IN_SALT',   'z4)*rFz~,#H3p[O2-xQV^cjJD: x,g&p|-on-B;<~!ld*>wEP4#hp$9b^^/?O.ct');
define('NONCE_SALT',       'B2COu%8x ; ia9ldu!~ceVl^|BW!9eO1y(aBWz<2?X w|F#gX^Nl+5fKsI{feG5b');

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
