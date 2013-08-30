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
 *                              Item view                             *
 *                                                                    *
 **********************************************************************/

require('../../config.php');
require_once("lib.php");
global $CFG, $USER, $DB, $PAGE;

// dont do anything if not logged in
require_login();

$id = optional_param('id', 0, PARAM_INT);        // Course Module ID
$listid = optional_param('list', NAN, PARAM_INT); // streamingvideo id


if (is_integer($id)) {
    if (! $module = $DB->get_record("course_modules", array("id"=>$id))) {
        throw new Exception(get_string('cmunknown','error'));
    }

    if (! $course = $DB->get_record("course", array("id"=>$module->course))) {
        throw new Exception(get_string('invalidcourseid','error', $module->course));
    }

    if (!$readinglist = $DB->get_record('aspirelists', array('id'=>$module->instance), '*', MUST_EXIST)) {
        throw new Exception(get_string('cmunknown','error'));
    }

} else if ($listid) {
    
    if (! $readinglist = $DB->get_record('aspirelists', array('id'=>$listid), '*', MUST_EXIST)) {
        throw new Exception(get_string('cmunknown','error'));
    }

    if (! $module = $DB->get_record("course_modules", array("instance"=>$readinglist->id))) {
        throw new Exception(get_string('cmunknown','error'));
    }

    if (! $course = $DB->get_record('course', array('id'=>$readinglist->course))) {
        throw new Exception(get_string('invalidcourseid','error', $readinglist->course));
    }
} else throw new Exception("A module ID or resource id must be specified");

$config = get_config('aspirelists');

add_to_log($course->id, 'aspirelist', 'view', "view.php?id={$id}", '');

$context = get_context_instance(CONTEXT_COURSE, $course->id);
$PAGE->set_context($context);

//Set page params and layout
$PAGE->set_url('/mod/aspirelists/view.php', array('id'=>$id));
$PAGE->set_title(format_string($readinglist->name));
$PAGE->add_body_class('mod_aspirelists');
$PAGE->set_heading(format_string($readinglist->name));
$PAGE->navbar->add($course->shortname,"{$CFG->wwwroot}/course/view.php?id=$course->id");
$PAGE->navbar->add(get_string('modulename', 'aspirelists'));
$PAGE->set_pagelayout('admin');

// Added by Steve Bond 21/06/13: Use shortname override
if (!empty($readinglist->shortnameoverride)) {
    $shortname = $readinglist->shortnameoverride;
} else {
    $shortname = $course->shortname;
}
$shortnamelc = strtolower($shortname);

$output = '';
$lists = array();

//Check to see if a non-category option has been picked

if(empty($readinglist->category) or $readinglist->category == 'all'
    or substr($readinglist->category, 0, 7) == 'private') {

    // Display the main page for the course matching the shortname
    echo $OUTPUT->header();
    
    $url = "$config->baseurl/$config->group/$shortnamelc.html";
    
    // Just redirect to the Talis page for this shortname
    redirect($url);
   
} elseif ($readinglist->category == 'null') {
    // No course found
    echo "ERROR NO SUCH COURSE";
    echo $OUTPUT->footer();
    
} else {
    // Display the specific category within the list
    echo $OUTPUT->header();
        
    $url = $config->baseurl . '/sections/' . $readinglist->category;

    if(isset($CFG->aspirelists_resourcelist) && $CFG->aspirelists_resourcelist === true) {
        aspirelists_getResources($url);
    } else {
        redirect($url . '.html');
    }

    
}