<?php
/**
 * DokuWiki Bootstrap3 Template: Administration Menu
 *
 * @link     http://dokuwiki.org/template:bootstrap3
 * @author   Giuseppe Di Terlizzi <giuseppe.diterlizzi@gmail.com>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

global $ID, $auth;

if (bootstrap3_conf('showAdminMenu')):

$admin_plugins        = plugin_list('admin');
$administrative_tasks = array('usermanager', 'acl', 'extension', 'config', 'styling', 'revert', 'popularity');
$additional_plugins   = array_diff($admin_plugins, $administrative_tasks);

$admin = array(
  'administrative_tasks' => array('label' => tpl_getLang('administrative_tasks'), 'plugins' => $administrative_tasks),
  'additional_plugins'   => array('label' => tpl_getLang('additional_plugins'),   'plugins' => $additional_plugins),
);

?>
<ul class="nav navbar-nav" id="dw__admin">
  <li class="dropdown dropdown-large">

    <a href="#" class="dropdown-toggle faa-horizontal animated-hover" data-toggle="dropdown" title="<?php echo $lang['btn_admin'] ?>">
      <i class="fa fa-fw fa-cogs"></i>  <span class="hidden-lg hidden-md hidden-sm"><?php echo $lang['btn_admin'] ?></span> <span class="caret"></span>
    </a>

    <ul class="dropdown-menu dropdown-menu-large" role="menu">
      <li class="open dropdown-row">

        <?php foreach ($admin as $key => $items): if (! count($items['plugins'])) continue ?>

        <ul class="dropdown-menu col-sm-<?php echo (count($additional_plugins) > 0) ? '6' : '12' ?>">

          <li class="dropdown-header">
            <span class="<?php echo $key ?>"><?php echo ucfirst($items['label']) ?></span>
          </li>

          <?php

            foreach($items['plugins'] as $item) {

              if (($plugin = plugin_load('admin', $item)) === null) continue;
              if ($plugin->forAdminOnly() && !$INFO['isadmin']) continue;
              if ($item == 'usermanager' && ! ($auth && $auth->canDo('getUsers'))) continue;

              $label = $plugin->getMenuText($conf['lang']);

              if (!$label) continue; //$label == "Limpiar Lista de Preregistros"

              switch( $label ){
              	case 'Configuración avanzada':
              		$icon =  '><i class="glyphicon glyphicon-cog"></i> ';
              		break;
              	case 'Limpiar Historial':
              		$icon =  '><i class="glyphicon glyphicon-time"></i> ';
              		break;
              	case 'Administrar Accesos Rápidos':
              		$icon =  '><i class="glyphicon glyphicon-share-alt"></i> ';
              		break;
                case 'Administrar Subscripciones':
                  $icon =  '><i class="glyphicon glyphicon-star"></i> ';
                  break;
              	case 'Administrar grupos':
              		$icon =  '><i class="glyphicon glyphicon-check"></i> ';
              		break;
              	case 'Registro de Sesiones':
              		$icon =  '><i class="glyphicon glyphicon-th-list"></i> ';
              		break;
              	case 'Mover/Renombrar página':
              		$icon =  '><i class="glyphicon glyphicon-copy"></i> ';
              		break;
              	case 'Permisos ACL':
              		$icon =  '><i class="glyphicon glyphicon-eye-open"></i> ';
              		break;
              	case 'Limpiar Lista de Preregistros':
              		$icon =  '><i class="glyphicon glyphicon-trash"></i> ';
              		break;
              	case 'Configuración de Referencias':
              		$icon =  '><i class="glyphicon glyphicon-asterisk"></i> ';
              		break;
              	case 'Estadísticas de Búsqueda':
              		$icon =  '><i class="glyphicon glyphicon-stats"></i> ';
              		break;
              	case 'Moderar Linkbacks':
              		$icon =  '><i class="glyphicon glyphicon-link"></i> ';
              		break;
                case 'Mensajes Informativos':
                  $icon =  '><i class="glyphicon glyphicon-exclamation-sign"></i> ';
                  break;
              	default:
              		$icon =  'class="admin '.$item.'">';
              		break;
              }

              echo sprintf('<li><a href="%s" title="%s"%s%s</a></li>',
                           wl($ID, array('do' => 'admin', 'page' => $item)), $label, $icon, $label);

            }

          ?>

        </ul>
        <?php endforeach; ?>
      </li>
    </ul>
  </li>
</ul>
<?php endif; ?>
