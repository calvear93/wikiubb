<?php
/**
 * @file       divalign2/action.php
 * @brief      Action component for the divalign2 plugin.
 *
 * See common.php for more information.
**/

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');
require_once(DOKU_PLUGIN. 'divalign2/common.php'); // for common functions

class action_plugin_divalign2 extends DokuWiki_Action_Plugin {


function register(Doku_Event_Handler $controller) {
    $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'divalign_toolbar', array ());

    }

function fix_par_stack (&$event, $param) {
    DW_common_divalign2::FixRenderStack($event->data, 0);
    }

function divalign_toolbar(Doku_Event $event, $param) {
        $icobase = '../../plugins/divalign2/icons/';

        $left = array (
	        'type' => 'format',
	        'title' => 'Izquierda',
	        'icon' => $icobase.'left.png',
	        'sample' => 'Texto alineado a la izquierda.',
	        'open' => '#;;\n',
	        'close' => '\n#;;',
	    );
	    $center = array (
	        'type' => 'format',
	        'title' => 'Centrado',
	        'icon' => $icobase.'center.png',
	        'sample' => 'Texto centrado.',
	        'open' => ';#;\n',
	        'close' => '\n;#;',
	    );
	    $right = array (
	        'type' => 'format',
	        'title' => 'Derecha',
	        'icon' => $icobase.'right.png',
	        'sample' => 'Texto alineado a la derecha.',
	        'open' => ';;#\n',
	        'close' => '\n;;#',
	    );
	    $justify = array (
	        'type' => 'format',
	        'title' => 'Justificado',
	        'icon' => $icobase.'justify.png',
	        'sample' => 'Texto justificado.',
	        'open' => '###\n',
	        'close' => '\n###',
	    );

        $alignment = array (
	        'type' => 'picker',
	        'title' => 'Alinear Párrafo',
	        'icon' => $icobase.'paragraph.png',
	        'class'  => 'pk_hl',
	        'list' => array (
                $left,
                $center,
                $right,
                $justify
            )
	    );
        $event->data[] = $alignment;
    }

}
//... and that's all
