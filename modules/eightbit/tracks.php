<?php
/**
 * 8Bit Collective Display and Email Packing
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2019 Chronolabs Cooperative (8Bit.snails.email)
 * @license             GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package             eightbit
 * @since               1.1.11
 * @author              Simon Antony Roberts <wishcraft@users.sourceforge.net>
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';


$xoopsOption['template_main'] = 'db:eightbit_tracks.html';
include_once dirname(dirname(__DIR__)) . DS . 'header.php';
include_once dirname(dirname(__DIR__)) . DS . 'class' . DS . 'pagenav.php';

$title = $breadcrumb = array();
$breadcrumb['home']['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/index.php";
$breadcrumb['home']['chars'] = 'home';
foreach(xoops_getModuleHandler('tracks', basename(__DIR__))->getCrumbs($_REQUEST['trackalpha']) as $chars => $values)
{
    $title[$chars] = $chars;
    $breadcrumb[$chars]['chars'] = $chars;
    $breadcrumb[$chars]['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/tracks.php?trackalpha=" . $chars;
}
$crumbkeys = array_keys($breadcrumb);
$GLOBALS['xoopsTpl']->assign('breadcrumb', $breadcrumb);
$GLOBALS['xoopsTpl']->assign('lastcrumb', $crumbkeys[count($crumbkeys) - 1]);
$xoopsOption['xoops_pagetitle'] = "Tracks: " .implode (" -> ", $title);

$totalseconds = 0;
$alpha = array();
if (strlen($_REQUEST['trackalpha'])<3)
{
    foreach(xoops_getModuleHandler('tracks', basename(__DIR__))->selAlpha($_REQUEST['trackalpha']) as $chars => $values)
    {
        $alpha[$chars]['chars'] = $chars;
        $alpha[$chars]['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/tracks.php?trackalpha=" . $chars;
        $alpha[$chars]['tracks'] = eightbit_secondsDiplay($values['totalseconds']);
        
    }
}
$GLOBALS['xoopsTpl']->assign('tracksalpha', $alpha);

$ids = xoops_getModuleHandler('tracks', basename(__DIR__))->getIDsAlpha($_REQUEST['trackalpha']);

$tracksids = array();
$criteria = new Criteria('alphaid', '(' . implode(', ', array_keys($ids)) . ')', 'IN');
foreach(xoops_getModuleHandler('alpha_tracks', basename(__DIR__))->getObjects($criteria, true) as $key => $object)
    $tracksids[$object->getVar('trackid')] = $object->getVar('trackid');
    
$artistsids = array();
$criteria = new Criteria('trackid', '(' . implode(', ', array_keys($tracksids)) . ')', 'IN');
foreach(xoops_getModuleHandler('albums_tracks', basename(__DIR__))->getObjects($criteria, true) as $key => $object)
{
    $criteriab = new Criteria('albumid', $object->getVar('albumid'));
    foreach(xoops_getModuleHandler('albums_artists', basename(__DIR__))->getObjects($criteriab, true) as $key => $object)
        $artistsids[$object->getVar('artistid')] = $object->getVar('artistid');
}
    
$albumsids = array();
$criteria = new Criteria('artistid', '(' . implode(', ', array_keys($artistsids)) . ')', 'IN');
foreach(xoops_getModuleHandler('albums_artists', basename(__DIR__))->getObjects($criteria, true) as $key => $object)
    $albumsids[$object->getVar('albumid')] = $object->getVar('albumid');
    
$criteria = new Criteria('id', '(' . implode(', ', array_keys($albumsids)) . ')', 'IN');
$criteria->setSort((isset($_REQUEST['albumsort'])?$_REQUEST['albumsort']:$GLOBALS['sort']));
$criteria->setOrder((isset($_REQUEST['albumorder'])?$_REQUEST['albumorder']:$GLOBALS['order']));
$ttl = xoops_getModuleHandler('albums', basename(__DIR__))->getCount($criteria);
$criteria->setStart((isset($_REQUEST['albumstart'])?$_REQUEST['albumstart']:$GLOBALS['start']));
$criteria->setLimit((isset($_REQUEST['albumlimit'])?$_REQUEST['albumlimit']:$GLOBALS['limit']));
foreach(xoops_getModuleHandler('albums', basename(__DIR__))->getObjects($criteria, true) as $key => $object) {
    $GLOBALS['xoopsTpl']->append('albums', array(   'album'     =>      $object->getVar('album'),
                                                    'url'       =>      XOOPS_URL . '/modules/' . basename(__DIR__) . '/album.php?key='.md5($object->getVar('id')),
                                                    'artists'   =>      $object->getVar('artists'),
                                                    'tracks'    =>      $object->getVar('tracks'),
                                                    'bytes'     =>      $object->getVar('bytes'),
                                                    'hits'      =>      $object->getVar('hits'),
                                                    'playtime'  =>      eightbit_secondsDiplay($object->getVar('totalseconds'))));
}
$pagenav = new XoopsPageNav($ttl, (isset($_REQUEST['albumlimit'])?$_REQUEST['albumlimit']:$GLOBALS['limit']), (isset($_REQUEST['albumstart'])?$_REQUEST['albumstart']:$GLOBALS['start']), 'albumstart', 'trackalpha='.$_REQUEST['trackalpha'].'&albumlimit='.(isset($_REQUEST['albumlimit'])?$_REQUEST['albumlimit']:$GLOBALS['limit']).'&'.http_build_query(eightbit_RemoveFieldKeywords('album', parse_str($_SERVER['QUERY_STRING']))));
$GLOBALS['xoopsTpl']->assign('albumspagenav', $pagenav->renderNav(7));
$GLOBALS['xoopsTpl']->assign('albumsort', (isset($_REQUEST['albumsort'])?$_REQUEST['albumsort']:$GLOBALS['sort']));
$GLOBALS['xoopsTpl']->assign('albumorder', (isset($_REQUEST['albumorder'])?$_REQUEST['albumorder']:$GLOBALS['order']));
$GLOBALS['xoopsTpl']->assign('albumstart', (isset($_REQUEST['albumstart'])?$_REQUEST['albumstart']:$GLOBALS['start']));
$GLOBALS['xoopsTpl']->assign('albumlimit', (isset($_REQUEST['albumlimit'])?$_REQUEST['albumlimit']:$GLOBALS['limit']));
$GLOBALS['xoopsTpl']->assign('albumsdigression', http_build_query(eightbit_RemoveFieldKeywords('album', parse_str($_SERVER['QUERY_STRING']))));

$criteria = new Criteria('id', '(' . implode(', ', array_keys($artistsids)) . ')', 'IN');
$criteria->setSort((isset($_REQUEST['artistsort'])?$_REQUEST['artistsort']:$GLOBALS['sort']));
$criteria->setOrder((isset($_REQUEST['artistorder'])?$_REQUEST['artistorder']:$GLOBALS['order']));
$ttl = xoops_getModuleHandler('albums', basename(__DIR__))->getCount($criteria);
$criteria->setStart((isset($_REQUEST['artiststart'])?$_REQUEST['artiststart']:$GLOBALS['start']));
$criteria->setLimit((isset($_REQUEST['artistlimit'])?$_REQUEST['artistlimit']:$GLOBALS['limit']));
foreach(xoops_getModuleHandler('artists', basename(__DIR__))->getObjects($criteria, true) as $key => $object) {
    $GLOBALS['xoopsTpl']->append('artists', array(      'artist'    =>      eightbit_getArtistsHTML($object->getVar('id'), $object->getVar('artist')),
                                                        'albums'    =>      $object->getVar('albums'),
                                                        'tracks'    =>      $object->getVar('tracks'),
                                                        'bytes'     =>      $object->getVar('bytes'),
                                                        'hits'      =>      $object->getVar('hits'),
                                                        'playtime'  =>      eightbit_secondsDiplay($object->getVar('totalseconds'))));
}
$pagenav = new XoopsPageNav($ttl, (isset($_REQUEST['artistlimit'])?$_REQUEST['artistlimit']:$GLOBALS['limit']), (isset($_REQUEST['artiststart'])?$_REQUEST['artiststart']:$GLOBALS['start']), 'artiststart', 'trackalpha='.$_REQUEST['trackalpha'].'&artistlimit='.(isset($_REQUEST['artistlimit'])?$_REQUEST['artistlimit']:$GLOBALS['limit']).'&'.http_build_query(eightbit_RemoveFieldKeywords('artist', parse_str($_SERVER['QUERY_STRING']))));
$GLOBALS['xoopsTpl']->assign('artistspagenav', $pagenav->renderNav(7));
$GLOBALS['xoopsTpl']->assign('artistspagenav', $pagenav->renderNav(5));
$GLOBALS['xoopsTpl']->assign('artistsort', (isset($_REQUEST['artistsort'])?$_REQUEST['artistsort']:$GLOBALS['sort']));
$GLOBALS['xoopsTpl']->assign('artistorder', (isset($_REQUEST['artistorder'])?$_REQUEST['artistorder']:$GLOBALS['order']));
$GLOBALS['xoopsTpl']->assign('artiststart', (isset($_REQUEST['artiststart'])?$_REQUEST['artiststart']:$GLOBALS['start']));
$GLOBALS['xoopsTpl']->assign('artistlimit', (isset($_REQUEST['artistlimit'])?$_REQUEST['artistlimit']:$GLOBALS['limit']));
$GLOBALS['xoopsTpl']->assign('artistsdigression', http_build_query(eightbit_RemoveFieldKeywords('artist', parse_str($_SERVER['QUERY_STRING']))));
     
$titles = array();
$fieldobj = xoops_getModuleHandler('tracks', basename(__DIR__))->create();
foreach(array_keys($fieldobj->vars) as $field)
    if (!in_array($field, array('title')))
        $fields[$field] = $field;
$criteria = new CriteriaCompo(new Criteria('id', '(' . implode(', ', array_keys($tracksids)) . ')', 'IN'));
$criteria->add(new Criteria('mode', 'online', "LIKE"), "AND");
$criteria->setSort((isset($_REQUEST['tracksort'])?$_REQUEST['tracksort']:$GLOBALS['sort']));
$criteria->setOrder((isset($_REQUEST['trackorder'])?$_REQUEST['trackorder']:$GLOBALS['order']));
$ttl = xoops_getModuleHandler('tracks', basename(__DIR__))->getCountGroupBy($criteria, 'title', '`title`');
$criteria->setStart((isset($_REQUEST['trackstart'])?$_REQUEST['trackstart']:$GLOBALS['start']));
$criteria->setLimit((isset($_REQUEST['tracklimit'])?$_REQUEST['tracklimit']:$GLOBALS['limit']));
foreach(xoops_getModuleHandler('tracks', basename(__DIR__))->getObjectsGroupedBy($criteria, true, true, 'title', '`title`', array_reverse($fields)) as $key => $object) {
    if (!in_array($object->getVar('title'), $titles) && $titles[$object->getVar('title')] = $object->getVar('title'))
    $GLOBALS['xoopsTpl']->append('tracks', array(   'title'         =>      $object->getVar('title'),
                                                    'album'         =>      xoops_getModuleHandler('albums', basename(__DIR__))->get($object->getVar('albumid'))->getVar('album'),
                                                    'artist'        =>      eightbit_getArtistsHTML($object->getVar('artistid'), xoops_getModuleHandler('artists', basename(__DIR__))->get($object->getVar('artistid'))->getVar('artist')),
                                                    'album_url'     =>      XOOPS_URL . '/modules/' . basename(__DIR__) . '/album.php?key='.md5($object->getVar('albumid')),
                                                    'track_url'     =>      XOOPS_URL . '/modules/' . basename(__DIR__) . '/track.php?key='.md5($object->getVar('artistid')),
                                                    'year'          =>      $object->getVar('year'),
                                                    'bitrate'       =>      number_format($object->getVar('bitrate')/ 1024, 0) . 'Kbs',
                                                    'bytes'         =>      number_format($object->getVar('bytes'), 0),
                                                    'hits'          =>      $object->getVar('hits'),
                                                    'player'        =>      eightbit_PlayerHTML('player.swf', sprintf(xoops_getModuleHandler('repositories', basename(__DIR__))->get($object->getVar('repoid'))->getVar('raw'), substr($object->getVar('path'), 1) . "/" . urlencode($object->getVar('file')))),
                                                    'playseconds'   =>      eightbit_secondsDiplay($object->getVar('playseconds'))));
}
$pagenav = new XoopsPageNav($ttl, (isset($_REQUEST['tracklimit'])?$_REQUEST['tracklimit']:$GLOBALS['limit']), (isset($_REQUEST['trackstart'])?$_REQUEST['trackstart']:$GLOBALS['start']), 'trackstart', 'trackalpha='.$_REQUEST['trackalpha'].'&tracklimit='.(isset($_REQUEST['tracklimit'])?$_REQUEST['tracklimit']:$GLOBALS['limit']).'&'.http_build_query(eightbit_RemoveFieldKeywords('track', parse_str($_SERVER['QUERY_STRING']))));
$GLOBALS['xoopsTpl']->assign('trackspagenav', $pagenav->renderNav(7));
$GLOBALS['xoopsTpl']->assign('tracksort', (isset($_REQUEST['tracksort'])?$_REQUEST['tracksort']:$GLOBALS['sort']));
$GLOBALS['xoopsTpl']->assign('trackorder', (isset($_REQUEST['trackorder'])?$_REQUEST['trackorder']:$GLOBALS['order']));
$GLOBALS['xoopsTpl']->assign('trackstart', (isset($_REQUEST['trackstart'])?$_REQUEST['trackstart']:$GLOBALS['start']));
$GLOBALS['xoopsTpl']->assign('tracklimit', (isset($_REQUEST['tracklimit'])?$_REQUEST['tracklimit']:$GLOBALS['limit']));
$GLOBALS['xoopsTpl']->assign('tracksdigression', http_build_query(eightbit_RemoveFieldKeywords('track', parse_str($_SERVER['QUERY_STRING']))));
    
include_once dirname(dirname(__DIR__)) . DS . 'footer.php';