<?php
/**
 * registers users by means of a confirmation link
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Myron Turner<turnermm02@shaw.ca>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');
require_once(DOKU_INC. 'inc/auth.php');

class action_plugin_preregister extends DokuWiki_Action_Plugin {

    /**
     * register the eventhandlers
     */
    private $metaFn;
    private $captcha;

    function register(Doku_Event_Handler $controller){
            $controller->register_hook('HTML_REGISTERFORM_OUTPUT', 'BEFORE', $this, 'update_register_form');
            $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE',  $this, 'allow_preregister_check');
            $controller->register_hook('TPL_ACT_UNKNOWN', 'BEFORE',  $this, 'process_preregister_check');
            $controller->register_hook('TPL_METAHEADER_OUTPUT', 'AFTER', $this, 'metaheaders_after');
     }

    function __construct() {
       $metafile= 'preregister:db';
       $this->metaFn = metaFN($metafile,'.ser');
       $this->check_captcha_selection();
    }
    function metaheaders_after (&$event, $param) {
         global $ACT;
         if($ACT !== 'register') return;

         if($this->captcha == 'ninguno' || $this->captcha == 'defecto')  {
            ptln( "\n<style type='text/css'>\n   /*<![CDATA[*/");
            ptln("#plugin__captcha_wrapper{ display:none; }\n   /*]]>*/\n</style>");
         }

    }

   function allow_preregister_check(&$event, $param) {
    $act = $this->_act_clean($event->data);
    if($act != 'preregistercheck') return;
    $event->preventDefault();
  }

    function process_preregister_check(&$event, $param) {
         global $ACT, $INPUT;

         if($ACT != 'preregistercheck') return;

         $event->preventDefault();

         if($_GET && $_GET['prereg']) {
             $md5= $INPUT->str('prereg');
             if(!preg_match("/^(\w){32}$/",$md5,$matches)) return;
             //echo $this->getLang('registering') . $md5;
             $this->process_registration($md5);
             return;
         }

        if($this->captcha =='mejorado') {
            $captcha = $this->loadHelper('mejorado', true);
            if(!$captcha->check()) {
               return;
            }
        }

         if($this->verify_data()) //verifies invalid data.
          return;

         if($this->captcha == 'defecto') {
             $failed = false;
             if(!isset($_REQUEST['card'])) {
               echo '<h4>'. $this->getLang('cards_nomatch') . '</h4>';
               return;
             }
             foreach($_REQUEST['card'] as $card) {
                 if(strpos($_REQUEST['sel'],$card) === false) {
                     $failed = true;
                     break;
                 }
              }
             if($failed) {
                 echo '<h4>'. $this->getLang('cards_nomatch') . '</h4>';
                 return;
            }
        }
        $t = time();
        $salt =  auth_cookiesalt();
        $index = md5(microtime() .  $salt);
        $url = DOKU_URL . 'doku.php?' . htmlentities($INPUT->str('id')). '&do=preregistercheck&prereg='. $index;
        if($this->getConf('send_confirm')) {
            $valid_email = true;
            if($this->send_link($_REQUEST['email'], $url,$valid_email) ) {
              msg($this->getLang('confirmation'), 1);
            }
            else if($valid_email) {
              msg($this->getLang('email_problem'), -1);
              return false;
            }
        }
        else {
           echo "<a href='$url'>$url</a><br /><br />\n";
           echo $this->getLang('screen_confirm');
        }

          $data = unserialize(io_readFile($this->metaFn,false));
          if(!$data) $data = array();
          $data[$index] = $_POST;
          $data[$index]['savetime'] = $t;
          io_saveFile($this->metaFn,serialize($data));
    }

    function update_register_form(&$event, $param) {
        if($_SERVER['REMOTE_USER']){
            return;
        }

        $event->data->_hidden['save'] = 0;
        $event->data->_hidden['do'] = 'preregistercheck';

        for($i=0; $i <count($event->data->_content); $i++) {
            if(isset($event->data->_content[$i]['type']) && $event->data->_content[$i]['type'] == 'submit')
            {
                $event->data->_content[$i]['value'] = 'Registrarse';
                break;
            }
        }
        $pos = $event->data->findElementByAttribute('type','submit');
        if(!$pos) return; // no button -> source view mode
        if($this->captcha == 'defecto') {
            $cards = $this-> get_cards();
            $sel ="";
            $out = $this->format_cards($cards,$sel);
            $event->data->_hidden['sel'] = implode("",$sel);
           $event->data->insertElement($pos++,$out);
        }
    }


    function process_registration($index) {
        global $lang;
        global $conf;

           $data = unserialize(io_readFile($this->metaFn,false));
           if(!isset($data[$index])) {
              msg($this->getLang('old_confirmation'));
              return;
           }

           $post = $data[$index];
           $post['save'] = 1;
          // loads degree list.
          $file = io_readFile('conf/degrees.list',false);
          $degrees = explode("\n", $file);
          // gets an array of degree list loaded.
          $array = array();
          foreach ($degrees as $career) {
            array_push( $array, explode(":", $career) );
          }

          //verifies if user is a teacher.
          $pos = strrpos($post['email'], "@") + 1;
          $domain = substr ($post['email'] , $pos);
          if($domain == $lang['teacher_domain']) {
            $groups = array($conf['defaultteachergroup']);
            if($conf['teacherismanager'])
              $groups[] = substr($conf['manager'], 1);
          } else {
            $groups = array($conf['defaultgroup']);
          }
          // assigns the group depending of the degree.
          foreach ($array as $degree)
            if($post['degree'] == trim($degree[1]))
              $post['groups'][] = trim($degree[0]);

           $_POST = $post;//array_merge($post, array());
           if(register()) {
              unset($data[$index]);
              io_saveFile($this->metaFn,serialize($data));
           }

    }


    function check_captcha_selection() {
       $list = plugin_list();
       $this->captcha = $this->getConf('mejorado');
       if(!in_array('mejorado', $list)) {
           if(preg_match("/captcha/", $this->captcha)) {
               $this->captcha = 'defecto';
           }
           return;
       }
       if($this->captcha == 'ninguno' || $this->captcha == 'defecto')  {
           return;
       }
      if(plugin_isdisabled('mejorado')) {
          $this->captcha = 'defecto';
          return;
      }
      $this->captcha ='mejorado';

    }

    /**
     * Pre-Sanitize the action command
     *
     * Similar to act_clean in action.php but simplified and without
     * error messages
     */
    function _act_clean($act){
         // check if the action was given as array key
         if(is_array($act)){
           list($act) = array_keys($act);
         }

         //remove all bad chars
         $act = strtolower($act);
         $act = preg_replace('/[^a-z_]+/','',$act);

         return $act;
     }

    function format_cards($cards,&$sel) {
        $sel = array_slice($cards,0,3);
        shuffle($cards);
        $new_row = (int)(count($cards)/2);
        $out = $sel[0] . '&nbsp;&nbsp;' . $sel[1] . '&nbsp;&nbsp;' . $sel[2] . '<br />';
        $out = str_replace(array('H','S','D','C'),array('&#9829;','&#9824;','&#9830;','&#9827;'),$out);
        $out = $this->getLang('check_matching'). '<br />' . $out;
        $out .= '<center><table cellspacing="2"><tr>';
        $i=0;
        foreach($cards as $card) {
            $i++;
            $name = 'card[]';

            $out .= '<td>' . str_replace(array('H','S','D','C'),array('&#9829;','&#9824;','&#9830;','&#9827;'),$card)
                    . " <input type = 'checkbox' name = '$name' value = '$card' /></td>";
            if($i==$new_row) $out .='</tr><tr>';
        }
        $out .= '</tr></table></center>';
        return $out;
   }

    function get_cards() {
         for($i=1; $i<14; $i++) {
            $c = $i;
            if($i == 1) {
              $c='A';
             }
            if($i == 11) {
              $c='J';
            }
             if($c == 12) {
              $c='Q';
            }
            if($i == 13) {
              $c='K';
            }
            $hearts[] = $c . "H";
            $clubs[] = $c. "C";
            $diamonds[] = $c ."D";
            $spades[] =  $c."S";
         }
     $deck = array_merge($hearts,$clubs, $diamonds, $spades);
     shuffle($deck);
      return array_slice($deck,0,10);
    }


    function send_link($email, $url, &$valid_email) {

        if(!mail_isvalid($email)) {
             msg($this->getLang('bad_email') . $email);
             $valid_email = false;
             return false;
        }

        $text = $this->getLang('email_confirm')  . "\n\n";
        $text .= "@URL@\n\n";
        $subject =$this->getLang('subject_confirm');

        $mail = new Mailer();
        $mail->to($email);
        $mail->subject($subject);
        $mail->setBody($text, array('url'=>$url));
        return $mail->send();
}

