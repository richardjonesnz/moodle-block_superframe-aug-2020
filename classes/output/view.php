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
 * Superframe: prepare data for view page content
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

class view implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return \stdClass|array
     */

    protected $url;
    protected $width;
    protected $height;
    protected $courseid;
    protected $blockid;

    public function __construct($url, $width, $height, $courseid, $blockid) {

        $this->url = $url;
        $this->width = $width;
        $this->height = $height;
        $this->courseid = $courseid;
        $this->blockid = $blockid;
    }

    public function export_for_template(renderer_base $output) {
        global $USER, $PAGE;

        $data = new stdClass();

        $PAGE->requires->js_call_amd('block_superframe/amd_modal', 'init');

        // Page heading and iframe data.
        $data->heading = get_string('pluginname', 'block_superframe');
        $data->url = $this->url;
        $data->height = $this->height;
        $data->width = $this->width;
        $data->returnlink = new moodle_url('/course/view.php', ['id' => $this->courseid]);
        $data->returntext = get_string('returncourse', 'block_superframe');
        $data->userdetail = fullname($USER);

        // Text for the links and the size parameter.
        $strings = array();
        $strings[] = get_string('custom', 'block_superframe');
        $strings[] = get_string('small', 'block_superframe');
        $strings[] = get_string('medium', 'block_superframe');
        $strings[] = get_string('large', 'block_superframe');

        // Create the data structure for the links.
        $links = array();
        $link = new moodle_url('/blocks/superframe/view.php', ['courseid' => $this->courseid,
                'blockid' => $this->blockid]);

        foreach ($strings as $string) {
            $links[] = ['link' => $link->out(false, ['size' => $string]),
                    'text' => $string];
        }

        $data->linkdata = $links;

        // Link to the AMD modal.
        $data->modallinktext = get_string('about', 'block_superframe');

        return $data;
    }

}
