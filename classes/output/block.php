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
 * Superframe: prepare data for block content
 *
 * @package    block_superframe
 * @copyright  2020 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_superframe\output;
use renderable;
use renderer_base;
use templatable;
use stdClass;
use moodle_url;

/**
 * Class containing data for the superiframe block content.
 *
 * @package    block_superframe
 * @copyright  202 Richard Jones <richardnz@outloook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block implements renderable, templatable {

    protected $blockid;

    public function __construct($blockid) {

        $this->blockid = $blockid;

    }

/**
 * Export the block content so it can be used as the context for a mustache template.
 *
 * @param renderer_base $output
 * @return return stdClass|array
 */
    public function export_for_template(renderer_base $output) {
        global $USER, $DB, $PAGE;

        // Get some useful data items.
        $courseid = $PAGE->course->id;
        $userid = $USER->id;
        $name = $USER->firstname . ' ' . $USER->lastname;

        // The data structure to hold the content for the block.
        $data = new stdClass();

        // Our JS testing code is required.
        $PAGE->requires->js_call_amd('block_superframe/test_amd', 'init', ['name' => $name]);

        // A greeting and a css class (normally to be avoided - prefer Bootstrap).
        $data->headingclass = 'block_superframe_heading';
        $data->welcome = get_string('welcomeuser', 'block_superframe', $name);

        // Link to the block view page.
        $data->url = new moodle_url('/blocks/superframe/view.php',
                ['blockid' => $this->blockid, 'courseid' => $courseid]);
        $data->linktext = get_string('viewlink', 'block_superframe');

        // Add a link to the popup page and to the tablemanager page:
        $data->popurl = new moodle_url('/blocks/superframe/block_data.php');
        $data->poptext = get_string('poptext', 'block_superframe');
        $data->tableurl = new moodle_url('/blocks/superframe/tablemanager.php');
        $data->tabletext = get_string('tabletext', 'block_superframe');

        // Get the user's last access time for the course the block is in.
        $data->access = $DB->get_field('user_lastaccess', 'timeaccess',
                ['courseid' => $courseid, 'userid' => $userid], MUST_EXIST);

        return $data;
    }
}