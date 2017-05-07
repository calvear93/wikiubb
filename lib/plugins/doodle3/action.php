<?php
/**
 * @file       divalign2/action.php
 * @brief      Action component for the divalign2 plugin.
 *
 * See common.php for more information.
**/

if(!defined('DOKU_INC')) die();

class action_plugin_doodle3 extends DokuWiki_Action_Plugin {


function register(Doku_Event_Handler $controller) {
    $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'doodle3_toolbar', array ());
}

function doodle3_toolbar(Doku_Event $event, $param) {
        $icobase = '../../plugins/doodle3/';

        $doodle3 = array (
	        'type' => 'format',
	        'title' => 'Cuestionario Múltiple',
	        'icon' => $icobase.'doodle.png',
	        'sample' => 'Pregunta',
	        'open' => '<doodle\ntitle="',
	        'close' => '"\nauth="none|ip|user"\nadminUsers="usuario1|usuario2"\nadminGroups="grupo1|grupo2"\nvoteType="single|multi"\nclosed="true|false"\n>\n   * Option 1\n   * Option 2 **sintaxis de wiki** está __permitida__\n   * Option 3\n</doodle>',
	    );
        $event->data[] = $doodle3;
    }

}
