<?php
/**
 * english language file for passpolicy plugin
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */

$lang['minlen']   = 'Longitud mínima para las contraseñas de usuario';
$lang['pools']    = 'Tipos de caracteres a utilizar en las contraseñas';
$lang['minpools'] = 'Número mínimo de diferentes tipos de caracteres que tienen que ser utilizados en las contraseñas. No puede ser mayor que el número de tipos seleccionados arriba';
$lang['user']     = 'Comprueba si la contraseña coincide contra el nombre del usuario. 0 para desactivarlo. 1 para coincidencias exactas. Cualquier otro número para el número de caracteres consecutivos que pueda estar contenida en tanto contraseña y nombre de usuario';
$lang['nocommon'] = 'Comprueba la contraseña es una de las 10.000 contraseñas más comunes.';

$lang['autotype'] = '¿Cómo generar contraseñas?';
$lang['autobits'] = 'Número mínimo de bits de información para generar contraseñas. Cuanto más alto, más seguro, pero más difícil de recordar. Mínimo: 24.';

$lang['autotype_o_random'] = 'Contraseña aleatoria';
$lang['autotype_o_pronouncable'] = 'Contraseña pronunciable';
$lang['autotype_o_phrase'] = 'Contraseña frase de múltiples palabras';

$lang['pools_lower']   = 'letras minusculas';
$lang['pools_upper']   = 'letras mayúsculas';
$lang['pools_numeric'] = 'números';
$lang['pools_special'] = 'caracteres especiales (ej. !, $, #, %, @)';

//Setup VIM: ex: et ts=4 :
