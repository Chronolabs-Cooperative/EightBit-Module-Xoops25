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

/**
 * EightBit Trackss Class
 *
 * @package             eightbit
 *
 * @author              Simon Antony Roberts <wishcraft@users.sourceforge.net>
 */
class EightbitTracks extends XoopsObject
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('sha1', XOBJ_DTYPE_TXTBOX, null, false, 44);
        $this->initVar('alphaid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('repoid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('artistid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('albumid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('year', XOBJ_DTYPE_INT, date('Y'), false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('path', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('file', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('minutes', XOBJ_DTYPE_INT, null, false);
        $this->initVar('seconds', XOBJ_DTYPE_INT, null, false);
        $this->initVar('bitrate', XOBJ_DTYPE_FLOAT, null, false);
        $this->initVar('playseconds', XOBJ_DTYPE_FLOAT, null, false);
        $this->initVar('bytes', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hits', XOBJ_DTYPE_INT, null, false);
        $this->initVar('created', XOBJ_DTYPE_INT, null, false);
        $this->initVar('played', XOBJ_DTYPE_INT, null, false);
        $this->initVar('packed', XOBJ_DTYPE_INT, null, false);
    }
}

/**
 * Class 8BitTrackssHandler
 */
class EightbitTracksHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, '8bit_tracks', 'EightbitTracks', 'id', 'last');
    }
    
    
    function insert(EightbitTracks $object, $force = true)
    {
        $isnew = false;
        if ($object->isNew())
        {
            $isnew = true;
            $object->setVar('created', time());
        }
        $trackid = parent::insert($object, $force);
        if ($isnew != false)
        {
            
            $title = strtolower(str_replace(" ", "", $object->getVar('title')));
            $alphachars = $bravochars = $charleychars = '-';
            $alpha_handler = xoops_getModuleHandler('alpha', basename(dirname(__DIR__)));
            $alphachars = substr($title, 0, 1);
            if (strlen($alphachars)!=1)
                $alphachars = '-';
            $bravochars = substr($title, 0, 2);
            if (strlen($bravochars)!=2)
                $bravochars = '--';
            $charleychars = substr($title, 0, 3);
            if (strlen($charleychars)!=3)
                $charleychars = '---';
            $criteria = new CriteriaCompo(new Criteria('alpha', $alphachars, 'LIKE'));
            $criteria->add(new Criteria('bravo', $bravochars, 'LIKE'));
            $criteria->add(new Criteria('charley', $charleychars, 'LIKE'));
            $criteria->add(new Criteria('type', 'track', 'LIKE'));
            if ($alpha_handler->getCount($criteria)!=0)
            {
                $alphaobjs = $alpha_handler->getObjects($criteria);
                $trackalphaid = $alphaobjs[0]->getVar('id');
            } else {
                $alphaobj = $alpha_handler->create();
                $alphaobj->setVar('type', 'track');
                $alphaobj->setVar('alpha', $alphachars);
                $alphaobj->setVar('bravo', $bravochars);
                $alphaobj->setVar('charley', $charleychars);
                $trackalphaid = $alpha_handler->insert($alphaobj);
            }
            
            $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_tracks') . "` SET `alphaid` = '$trackalphaid' WHERE `id` = '" . $trackid . "'";
            $GLOBALS['xoopsDB']->queryF($sql);
            
            $alpha_tracks_handler = xoops_getModuleHandler('alpha_tracks', basename(dirname(__DIR__)));
            $criteria = new CriteriaCompo(new Criteria('trackid', $trackid));
            $criteria->add(new Criteria('alphaid', $trackalphaid));
            if ($alpha_tracks_handler->getCount($criteria)==0)
            {
                $atobj = $alpha_tracks_handler->create();
                $atobj->setVar('trackid', $trackid);
                $atobj->setVar('alphaid', $trackalphaid);
                $alpha_tracks_handler->insert($atobj, true);
            }
            
            $albums_handler = xoops_getModuleHandler('albums', basename(dirname(__DIR__)));
            if ($album = $albums_handler->get($object->getVar('albumid'))) {
                $title = strtolower(str_replace(" ", "", $album->getVar('album')));
                $alphachars = $bravochars = $charleychars = '-';
                $alpha_handler = xoops_getModuleHandler('alpha', basename(dirname(__DIR__)));
                $alphachars = substr($title, 0, 1);
                if (strlen($alphachars)!=1)
                    $alphachars = '-';
                $bravochars = substr($title, 0, 2);
                if (strlen($bravochars)!=2)
                    $bravochars = '--';
                $charleychars = substr($title, 0, 3);
                if (strlen($charleychars)!=3)
                    $charleychars = '---';
                $criteria = new CriteriaCompo(new Criteria('alpha', $alphachars, 'LIKE'));
                $criteria->add(new Criteria('bravo', $bravochars, 'LIKE'));
                $criteria->add(new Criteria('charley', $charleychars, 'LIKE'));
                $criteria->add(new Criteria('type', 'album', 'LIKE'));
                if ($alpha_handler->getCount($criteria)!=0)
                {
                    $alphaobjs = $alpha_handler->getObjects($criteria);
                    $albumalphaid = $alphaobjs[0]->getVar('id');
                } else {
                    $alphaobj = $alpha_handler->create();
                    $alphaobj->setVar('type', 'album');
                    $alphaobj->setVar('alpha', $alphachars);
                    $alphaobj->setVar('bravo', $bravochars);
                    $alphaobj->setVar('charley', $charleychars);
                    $albumalphaid = $alpha_handler->insert($alphaobj);
                }
                
                $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_albums') . "` SET `alphaid` = '$albumalphaid' WHERE `id` = '" . $object->getVar('albumid') . "'";
                $GLOBALS['xoopsDB']->queryF($sql);
                
                $alpha_albums_handler = xoops_getModuleHandler('alpha_albums', basename(dirname(__DIR__)));
                $criteria = new CriteriaCompo(new Criteria('albumid', $object->getVar('albumid')));
                $criteria->add(new Criteria('alphaid', $albumalphaid));
                if ($alpha_albums_handler->getCount($criteria)==0)
                {
                    $aaobj = $alpha_albums_handler->create();
                    $aaobj->setVar('albumid', $object->getVar('albumid'));
                    $aaobj->setVar('alphaid', $trackalphaid);
                    $alpha_albums_handler->insert($aaobj, true);
                }
            }
            
            $artists_handler = xoops_getModuleHandler('artists', basename(dirname(__DIR__)));
            if ($artist = $artists_handler->get($object->getVar('albumid'))) {
                $artistalphaid = $artids = array();
                if ($artist->getVar('type') == 'alone')
                {
                    $artids[$artist->getVar('id')] = $artist->getVar('artist');
                } elseif ($artist->getVar('type') == 'chaining') {
                    $artists_chaining_handler = xoops_getModuleHandler('artists_chaining', basename(dirname(__DIR__)));
                    foreach($artists_chaining_handler->getObjects(new Criteria('artistid', $artist->getVar('id'))) as $chaining)
                    {
                        if ($artist = $artists_handler->get($chaining->getVar('childid')))
                            $artids[$artist->getVar('id')] = $artist->getVar('artist');
                    }
                    
                }
                foreach($artids as $artid => $title) {
                    $title = strtolower(str_replace(" ", "", $title));
                    $alphachars = $bravochars = $charleychars = '-';
                    $alpha_handler = xoops_getModuleHandler('alpha', basename(dirname(__DIR__)));
                    $alphachars = substr($title, 0, 1);
                    if (strlen($alphachars)!=1)
                        $alphachars = '-';
                    $bravochars = substr($title, 0, 2);
                    if (strlen($bravochars)!=2)
                        $bravochars = '--';
                    $charleychars = substr($title, 0, 3);
                    if (strlen($charleychars)!=3)
                        $charleychars = '---';
                    $criteria = new CriteriaCompo(new Criteria('alpha', $alphachars, 'LIKE'));
                    $criteria->add(new Criteria('bravo', $bravochars, 'LIKE'));
                    $criteria->add(new Criteria('charley', $charleychars, 'LIKE'));
                    $criteria->add(new Criteria('type', 'artist', 'LIKE'));
                    if ($alpha_handler->getCount($criteria)!=0)
                    {
                        $alphaobjs = $alpha_handler->getObjects($criteria);
                        $artistalphaid[] = $alphaobjs[0]->getVar('id');
                    } else {
                        $alphaobj = $alpha_handler->create();
                        $alphaobj->setVar('type', 'artist');
                        $alphaobj->setVar('alpha', $alphachars);
                        $alphaobj->setVar('bravo', $bravochars);
                        $alphaobj->setVar('charley', $charleychars);
                        $artistalphaid[] = $alpha_handler->insert($alphaobj);
                    }
                    
                    $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_artists') . "` SET `alphaid` = '" . $artistalphaid[count($artistalphaid) -1] . "' WHERE `id` = '" . $artid . "'";
                    $GLOBALS['xoopsDB']->queryF($sql);
                    
                    $alpha_artists_handler = xoops_getModuleHandler('alpha_artists', basename(dirname(__DIR__)));
                    $criteria = new CriteriaCompo(new Criteria('artistid', $artid));
                    $criteria->add(new Criteria('alphaid', $artistalphaid[count($artistalphaid) -1]));
                    if ($alpha_artists_handler->getCount($criteria)==0)
                    {
                        $aaobj = $alpha_artists_handler->create();
                        $aaobj->setVar('artistid', $artid);
                        $aaobj->setVar('alphaid', $artistalphaid[count($artistalphaid) -1]);
                        $alpha_artists_handler->insert($aaobj, true);
                    }
                }
            }
            
            $albums_artists_handler = xoops_getModuleHandler('albums_artists', basename(dirname(__DIR__)));
            $criteria = new CriteriaCompo(new Criteria('artistid', $object->getVar('artistid')));
            $criteria->add(new Criteria('albumid', $object->getVar('albumid')));
            if ($albums_artists_handler->getCount($criteria)==0)
            {
                $albumcount = $albums_artists_handler->getCount(new Criteria('albumid', $object->getVar('albumid')));
                $aaobj = $albums_artists_handler->create();
                $aaobj->setVar('artistid', $object->getVar('artistid'));
                $aaobj->setVar('albumid', $object->getVar('albumid'));
                if ($albums_artists_handler->insert($aaobj, true))
                {
                    if ($albumcount==0)
                    {
                        $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `albums` = `albums` + 1 WHERE `id` = '" . $trackalphaid . "'";
                        $GLOBALS['xoopsDB']->queryF($sql);
                        $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `albums` = `albums` + 1 WHERE `id` = '" . $albumalphaid . "'";
                        $GLOBALS['xoopsDB']->queryF($sql);
                        foreach($artistalphaid as $aaid) {
                            $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `albums` = `albums` + 1 WHERE `id` = '" . $aaid . "'";
                            $GLOBALS['xoopsDB']->queryF($sql);
                        }
                        $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_artists') . "` SET `albums` = `albums` + 1 WHERE `id` = '" . $object->getVar('artistid') . "'";
                        $GLOBALS['xoopsDB']->queryF($sql);
                    }
                    $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_albums') . "` SET `artists` = `artists` + 1 WHERE `id` = '" . $object->getVar('albumid') . "'";
                    $GLOBALS['xoopsDB']->queryF($sql);
                    $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `artists` = `artists` + 1 WHERE `id` = '" . $trackalphaid . "'";
                    $GLOBALS['xoopsDB']->queryF($sql);
                    $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `artists` = `artists` + 1 WHERE `id` = '" . $albumalphaid . "'";
                    $GLOBALS['xoopsDB']->queryF($sql);
                    foreach($artistalphaid as $aaid) {
                        $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `artists` = `artists` + 1 WHERE `id` = '" . $aaid . "'";
                        $GLOBALS['xoopsDB']->queryF($sql);
                    }
                }
            }
            $albums_tracks_handler = xoops_getModuleHandler('albums_tracks', basename(dirname(__DIR__)));
            $criteria = new CriteriaCompo(new Criteria('trackid', $trackid));
            $criteria->add(new Criteria('albumid', $object->getVar('albumid')));
            if ($albums_tracks_handler->getCount($criteria)==0)
            {
                $atobj = $albums_tracks_handler->create();
                $atobj->setVar('trackid', $trackid);
                $atobj->setVar('albumid', $object->getVar('albumid'));
                if ($albums_tracks_handler->insert($atobj, true))
                {
                    $alpha_tracks_handler = xoops_getModuleHandler('alpha_tracks', basename(dirname(__DIR__)));
                    $alphaobj = $alpha_tracks_handler->create();
                    $alphaobj->setVar('trackid', $trackid);
                    $alphaobj->setVar('albumid', $object->getVar('albumid'));
                    $alphaobj->setVar('artistid', $object->getVar('artistid'));
                    $alphaobj->setVar('alphaid', $alphaid);
                    if ($alpha_tracks_handler->insert($alphaobj))
                    {
                        $artists_chaining_handler = xoops_getModuleHandler('artists_chaining', basename(dirname(__DIR__)));
                        $criteria = new Criteria('artistid', $object->getVar('artistid'));
                        if ($artists_chaining_handler->getCount($criteria)) {
                            foreach($artists_chaining_handler->getObjects($criteria) as $artist_chain) {
                                $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_artists') . "` SET `tracks` = `tracks` + 1, `totalseconds` = `totalseconds` + '" . $object->getVar('playseconds') . "' WHERE `id` = '" . $artist_chain->getVar('childid') . "'";
                                $GLOBALS['xoopsDB']->queryF($sql);
                            }
                        }
                        $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_artists') . "` SET `tracks` = `tracks` + 1, `totalseconds` = `totalseconds` + '" . $object->getVar('playseconds') . "' WHERE `id` = '" . $object->getVar('artistid') . "'";
                        $GLOBALS['xoopsDB']->queryF($sql);
                        $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_albums') . "` SET `tracks` = `tracks` + 1, `totalseconds` = `totalseconds` + '" . $object->getVar('playseconds') . "' WHERE `id` = '" . $object->getVar('albumid') . "'";
                        $GLOBALS['xoopsDB']->queryF($sql);
                        $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `tracks` = `tracks` + 1, `totalseconds` = `totalseconds` + '" . $object->getVar('playseconds') . "' WHERE `id` = '" . $trackalphaid . "'";
                        $GLOBALS['xoopsDB']->queryF($sql);
                        $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `tracks` = `tracks` + 1, `totalseconds` = `totalseconds` + '" . $object->getVar('playseconds') . "' WHERE `id` = '" . $albumalphaid . "'";
                        $GLOBALS['xoopsDB']->queryF($sql);
                        foreach($artistalphaid as $aaid) {
                            $sql = "UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `tracks` = `tracks` + 1, `totalseconds` = `totalseconds` + '" . $object->getVar('playseconds') . "' WHERE `id` = '" . $aaid . "'";
                            $GLOBALS['xoopsDB']->queryF($sql);
                        }
                    }
                }
            }
        }
        return $trackid;
    }
}
