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
 * Superframe block renderer
 *
 * @package    block_superframe
 * @copyright  2020 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_superframe\output;
use plugin_renderer_base;

/**
 * block_superframe renderer
 */
class renderer extends plugin_renderer_base {

    /**
     * Return the main content for the superframe block.
     *
     * @param block $block the renderable for the block content.
     * @return string HTML string
     */
    public function render_block($block) {
        return $this->render_from_template('block_superframe/block',
                $block->export_for_template($this));
    }
    /**
     * Return the main content for the superframe view page.
     *
     * @param view $view the renderable for the view page content.
     * @return string HTML string
     */
     public function render_view($view) {
        return $this->render_from_template('block_superframe/view',
                $view->export_for_template($this));
    }
    /**
     * Return the main content for the blocks list page.
     *
     * @param block_data $block_data the renderable for the blocks list content.
     * @return string HTML string
     */
    public function render_block_data($block_data) {
        return $this->render_from_template('block_superframe/block_data',
                $block_data->export_for_template($this));
    }
}