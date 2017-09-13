<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of block_renderer
 *
 * @author erick
 */
if (file_exists("$CFG->dirroot/blocks/course_overview/renderer.php")) {
    include_once($CFG->dirroot . "/blocks/course_overview/renderer.php");

    class theme_SAVIOTheme_block_course_overview_renderer extends block_course_overview_renderer {

        public function course_overview($courses, $overviews) {
            GLOBAL $CFG;

            $html = '';
            $config = get_config('block_course_overview');
            $ismovingcourse = false;
            $courseordernumber = 0;
            $maxcourses = count($courses);
            $userediting = false;
            // Intialise string/icon etc if user is editing and courses > 1
            if ($this->page->user_is_editing() && (count($courses) > 1)) {
                $userediting = true;
                $this->page->requires->js_init_call('M.block_course_overview.add_handles');

                // Check if course is moving
                $ismovingcourse = optional_param('movecourse', FALSE, PARAM_BOOL);
                $movingcourseid = optional_param('courseid', 0, PARAM_INT);
            }

            // Render first movehere icon.
            if ($ismovingcourse) {
                // Remove movecourse param from url.
                $this->page->ensure_param_not_in_url('movecourse');

                // Show moving course notice, so user knows what is being moved.
                $html .= $this->output->box_start('notice');
                $a = new stdClass();
                $a->fullname = $courses[$movingcourseid]->fullname;
                $a->cancellink = html_writer::link($this->page->url, get_string('cancel'));
                $html .= get_string('movingcourse', 'block_course_overview', $a);
                $html .= $this->output->box_end();

                $moveurl = new moodle_url('/blocks/course_overview/move.php', array('sesskey' => sesskey(), 'moveto' => 0, 'courseid' => $movingcourseid));
                // Create move icon, so it can be used.
                $movetofirsticon = html_writer::empty_tag('img', array('src' => $this->output->pix_url('movehere'),
                            'alt' => get_string('movetofirst', 'block_course_overview', $courses[$movingcourseid]->fullname),
                            'title' => get_string('movehere')));
                $moveurl = html_writer::link($moveurl, $movetofirsticon);
                $html .= html_writer::tag('div', $moveurl, array('class' => 'movehere'));
            }

            foreach ($courses as $key => $course) {
                
                $html .= html_writer::start_tag('div', array('class' => 'wrap-coursebox'));
                
                // If moving course, then don't show course which needs to be moved.
                if ($ismovingcourse && ($course->id == $movingcourseid)) {
                    continue;
                }
                $html .= $this->output->box_start('coursebox', "course-{$course->id}");

                if ($course instanceof stdClass) {
                    require_once($CFG->libdir . '/coursecatlib.php');
                    $course = new course_in_list($course);
                }

                $img_src = $this->get_image_course($course);
                $html .= html_writer::start_tag('figure', array('class' => 'course_image'));
                    
                if ($img_src) {
                    $image = html_writer::empty_tag('img', array('src' => $img_src,
                                'alt' => '', 'title' => $course->fullname));
                }else{
                    $image = '';
                }
                
                $html .= html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)), $image);
                $html .= html_writer::end_tag('figure');

                $html .= html_writer::start_tag('div', array('class' => 'course_info'));
                $html .= html_writer::start_tag('div', array('class' => 'course_title'));
                // If user is editing, then add move icons.
                if ($userediting && !$ismovingcourse) {
                    $moveicon = html_writer::empty_tag('img', array('src' => $this->pix_url('t/move')->out(false),
                                'alt' => get_string('movecourse', 'block_course_overview', $course->fullname),
                                'title' => get_string('move')));
                    $moveurl = new moodle_url($this->page->url, array('sesskey' => sesskey(), 'movecourse' => 1, 'courseid' => $course->id));
                    $moveurl = html_writer::link($moveurl, $moveicon);
                    $html .= html_writer::tag('div', $moveurl, array('class' => 'move'));
                }

                // No need to pass title through s() here as it will be done automatically by html_writer.
                $attributes = array('title' => $course->fullname);
                if ($course->id > 0) {
                    if (empty($course->visible)) {
                        $attributes['class'] = 'dimmed';
                    }
                    $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
                    $coursefullname = format_string(get_course_display_name_for_list($course), true, $course->id);
                    $link = html_writer::link($courseurl, $coursefullname, $attributes);
                    $html .= $this->output->heading($link, 2, 'title');
                } else {
                    $html .= $this->output->heading(html_writer::link(
                                    new moodle_url('/auth/mnet/jump.php', array('hostid' => $course->hostid, 'wantsurl' => '/course/view.php?id=' . $course->remoteid)), format_string($course->shortname, true), $attributes) . ' (' . format_string($course->hostname) . ')', 2, 'title');
                }

                $html .= html_writer::end_tag('div');
                $html .= $this->output->box('', 'flush');
                $html .= html_writer::end_tag('div');

                $html .= html_writer::start_tag('div', array('class' => 'course_extra_info'));

                $html .= html_writer::tag('h2', html_writer::link($courseurl, get_string('gotocourse', 'theme_metro')));
                $html .= html_writer::start_tag('div', array('class' => 'option-course-info'));

                // display course contacts. See course_in_list::get_course_contacts()     
                if ($course->has_course_contacts()) {
                    $teacher = html_writer::start_tag('div', array('class' => 'teachers-wrap'));
                    $i = 1;
                    $limit = 2;
                    foreach ($course->get_course_contacts() as $userid => $coursecontact) {
                        $name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $coursecontact['username']);
                        $teacher .= $name;
                        if ($i == $limit) {
                            break;
                        } else {
                            $teacher .= ", ";
                        }

                        $i++;
                    }
                    $teacher .= html_writer::end_tag('div'); // .teachers

                    $html .= html_writer::tag('span', '<i class="fa fa-user"></i>', array(
                                "class" => "teachers_access",
                                "rel" => "popover-ele",
                                "data-content" => str_replace("\"", "'", $teacher),
                                "data-placement" => "top",
                                "data-container" => ".course_list"
                                    )
                    );
                }

                if ($course->lastaccess > 0) {
                    $lastaccess = format_time(time() - $course->lastaccess);

                    $html .= html_writer::tag('span', '<i class="fa fa-clock-o"></i>', array(
                                "class" => "lastaccess_access",
                                "rel" => "popover-ele",
                                "data-content" => str_replace("\"", "'", $lastaccess),
                                "data-placement" => "top",
                                "data-container" => ".course_list",
                                "data-title" => get_string('lastaccesscourse', 'theme_metro')
                                    )
                    );
                }

                $html .= html_writer::end_tag('div');

                $html .= html_writer::end_tag('div'); // .course_extra_info
                //$html .= $this->output->box('', 'flush');
                $html .= $this->output->box_end();
                $courseordernumber++;
                if ($ismovingcourse) {
                    $moveurl = new moodle_url('/blocks/course_overview/move.php', array('sesskey' => sesskey(), 'moveto' => $courseordernumber, 'courseid' => $movingcourseid));
                    $a = new stdClass();
                    $a->movingcoursename = $courses[$movingcourseid]->fullname;
                    $a->currentcoursename = $course->fullname;
                    $movehereicon = html_writer::empty_tag('img', array('src' => $this->output->pix_url('movehere'),
                                'alt' => get_string('moveafterhere', 'block_course_overview', $a),
                                'title' => get_string('movehere')));
                    $moveurl = html_writer::link($moveurl, $movehereicon);
                    $html .= html_writer::tag('div', $moveurl, array('class' => 'movehere'));
                }
                $html .= html_writer::end_tag('div');

            }
            
            // Wrap course list in a div and return.
            return html_writer::tag('div', $html, array('class' => 'course_list'));
        }

        /**
         * Cretes html for welcome area
         *
         * @param int $msgcount number of messages
         * @return string html string for welcome area.
         */
        public function welcome_area($msgcount) {
            global $USER;
            $output = $this->output->box_start('welcome_area');
            $output .= $this->output->box_start('welcome_message');
            $output .= $this->output->heading(get_string('welcome', 'block_course_overview', $USER->firstname));

            $output .= $this->output->box_end();
            $output .= $this->output->box('', 'flush');
            $output .= $this->output->box_end();

            return $output;
        }

        protected function activity_display($cid, $overview) {
            $output = html_writer::start_tag('div', array('class' => 'activity_info'));
            foreach (array_keys($overview) as $module) {
                $output .= html_writer::start_tag('div', array('class' => 'activity_overview'));
                $url = new moodle_url("/mod/$module/index.php", array('id' => $cid));
                $modulename = get_string('modulename', $module);
                $icontext = html_writer::link($url, $this->output->pix_icon('icon', $modulename, 'mod_' . $module, array('class' => 'iconlarge')));
                if (get_string_manager()->string_exists("activityoverview", $module)) {
                    $icontext .= get_string("activityoverview", $module);
                } else {
                    $icontext .= get_string("activityoverview", 'block_course_overview', $modulename);
                }

                // Add collapsible region with overview text in it.
                //$output .= $this->collapsible_region($overview[$module], '', 'region_'.$cid.'_'.$module, $icontext, '', true);
                $output .= $icontext . $overview[$module];

                $output .= html_writer::end_tag('div');
            }
            $output .= html_writer::end_tag('div');
            return $output;
        }

        protected function get_image_course($course) {
            GLOBAL $CFG;
            $url = null;
            foreach ($course->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();
                $url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                        $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);
                if (!$isimage) {
                    $url = null;
                }
            }
            return $url;
        }

    }

}