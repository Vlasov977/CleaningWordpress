<?php
define('WP_CACHE', false);


// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', 'silveryp_clearni');

/** MySQL database username */
define('DB_USER', 'silveryp_clearni');

/** MySQL database password */
define('DB_PASSWORD', '12345678');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('AUTH_KEY',         'v$_L8ZV*|;[=^,K3U+L +7p])27Q-a;c2:Eh3YIXX~T?%tu>`5M+u^&VUm>Q1-7s');
define('SECURE_AUTH_KEY',  '&ui*9(9YZ+bJ;P-- [v^s;%l,#KEP>@V_/PFY+!9L+OK&/q|DsTSp0A0Mx./1>mx');
define('LOGGED_IN_KEY',    '=E6RHh+0aeh`#p+4oV,sxol-d|.wj:]+Y@f7E,=-+6D1r6m+]|LJZj.5pTt**(44');
define('NONCE_KEY',        'z`wE_OPG5i!e?l0a.9>$Dm|,>>M=jm/8/E5TR@OddQ?+bPAgP{F_~+HB<I_o 3R>');
define('AUTH_SALT',        'hg]z~h#~Vf[t+Sxo1lJ6+3N]]+WrM6G>5<*Tf;?2&Kt7AO|(ex `V;tE$|;S<!km');
define('SECURE_AUTH_SALT', 'RF8ro.XqBc6oQZnkZcn?;Cfb1~q90;$,Zumw^z&Dya+`AzD,`lAs`ZtE-|$*]T({');
define('LOGGED_IN_SALT',   '7]u&8cXRV$U^lb7J#9HsEr~!X7|j]_|p_]hwieM2l.^ft>Rb|+!Q+-KEyLS,->HJ');
define('NONCE_SALT',       'w-(w1<4g/?^59{(,H0|hy|Gq(@{ +*1f}@_k})an:E9n|Gt)$[rWvU&!]lw*@fy6');


$table_prefix = 'wp_';





define('ALLOW_UNFILTERED_UPLOADS', true);
define('WP_MEMORY_LIMIT','64M');
define('WP_DEBUG', true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
define('FS_METHOD','direct');
