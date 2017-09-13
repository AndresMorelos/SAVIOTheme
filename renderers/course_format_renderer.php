<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once($CFG->dirroot . "/course/format/topics/renderer.php");

class theme_saviotheme_format_topics_renderer extends format_topics_renderer {

    protected function get_nav_links($course, $sections, $sectionno) {
        return theme_saviotheme_get_nav_links($course, $sections, $sectionno);
    }

    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE;

        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        // Can we view the section in question?
        if (!($sectioninfo = $modinfo->get_section_info($displaysection))) {
            // This section doesn't exist
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }

        if (!$sectioninfo->uservisible) {
            if (!$course->hiddensections) {
                echo $this->start_section_list();
                echo $this->section_hidden($displaysection);
                echo $this->end_section_list();
            }
            // Can't view this section.
            return;
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);
        $thissection = $modinfo->get_section_info(0);
        if ($thissection->summary or ! empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
            echo $this->start_section_list();
            echo $this->section_header($thissection, $course, true, $displaysection);
            echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
            echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);
            echo $this->section_footer();
            echo $this->end_section_list();
        }

        // Start single-section div
        echo html_writer::start_tag('div', array('class' => 'single-section'));

        // The requested section page.
        $thissection = $modinfo->get_section_info($displaysection);

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
        $sectiontitle = '';
        $sectiontitle .= html_writer::start_tag('div', array('class' => 'section-navigation header headingblock'));
        // Title attributes
        $titleattr = 'title';
        if (!$thissection->visible) {
            $titleattr .= ' dimmed_text';
        }
        $sectiontitle .= html_writer::tag('div', get_section_name($course, $displaysection), array('class' => $titleattr));
        $sectiontitle .= html_writer::end_tag('div');
        echo $sectiontitle;

        // Now the list of sections..
        echo $this->start_section_list();

        echo $this->section_header($thissection, $course, true, $displaysection);
        // Show completion help icon.
        $completioninfo = new completion_info($course);
        echo $completioninfo->display_help_icon();

        echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
        echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
        echo $this->section_footer();
        echo $this->end_section_list();

        // Display section bottom navigation.
        $sectionbottomnav = '';
        $sectionbottomnav .= html_writer::start_tag('nav', array('id' => 'section_footer'));
        $sectionbottomnav .= $sectionnavlinks['previous'];
        $sectionbottomnav .= $sectionnavlinks['next'];
        // $sectionbottomnav .= html_writer::tag('div', $this->section_nav_selection($course, $sections, $displaysection), array('class' => 'mdl-align'));
        $sectionbottomnav .= html_writer::empty_tag('br', array('style' => 'clear:both'));
        $sectionbottomnav .= html_writer::end_tag('nav');
        echo $sectionbottomnav;

        // Close single-section div.
        echo html_writer::end_tag('div');
    }

}

include_once($CFG->dirroot . "/course/format/weeks/renderer.php");

class theme_saviotheme_format_weeks_renderer extends format_weeks_renderer {

    protected function get_nav_links($course, $sections, $sectionno) {
        return theme_saviotheme_get_nav_links($course, $sections, $sectionno);
    }

    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE;

        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        // Can we view the section in question?
        if (!($sectioninfo = $modinfo->get_section_info($displaysection))) {
            // This section doesn't exist
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }

        if (!$sectioninfo->uservisible) {
            if (!$course->hiddensections) {
                echo $this->start_section_list();
                echo $this->section_hidden($displaysection);
                echo $this->end_section_list();
            }
            // Can't view this section.
            return;
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);
        $thissection = $modinfo->get_section_info(0);
        if ($thissection->summary or ! empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
            echo $this->start_section_list();
            echo $this->section_header($thissection, $course, true, $displaysection);
            echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
            echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);
            echo $this->section_footer();
            echo $this->end_section_list();
        }

        // Start single-section div
        echo html_writer::start_tag('div', array('class' => 'single-section'));

        // The requested section page.
        $thissection = $modinfo->get_section_info($displaysection);

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
        $sectiontitle = '';
        $sectiontitle .= html_writer::start_tag('div', array('class' => 'section-navigation header headingblock'));
        // Title attributes
        $titleattr = 'title';
        if (!$thissection->visible) {
            $titleattr .= ' dimmed_text';
        }
        $sectiontitle .= html_writer::tag('div', get_section_name($course, $displaysection), array('class' => $titleattr));
        $sectiontitle .= html_writer::end_tag('div');
        echo $sectiontitle;

        // Now the list of sections..
        echo $this->start_section_list();

        echo $this->section_header($thissection, $course, true, $displaysection);
        // Show completion help icon.
        $completioninfo = new completion_info($course);
        echo $completioninfo->display_help_icon();

        echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
        echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
        echo $this->section_footer();
        echo $this->end_section_list();

        // Display section bottom navigation.
        $sectionbottomnav = '';
        $sectionbottomnav .= html_writer::start_tag('nav', array('id' => 'section_footer'));
        $sectionbottomnav .= $sectionnavlinks['previous'];
        $sectionbottomnav .= $sectionnavlinks['next'];
        // $sectionbottomnav .= html_writer::tag('div', $this->section_nav_selection($course, $sections, $displaysection), array('class' => 'mdl-align'));
        $sectionbottomnav .= html_writer::empty_tag('br', array('style' => 'clear:both'));
        $sectionbottomnav .= html_writer::end_tag('nav');
        echo $sectionbottomnav;

        // Close single-section div.
        echo html_writer::end_tag('div');
    }

}

// Requires V2.6.1.3+ of Collapsed Topics format.
if (file_exists("$CFG->dirroot/course/format/topcoll/renderer.php")) {
    include_once($CFG->dirroot . "/course/format/topcoll/renderer.php");

    class theme_saviotheme_format_topcoll_renderer extends format_topcoll_renderer {

        protected function get_nav_links($course, $sections, $sectionno) {
            return theme_saviotheme_get_nav_links($course, $sections, $sectionno);
        }

        public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
            global $PAGE;

            $modinfo = get_fast_modinfo($course);
            $course = course_get_format($course)->get_course();

            // Can we view the section in question?
            if (!($sectioninfo = $modinfo->get_section_info($displaysection))) {
                // This section doesn't exist
                print_error('unknowncoursesection', 'error', null, $course->fullname);
                return;
            }

            if (!$sectioninfo->uservisible) {
                if (!$course->hiddensections) {
                    echo $this->start_section_list();
                    echo $this->section_hidden($displaysection);
                    echo $this->end_section_list();
                }
                // Can't view this section.
                return;
            }

            // Copy activity clipboard..
            echo $this->course_activity_clipboard($course, $displaysection);
            $thissection = $modinfo->get_section_info(0);
            if ($thissection->summary or ! empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
                echo $this->start_section_list();
                echo $this->section_header($thissection, $course, true, $displaysection);
                echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);
                echo $this->section_footer();
                echo $this->end_section_list();
            }

            // Start single-section div
            echo html_writer::start_tag('div', array('class' => 'single-section'));

            // The requested section page.
            $thissection = $modinfo->get_section_info($displaysection);

            // Title with section navigation links.
            $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
            $sectiontitle = '';
            $sectiontitle .= html_writer::start_tag('div', array('class' => 'section-navigation header headingblock'));
            // Title attributes
            $titleattr = 'title';
            if (!$thissection->visible) {
                $titleattr .= ' dimmed_text';
            }
            $sectiontitle .= html_writer::tag('div', get_section_name($course, $displaysection), array('class' => $titleattr));
            $sectiontitle .= html_writer::end_tag('div');
            echo $sectiontitle;

            // Now the list of sections..
            echo $this->start_section_list();

            echo $this->section_header($thissection, $course, true, $displaysection);
            // Show completion help icon.
            $completioninfo = new completion_info($course);
            echo $completioninfo->display_help_icon();

            echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
            echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
            echo $this->section_footer();
            echo $this->end_section_list();

            // Display section bottom navigation.
            $sectionbottomnav = '';
            $sectionbottomnav .= html_writer::start_tag('nav', array('id' => 'section_footer'));
            $sectionbottomnav .= $sectionnavlinks['previous'];
            $sectionbottomnav .= $sectionnavlinks['next'];
            // $sectionbottomnav .= html_writer::tag('div', $this->section_nav_selection($course, $sections, $displaysection), array('class' => 'mdl-align'));
            $sectionbottomnav .= html_writer::empty_tag('br', array('style' => 'clear:both'));
            $sectionbottomnav .= html_writer::end_tag('nav');
            echo $sectionbottomnav;

            // Close single-section div.
            echo html_writer::end_tag('div');
        }

    }

}

if (file_exists("$CFG->dirroot/course/format/grid/renderer.php")) {
    include_once($CFG->dirroot . "/course/format/grid/renderer.php");

    class theme_saviotheme_format_grid_renderer extends format_grid_renderer {

        protected function get_nav_links($course, $sections, $sectionno) {
            return theme_saviotheme_get_nav_links($course, $sections, $sectionno);
        }

        public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
            global $PAGE;

            $modinfo = get_fast_modinfo($course);
            $course = course_get_format($course)->get_course();

            // Can we view the section in question?
            if (!($sectioninfo = $modinfo->get_section_info($displaysection))) {
                // This section doesn't exist
                print_error('unknowncoursesection', 'error', null, $course->fullname);
                return;
            }

            if (!$sectioninfo->uservisible) {
                if (!$course->hiddensections) {
                    echo $this->start_section_list();
                    echo $this->section_hidden($displaysection);
                    echo $this->end_section_list();
                }
                // Can't view this section.
                return;
            }

            // Copy activity clipboard..
            echo $this->course_activity_clipboard($course, $displaysection);
            $thissection = $modinfo->get_section_info(0);
            if ($thissection->summary or ! empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
                echo $this->start_section_list();
                echo $this->section_header($thissection, $course, true, $displaysection);
                echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);
                echo $this->section_footer();
                echo $this->end_section_list();
            }

            // Start single-section div
            echo html_writer::start_tag('div', array('class' => 'single-section'));

            // The requested section page.
            $thissection = $modinfo->get_section_info($displaysection);

            // Title with section navigation links.
            $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
            $sectiontitle = '';
            $sectiontitle .= html_writer::start_tag('div', array('class' => 'section-navigation header headingblock'));
            // Title attributes
            $titleattr = 'title';
            if (!$thissection->visible) {
                $titleattr .= ' dimmed_text';
            }
            $sectiontitle .= html_writer::tag('div', get_section_name($course, $displaysection), array('class' => $titleattr));
            $sectiontitle .= html_writer::end_tag('div');
            echo $sectiontitle;

            // Now the list of sections..
            echo $this->start_section_list();

            echo $this->section_header($thissection, $course, true, $displaysection);
            // Show completion help icon.
            $completioninfo = new completion_info($course);
            echo $completioninfo->display_help_icon();

            echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
            echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
            echo $this->section_footer();
            echo $this->end_section_list();

            // Display section bottom navigation.
            $sectionbottomnav = '';
            $sectionbottomnav .= html_writer::start_tag('nav', array('id' => 'section_footer'));
            $sectionbottomnav .= $sectionnavlinks['previous'];
            $sectionbottomnav .= $sectionnavlinks['next'];
            // $sectionbottomnav .= html_writer::tag('div', $this->section_nav_selection($course, $sections, $displaysection), array('class' => 'mdl-align'));
            $sectionbottomnav .= html_writer::empty_tag('br', array('style' => 'clear:both'));
            $sectionbottomnav .= html_writer::end_tag('nav');
            echo $sectionbottomnav;

            // Close single-section div.
            echo html_writer::end_tag('div');
        }

    }

}


// Requires V2.6.1.1+ of Columns format.
if (file_exists("$CFG->dirroot/course/format/onetopic/renderer.php")) {
    include_once($CFG->dirroot . "/course/format/onetopic/renderer.php");

    class theme_saviotheme_format_onetopic_renderer extends format_onetopic_renderer {

        protected function get_nav_links($course, $sections, $sectionno) {
            return theme_saviotheme_get_nav_links($course, $sections, $sectionno);
        }

    }

}

// Requires V2.6.1.1+ of Columns format.
if (file_exists("$CFG->dirroot/course/format/weekssavio/renderer.php")) {
    include_once($CFG->dirroot . "/course/format/weekssavio/renderer.php");

    class theme_saviotheme_format_weekssavio_renderer extends format_weekssavio_renderer {

        protected function get_nav_links($course, $sections, $sectionno) {
            return theme_saviotheme_get_nav_links($course, $sections, $sectionno);
        }

    }

}


function theme_saviotheme_get_nav_links($course, $sections, $sectionno) {
    // FIXME: This is really evil and should by using the navigation API.
    $courseformat = course_get_format($course);
    $course = $courseformat->get_course();
    $previousarrow = '<i class="fa fa-chevron-circle-left"></i>';
    $nextarrow = '<i class="fa fa-chevron-circle-right"></i>';
    $canviewhidden = has_capability('moodle/course:viewhiddensections', context_course::instance($course->id))
            or ! $course->hiddensections;

    $links = array('previous' => '', 'next' => '');
    $back = $sectionno - 1;
    while ($back > 0 and empty($links['previous'])) {
        if ($canviewhidden || $sections[$back]->uservisible) {
            $params = array('id' => 'previous_section');
            if (!$sections[$back]->visible) {
                $params = $params + array('class' => 'dimmed_text');
            }
            $previouslink = html_writer::start_tag('div', array('class' => 'nav_icon'));
            $previouslink .= $previousarrow;
            $previouslink .= html_writer::end_tag('div');
            $previouslink .= html_writer::start_tag('span', array('class' => 'text'));
            $previouslink .= html_writer::start_tag('span', array('class' => 'nav_guide'));
            $previouslink .= get_string('previoussection', 'theme_saviotheme');
            $previouslink .= html_writer::end_tag('span');
            $previouslink .= html_writer::empty_tag('br');
            $previouslink .= $courseformat->get_section_name($sections[$back]);
            $previouslink .= html_writer::end_tag('span');
            $links['previous'] = html_writer::link(course_get_url($course, $back), $previouslink, $params);
        }
        $back--;
    }

    $forward = $sectionno + 1;
    while ($forward <= $course->numsections and empty($links['next'])) {
        if ($canviewhidden || $sections[$forward]->uservisible) {
            $params = array('id' => 'next_section');
            if (!$sections[$forward]->visible) {
                $params = $params + array('class' => 'dimmed_text');
            }
            $nextlink = html_writer::start_tag('div', array('class' => 'nav_icon'));
            $nextlink .= $nextarrow;
            $nextlink .= html_writer::end_tag('div');
            $nextlink .= html_writer::start_tag('span', array('class' => 'text'));
            $nextlink .= html_writer::start_tag('span', array('class' => 'nav_guide'));
            $nextlink .= get_string('nextsection', 'theme_saviotheme');
            $nextlink .= html_writer::end_tag('span');
            $nextlink .= html_writer::empty_tag('br');
            $nextlink .= $courseformat->get_section_name($sections[$forward]);
            $nextlink .= html_writer::end_tag('span');
            $links['next'] = html_writer::link(course_get_url($course, $forward), $nextlink, $params);
        }
        $forward++;
    }

    return $links;
}