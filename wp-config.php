<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'guilbfr');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'o,,@>:VJ7CdZIpwKp[/HdTTMqjH|)sbxUyc664 <d: 0I@;dz16QCUa}S/3sns%[');
define('SECURE_AUTH_KEY',  'z}bt]9yO~b8uBb17,(=J?{es^vo5wgwvL@(8z=j#W{2VQ*ylm*L1tt#Lmi{h(7T(');
define('LOGGED_IN_KEY',    ';~iG{(qq= M6cf$`{mkwLEH_n@Ac@{e=.&dQXZ]>^[UP0!Q^M,jEhTZ*^wFNYOJB');
define('NONCE_KEY',        'Z1Y/<Fmn7rkzpb@l]bF}T4_i!q}`G^`$w_/Bq[p 4nwztP@G}:xf1A:ZaD!CBaL,');
define('AUTH_SALT',        'hAT<9bpf6PUBcu!]o5]-:l>G 93qJF:k:9u?hbJ0uC3Ciw=U06/Dx5,89h!dx^x.');
define('SECURE_AUTH_SALT', '2@0BRdu:GP{o~L;Jjsjez>7ZfV6|gTn_m);2=nL.7%jU4t>Onh,gD]?CV+~g^)ak');
define('LOGGED_IN_SALT',   '&Ev0r]-;s4x^(%N+%iAEdCTHbW{_XV%hEhsD)~7Lzx/3Vm#VNb=Xw<2ICk!@kTL6');
define('NONCE_SALT',       ']gb4ULw/T`EGurpmQ$?P&Hv;IZ_!jB9||{DhQX!)E%@u;,qER!iPt|0Wn%6u5_#,');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');