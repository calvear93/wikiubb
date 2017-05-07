<?php
/**
 * English settings file for Userhomepage plugin
 * Previous authors: James GuanFeng Lin, Mikhail I. Izmestev, Daniel Stonier
 * @author: Simon Delage <simon.geekitude@gmail.com>
 * @license: CC Attribution-Share Alike 3.0 Unported <http://creativecommons.org/licenses/by-sa/3.0/>
 */

    $lang['create_private_ns'] = 'Crear espacio de nombres Privado (Compruebe todas las opciones antes de habilitar)';
    $lang['use_name_string'] = 'Utilice el nombre completo en lugar del usuario para el espacio de nombres privado.';
    $lang['use_start_page'] = 'Utilice el nombre de la página de inicio wiki para la página de inicio de cada espacio de nombres privado (de lo contrario, se utilizará el nombre de espacio de nombres privado).';
    $lang['users_namespace'] = 'Espacio de nombres bajo los cuales se crean espacios de nombres de usuario.';
    $lang['group_by_name'] = 'Crear espacios de nombres y agrupar a los usuarios por el primer carácter del nombre de usuario';
    $lang['edit_before_create'] = 'Permitir a los usuarios editar la Página de inicio de su espacio de nombres privado en la creación (sólo funcionará si la página pública no se crea al mismo tiempo).';
    $lang['acl_all_private'] = 'Permisos para el grupo @ALL en el espacio de nombres privado';
    $lang['acl_all_private_o_0'] = 'Ninguno (por defecto)';
    $lang['acl_all_private_o_1'] = 'Leer';
    $lang['acl_all_private_o_2'] = 'Editar';
    $lang['acl_all_private_o_4'] = 'Crear';
    $lang['acl_all_private_o_8'] = 'Subir';
    $lang['acl_all_private_o_16'] = 'Eliminar';
    $lang['acl_all_private_o_noacl'] = 'No establecer ACL';
    $lang['acl_user_private'] = 'Permisos para el grupo "defaultgroup" en el espacio de nombres privado';
    $lang['acl_user_private_o_0'] = 'Ninguno (por defecto)';
    $lang['acl_user_private_o_1'] = 'Leer';
    $lang['acl_user_private_o_2'] = 'Editar';
    $lang['acl_user_private_o_4'] = 'Crear';
    $lang['acl_user_private_o_8'] = 'Subir';
    $lang['acl_user_private_o_16'] = 'Eliminar';
    $lang['acl_user_private_o_noacl'] = 'No establecer ACL';
    $lang['groups_private'] = 'Lista separada por comas de los grupos de usuarios afectados por la creación de espacio de nombres privado (dejar en blanco para aplicar los ajustes anteriores para todos los usuarios).';
    $lang['create_public_page'] = 'Crear Página Pública para los usuarios';
    $lang['public_pages_ns'] = 'Espacio de nombres bajo los cuales se crean las páginas públicas.';
    $lang['acl_all_public'] = 'Permisos para el grupo @ALL en Páginas Públicas';
    $lang['acl_all_public_o_0'] = 'Ninguno';
    $lang['acl_all_public_o_1'] = 'Leer (por defecto)';
    $lang['acl_all_public_o_2'] = 'Editar';
    $lang['acl_all_public_o_noacl'] = 'No establecer ACL';
    $lang['acl_user_public'] = 'Permissions for "defaultgroup" group on Public Pages';
    $lang['acl_user_public_o_0'] = 'Ninguno';
    $lang['acl_user_public_o_1'] = 'Leer (por defecto)';
    $lang['acl_user_public_o_2'] = 'Editar';
    $lang['acl_user_public_o_noacl'] = 'No establecer ACL';
    $lang['groups_public'] = 'Lista separada por comas de los grupos de usuarios afectados por la creación de página pública (dejar en blanco para aplicar los ajustes anteriores para todos los usuarios).';
    $lang['templates_path'] = 'Ruta relativa desde [<code>savedir</code>] en la que se almacenan las plantillas (userhomepage_private.txt y userhomepage_public.txt). Ejemplos: <code>./pages/user</code> o <code>../lib/plugins/userhomepage</code>.';
    $lang['templatepath'] = 'Ruta de la plantilla versión 3.0.4. Si este archivo existe, se utiliza como fuente por defecto para la nueva plantilla de página de inicio de espacio de nombres privado (limpie la ruta si no desea esto).';
    $lang['acl_all_templates'] = 'Permisos para el grupo @ALL en las plantillas (si están almacenadas en <code>data/pages...</code>)';
    $lang['acl_all_templates_o_0'] = 'Ninguno';
    $lang['acl_all_templates_o_1'] = 'Leer (por defecto)';
    $lang['acl_all_templates_o_2'] = 'Editar';
    $lang['acl_all_templates_o_noacl'] = 'No establecer ACL';
    $lang['acl_user_templates'] = 'Permissions for "defaultgroup" group on templates (if they are stored in <code>data/pages...</code>)';
    $lang['acl_user_templates_o_0'] = 'Ninguno';
    $lang['acl_user_templates_o_1'] = 'Leer (por defecto)';
    $lang['acl_user_templates_o_2'] = 'Editar';
    $lang['acl_user_templates_o_noacl'] = 'No establecer ACL';
    $lang['no_acl'] = 'Ajuste de No establecer ACL, tendrá que eliminar los creados hasta ahora manualmente. No se olvide de establecer las demás ACL en las plantillas.';
    $lang['redirection'] = 'Habilitar la redirección (aunque se hayan desactivado, todavía se producirá en la creación de páginas).';
    $lang['action'] = 'Acción en primera redirección a la página pública después de su creación (o página de inicio del espacio de nombres privado).';
    $lang['action_o_edit'] = 'Editar (por defecto)';
    $lang['action_o_show'] = 'Mostrar';
    $lang['userlink_replace'] = 'Permitir la sustitución del enlace interwiki [<code>Conectado como</code>], dependiendo de páginas creadas por Userhomepage (osólo funciona si la opción <code>showuseras</code> está establecida).';
    $lang['userlink_classes'] = 'Lista separada por espacios de clases CSS para aplicar a los enlaces interwiki [<code>Conectado como</code>] (por defecto: <code>interwiki iw_user wikilink1</code>).';
    $lang['userlink_fa'] = 'Usar Fontawesome en lugar de imágenes (Fontawesome debería estar instalado)';
