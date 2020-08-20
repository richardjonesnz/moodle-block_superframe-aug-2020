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
 * Superframe: prepare block_data for page.
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

class block_data implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return \stdClass|array
     */

    protected $records;

    public function __construct() {

        $this->records = self::fetch_block_data();
    }

    public function export_for_template(renderer_base $output) {
        // Prepare the data for the template.
        $table = new stdClass();
        // Table headers.
        $table->tableheaders = [
                get_string('blockid', 'block_superframe'),
                get_string('blockname', 'block_superframe'),
                get_string('course', 'block_superframe'),
                get_string('catname', 'block_superframe'),
        ];
        // Build the data rows.
        foreach ($this->records as $record) {
            $data = array();
            $data[] = $record->id;
            $data[] = $record->blockname;
            $data[] = $record->shortname;
            $data[] = $record->catname;
            $table->tabledata[] = $data;
        }

        return $table;
    }

    public static function fetch_block_data() {
        global $DB;
        $sql = "SELECT b.id, cat.id AS catid, cat.name AS catname,
                b.blockname, c.shortname
                FROM {context} x
                JOIN {block_instances} b ON b.parentcontextid = x.id
                JOIN {course} c ON c.id = x.instanceid
                JOIN {course_categories} cat ON cat.id = c.category
                WHERE x.contextlevel <= :clevel
                ORDER BY b.blockname DESC";
        return $DB->get_records_sql($sql, ['clevel' => 80]);
    }

}
