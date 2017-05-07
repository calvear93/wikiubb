<?php
/**
 * Dokuwiki Advanced Config Plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Giuseppe Di Terlizzi <giuseppe.diterlizzi@gmail.com>
 */

class admin_plugin_advanced extends DokuWiki_Admin_Plugin {

  private $allowedFiles = array();
  private $fileInfo     = array();

  /**
   * @return int sort number in admin menu
   */
  public function getMenuSort() {
      return 1;
  }

  /**
   * @return bool true if only access for superuser, false is for superusers and moderators
   */
  public function forAdminOnly() {
      return true;
  }

  /**
    * handle user request
    */
  public function handle() {

    global $INPUT;

    if (! $_REQUEST['cmd'])    return;
    if (!checkSecurityToken()) return;

    $cmd = $INPUT->extract('cmd')->str('cmd');

    if ($cmd) {
      $cmd = "cmd_$cmd";
      $this->$cmd();
    }

  }


  /**
   * Get configuration file info
   *
   * @return array
   */
  private function getFileInfo() {

    global $INPUT;
    global $conf;
    global $config_cascade;

    $file = $INPUT->str('file');
    $type = $INPUT->str('type');

    $file_local   = null;
    $file_default = null;

    if (! $file || ! $type) return array();

    switch($type) {

      case 'config':
        $configs      = $config_cascade[$file];
        $file_default = @$configs['default'][0];
        $file_local   = @$configs['local'][0];
        break;

      case 'userstyle':
      case 'userscript':
        $configs      = $config_cascade[$type][$file];
        $file_local   = @$configs[0];
        break;

      case 'hook':
        $file_local   = DOKU_CONF . "$file.html";
        $file_default = tpl_incdir() . "$file.html";
        break;

    }

    if ($type == 'other') {
      switch ($file) {
        case 'htaccess':
          $file_default = DOKU_INC . '.htaccess.dist';
          $file_local   = DOKU_INC . '.htaccess';
          break;
        case 'userscript':
          $configs      = $config_cascade['userscript'];
          $file_local   = @$configs['default'][0];
      }
    }

    $file_info = array(
      'type'            => $type,
      'file'            => $file,
      'default'         => $file_default,
      'local'           => $file_local,
      'localName'       => basename($file_local),
      'defaultName'     => basename($file_default),
      'localLastModify' => (file_exists($file_local) ? strftime($conf['dformat'], filemtime($file_local)) : ''),
    );

    return $file_info;

  }


  public function cmd_save() {

    global $INPUT;

    $file_info = $this->getFileInfo();

    $file_path   = $file_info['local'];
    $file_name   = $file_info['localName'];
    $file_backup = sprintf('%s.%s.gz', $file_path, date('YmdHis'));

    $content_old = io_readFile($file_path);
    $content_new = cleanText($INPUT->post->str('content'));

    if (md5($content_old) === md5($content_new)) {
      return false;
    }

    if (io_saveFile($file_path, $content_new)) {

      if ($this->getConf('backup')) io_saveFile($file_backup, $content_old); // Create a backup
      msg(sprintf($this->getLang('adv_file_save_success'), $file_name), 1);

    } else {
      msg(sprintf($this->getLang('adv_file_save_fail'), $file_name), -1);
    }

  }


  public function cmd_wordblock_update() {

    $file_info = $this->getFileInfo();

    $http = new DokuHTTPClient();
    $blacklist = $http->get('https://meta.wikimedia.org/wiki/Spam_blacklist?action=raw');
    $blacklist = trim(preg_replace('/#(.*)$/m', '', $blacklist));  # Remove all comments from file
    $blacklist = trim(preg_replace('/[\n]+/m', "\n", $blacklist)); # Remove multiple new line

    if (io_saveFile($file_info['local'], $blacklist)) {
      msg($this->getLang('adv_blacklist_update'), 1);
    } else {
      msg($this->getLang('adv_blacklist_failed'), -1);
    }

  }


  private function help() {

    echo '<div class="help">';
    echo $this->locale_xhtml((($this->fileInfo['type'] == 'hook') ? 'hooks' : $this->fileInfo['file']));
    echo '</div>';
    echo '<p>&nbsp;</p>';

    return true;

  }


  private function getDefault() {

    $file_info = $this->fileInfo;

    if (! $file_info['default'] || ! file_exists($file_info['default'])) return;

    $file_name   = $file_info['defaultName'];
    $file_path   = $file_info['default'];
    $lng_default = $this->getLang('adv_default');

    echo "<h3>$lng_default $file_name <small><a class=\"expand-reduce\" href=\"javascript:void(0)\">[+]</a></small></h3>";
    echo '<div class="default-config" style="display:none">';
    echo '<textarea class="edit" rows="15" cols="" disabled="disabled">';
    echo io_readFile($file_path);
    echo '</textarea>';
    echo '<p class="docInfo small pull-right">'. $file_path .'</p>';
    echo '</div>';

    return true;

  }


