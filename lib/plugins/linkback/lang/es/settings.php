<?php

/**
 * english language file
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Gina Haeussge <osd@foosel.net>
 */

$lang['enable_pingback'] = 'Activar pingbacks (señales).';
$lang['enable_trackback'] = 'Activar trackbacks (rastreos).';
$lang['order'] = 'Orden en el que al intentar enviar linkbacks.';
$lang['range'] = 'Cuántos kilobytes se recuperarán de la página enlazada para detección automática.';
$lang['allow_guests'] = 'Permitir a los usuarios no registrados enviar linkbacks.';
$lang['enabled_namespaces'] = 'Los espacios de nombres en el que el envío de los linkbacks deben ser activadas por defecto (lista de elementos separados por coma, * activa linkbacks por defecto en toda la wiki).';
$lang['ping_internal'] = 'Conectar vínculos internos ej. para referencias cruzadas en entradas de blogs.';
$lang['show_trackback_url'] = 'Mostrar URL de trackbacks URL en los sitios habilitados';
$lang['log_processing'] = 'Registro de procesamiento de entrada de linkbacks (El registro se llamará linkback.log y se almacenará en el direcotrio de caché).';

$lang['usefavicon'] = 'Recuperar favicon entrante de linkbacks y mostrarlos.';
$lang['favicon_default'] = 'URL del favicon a usar si el linkback no tiene uno.';

$lang['antispam_linkcount_enable'] = 'Activar linkcount antispam.';
$lang['antispam_linkcount_moderate'] = 'Si linkcount excede el límite, moderar linkback en vez de eliminarlo.';
$lang['antispam_linkcount_max'] = 'El número máximo de enlaces a permitir en extracto sin tomar medidas.';

$lang['antispam_wordblock_enable'] = 'Activar bloqueo de palabras antispam.';
$lang['antispam_wordblock_moderate'] = 'Si linkback contiene palabras de la lista negra, moderar linkback en vez de eliminarlo.';

$lang['antispam_host_enable'] = 'Activar host antispam.';
$lang['antispam_host_moderate'] = 'Si el nombre del sitio y la dirección remota no coinciden, moderar linkback en vez de eliminarlo.';

$lang['antispam_link_enable'] = 'Activar vínculo antispam.';
$lang['antispam_link_moderate'] = 'Si el envío de sitio no contiene un enlace hacia nosotros, moderar linkback en vez de eliminarlo.';

$lang['akismet_enable'] = 'Activar Akismet antispam.';
$lang['akismet_apikey'] = 'La <a href="http://wordpress.com/signup/">Akismet API Key</a>. ¡Esta función no servirá sin él!';
$lang['akismet_moderate'] = 'Si Akismet clasifica un linkback como spam, moderar linkback en vez de eliminarlo.';
