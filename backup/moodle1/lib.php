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
 * Provides support for the conversion of moodle1 backup to the moodle2 format
 *
 * @package    block_html_student
 * @copyright  2012 Paul Nicholls
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Block conversion handler for html
 */
class moodle1_block_html_student_handler extends moodle1_block_handler {
    /**
     * File manager
     *
     * @var moodle1_file_manager
     */
    private $fileman = null;
    /**
     * Convert config data
     *
     * @param array $olddata
     * @return string
     */
    protected function convert_configdata(array $olddata) {
        $instanceid = $olddata['id'];
        $contextid  = $this->converter->get_contextid(CONTEXT_BLOCK, $olddata['id']);
        $configdata = unserialize_object(base64_decode($olddata['configdata']));

        // Get a fresh new file manager for this instance.
        $this->fileman = $this->converter->get_file_manager($contextid, 'block_html_student');

        // Convert course files embedded in the block content.
        $this->fileman->filearea = 'content';
        $this->fileman->itemid   = 0;
        $configdata->text = moodle1_converter::migrate_referenced_files($configdata->text ?? '', $this->fileman);
        $configdata->format = FORMAT_HTML;

        return base64_encode(serialize($configdata));
    }

    /**
     * Write xml
     *
     * @param array $newdata
     * @param array $data
     * @return void
     */
    protected function write_inforef_xml($newdata, $data) {
        $this->open_xml_writer("course/blocks/{$data['name']}_{$data['id']}/inforef.xml");
        $this->xmlwriter->begin_tag('inforef');
        $this->xmlwriter->begin_tag('fileref');
        foreach ($this->fileman->get_fileids() as $fileid) {
            $this->write_xml('file', ['id' => $fileid]);
        }
        $this->xmlwriter->end_tag('fileref');
        $this->xmlwriter->end_tag('inforef');
        $this->close_xml_writer();
    }
}
