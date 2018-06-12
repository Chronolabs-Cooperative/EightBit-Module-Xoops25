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
 * EightBit Alpha Class
 *
 * @package             eightbit
 *
 * @author              Simon Antony Roberts <wishcraft@users.sourceforge.net>
 */
class EightbitAlpha extends XoopsObject
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('type', XOBJ_DTYPE_ENUM, 'album', false, false, false, false, false, array('album', 'artist', 'track'));
        $this->initVar('alpha', XOBJ_DTYPE_TXTBOX, '-', false, 1);
        $this->initVar('bravo', XOBJ_DTYPE_TXTBOX, '--', false, 2);
        $this->initVar('charley', XOBJ_DTYPE_TXTBOX, '---', false, 3);
        $this->initVar('artists', XOBJ_DTYPE_INT, null, false);
        $this->initVar('albums', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tracks', XOBJ_DTYPE_INT, null, false);
        $this->initVar('bytes', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hits', XOBJ_DTYPE_INT, null, false);
        $this->initVar('totalseconds', XOBJ_DTYPE_FLOAT, null, false);
    }
}

/**
 * Class 8BitAlphaHandler
 */
class EightbitAlphaHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, '8bit_alpha', 'EightbitAlpha', 'id', 'album');
    }
    
    public function selAlpha($alpha = '', $type = 'album')
    {
        
        switch (strlen($alpha))
        {
            default:
                $sql = "SELECT DISTINCT `alpha` as `pheto`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' GROUP BY `alpha` ORDER BY `alpha` ASC";
                break;
            case 1:
                $sql = "SELECT DISTINCT `bravo` as `pheto`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' AND `alpha` = '$alpha' GROUP BY `bravo` ORDER BY `bravo` ASC";
                break;
            case 2:
                $sql = "SELECT DISTINCT `charley` as `pheto`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' AND `bravo` = '$alpha' GROUP BY `charley` ORDER BY `charley` ASC";
                break;
        }
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        $return = array();
        while($row = $GLOBALS['xoopsDB']->fetchArray($result))
            $return[$row['pheto']] = $row;
        return $return;
    }
    
    public function getIDsAlpha($alpha = '', $type = 'album')
    {
        
        switch (strlen($alpha))
        {
            default:
                $sql = "SELECT DISTINCT `id` as `id`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' GROUP BY `id` ORDER BY `alpha` ASC";
                break;
            case 1:
                $GLOBALS['xoopsDB']->queryF("UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `hits` = `hits` + 1 WHERE `type` = '$type' AND `alpha` = '$alpha'");
                $sql = "SELECT DISTINCT `id` as `id`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' AND `alpha` = '$alpha' GROUP BY `id` ORDER BY `bravo` ASC";
                break;
            case 2:
                $GLOBALS['xoopsDB']->queryF("UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `hits` = `hits` + 1 WHERE `type` = '$type' AND `bravo` = '$alpha'");
                $sql = "SELECT DISTINCT `id` as `id`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' AND `bravo` = '$alpha' GROUP BY `id` ORDER BY `charley` ASC";
                break;
            case 3:
                $GLOBALS['xoopsDB']->queryF("UPDATE `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` SET `hits` = `hits` + 1 WHERE `type` = '$type' AND `charley` = '$alpha'");
                $sql = "SELECT DISTINCT `id` as `id`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' AND `charley` = '$alpha' GROUP BY `id` ORDER BY `charley` ASC";
                break;
        }
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        $return = array();
        while($row = $GLOBALS['xoopsDB']->fetchArray($result))
            $return[$row['id']] = $row;
        return $return;
    }
    
    
    public function getCrumbs($alpha = '', $type = 'album')
    {
        
        switch (strlen($alpha))
        {
            default:
                $sql = "SELECT DISTINCT `alpha` as `alpha`, `bravo` as `bravo`, `charley` as `charley`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' GROUP BY `id` ORDER BY `alpha` ASC";
                break;
            case 1:
                $sql = "SELECT DISTINCT `alpha` as `alpha`, `bravo` as `bravo`, `charley` as `charley`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' AND `alpha` = '$alpha' GROUP BY `alpha`, `bravo`, `charley` ORDER BY `bravo` ASC";
                break;
            case 2:
                $sql = "SELECT DISTINCT `alpha` as `alpha`, `bravo` as `bravo`, `charley` as `charley`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' AND `bravo` = '$alpha' GROUP BY `alpha`, `bravo`, `charley` ORDER BY `charley` ASC";
                break;
            case 3:
                $sql = "SELECT DISTINCT `alpha` as `alpha`, `bravo` as `bravo`, `charley` as `charley`, sum(`totalseconds`) as `totalseconds`, sum(`tracks`) as `tracks`, sum(`albums`) as `albums`, sum(`artists`) as `artists`, sum(`hits`) as `hits` FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_alpha') . "` WHERE `type` = '$type' AND `charley` = '$alpha' GROUP BY `alpha`, `bravo`, `charley` ORDER BY `charley` ASC";
                break;
        }
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        $return = array();
        while($row = $GLOBALS['xoopsDB']->fetchArray($result))
            $return[] = $row;
        
        $result = array();
        for($i=1;$i<=strlen($alpha);$i++)
            switch ($i)
            {
                default:
                    break;
                case 1:
                    foreach($return as $values)
                    {
                        if ($values['alpha'] == substr($alpha, 0, 1))
                            $result[$values['alpha']] = $values;
                            break;
                    }
                    break;
                case 2:
                    foreach($return as $values) {
                        if ($values['bravo'] == substr($alpha, 0, 2))
                            $result[$values['bravo']] = $values;
                            break;
                    }
                    break;
                case 3:
                    foreach($return as $values) {
                        if ($values['charley'] == substr($alpha, 0, 3))
                            $result[$values['charley']] = $values;
                            break;
                    }
                    break;
            }
        return $result;
    }
    
}
