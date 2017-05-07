<?php
/**
 * XML feed export
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Böhler <dev@aboehler.at>
 */

if(!defined('DOKU_INC'))    define('DOKU_INC', realpath(dirname(__FILE__) . '/../../../') . '/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(DOKU_INC . 'inc/init.php');
require_once(DOKU_INC . 'inc/common.php');
require_once(DOKU_INC . 'inc/events.php');
require_once(DOKU_INC . 'inc/parserutils.php');
require_once(DOKU_INC . 'inc/feedcreator.class.php');
require_once(DOKU_INC . 'inc/auth.php');
require_once(DOKU_INC . 'inc/pageutils.php');
require_once(DOKU_INC . 'inc/httputils.php');

//close session
session_write_close();

$id     = $_REQUEST['id'];

$type = $conf['rss_type'];

switch($type) {
    case 'rss':
        $type = 'RSS0.91';
        $mime = 'text/xml';
        break;
    case 'rss2':
        $type = 'RSS2.0';
        $mime = 'text/xml';
        break;
    case 'atom':
        $type = 'ATOM0.3';
        $mime = 'application/xml';
        break;
    case 'atom1':
        $type = 'ATOM1.0';
        $mime = 'application/atom+xml';
        break;
    default:
        $type = 'RSS1.0';
        $mime = 'application/xml';
}

// the feed is dynamic - we need a cache for each combo
// (but most people just use the default feed so it's still effective)
$cache = getCacheName($id . $_SERVER['REMOTE_USER'], '.feed');
$cmod  = @filemtime($cache); // 0 if not exists
if($cmod && (@filemtime(DOKU_CONF . 'local.php') > $cmod
        || @filemtime(DOKU_CONF . 'dokuwiki.php') > $cmod)
) {
    // ignore cache if feed prefs may have changed
    $cmod = 0;
}

// check cacheage and deliver if nothing has changed since last
// time or the update interval has not passed, also handles conditional requests
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Type: application/xml; charset=utf-8');

if($cmod && (
        ($cmod + $conf['rss_update'] > time())
        || (
            ($cmod > @filemtime($conf['changelog']))
        )
    )) {
    http_conditionalRequest($cmod);
    if($conf['allowdebug']) header("X-CacheUsed: $cache");
    print io_readFile($cache);
    exit;
} else {
    http_conditionalRequest(time());
}

// create new feed
$rss = new DokuWikiFeedCreator();
$rss->title = p_get_metadata($id, 'title', METADATA_DONT_RENDER);
$rss->title .= ' · ' . $conf['title'];
$rss->link = DOKU_URL;
$rss->syndicationURL = DOKU_PLUGIN . 'csstimeline/feed.php';
$rss->cssStyleSheet = DOKU_URL . 'lib/exe/css.php?s=feed';

$author = p_get_metadata($id, 'creator', METADATA_DONT_RENDER);

$image = new FeedImage();
$image->title = $conf['title'];
$image->url = DOKU_URL . "lib/images/favicon.ico";
$image->link = DOKU_URL;
$rss->image = $image;

$page = rawWiki($id);// get pages here
$po = &plugin_load('helper', 'csstimeline');
preg_match('/'.str_replace('/', '\/', $po->specialPattern).'/si', $page, $matches);

foreach($matches as $match)
{
    $data = $po->handleMatch($match);
    foreach($data['entries'] as $entry)
    {
        $item = new FeedItem();
        $item->title = htmlspecialchars_decode($entry['title']);
        if($entry['link'])
            $item->link = htmlspecialchars_decode($entry['link']);
        else
            $item->link = wl($id, '', true, '&');
        $item->description = hsc(strip_tags(htmlspecialchars_decode($entry['description'])));
        $item->date = date('r', strtotime($entry['date']));
        $item->author = $author;
        $rss->addItem($item);
    
    }
}

$feed = $rss->createFeed($type, 'utf-8');

// save cachefile
io_saveFile($cache, $feed);

// finally deliver
print $feed;

