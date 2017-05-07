<?php
/**
 * spanish language file
 *
 * @license      GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author       Gary Owen <>
 * @translation  Ruben Squartini <>
*/

// settings must be present and set appropriately for the language
$lang['encoding']   = 'utf-8';
$lang['direction']  = 'ltr';

// for admin plugins, the menu prompt to be displayed in the admin menu
// if set here, the plugin doesn't need to override the getMenuText() method
$lang['menu'] = 'Mover/Renombrar página';
$lang['desc'] = 'Extensión para Mover/Renombrar páginas';
$lang['inprogress'] = '(tarea)';
$lang['treelink']   = 'Como alternativa, puedes administrar una restructuración compleja de WikiUBB usando <a href="%s">Gestor de Movimiento basado en el Árbol</a>.';

//$lang['notexist']   = 'Este tema no existe aún';
$lang['notwrite']   = 'No tiene suficientes derechos para modificar esta página';
$lang['badns']      = 'Caractéres inválidos en el Espacio de Nombres.';
$lang['badname']    = 'Caractéres inválidos en el nombre de la página.';
$lang['nochange']   = 'Los cambios en el nombre de la página y del espacio no fueron cambiados.';
$lang['existing']  = 'Un documento llamado %s ya existe en %s';
$lang['root']       = '[Espacio de Nombres Raiz]';
$lang['current']    = '(Actual)';
//$lang['norights']   = 'No tiene suficientes permisos para editar uno o mas de los punteros a este documento.';
$lang['filelocked']  = 'Este documento está bloqueado - trate de nuevo en unos minutos.';
$lang['linkchange1'] = 'Enlaces a %s cambiados a %s';
$lang['newname']     = 'Nuevo nombre:';
$lang['targetns']    = 'Seleccione el Espacio de Nombres de Destino :';
$lang['submit']      = 'Enviar';

// page errors
$lang['notexist']      = 'La página %s no existe';
$lang['norights']      = 'No tiene los permisos suficientes para editar la página %s.';
$lang['notchanged']    = 'Ningún destino para %s (operación anulada).';
$lang['exists']        = 'Página %s no se puede mover a %s, la página ya existe.';
$lang['notargetperms'] = 'No tienes los permisos suficientes para crear la página %s.';

// media errors
$lang['medianotexist']      = 'El fichero multimedia %s no existe';
$lang['nomediarights']      = 'No tienes los permisos suficientes para eliminar la página %s.';
$lang['medianotchanged']    = 'Ningún destino para %s (operación anulada).';
$lang['mediaexists']        = 'Fichero multimedia %s no puede ser movido a %s, el fichero ya existe.';
$lang['nomediatargetperms'] = 'No tienes los permisos suficientes para crear el archivo multimedia %s.';

// system errors
$lang['indexerror']          = 'Error mientras se actualizaba el índice de búsqueda %s';
$lang['metamoveerror']       = 'Los metadatos de la página %s no puede ser movido';
$lang['atticmoveerror']      = 'Los datos persistentes de la página %s no pueden ser movidos. Por favor, muévalos manualmente.';
$lang['mediametamoveerror']  = 'Los metadatos del fichero multimedia %s no pueden ser movidas';
$lang['mediamoveerror']      = 'Mover el fichero multimedia %s ha fallado';
$lang['mediaatticmoveerror'] = 'Los datos persistentes del fichero multimedia %s no pueden ser movidos. Por favor, muévalos manualmente.';

// changelog summaries
$lang['renamed']     = 'Nombre de la página cambiado de %s a %s';
$lang['moved']       = 'Página movida desde %s a %s';
$lang['move_rename'] = 'Página movida y renombrada desde %s a %s';
$lang['delete']      = 'Eliminada';
$lang['linkchange']  = 'Enlaces adaptados por la operación de mover';

// progress view
$lang['intro']        = 'La opreación de mover no ha comenzado todavía!';
$lang['preview']      = 'Vista previa de cambios.';
$lang['inexecution']  = 'Una operación de mover previa no ha sido completada - use los botones para continuar o abortar la operación.';
$lang['btn_start']    = 'Comenzar';
$lang['btn_continue'] = 'Continuar';
$lang['btn_retry']    = 'Reintentar';
$lang['btn_skip']     = 'Saltar';
$lang['btn_abort']    = 'Abortar';

// Form labels
$lang['legend']               = 'Mover directorio o página actual';
$lang['movepage']             = 'Mover página';
$lang['movens']               = 'Mover directorio';
$lang['dst']                  = 'Nuevo nombre:';
$lang['content_to_move']      = 'Contenido a mover:';
$lang['autoskip']             = 'Ignorar errores y páginas o ficheros que no pueden ser movidos';
$lang['autorewrite']          = 'Reescribir links luego de mover la página';
$lang['move_pages']           = 'Páginas';
$lang['move_media']           = 'Ficheros multimedia';
$lang['move_media_and_pages'] = 'Páginas y Ficheros multimedia';
$lang['nodst']                = 'Ningún nuevo nombre dado';
$lang['noaction']             = 'No hay operaciones definidas';

// Rename feature
$lang['renamepage']       = 'Renombrar página';
$lang['cantrename']       = 'La página no puede ser renombrada ahora. Por favor inténtelo mas tarde.';
$lang['js']['rename']     = 'Renombrar';
$lang['js']['cancel']     = 'Cancelar';
$lang['js']['newname']    = 'Nuevo nombre:';
$lang['js']['inprogress'] = 'renombrando página y ajustando links...';
$lang['js']['complete']   = 'Operación finalizada.';

// Tree Manager
$lang['noscript']         = 'Esta característica tiene JavaScript';
$lang['moveinprogress']   = 'Hay otra operación en progreso, No puedes usar esta herramienta por ahora.';
$lang['js']['renameitem'] = 'Renombrar este item';
$lang['js']['duplicate']  = 'Disculpe, "%s" ya existe este directorio.';
