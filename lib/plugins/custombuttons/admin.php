<?php
/**
 * DokuWiki Plugin custombuttons (Admin Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author Constantinos Xanthopoulos <conx@xanthopoulos.info>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

/**
 * Class admin_plugin_custombuttons
 */
class admin_plugin_custombuttons extends DokuWiki_Admin_Plugin {

    /**
     * Return true for access only by admins (config:superuser) or false if managers are allowed as well
     *
     * @return bool
     */
    public function forAdminOnly() {
        return true;
    }

    public function getMenuText($language) {
        return 'Administrar Accesos Rápidos';
    }

    /**
     * Read config
     *
     * @return bool|mixed
     */
    protected function loadCBData() {
        $json = new JSON(JSON_LOOSE_TYPE);
        $file = @file_get_contents(DOKU_PLUGIN."custombuttons/shortcuts.json");
        if(!$file) return false;
        return $json->decode($file);
    }

    /**
     * Store config
     *
     * @param $conf
     */
    protected function saveCBData($conf) {
        $json = new JSON();
        $json = $json->encode($conf);
        $configfile = DOKU_PLUGIN."custombuttons/shortcuts.json";
        if(is_writable($configfile) || (!file_exists($configfile) && is_writable(DOKU_PLUGIN."custombuttons"))) {
            file_put_contents($configfile, $json);
        } else {
            msg('No write access to config file', -1);
        }
    }

    protected function reloadBar() {
        touch(DOKU_CONF."local.php");
    }

    public function handle() {

        if (isset($_REQUEST['add'])) {
            if (!checkSecurityToken()) return;

            $conf = $this->loadCBData();
            if(!$conf) {
                $conf = array();
            }
            $type = 0;
            if ($_REQUEST["pretag"] != "" && $_REQUEST["posttag"] != "") {
                $type = 1;
            }
            array_push($conf, array(
                "label"     => $_REQUEST["label"],
                "code"      => $_REQUEST["code"],
                "type"      => $type,
                "pretag"    => $_REQUEST["pretag"],
                "posttag"   => $_REQUEST["posttag"],
                "icon"      => $_REQUEST["icon"],
            ));

            $this->saveCBData($conf);
            $this->reloadBar();
        } elseif (isset($_REQUEST['delete'])) {
            if (!checkSecurityToken()) return;

            $conf = $this->loadCBData();
            unset($conf[$_REQUEST["delete"]]);
            $this->saveCBData($conf);
            $this->reloadBar();
        }
    }

    public function html() {
        global $ID;
        $conf = $this->loadCBData();

        ptln('<h3>Lista de Accesos</h3>');

        ptln('<form action="'.wl($ID).'" method="post">');
        ptln('  <input type="hidden" name="do"   value="admin" />');
        ptln('  <input type="hidden" name="page" value="'.$this->getPluginName().'" />');
        formSecurityToken();

        ptln('  <table class="inline">');
        ptln('    <tr><th>Nombre</th><th>Código</th><th>Borrar</th></tr>');
        if ($conf) {
            foreach ($conf as $key => $button) {
                if (!$button["type"]) {
                    ptln('    <tr>');
                    ptln('        <td>' . hsc($button["label"]).'</td>');
                    ptln('        <td>'.hsc($button["code"]).'</td>');
                    ptln('        <td><center><input type="radio" name="delete" value="'.$key.'"/></center></td>'); # FIXME Del image
                    ptln('    </tr>');
                } else {
                    $icon = '';
                    if($button['icon']) {
                        $icon = '<img src="' . DOKU_BASE.'lib/plugins/custombuttons/ico/'.hsc($button['icon']) . '"> ';
                    }

                    ptln('    <tr>');
                    ptln('        <td>' . $icon . hsc($button["label"]).'</td>');
                    ptln('        <td>'.hsc($button["pretag"]).hsc($button["code"]).hsc($button["posttag"]).'</td>');
                    ptln('        <td><center><input type="radio" name="delete" value="'.$key.'"/></center></td>'); # FIXME Del image
                    ptln('    </tr>');
                }
            }
        }
        ptln('  </table>');

        ptln('<input type="submit" class="button btn-danger" value="Borrar Selección"/>');
        ptln('</form>');


        ptln('<br /><br />');

        ptln('<h3>Crear Acceso</h3>');

        ptln('<form action="'.wl($ID).'" method="post">');
        ptln('  <input type="hidden" name="do"   value="admin" />');
        ptln('  <input type="hidden" name="add"   value="1" />');
        ptln('  <input type="hidden" name="page" value="'.$this->getPluginName().'" />');
        formSecurityToken();

        ptln('  <table>');
        ptln('    <tr><th>Icono:</th><td>');
        ptln('<select name="icon" class="custombutton_iconpicker">');
        ptln('<option value="">Sólo Texto</option>');
        $files = glob(dirname(__FILE__).'/ico/*.png');
        foreach($files as $file){
            $file = hsc(basename($file));
            ptln('<option value="'.$file.'" style="padding-left: 18px; background: #fff url('.DOKU_BASE.'lib/plugins/custombuttons/ico/'.$file.') left center no-repeat">'.$file.'</option>');
        }
        ptln('</select>');
        ptln('    </td></tr>');
        ptln('    <tr><th>Nombre:</th><td><input type="text" name="label" /></td></tr>');
        ptln('    <tr><th>Pre tag:</th><td><input type="text" name="pretag" /><b> *</b></td></tr>');
        ptln('    <tr><th>Post tag:</th><td><input type="text" name="posttag" /><b> *</b></td></tr>');
        ptln('    <tr><th>Código:</th><td><input type="text" name="code" /></td></tr>');
        ptln('  </table>');

        ptln('<div></br><input type="submit" class="button btn-success" value="Crear Acceso" /></div>');
        ptln('</form>');

        ptln('<br><br>');

        ptln('<div><b>*</b> Si no desea añadir un botón con pre y post tag dee estos campos vacíos.</div>');
    }
}
