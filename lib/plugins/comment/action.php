<?php

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');
require_once(DOKU_PLUGIN.'syntax.php');

class action_plugin_comment extends DokuWiki_Action_Plugin {


function register(Doku_Event_Handler $controller) {
    $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'comment_toolbar', array ());

 }

function comment_toolbar(Doku_Event $event, $param) {
	    $hide = array (
	        'type' => 'format',
	        'title' => 'Comentar',
	        'icon' => '../../plugins/comment/hide.png',
	        'sample' => 'Este es un comentario invisible en la lectura.',
	        'open' => '/*\n',
	        'close' => '\n*/',
	    );
        $event->data[] = $hide;
    }
}
