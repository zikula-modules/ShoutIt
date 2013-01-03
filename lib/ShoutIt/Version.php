<?php
/**
 * ShoutIt module for Zikula Application Framework
 *
 * @author       Gabriel Freinbichler
 *              refactored for zk 1.3 by Philippe Baudrion - UniGE/FTI
 * @link         http://www.cmods-dev.de
 * @copyright    Copyright (C) by Gabriel Freinbichler
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version      $Id$
 */
class ShoutIt_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();

        $meta['name']        = 'ShoutIt';
        $meta['displayname'] = $this->__('ShoutIt');
        $meta['description'] = $this->__('Ajax shoutbox');
        $meta['version']     = '3.0.0';
        $meta['url']          = $this->__('shoutit');

        $meta['credits']     = 'docs/credits.txt';
        $meta['help']        = 'docs/help.txt';
        $meta['changelog']   = 'docs/changelog.txt';
        $meta['license']     = 'docs/license.txt';
        $meta['official']    = 0;
        $meta['author']      = 'Gabriel Freinbichler & Philippe Baudrion - UniGE/FTI';
        $meta['contact']     = 'http://www.cmods-dev.de';
        $meta['securityschema'] = array('ShoutIt::' => 'gid::');
        $meta['core_min']       = '1.3.0';
        $meta['core_max']       = '1.3.99';

        // Capabilities
        $meta['capabilities'] = array(
                HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true),
        );

        // Module depedencies
        $meta['dependencies'] = array(
                array('modname' => 'BBSmile',
                      'minversion' => '3.0.0',
                      'maxversion' => '',
                      'status' => ModUtil::DEPENDENCY_RECOMMENDED)
        );

        return $meta;
    }

    protected function setupHookBundles()
    {
        $modinfo = ModUtil::getInfoFromName('BBSmile');

        if ($modinfo['state'] == ModUtil::STATE_ACTIVE) {
            $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.bbsmile.filter_hooks.smilies', 'filter_hooks', $this->__('BBSmile - Transform Smilies'));
            $bundle->addEvent('filter','BBSmile_HookHandlers', 'uifilter', 'bbsmile.smilies');

            $this->registerHookSubscriberBundle($bundle);
        }
    }

// end of class
}
