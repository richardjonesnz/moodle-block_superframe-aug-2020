<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * block_superframe main file
 *
 * @package   block_superframe
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Modified for use in MoodleBites for Developers Level 1
 * by Richard Jones & Justin Hunt.
 *
 * See: https://www.moodlebites.com/mod/page/view.php?id=24546
 */

defined('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/formslib.php");
/*

Notice some rules that will keep plugin approvers happy when you want
to register your plugin in the plugins database

    Use 4 spaces to indent, no tabs
    Use 8 spaces for continuation lines
    Make sure every class has php doc to describe it
    Describe the parameters of each class and function

    https://docs.moodle.org/dev/Coding_style
*/

/**
 * Class superframe minimal required block class.
 *
 */

class block_superframe extends block_base {
    /**
     * Initialize our block with a language string.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_superframe');
    }

    /**
     * Add some text content to our block.
     */
    public function get_content() {
        global $USER, $CFG, $OUTPUT;

        // Do we have any content?
        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        // OK let's add some content.
        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text = get_string('welcomeuser', 'block_superframe',
                $USER);

        // Add the blockid to the Moodle URL for the view page.
        $blockid = $this->instance->id;
        $courseid = $this->page->course->id;

        $context = context_block::instance($blockid);

        // Get the user list detail.
        if (has_capability('block/superframe:seeuserlist', $context)) {
            $students = self::get_course_users($courseid);
        }
        // Check the capability.
        if (has_capability('block/superframe:seeviewpage', $context)) {

            $renderer = $this->page->get_renderer('block_superframe');
            $this->content->text = $renderer->fetch_block_content($blockid, $students);
        }

        return $this->content;
    }
    /**
     * This is a list of places where the block may or
     * may not be added.
     */
    public function applicable_formats() {
        return array('all' => false,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true,
                     'my' => true);
    }
    /**
     * Allow multiple instances of the block.
     */
    public function instance_allow_multiple() {
        return true;
    }
    /**
     * Allow block configuration.
     */
    function has_config() {
        return true;
    }

    private static function get_course_users($courseid) {
        global $DB;

        $sql = "SELECT u.id, u.firstname
                FROM {course} as c
                JOIN {context} as x ON c.id = x.instanceid
                JOIN {role_assignments} as r ON r.contextid = x.id
                JOIN {user} AS u ON u.id = r.userid
               WHERE c.id = :courseid
                 AND r.roleid = :roleid";

        // In real world query should check users are not deleted/suspended.

        $records = $DB->get_records_sql($sql, ['courseid' => $courseid, 'roleid' => 5]);

        return $records;
    }
}