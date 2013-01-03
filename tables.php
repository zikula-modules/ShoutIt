<?php
/**
 * ShoutIt module for Zikula Application Framework
 *
 * @author       Gabriel Freinbichler & Philippe Baudrion - UniGE/FTI
 * @link         http://www.cmods-dev.de
 * @copyright    Copyright (C) by Gabriel Freinbichler
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version      $Id$
 */

/**
 * Populate table array for ShoutIt module
 *
 * This function is called internally by the core whenever the module is
 * loaded. It delivers the table information to the core.
 *
 * @return      array       The table information.
 */
function shoutit_tables()
{
    // Initialise table array
    $tables = array();

    // Build the ShoutIt message table definition
    $tables['shoutit_messages'] = 'shoutit_messages';
    // Set the column names.
    $tables['shoutit_messages_column'] = array (
        'id'            => 'id',
        'message'       => 'message',
        'gid'           => 'gid',   // selected group id
        'bid'           => 'bid'    // ShoutIt block ID
        );
    $tables['shoutit_messages_column_def'] = array(
        'id'		=> "I8 NOTNULL AUTO PRIMARY",
        'message'       => "X NOTNULL DEFAULT ''",
        'gid'           => "I4 NOTNULL DEFAULT '1'",
        'bid'           => "I4 NOTNULL DEFAULT ''"
        );

    ObjectUtil::addStandardFieldsToTableDefinition ($tables['shoutit_messages_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['shoutit_messages_column_def']);
    $tables['shoutit_messages_primary_key_column'] = 'id';

    // Return the table information
    return $tables;
}
?>