function verify_data() {
    global $config_cascade;
    global $lang;

    $passpolicy = plugin_load('helper', 'passpolicy');
    if(!$passpolicy->checkPolicy($_REQUEST['pass'], $_REQUEST['user'])) {
        // passpolicy not matched, throw error and stop modification
        msg($lang['badpasspolicy'], -1);
        return true;
    }

    //verifies universidad del bio-bio domain.
    $pos = strrpos($_REQUEST['email'], "@") + 1;
    $domain = substr ($_REQUEST['email'] , $pos);
    if(($domain != $lang['student_domain'] ) && ($domain != $lang['teacher_domain'])) {
      msg($lang['email_bad_domain'], -1);
      return true;
    }

    if(($domain == $lang['student_domain']) && ($_REQUEST['degree'] == $lang['none_career'])) {
      msg($lang['bad_degree_choosen'], -1);
      return true;
    }

    //verifies user data.
    $authusers = $config_cascade['plainauth.users']['default'];
    if(!is_readable($authusers)) return false;

    $users = file($authusers);
    $uname = utf8_strtolower($uname);
    foreach($users as $line) {
        $line = trim($line);
        if($line{0} == '#') continue;
        $data = preg_split('/:/',$line,5);
        if(!trim($data[2]) || !trim($data[3])) continue;
        if($_REQUEST['login'] == $data[2]) {
           msg($this->getLang('uid_inuse') . $data[2], -1);
           return true;
        }
        if($_REQUEST['email'] == $data[3]) {
           msg($this->getLang('uemail_inuse') . $data[3], -1);
           return true;
        }
    }
    return false;
 }

    function write_debug($what, $toscreen=true, $tofile=false) {

        if(is_array($what)) {
            $what = print_r($what,true);
        }
        if($toscreen) {
           return "<pre>$what</pre>" ;
        }
        if(!$tofile) {
           return "";
        }


       $handle=fopen('preregister.txt','a');
        fwrite($handle, "$what\n");
        fclose($handle);
     }
}

