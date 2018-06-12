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

require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'mainfile.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include'. DIRECTORY_SEPARATOR . 'functions.php';

$criteria = new Criteria('last', time(), "<=");
$repositories_handler = xoops_getModuleHandler('repositories', basename(dirname(__DIR__)));
$repositories = $repositories_handler->getObjects($criteria);

foreach($repositories as $key => $repository)
{
    
    $repository->setVar('last', time() + (3600 * mt_rand(12, 196)));
    $repositories_handler->insert($repository);
    
    $GLOBALS['xoopsDB']->queryF("START TRANSACTION");
    // Albums
    $hashing_handler = xoops_getModuleHandler('hashing', basename(dirname(__DIR__)));
    $albums_json = eightbit_getURIData($repository->getVar('json_albums'));
    $criteria = new CriteriaCompo(new Criteria('hash', sha1($albums_json), "LIKE"));
    $criteria->add(new Criteria('type', 'repository'));
    if ($hashing_handler->getCount($criteria) == 0)
    {
        $albums = json_decode($albums_json, true);
        foreach($albums as $hash => $album)
        {
            $albums_handler = xoops_getModuleHandler('albums', basename(dirname(__DIR__)));
            $found = true;
            $criteria = new CriteriaCompo(new Criteria('hash', $hash, "LIKE"));
            $criteria->add(new Criteria('type', 'album'));
            if ($hashing_handler->getCount($criteria) == 0)
                $found = false;
            $criteria = new CriteriaCompo(new Criteria('album', $album, "LIKE"));
            if ($albums_handler->getCount($criteria) != 0 && $found == false)
            {
                $found = true;
                $albumobjs = $albums_handler->getObjects($criteria);
                $hashing = $hashing_handler->create();
                $hashing->setVar('type', 'album');
                $hashing->setVar('repoid', $repository->getVar('id'));
                $hashing->setVar('itemid', $albumobjs[0]->getVar('id'));
                $hashing->setVar('hash', $hash);
                if (!$hashing_handler->insert($hashing))
                    die('Problem Inserting hashing reference!');
            }
            if ($found == false)
            {
                $albumobj = $albums_handler->create();
                $albumobj->setVar('album', $album);
                if ($albumid = $albums_handler->insert($albumobj, true)) {
                    if (!defined('_8BIT_PRELOADED_CRON'))
                        echo "Album Inserted [$albumid] ~ $album\n";
                    $hashing = $hashing_handler->create();
                    $hashing->setVar('type', 'album');
                    $hashing->setVar('repoid', $repository->getVar('id'));
                    $hashing->setVar('itemid', $albumid);
                    $hashing->setVar('hash', $hash);
                    if (!$hashing_handler->insert($hashing))
                        die('Problem Inserting new hashing reference!');
                } else {
                    die('Problem Inserting album reference!');
                }
            }
        }
        $hashing = $hashing_handler->create();
        $hashing->setVar('type', 'repository');
        $hashing->setVar('repoid', $repository->getVar('id'));
        $hashing->setVar('itemid', 0);
        $hashing->setVar('hash', sha1($albums_json));
        if (!$hashing_handler->insert($hashing))
            die('Problem Inserting new hashing reference!');
    }
    $GLOBALS['xoopsDB']->queryF("COMMIT");
    
    $GLOBALS['xoopsDB']->queryF("START TRANSACTION");
    // Artists
    $artists_json = eightbit_getURIData($repository->getVar('json_artists'));
    $criteria = new CriteriaCompo(new Criteria('hash', sha1($artists_json), "LIKE"));
    $criteria->add(new Criteria('type', 'repository'));
    if ($hashing_handler->getCount($criteria) == 0)
    {
        $artists = json_decode($artists_json, true);
        $artistids = array();
        foreach($artists as $hash => $artist)
        {
            $artistsarr = eightbit_splitArtists($artist);
            if (count($artistsarr)<=1)
            {
                $artist = $artistsarr[0];
                $artists_handler = xoops_getModuleHandler('artists', basename(dirname(__DIR__)));
                $found = true;
                $criteria = new CriteriaCompo(new Criteria('hash', $hash, "LIKE"));
                $criteria->add(new Criteria('type', 'artist'));
                if ($hashing_handler->getCount($criteria) == 0)
                    $found = false;
                $criteria = new CriteriaCompo(new Criteria('artist', $artist, "LIKE"));
                if ($artists_handler->getCount($criteria) != 0 && $found == false)
                {
                    $found = true;
                    $artistobjs = $artists_handler->getObjects($criteria);
                    $hashing = $hashing_handler->create();
                    $hashing->setVar('type', 'artist');
                    $hashing->setVar('repoid', $repository->getVar('id'));
                    $hashing->setVar('itemid', $artistid = $artistobjs[0]->getVar('id'));
                    $hashing->setVar('hash', $hash);
                    if (!$hashing_handler->insert($hashing))
                        die('Problem Inserting hashing reference!');
                }
                if ($found == false)
                {
                    $artistobj = $artists_handler->create();
                    $artistobj->setVar('type', 'alone');
                    $artistobj->setVar('artist', $artist);
                    if ($artistid = $artists_handler->insert($artistobj, true)) {
                        if (!defined('_8BIT_PRELOADED_CRON'))
                            echo "Artist Inserted [$artistid] ~ $artist\n";
                        $hashing = $hashing_handler->create();
                        $hashing->setVar('type', 'artist');
                        $hashing->setVar('repoid', $repository->getVar('id'));
                        $hashing->setVar('itemid', $artistid);
                        $hashing->setVar('hash', $hash);
                        if (!$hashing_handler->insert($hashing))
                            die('Problem Inserting new hashing reference!');
                    } else {
                        die('Problem Inserting artist reference!');
                    }
                }
            } elseif (count($artistsarr)>1) {
                foreach($artistsarr as $artist)
                {
                    $artists_handler = xoops_getModuleHandler('artists', basename(dirname(__DIR__)));
                    $found = true;
                    $criteria = new CriteriaCompo(new Criteria('hash', md5($artist.$hash), "LIKE"));
                    $criteria->add(new Criteria('type', 'artist'));
                    if ($hashing_handler->getCount($criteria) == 0)
                        $found = false;
                    $criteria = new CriteriaCompo(new Criteria('artist', $artist, "LIKE"));
                    if ($artists_handler->getCount($criteria) != 0)
                    {
                        $found = true;
                        $artistobjs = $artists_handler->getObjects($criteria);
                        $hashing = $hashing_handler->create();
                        $hashing->setVar('type', 'artist');
                        $hashing->setVar('repoid', $repository->getVar('id'));
                        $hashing->setVar('itemid', $artistids[] = $artistobjs[0]->getVar('id'));
                        $hashing->setVar('hash', md5($artist.$hash));
                        if (!$hashing_handler->insert($hashing))
                            die('Problem Inserting hashing reference!');
                    }
                    if ($found == false)
                    {
                        $artistobj = $artists_handler->create();
                        $artistobj->setVar('type', 'alone');
                        $artistobj->setVar('artist', $artist);
                        if ($artistids[] = $artists_handler->insert($artistobj, true)) {
                            if (!defined('_8BIT_PRELOADED_CRON'))
                                echo "Artist Inserted [$artistid] ~ $artist\n";
                            $hashing = $hashing_handler->create();
                            $hashing->setVar('type', 'artist');
                            $hashing->setVar('repoid', $repository->getVar('id'));
                            $hashing->setVar('itemid', $artistids[count($artistids)-1]);
                            $hashing->setVar('hash', md5($artist.$hash));
                            if (!$hashing_handler->insert($hashing))
                                die('Problem Inserting new hashing reference!');
                        } else {
                            die('Problem Inserting artist reference!');
                        }
                    }
                }
                
                $artists_handler = xoops_getModuleHandler('artists', basename(dirname(__DIR__)));
                $found = true;
                $criteria = new CriteriaCompo(new Criteria('hash', $hash, "LIKE"));
                $criteria->add(new Criteria('type', 'artist'));
                if ($hashing_handler->getCount($criteria) == 0) 
                    $found = false;
                $criteria = new CriteriaCompo(new Criteria('artist', sha1(json_encode($artistsarr)), "LIKE"));
                if ($artists_handler->getCount($criteria) != 0 && $found == false)
                {
                    $found = true;
                    $artistobjs = $artists_handler->getObjects($criteria);
                    $hashing = $hashing_handler->create();
                    $hashing->setVar('type', 'artist');
                    $hashing->setVar('repoid', $repository->getVar('id'));
                    $hashing->setVar('itemid', $artistid = $artistobjs[0]->getVar('id'));
                    $hashing->setVar('hash', $hash);
                    if (!$hashing_handler->insert($hashing))
                        die('Problem Inserting hashing reference!');
                }
                if ($found == false)
                {
                    $artistobj = $artists_handler->create();
                    $artistobj->setVar('type', 'chaining');
                    $artistobj->setVar('artist', sha1(json_encode($artistsarr)));
                    if ($artistid = $artists_handler->insert($artistobj, true)) {
                        $artists_chaining_handler = xoops_getModuleHandler('artists_chaining', basename(dirname(__DIR__)));
                        foreach($artistids as $artid)
                        {
                            $acobj = $artists_chaining_handler->create();
                            $acobj->setVar('artistid', $artistid);
                            $acobj->setVar('childid', $artid);
                            $artists_chaining_handler->insert($acobj);
                        }
                        if (!defined('_8BIT_PRELOADED_CRON'))
                            echo "Artist Inserted [$artistid] ~ $artist\n";
                        $hashing = $hashing_handler->create();
                        $hashing->setVar('type', 'artist');
                        $hashing->setVar('repoid', $repository->getVar('id'));
                        $hashing->setVar('itemid', $artistid);
                        $hashing->setVar('hash', $hash);
                        if (!$hashing_handler->insert($hashing))
                            die('Problem Inserting new hashing reference!');
                    } else {
                        die('Problem Inserting artist reference!');
                    }
                }
            }
        }
        
        $hashing = $hashing_handler->create();
        $hashing->setVar('type', 'repository');
        $hashing->setVar('repoid', $repository->getVar('id'));
        $hashing->setVar('itemid', 0);
        $hashing->setVar('hash', sha1($artists_json));
        if (!$hashing_handler->insert($hashing))
            die('Problem Inserting new hashing reference!');
    }
    $GLOBALS['xoopsDB']->queryF("COMMIT");
    $GLOBALS['xoopsDB']->queryF("START TRANSACTION");
    // Tracks
    $tracks_json = eightbit_getURIData($repository->getVar('json_tracks'));
    $criteria = new CriteriaCompo(new Criteria('hash', sha1($tracks_json), "LIKE"));
    $criteria->add(new Criteria('type', 'repository'));
    if ($hashing_handler->getCount($criteria) == 0)
    {
        $tracks = json_decode($tracks_json, true);
        foreach($tracks as $hash => $track)
        {
            $albumid = $artistid = false;
            $tracks_handler = xoops_getModuleHandler('tracks', basename(dirname(__DIR__)));
            $import = true;
            $criteria = new CriteriaCompo(new Criteria('hash', $track['artist-key'], "LIKE"));
            $criteria->add(new Criteria('type', 'artist'));
            if ($hashing_handler->getCount($criteria) != 0) {
                $import = true;
                $hashingobjs = $hashing_handler->getObjects($criteria);
                $artistid = $hashingobjs[0]->getVar('itemid');
            }
            $criteria = new CriteriaCompo(new Criteria('hash', $track['album-key'], "LIKE"));
            $criteria->add(new Criteria('type', 'album'));
            if ($hashing_handler->getCount($criteria) != 0) {
                $import = true;
                $hashingobjs = $hashing_handler->getObjects($criteria);
                $albumid = $hashingobjs[0]->getVar('itemid');
            }
            $criteria = new CriteriaCompo(new Criteria('hash', $hash, "LIKE"));
            $criteria->add(new Criteria('type', 'track'));
            if ($hashing_handler->getCount($criteria) != 0) {
                $import = false;
            }

            if ($import != false)
            {
                
                $trackobj = $tracks_handler->create();
                $trackobj->setVar('repoid', $repository->getVar('id'));
                $trackobj->setVar('albumid', $albumid);
                $trackobj->setVar('artistid', $artistid);
                $trackobj->setVar('title', $track['title']);
                $trackobj->setVar('path', $track['path']);
                $trackobj->setVar('file', $track['file']);
                $trackobj->setVar('year', $track['year']);
                $trackobj->setVar('minutes', $track['minutes']);
                $trackobj->setVar('seconds', $track['seconds']);
                $trackobj->setVar('bitrate', $track['bitrate']);
                $trackobj->setVar('playseconds', $track['playseconds']);
                if ($trackid = $tracks_handler->insert($trackobj, true)) {
                    if (!defined('_8BIT_PRELOADED_CRON'))
                        echo "Track Inserted [$trackid] ~ ".$track['title']."\n";
                    $hashing = $hashing_handler->create();
                    $hashing->setVar('type', 'track');
                    $hashing->setVar('repoid', $repository->getVar('id'));
                    $hashing->setVar('itemid', $trackid);
                    $hashing->setVar('hash', $hash);
                    if (!$hashing_handler->insert($hashing))
                        die('Problem Inserting new hashing reference!');
                } else {
                    die('Problem Inserting track reference!');
                }
                
            }
        }
        
        $hashing = $hashing_handler->create();
        $hashing->setVar('type', 'repository');
        $hashing->setVar('repoid', $repository->getVar('id'));
        $hashing->setVar('itemid', 0);
        $hashing->setVar('hash', sha1($tracks_json));
        if (!$hashing_handler->insert($hashing))
            die('Problem Inserting new hashing reference!');
    }
    $GLOBALS['xoopsDB']->queryF("COMMIT");
    
}