  private function editForm() {

    global $lang;

    $file_info    = $this->fileInfo;
    $file_path    = $file_info['local'];
    $file_data    = (file_exists($file_path) ? io_readFile($file_path) : '');
    $file_lastmod = $file_info['localLastModify'];
    $file_name    = $file_info['localName'];

    $lng_edit     = $this->getLang('adv_edit');
    $lng_upd      = $this->getLang('adv_blacklist_download');

    echo "<h3>$lng_edit $file_name</h3>";

    echo '<form action="" method="post">';
    echo '<textarea name="content" class="edit" rows="15" cols="">';
    echo $file_data;
    echo '</textarea>';

    echo '<p class="docInfo small pull-right">';
    echo $file_path;
    echo (file_exists($file_path) ? ' · '. $lang['lastmod'] . ' ' . $file_lastmod : '');
    echo '</p>';

    echo '<p>&nbsp;</p>';

    formSecurityToken();

    echo '<input type="hidden" name="do" value="admin" />';
    echo '<input type="hidden" name="page" value="advanced" />';

    echo '<button type="submit" name="cmd[save]" class="btn btn-primary primary">'. $lang['btn_save'] .'</button> ';

    if ($file_info['type'] == 'userstyle' || $file_info['file'] == 'userscript') {

      $purge_type = (($file_info['type'] == 'userstyle') ? 'css' : 'js');

      echo '<button type="button" class="primary btn btn-default purge-cache" data-purge-msg="'. $this->getLang('adv_cache_purged') .'" data-purge-type="'. $purge_type .'">'. $this->getLang("adv_btn_purge_$purge_type") .'</button> ';

    }

    if ($file_info['file'] == 'wordblock') {
      echo '<button type="submit" name="cmd[wordblock_update]" class="btn btn-default">'.$lng_upd.'</button> ';
    }

    echo '<button type="submit" class="btn btn-default">'. $lang['btn_cancel'] .'</button>';
    echo '</form>';

    return true;

  }


  /**
    * output appropriate html
    */
  public function html() {

    global $INPUT;
    global $lang;
    global $conf;
    global $ID;

    $lang['toc'] = $this->getLang('menu');

    $this->fileInfo = $file_info = $this->getFileInfo();

    echo sprintf('<div id="plugin_%s">', $this->getPluginName());
    echo $this->locale_xhtml('intro');
    echo '<p>&nbsp;</p>';

    if (isset($file_info) && in_array($file_info['file'], $this->allowedFiles)) {

      $this->help();
      $this->getDefault();
      $this->editForm();

    }

    echo '</div>';

  }


  public function getTOC() {

    global $INPUT;
    global $conf;
    global $ID;

    $current_section = $INPUT->str('type');

    // TOC Sections
    $toc_sections = array(
      'config'     => 'Configuración',
      'userstyle'  => 'Estilos',
      'hook'       => $this->getLang('adv_hooks'),
      'other'      => $this->getLang('adv_others'),
    );

    // DokuWiki config
    $toc_configs = array(
      'acronyms'  => $this->getLang('adv_abbrev'),
      'entities'  => $this->getLang('adv_entities'),
      'interwiki' => $this->getLang('adv_iwiki'),
      'mime'      => $this->getLang('adv_mime'),
      'smileys'   => $this->getLang('adv_smiley'),
      'scheme'    => $this->getLang('adv_scheme'),
      'wordblock' => $this->getLang('adv_blacklist'),
    );

    // User Style
    $toc_styles = array(
      'screen' => 'Páginas',
      'print'  => 'Imprimir',
      'feed'   => 'Feed',
      'all'    => 'Todo',
    );

    // Template Hooks
    $toc_hooks = array(
      'meta'          => 'Meta',
      'sidebarheader' => $this->getLang('adv_sidebar').' ('.$this->getLang('adv_header').')',
      'sidebarfooter' => $this->getLang('adv_sidebar').' ('.$this->getLang('adv_footer').')',
      'pageheader'    => 'Page ('.$this->getLang('adv_header').')',
      'pagefooter'    => 'Page ('.$this->getLang('adv_footer').')',
      'header'        => $this->getLang('adv_header'),
      'footer'        => $this->getLang('adv_footer'),
    );

    // Other config
    $toc_others = array(
      'userscript' => $this->getLang('adv_ujs'),
      'htaccess'   => '.htaccess',
    );

    // Specific Template Hooks
    switch ($conf['template']) {

      case 'bootstrap3':

        $toc_hooks['topheader']          = $this->getLang('adv_topheader');
        $toc_hooks['rightsidebarheader'] = $this->getLang('adv_rsidebar').' ('.$this->getLang('adv_header').')';
        $toc_hooks['rightsidebarfooter'] = $this->getLang('adv_rsidebar').' ('.$this->getLang('adv_footer').')';
        $toc_hooks['social']             = 'Social';

        break;

    }


    $toc_items = array(
      'config'     => $toc_configs,
      'userstyle'  => $toc_styles,
      'hook'       => $toc_hooks,
      'other'      => $toc_others,
    );

    if ($current_section) {
      $this->allowedFiles = array_keys($toc_items[$current_section]);
    }

    foreach ($toc_sections as $section => $section_title) {

      $toc[] = array(
        'link'  => wl($ID, array(
          'do'   => 'admin',
          'page' => $this->getPluginName(),
          'type' => $section)
        ),
        'title' => $section_title,
        'level' => 1,
        'type'  => 'ul',
      );

      foreach ($toc_items[$section] as $file => $title) {

        if ($current_section == $section) {

          $toc[] = array(
            'link'  => wl($ID, array(
              'do' => 'admin',
              'page'   => $this->getPluginName(),
              'type'   => $section,
              'file'   => $file,
              'sectok' => getSecurityToken())
            ),
            'title' => $title,
            'level' => 2,
            'type'  => 'ul',
          );

        }

      }

    }

    return $toc;

  }

}
