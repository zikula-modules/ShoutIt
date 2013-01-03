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

/**
 * initialise Shoutit module once
 *
 * @return  boolean    true/false
 */
class Shoutit_Installer extends Zikula_AbstractInstaller
{
    public function install()
    {
        // Create both the tables table returning false if either creation fails. The system will
        // handle the display of any errors during creation.
        if (!DBUtil::createTable('shoutit_messages')){
            return false;
        }

        // Module variables initialisation
        ModUtil::setVar('Shoutit', 'shoutit_refresh_rate', '8');

        // Register hook
        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());

        return true;
    }

    /**
     * TODO : upgrade function still in development not tested!!
     * Upgrade function 
     *
     * @return  boolean    true/false
     */
    public function upgrade($oldversion)
    {
        switch ($oldversion)
        {
            case '2.0':
                // Module variables initialisation
                ModUtil::setVar('Shoutit', 'shoutit_refresh_rate', '10');
                
                // Register hook
                HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
        }
        return true;
    }

    /**
     * Deletes Shoutit modules
     *
     * @return  boolean    true/false
     */
    public function uninstall()
    {
        $result = DBUtil::dropTable('shoutit_messages');
        $result = $result && ModUtil::delVar('Shoutit');

        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());

        return $result;
     }

// end of class
}
?>
