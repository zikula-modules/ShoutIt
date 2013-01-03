<?php
/**
 * Shoutit module for Zikula Application Framework
 *
 * @author       Gabriel Freinbichler
 *              refactored for zk 1.3 by Philippe Baudrion - UniGE/FTI
 * @link         http://www.cmods-dev.de
 * @copyright    Copyright (C) by Gabriel Freinbichler
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version      $Id$
 */
class Shoutit_Block_Shoutit extends Zikula_Controller_AbstractBlock
{
    /**
     * Initialise block.
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema($this->name.':block:', 'Block Id::');
    }
    
    /**
     * Get information on block.
     */
    public function info()
    {
        return array(
            'module'        => $this->name,
            'text_type'     => $this->__('Shout it'),
            'text_type_long'=> $this->__('Displays a shoutbox'),
            'allow_multiple'=> true,
            'form_content'  => false,
            'form_refresh'  => false,
            'show_preview'  => true
        );
    }

    /**
     * Display the block according its configuration.
     */
    public function display($blockinfo)
    {
        // Security check
        if (!SecurityUtil::checkPermission($this->name.':block:', $blockinfo['bid']."::", ACCESS_OVERVIEW)) {
            return false;
        }

        // get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        $vars['bid'] = $blockinfo['bid'];
        $uid = UserUtil::getVar('uid');
        
        //$this->view->setCaching(Zikula_View::CACHE_DISABLED);
        $vars['postPerm'] = SecurityUtil::checkPermission($this->name.'::', $blockinfo['bid']."::", ACCESS_COMMENT);

        if($vars['grpMsg']) {
            // Zikula Groups API: get Zikula users groups to create the dropdown menu
            // requires SecurityUtil::checkPermission('Groups::', '::', ACCESS_READ)
            $vars['groups'] = ModUtil::apiFunc('Groups', 'user', 'getall');
            $myGroups = ModUtil::apiFunc('Groups', 'user', 'getusergroups', array('uid' => $uid));
            // if this is a regular user hide Users and Administrators groups
            if(!SecurityUtil::checkPermission($this->name.':block:', $blockinfo['bid']."::", ACCESS_MODERATE)) {
                foreach ($vars['groups'] as $key => $group) {
                    if($group['gid'] == '1' ||  $group['gid'] == '2' || !in_array(array('gid' => $group['gid'],'uid' => $uid), $myGroups)) {
                        unset($vars['groups'][$key]);
                    }
                }
            }
        }

        // assign the block vars
        $this->view->assign($vars);

        if($this->view->template_exists("user/box_{$blockinfo['bid']}.tpl")) {
            $blockinfo['content'] = $this->view->fetch("user/box_{$blockinfo['bid']}.tpl");
        } else {
            $blockinfo['content'] = $this->view->fetch('user/box.tpl');
        }

        // return the block to the theme
        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * Modify block settings.
     */
    public function modify($blockinfo)
    {
        // get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // defaults
        if (!isset($vars['nbMsg'])) {
            $vars['nbMsg'] = '100';
            ModUtil::setVar('Shoutit', "shoutit_lastx_messages_{$blockinfo['bid']}", '100');
        }
        if (!isset($vars['refRate'])) {
            $vars['refRate'] = ModUtil::getVar('Shoutit', 'shoutit_refresh_rate');
        }
        if (!isset($vars['msgLength'])) {
            $vars['msgLength'] = '70';
        }
        if (!isset($vars['grpMsg'])) {
            $vars['grpMsg'] = '0';
            ModUtil::setVar('Shoutit', "shoutit_group_messages_{$blockinfo['bid']}", '0');
        }
        if (!isset($vars['delMsg'])) {
            $vars['delMsg'] = '0';
        }

        // builds and return the output
        return $this->view->assign('vars', $vars)
                          ->fetch('admin/block_modify.tpl');
    }

    /**
     * Update block settings.
     *
     */
    public function update($blockinfo)
    {
        // Get form data
        $nbMsg      = (int)FormUtil::getPassedValue('shoutit_nbMsg');
        $refRate    = (int)FormUtil::getPassedValue('shoutit_refRate');
        $msgLength  = (int)FormUtil::getPassedValue('shoutit_msgLength');
        $grpMsg     = (int)FormUtil::getPassedValue('shoutit_grpMsg');
        $delMsg     = (int)FormUtil::getPassedValue('shoutit_delMsg');

        $nbMsg      = ($nbMsg >= 10) ? $nbMsg : '10';
        $refRate    = ($refRate >= 6) ? $refRate : '6';
        $msgLength  = ($msgLength >= 20) ? $msgLength : '20';

        if($delMsg) {
            ModUtil::apiFunc('Shoutit', 'user', 'deleteMessages', array(
                'bid' => $blockinfo['bid']
                ));
        }

        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        $vars['nbMsg']      = $nbMsg;
        $vars['refRate']    = $refRate;
        $vars['msgLength']  = $msgLength;
        $vars['grpMsg']     = $grpMsg;
        $vars['delMsg']     = '0';

        ModUtil::setVar('Shoutit', "shoutit_lastx_messages_{$blockinfo['bid']}", $nbMsg);
        ModUtil::setVar('Shoutit', 'shoutit_refresh_rate', $refRate);
        ModUtil::setVar('Shoutit', "shoutit_group_messages_{$blockinfo['bid']}", $grpMsg);

        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        return $blockinfo;
    }
// end of class
}
?>
