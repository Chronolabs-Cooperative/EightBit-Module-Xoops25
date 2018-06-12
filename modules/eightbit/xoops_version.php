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


$modversion                   = array();
$modversion['name']           = _8BIT_MI_NAME;
$modversion['version']        = _8BIT_MI_VERSION;
$modversion['description']    = _8BIT_MI_DESCRIPTION;
$modversion['author']         = 'Dr. Simon Antony Roberts <wishcraft>';
$modversion['credits']        = 'Chronolabs Cooperative';
$modversion['help']           = 'page=help';
$modversion['license']        = 'GNU GPL 2.0 or later';
$modversion['license_url']    = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['image']          = 'assets/images/8bit-logo.png';
$modversion['dirname']        = 'eightbit';
$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';

//about
$modversion['module_status']       = 'Final';
$modversion['release_date']        = '2018/04/04';
$modversion['module_website_url']  = 'http://github.com/Chronolabs-Cooperative/';
$modversion['module_website_name'] = 'Chronolabs Cooperative';
$modversion['min_php']             = '5.3.7';
$modversion['min_xoops']           = '2.5.8';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7');

// Admin menu
// Set to 1 if you want to display menu generated by system module
$modversion['system_menu'] = 1;

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/admin.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysqli.sql";

// Table
$modversion['tables'][] = "8bit_albums";
$modversion['tables'][] = "8bit_albums_artists";
$modversion['tables'][] = "8bit_albums_tracks";
$modversion['tables'][] = "8bit_alpha";
$modversion['tables'][] = "8bit_alpha_albums";
$modversion['tables'][] = "8bit_alpha_artists";
$modversion['tables'][] = "8bit_alpha_tracks";
$modversion['tables'][] = "8bit_artists";
$modversion['tables'][] = "8bit_artists_chaining";
$modversion['tables'][] = "8bit_hashing";
$modversion['tables'][] = "8bit_repositories";
$modversion['tables'][] = "8bit_tracks";
$modversion['tables'][] = "8bit_tracks_emails";

// Scripts to run upon installation or update
// $modversion['onInstall'] = 'include/install.php';
// $modversion['onUpdate']  = 'include/update.php';

// Templates
$i=0;
$modversion['templates']                        = array();
$modversion['templates'][++$i]['file']          = 'eightbit_album.html';
$modversion['templates'][$i]['description']     = 'Album Single Display';
$modversion['templates'][++$i]['file']          = 'eightbit_albums_grid.html';
$modversion['templates'][$i]['description']     = 'Albums Tabled Grid Display';
$modversion['templates'][++$i]['file']          = 'eightbit_albums.html';
$modversion['templates'][$i]['description']     = 'Albums Display';
$modversion['templates'][++$i]['file']          = 'eightbit_alpha_albums_grid.html';
$modversion['templates'][$i]['description']     = 'Alpha Numeric Albums Tabled Grid Display';
$modversion['templates'][++$i]['file']          = 'eightbit_alpha_artists_grid.html';
$modversion['templates'][$i]['description']     = 'Alpha Numeric Artists Tabled Grid Display';
$modversion['templates'][++$i]['file']          = 'eightbit_alpha_tracks_grid.html';
$modversion['templates'][$i]['description']     = 'Alpha Numeric Tracks Tabled Grid Display';
$modversion['templates'][++$i]['file']          = 'eightbit_artist.html';
$modversion['templates'][$i]['description']     = 'Artist Single Display';
$modversion['templates'][++$i]['file']          = 'eightbit_artists_grid.html';
$modversion['templates'][$i]['description']     = 'Artists Tabled Grid Display';
$modversion['templates'][++$i]['file']          = 'eightbit_artists.html';
$modversion['templates'][$i]['description']     = 'Artists Single Display';
$modversion['templates'][++$i]['file']          = 'eightbit_breadcrumb.html';
$modversion['templates'][$i]['description']     = '8Bit Breadcrumb';
$modversion['templates'][++$i]['file']          = 'eightbit_index.html';
$modversion['templates'][$i]['description']     = '8Bit Index Display';
$modversion['templates'][++$i]['file']          = 'eightbit_track.html';
$modversion['templates'][$i]['description']     = 'Track Single Display';
$modversion['templates'][++$i]['file']          = 'eightbit_tracks_grid.html';
$modversion['templates'][$i]['description']     = 'Tracks Tabled Grid Display';
$modversion['templates'][++$i]['file']          = 'eightbit_tracks.html';
$modversion['templates'][$i]['description']     = 'Tracks Display';


// Menu
$modversion['hasMain'] = 1;

$modversion['sub'][1]['name'] = '8 Bit Albums';
$modversion['sub'][1]['url']  = 'albums.php';
$modversion['sub'][2]['name'] = '8 Bit Artists';
$modversion['sub'][2]['url']  = 'artists.php';
$modversion['sub'][3]['name'] = '8 Bit Tracks';
$modversion['sub'][3]['url']  = 'tracks.php';
$modversion['sub'][4]['name'] = 'Queued Emails';
$modversion['sub'][4]['url']  = 'emails.php';
$modversion['sub'][5]['name'] = '8 Bit Search';
$modversion['sub'][5]['url']  = 'search.php';

/*
$modversion['config']   = array();
$modversion['config'][] = array(
    'name'        => 'perpage',
    'title'       => '_8BIT_MI_PERPAGE',
    'description' => '_8BIT_MI_PERPAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 20);

$modversion['config'][] = array(
    'name'        => 'max_save',
    'title'       => '_8BIT_MI_MAXSAVE',
    'description' => '_8BIT_MI_MAXSAVE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10);

$modversion['config'][] = array(
    'name'        => 'prunesubject',
    'title'       => '_8BIT_MI_PRUNESUBJECT',
    'description' => '_8BIT_MI_PRUNESUBJECT_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _8BIT_MI_PRUNESUBJECTDEFAULT);

$modversion['config'][] = array(
    'name'        => 'prunemessage',
    'title'       => '_8BIT_MI_PRUNEMESSAGE',
    'description' => '_8BIT_MI_PRUNEMESSAGE_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => _8BIT_MI_PRUNEMESSAGEDEFAULT);
    */
