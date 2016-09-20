<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'contthrn_wo5297');

/** MySQL database username */
define('DB_USER', 'contthrn_wo5297');

/** MySQL database password */
define('DB_PASSWORD', 'tgGvihB3rx5x');

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
define('AUTH_KEY', 'HF_sNbzIlNASo+EYj}zMcExlN^-+!rQH>p@fUfX=V?G|Y}rc;P$zwCLmfRNxBiOu]Dy?)Le[AVIXXNFnmmwkA|]Gue%!z%p=oMsnyQpogME}ATfyp|X*llr}f?zB>Dqq');
define('SECURE_AUTH_KEY', 'z]H?Kl&g?zEGfCFRPGXrCjqCC>Fb=VHSyVmuR%h^IwVYYYD;Bj;h>BNxm+U_+!neoskDblW&V>Zs&EB[}iqlBgwwsAqSN<g/{@;oZ(_$QeXoUzBw/aVhc>^!wb+/l=gG');
define('LOGGED_IN_KEY', 'KpGKd)|}F>*LP@WUhSof$=xJ%X/cpF>]Yoc=A*>(Z/&N<bx;;qE$[h%uxrf;hcP^u;vBh_oFMf%fBWobz+xv!{;il]GKQh@md}-IYduTjZdH^cazPtAjW;jf*DIONcbD');
define('NONCE_KEY', '@HdIeFpde!-^Ggi>*f_DRAQLEEv$FzFi=O-TJt^-|^oAU<AV(@-_AXmt?MwfuYlK=h<?xjuViwRL|_?/*sGj(Fq$-^OUo-I}U(ahPc?b|v&w$r!YpuD$ZdUcmYC]pfay');
define('AUTH_SALT', 'J_WdoZrCl(xqi_]vRDmFWIEbOA%SwlTpUQ{nM[fp*ORw$Y/Kad@r&m^DD$<RObt=Ej^?SOyhfpAMM]mBLwu;bC?cJS^G*PsJ_Ehu&tZYT$sWKrZ?*IE&N%&]FAPlc%X(');
define('SECURE_AUTH_SALT', 'mKGxGNAINUu{eSsB]w+pw;)P-d^<gaMdkV+PZmfoPohFtDokGafgN^(iQh-i[^/JuoQ<s<{CjwIa%->YU}m|!?T(-(ppNx}C_/tgPr&>%d)|Kh%HZtAH}elklM_YUREK');
define('LOGGED_IN_SALT', 'M=(J%<+={+e}vAftQgr?/p&Z$<c$tylUP)@tKF!UMCnu^{<XW*fdXzodIIGEcY&u/{%M;qVFK&W+Mr*zhk%xYQYuTv)ubh-dsMfOkhn&lIQ>c_JLEqaHh%Y]f)Ad^TLd');
define('NONCE_SALT', 'WhJ<UL@Yv(I)t^qg(GKrQyE*E=MWd!-Oay!^vu-Lo]Dis{)&kQkeHryl+G;nt+Mz{CP{@cTFV<PucQ=!V>jC)<i]adpdt_XQ>Gi]+|m?fRW&Pc$^B&>cALnsvc^}As%J');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_kkaa_';

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

/**
 * Include tweaks requested by hosting providers.  You can safely
 * remove either the file or comment out the lines below to get
 * to a vanilla state.
 */
if (file_exists(ABSPATH . 'hosting_provider_filters.php')) {
	include('hosting_provider_filters.php');
}
