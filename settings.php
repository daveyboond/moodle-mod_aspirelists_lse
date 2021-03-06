<?php

/**********************************************************************
 *                      @package Kent aspirelists                     *
 *                                                                    *
 *              University of Kent aspirelists resource               *
 *                                                                    *
 *                       Authors: jwk8, fg30, jw26                    *
 *                                                                    *
 *--------------------------------------------------------------------*
 *                                                                    *
 *                            Settings file                           *
 *                                                                    *
 **********************************************************************/

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once("$CFG->libdir/resourcelib.php");

    $settings->add(new admin_setting_configtext('aspirelists/timeout',
        get_string('settings:timeout', 'aspirelists'), get_string('settings:configtimeout', 'aspirelists'), '2'));

    $settings->add(new admin_setting_configtext('aspirelists/baseurl',
        get_string('settings:baseurl', 'aspirelists'), get_string('settings:configbaseurl', 'aspirelists'), 'http://readinglists.lse.ac.uk'));

    $options = array();
    $options['courses']         = get_string('settings:group:courses', 'aspirelists');
    $options['modules']         = get_string('settings:group:modules', 'aspirelists');
    $options['units']           = get_string('settings:group:units', 'aspirelists');
    $options['programmes']     = get_string('settings:group:programmes', 'aspirelists');
    $options['subjects']        = get_string('settings:group:subjects', 'aspirelists');

    $settings->add(new admin_setting_configselect('aspirelists/group', get_string('settings:group', 'aspirelists'),
                       get_string('settings:configgroup', 'aspirelists'), 'modules', $options));

    $settings->add(new admin_setting_configcheckbox('aspirelists/redirect',
        get_string('settings:redirect', 'aspirelists'), get_string('settings:configredirect', 'aspirelists'), 1));

    $displayoptions = resourcelib_get_displayoptions(array(RESOURCELIB_DISPLAY_OPEN, RESOURCELIB_DISPLAY_NEW, RESOURCELIB_DISPLAY_POPUP));
    $settings->add(new admin_setting_configselect('aspirelists/display',
        get_string('displayselect', 'page'), get_string('displayselectexplain', 'page'),
        array('value'=>RESOURCELIB_DISPLAY_NEW, 'adv'=>true), $displayoptions));
    
    $settings->add(new admin_setting_configtext('aspirelists/modTimePeriod',get_string('config_timePeriod', 'aspirelists'),get_string('config_timePeriod_desc', 'aspirelists'),get_string('config_timePeriod_ex', 'aspirelists')));
}