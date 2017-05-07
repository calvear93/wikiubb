<?php
/**
 * DokuWiki Plugin showpageafterlogin (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Sam B. <sam@s-blu.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class action_plugin_showpageafterlogin extends DokuWiki_Action_Plugin
{

    /**
     * Registers a callback function for ACTION_SHOW_REDIRECT to get notified after a login/logout happened
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('ACTION_SHOW_REDIRECT', 'BEFORE', $this, 'handle_redirect');
    }

    /**
     * Change the Page ID for the redirect after login to the configurated value.
     * 
     * Checks if a maximum Count is configured and if the calling user is within this count
     *
     * @param Doku_Event $event event object by reference
     * @param mixed $param [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */
    public function handle_redirect(Doku_Event $event)
    {
        global $INPUT;
        global $INFO;

        if ($INPUT->server->has('REMOTE_USER') && $event->data['preact'] == 'login') {
            
            $pageid = $this->getConf('page_after_login');
            $displayCountConfig = $this->getConf('login_display_count');

            if ($displayCountConfig == 0) {

                $event->data['id'] = $pageid;

            } else if ($displayCountConfig > 0) {

                $displayCount = showpageafterlogin_read_json();
                $username = $INFO['client'];

                if ($username && !isset($displayCount[$username])) {
                    $displayCount[$username] = 0;
                }
                
                if ($displayCount[$username] <= $displayCountConfig) {
                    $displayCount[$username]++;
                    $event->data['id'] = $pageid;
                    showpageafterlogin_save_json($displayCount);
                }
            }
        }


    }


}

function showpageafterlogin_read_json()
{
    $json = new JSON(JSON_LOOSE_TYPE);
    $file = @file_get_contents(DOKU_PLUGIN . "showpageafterlogin/showpageafterlogincount.json");
    if (!$file) return Array();
    return $json->decode($file);
}

// Save the statistics into the JSON file
function showpageafterlogin_save_json($data)
{
    $json = new JSON();
    $json = $json->encode($data);
    file_put_contents(DOKU_PLUGIN . "showpageafterlogin/showpageafterlogincount.json", $json);
}
// vim:ts=4:sw=4:et:
