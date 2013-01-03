<?php
/**
 * Shoutit module for Zikula Application Framework
 *
 * @author       Gabriel Freinbichler
 *               refactored for zk 1.3 by Philippe Baudrion - UniGE/FTI
 * @link         http://www.cmods-dev.de
 * @copyright    Copyright (C) by Gabriel Freinbichler
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version      $Id$
 */

/**
 * User API
 */
class Shoutit_Api_User extends Zikula_AbstractApi
{
    /**
     * Get all allowed messages from the database
     * @param array $args['bid']
     * @return array $messages
     */
    public function getMessages($args) {

        if (!isset($args['bid'])) {
            return LogUtil::registerArgsError();
        }

        $bid      = $args['bid'];
        $messages = array();

        $tables = DBUtil::getTables();
        $shColumn = $tables['shoutit_messages_column'];
        $orderBy= "ORDER BY $shColumn[cr_date] DESC";
        $limitNumRows  = ModUtil::getVar('Shoutit', "shoutit_lastx_messages_{$bid}");
        $where  = '';
        $joinInfo[] = array (
            'join_table'            => 'users',   // table for the join
            'join_field'            => 'uname',   // field in the join table that should be in the result with
            'object_field_name'     => 'uname',   // ...this name for the new column
            'compare_field_table'   => 'cr_uid',  // regular table column that should be equal to
            'compare_field_join'    => 'uid'      // ...the table in join_table
            );

        // Only select user own messages and messages from own registered group(s)
        if(ModUtil::getVar('Shoutit', "shoutit_group_messages_{$bid}") == '1' &&
           !SecurityUtil::checkPermission('Shoutit::', $bid.'::', ACCESS_MODERATE)) {
            
            $uid = UserUtil::getVar('uid');
            $where = "WHERE $shColumn[cr_uid] = $uid";

            // Zikula Groups API: get user groups membership
            // requires SecurityUtil::checkPermission('Groups::', '::', ACCESS_READ)
            $groups = ModUtil::apiFunc('Groups', 'user', 'getusergroups', array('uid' => $uid));
            
            foreach ($groups as $group) {
                // remove Users and Administrators groups
                if($group['gid'] != '1' &&  $group['gid'] != '2') {
                    $where .= " OR {$shColumn['gid']} = {$group['gid']} AND {$shColumn['cr_uid']} <> {$uid}";
                }
            }
        } else {// get last x messages from all for "moderate" users
        }

        $messages = DBUtil::selectExpandedObjectArray('shoutit_messages', $joinInfo, $where, $orderBy, '', $limitNumRows);

        return $messages;
    }

    /**
     * Function to store one message in the database
     *
     * @param array $args[bid], $args[message], $args[gid]: optional
     * @return boolean
     */
    public function saveMessages($args)
    {
        if (!isset($args['bid']) ||
            !isset($args['message'])) {
            return LogUtil::registerArgsError();
        }

        $bid = $args['bid'];

        $record = array(
            'message'   => $args['message'],
            'bid'       => $args['bid'],
            'gid'       => $args['gid'],
            );

        $result = DBUtil::insertObject($record, 'shoutit_messages');

        return $result;
    }

    /**
     * Function to clear messages out of the database
     * @param integer $args['bid']
     * @return boolean
     */
    public function deleteMessages($args) {

        if (!is_numeric($args['bid'])){
            return LogUtil::registerArgsError();
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Shoutit::', $args['bid'].'::', ACCESS_DELETE), LogUtil::getErrorMsgPermission());

        $tables = DBUtil::getTables();
        $shColumns = $tables['shoutit_messages_column'];
        $where   = "WHERE $shColumns[bid] = $args[bid]";

        if(!DBUtil::deleteWhere('shoutit_messages', $where)) {
            return LogUtil::registerError ($this->__('Error! Update attempt failed.'));
        }

        LogUtil::registerStatus($this->__f("Done! Messages from block id '%s' deleted.", $args[bid]));

        return true;
    }
// end of class
}
?>