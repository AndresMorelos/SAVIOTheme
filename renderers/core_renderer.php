<?php

class theme_SAVIOTheme_core_renderer extends core_renderer {

    /** @var custom_menu_item language The language menu if created */
    protected $language = null;

    /*
     * This renders a notification message.
     * Uses bootstrap compatible html.
     */

    public function notification($message, $classes = 'notifyproblem') {
        $message = clean_text($message);
        $type = '';

        if (($classes == 'notifyproblem') || ($classes == 'notifytiny')) {
            $type = 'alert alert-error';
        }
        if ($classes == 'notifysuccess') {
            $type = 'alert alert-success';
        }
        if ($classes == 'notifymessage') {
            $type = 'alert alert-info';
        }
        if ($classes == 'redirectmessage') {
            $type = 'alert alert-block alert-info';
        }
        return "<div class=\"$type\">$message</div>";
    }

    /*
     * This renders the navbar.
     * Uses bootstrap compatible html.
     */

    public function navbar() {
        $items = $this->page->navbar->get_items();
        if (empty($items)) {
            return '';
        }
        $breadcrumbs = array();
        foreach ($items as $item) {
            $item->hideicon = true;
            $breadcrumbs[] = $this->render($item);
        }
        $divider = '<span class="divider">' . get_separator() . '</span>';
        $list_items = '<li>' . join(" $divider</li><li>", $breadcrumbs) . '</li>';
        $title = '<span class="accesshide">' . get_string('pagepath') . '</span>';
        return $title . "<ul class=\"breadcrumb\">$list_items</ul>";
    }

    /*
     * Overriding the custom_menu function ensures the custom menu is
     * always shown, even if no menu items are configured in the global
     * theme settings page.
     */

    public function custom_menu($custommenuitems = '') {
        global $CFG;

        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }

    /*
     * Overriding the custom_menu function ensures the custom menu is
     * always shown, even if no menu items are configured in the global
     * theme settings page.
     */

    public function user_menu() {
        global $CFG;
        $usermenu = new custom_menu('', current_language());
        return $this->render_user_menu($usermenu);
    }

    /*
     * This renders the bootstrap top menu.
     *
     * This renderer is needed to enable the Bootstrap style navigation.
     */

    protected function render_custom_menu(custom_menu $menu) {
        global $CFG;

        // TODO: eliminate this duplicated logic, it belongs in core, not
        // here. See MDL-39565.
        $addlangmenu = false;
        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2
                or empty($CFG->langmenu)
                or ( $this->page->course != SITEID and ! empty($this->page->course->lang))) {
            $addlangmenu = false;
        }

        if (!$menu->has_children() && $addlangmenu === false) {
            return '';
        }

        if ($addlangmenu) {
            $this->language = $menu->add(get_string('language'), new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        $content = '<ul class="nav pull-right">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }

        return $content . '</ul>';
    }

    /*
     * This code renders the custom menu items for the
     * bootstrap dropdown menu.
     */

    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 0) {
        static $submenucount = 0;

        if ($menunode->has_children()) {

            if ($level == 1) {
                $class = 'dropdown';
            } else {
                $class = 'dropdown-submenu';
            }

            if ($menunode === $this->language) {
                $class .= ' langmenu';
            }
            $content = html_writer::start_tag('li', array('class' => $class));
            // If the child has menus render it as a sub menu.
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_' . $submenucount;
            }
            $content .= html_writer::start_tag('a', array('href' => $url, 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'title' => $menunode->get_title(), 'role' => 'button','aria-haspopup'=>'true', 'aria-expanded' => 'false'     ));
            $content .= $menunode->get_text();
            if ($level == 1) {
                $content .= '<b class="caret"></b>';
            }
            $content .= '</a>';
            $content .= '<ul class="dropdown-menu">';
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode, 0);
            }
            $content .= '</ul>';
        } else {
            $content = '<li>';
            // The node doesn't have children so produce a final menuitem.
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('title' => $menunode->get_title()));
        }
        return $content;
    }

    /**
     * Renders tabtree
     *
     * @param tabtree $tabtree
     * @return string
     */
    protected function render_tabtree(tabtree $tabtree) {
        if (empty($tabtree->subtree)) {
            return '';
        }
        $firstrow = $secondrow = '';
        foreach ($tabtree->subtree as $tab) {
            $firstrow .= $this->render($tab);
            if (($tab->selected || $tab->activated) && !empty($tab->subtree) && $tab->subtree !== array()) {
                $secondrow = $this->tabtree($tab->subtree);
            }
        }
        return html_writer::tag('ul', $firstrow, array('class' => 'nav nav-tabs')) . $secondrow;
    }

    /**
     * Renders tabobject (part of tabtree)
     *
     * This function is called from {@link core_renderer::render_tabtree()}
     * and also it calls itself when printing the $tabobject subtree recursively.
     *
     * @param tabobject $tabobject
     * @return string HTML fragment
     */
    protected function render_tabobject(tabobject $tab) {
        if (($tab->selected and ( !$tab->linkedwhenselected)) or $tab->activated) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'active'));
        } else if ($tab->inactive) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'disabled'));
        } else {
            if (!($tab->link instanceof moodle_url)) {
                // backward compartibility when link was passed as quoted string
                $link = "<a href=\"$tab->link\" title=\"$tab->title\">$tab->text</a>";
            } else {
                $link = html_writer::link($tab->link, $tab->text, array('title' => $tab->title));
            }
            $params = $tab->selected ? array('class' => 'active') : null;
            return html_writer::tag('li', $link, $params);
        }
    }

    /**
     * Override block render
     */
    public function block(block_contents $bc, $region) {
        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        if (!empty($bc->blockinstanceid)) {
            $bc->attributes['data-instanceid'] = $bc->blockinstanceid;
        }
        $skiptitle = strip_tags($bc->title);
        if ($bc->blockinstanceid && !empty($skiptitle)) {
            $bc->attributes['aria-labelledby'] = 'instance-' . $bc->blockinstanceid . '-header';
        } else if (!empty($bc->arialabel)) {
            $bc->attributes['aria-label'] = $bc->arialabel;
        }
        if ($bc->dockable) {
            $bc->attributes['data-dockable'] = 1;
        }
        if ($bc->collapsible == block_contents::HIDDEN) {
            $bc->add_class('hidden');
        }
        if (!empty($bc->controls)) {
            $bc->add_class('block_with_controls');
        }


        if (empty($skiptitle)) {
            $output = '';
            $skipdest = '';
        } else {
            $output = html_writer::tag('a', get_string('skipa', 'access', $skiptitle), array('href' => '#sb-' . $bc->skipid, 'class' => 'skip-block'));
            $skipdest = html_writer::tag('span', '', array('id' => 'sb-' . $bc->skipid, 'class' => 'skip-block-to'));
        }

        $classes = explode(" ", $bc->attributes["class"]);
        $bc->attributes["class"] .= " asidebar-item-menu-wrap";
        $output .= html_writer::start_tag('div', $bc->attributes);

        $output .= html_writer::tag('span', '', array('class' => $classes[0] . ' ' . (isset($classes[1]) ? $classes[1] : "") . ' asidebar-item-menu header'));
        $output .= html_writer::start_tag('div', array("class" => "asidebar-item-menu-wrap-block"));


        $output .= html_writer::start_tag('div', array('class' => "block-internal"));

        $output .= $this->block_header($bc);
        $output .= $this->block_content($bc);

        $output .= html_writer::end_tag('div');


        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('div');

        $output .= $this->block_annotation($bc);

        $output .= $skipdest;

        $this->init_block_hider_js($bc);
        return $output;
    }

    public function blocks_side_pre_custom($region = "side-pre", $classes = array(), $tag = 'nav') {
        $displayregion = $this->page->apply_theme_region_manipulations($region);
        $classes = (array) $classes;
        $classes[] = 'block-region';
        $attributes = array(
            'id' => 'block-region-' . preg_replace('#[^a-zA-Z0-9_\-]+#', '-', $displayregion),
            'class' => join(' ', $classes),
            'data-blockregion' => $displayregion,
            'data-droptarget' => '1'
        );
        if ($this->page->blocks->region_has_content($displayregion, $this)) {
            $content = $this->blocks_for_asidebar($displayregion);
        } else {
            $content = '';
        }
        return html_writer::tag($tag, $content, $attributes);
    }

    public function blocks_for_asidebar($region = "side-pre") {
        $blockcontents = $this->page->blocks->get_content_for_region($region, $this);
        $blocks = $this->page->blocks->get_blocks_for_region($region);
        $lastblock = null;
        $zones = array();
        foreach ($blocks as $block) {
            $zones[] = $block->title;
        }
        $output = '';

        foreach ($blockcontents as $bc) {
            if ($bc instanceof block_contents) {
                $output .= $this->block($bc, $region);
                $lastblock = $bc->title;
            } else if ($bc instanceof block_move_target) {
                $output .= $this->block_move_target($bc, $zones, $lastblock);
            } else {
                throw new coding_exception('Unexpected type of thing (' . get_class($bc) . ') found in list of block contents.');
            }
        }
        return $output;
    }

    /*
     * This renders the bootstrap top menu.
     *
     * This renderer is needed to enable the Bootstrap style navigation.
     */

    protected function render_user_menu(custom_menu $menu) {
        global $CFG, $USER, $DB, $PAGE;

        $addusermenu = true;
        $addlangmenu = true;

        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2
                or empty($CFG->langmenu)
                or ( $this->page->course != SITEID and ! empty($this->page->course->lang))) {
            $addlangmenu = false;
        }

        if ($addlangmenu) {
            $language = $menu->add(get_string('language'), new moodle_url('#'), get_string('language'), 10000);
            foreach ($langs as $langtype => $langname) {
                $language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        if ($addusermenu) {
            if (isloggedin() && !isguestuser()) {
                $usermenu = $menu->add(
                        get_string('usergreeting', 'theme_SAVIOTheme', $USER->firstname)
                        , new moodle_url('#'), get_string('usergreeting', 'theme_SAVIOTheme', $USER->firstname), 10001);

                $usermenu->add(
                        '<i class="fa fa-briefcase"></i>' . get_string('mydashboard', 'theme_SAVIOTheme'), new moodle_url('/my'), get_string('mydashboard', 'theme_SAVIOTheme')
                );

                $usermenu->add(
                        '<i class="fa fa-user"></i>' . get_string('viewprofile'), new moodle_url('/user/profile.php', array('id' => $USER->id)), get_string('viewprofile')
                );

                $usermenu->add(
                        '<i class="fa fa-cog"></i>' . get_string('editmyprofile'), new moodle_url('/user/edit.php', array('id' => $USER->id)), get_string('editmyprofile')
                );

                $userauth = get_auth_plugin($USER->auth);
                if ($userauth->can_change_password()) {
                    $usermenu->add(
                            '<i class="fa fa-cog"></i>' . get_string('changepassword'), new moodle_url('/login/change_password.php'), get_string('changepassword')
                    );
                }


                $usermenu->add(
                        '<i class="fa fa-file"></i>' . get_string('privatefiles', 'block_private_files'), new moodle_url('/user/files.php', array('id' => $USER->id)), get_string('privatefiles', 'block_private_files')
                );


                $usermenu->add(
                        '<i class="fa fa-calendar"></i>' . get_string('pluginname', 'block_calendar_month'), new moodle_url('/calendar/view.php', array('id' => $USER->id)), get_string('pluginname', 'block_calendar_month')
                );

                if (file_exists("$CFG->dirroot/local/mail/renderer.php")) {
                    require_once($CFG->dirroot . '/local/mail/message.class.php');
                    $count = local_mail_message::count_menu($USER->id);
                    $text = get_string('mymail', 'local_mail');
                    if (!empty($count->inbox)) {
                        $text .= ' (' . $count->inbox . ')';
                    }
                    $usermenu->add(
                            '<i class="fa fa-inbox"></i>' . $text, new moodle_url('/local/mail/view.php', array('t' => 'inbox')), $text
                    );
                }

                // Add custom links to menu
                $customlinksnum = (empty($PAGE->theme->settings->usermenulinks)) ? false : $PAGE->theme->settings->usermenulinks;
                if ($customlinksnum != 0) {
                    foreach (range(1, $customlinksnum) as $customlinksnumber) {
                        $cli = "customlinkicon$customlinksnumber";
                        $cln = "customlinkname$customlinksnumber";
                        $clu = "customlinkurl$customlinksnumber";

                        if (!empty($PAGE->theme->settings->enablecalendar)) {
                            $usermenu->add(
                                    '<i class="fa fa-' . $PAGE->theme->settings->$cli . '"></i>' . $PAGE->theme->settings->$cln, new moodle_url($PAGE->theme->settings->$clu, array('id' => $USER->id)), $PAGE->theme->settings->$cln
                            );
                        }
                    }
                }

                $usermenu->add(
                        '<i class="fa fa-lock"></i>' . get_string('logout'), new moodle_url('/login/logout.php', array('sesskey' => sesskey(), 'alt' => 'logout')), get_string('logout')
                );
            } else {
                $usermenu = $menu->add('<i class="fa fa-key"></i>' . get_string('login'), new moodle_url('/login/index.php'), get_string('login'), 10001);
            }
        }

        $content = '<ul class="nav navbar-nav navbar-right pull-right">';
        $content .= $this->notification_menu();
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }
        return $content . '</ul>';
    }

    public function notification_menu() {
        if (isloggedin() && !isguestuser()) {
            global $USER;
            $menu = '<li class="dropdown" id="course_notifications_link">';
            $menu .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . get_string('notifications', 'theme_SAVIOTheme') . '">';
            $menu .= '<span id="flag_notification_wrapper" class="badge count_notification"></span> <i class="fa fa-bell"></i>' . get_string('notifications', 'theme_SAVIOTheme');
            $menu .= '</a>
                        <div class="dropdown-menu" id="notification-wrap" style="">
                           <div class="wrap first"><h4>' . get_string('upcomingevents', 'calendar') . '<span class="sub activity_since">' . get_string('upcoming_text', 'theme_SAVIOTheme') . '</span></h4><ul id="upcoming-notification"></ul></div>
                           <div class="wrap"><h4>' . get_string("courseupdates") . '<span class="sub activity_since">' . get_string('activitysince', '', userdate($USER->lastlogin)) . '</span></h4><ul id="recent-notification"></ul></div>
                        </div>
                    ';
            $menu .= '</li>';

            $menu .= '<li class="dropdown" id="message_notifications_link">';
            $menu .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' . get_string('messages', 'message') . '">';
            $menu .= '<span id="flag_messages_wrapper" class="badge count_message"></span> <i class="fa fa-comments"></i>' . get_string('messages', 'message');
            $menu .= '</a><ul class="dropdown-menu" id="message-list">
                            <li class="last all-message">
                                <a title="' . get_string('gotoallmessage', 'theme_SAVIOTheme') . '" href="' . new moodle_url('/message/index.php') . '">' . get_string('gotoallmessage', 'theme_SAVIOTheme') . '</a>
                            </li>
                        </ul>';
            $menu .= '';
            $menu .= '</li>';
            return $menu;
        } else {
            return;
        }
    }

    protected function get_cover_course() {

        GLOBAL $CFG;
        if (isset($this->page->course->id) && $this->page->course->id != SITEID) {
            $course = $this->page->course;
            if ($course instanceof stdClass) {
                require_once($CFG->libdir . '/coursecatlib.php');
                $course = new course_in_list($course);
            }
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

    public function get_tutors() {
        if (isset($this->page->course->id) && $this->page->course->id != SITEID) {
            GLOBAL $OUTPUT, $DB;
            $content = "";
            $course = $this->page->course;
            $coursecontext = context_course::instance($course->id);
            $roles_to_display = array('2' => true, '3' => true, '18' => true,);
            $now = time();

            foreach ($roles_to_display as $roleid => $tps) {
                $persons = get_role_users($roleid, $coursecontext, true, '', 'u.lastname ASC', false);
                if (count($persons)) {
                    $active = false;
                    $ids = array();
                    $person_array = array();
                    foreach ($persons as $person) {
                        $ids[] = $person->id;
                        $person_array[$person->id] = $person;
                    }
                    $sql = "SELECT DISTINCT(u.id), ue.*
                            FROM {user_enrolments} ue
                            JOIN {enrol} e ON (e.id = ue.enrolid AND e.courseid = :courseid)
                            JOIN {user} u ON u.id = ue.userid
                          	WHERE ue.userid IN ( " . implode(",", $ids) . " )
                          	AND ue.status = :active
                          	AND e.status = :enabled AND u.deleted = 0
							GROUP BY ue.userid";


                    $params = array('enabled' => ENROL_INSTANCE_ENABLED, 'active' => ENROL_USER_ACTIVE, 'courseid' => $course->id);

                    if (!$enrolments = $DB->get_records_sql($sql, $params)) {
                        continue;
                    }

                    $count_tutors = count($enrolments);
                    $active = 1;
                    foreach ($enrolments as $enrolment) {
                        if (isset($person_array[$enrolment->userid])) {

                            $person = $person_array[$enrolment->userid];

                            if (( $enrolment->timestart < $now && $now < $enrolment->timeend ) || $enrolment->timeend == 0) {
                                $person->imagealt = $person->firstname . " " . $person->lastname;
                                $img_person = $OUTPUT->user_picture($person, array('size' => 110, 'class' => 'img-circle'));
                                $content .=" <div class='item " . ( ($active) ? "active" : "" ) . "'>";
                                $content .= "   <div class='tutor-image'>$img_person</div>";
                                $content .= "   <div class='tutor-name'>$person->firstname $person->lastname</div>";
                                $content .=" </div>";
                                $active = 0;
                            }
                        }
                    }
                }
            }
            return $content;
        }
    }

}

/**
 * Overridden core maintenance renderer.
 *
 * This renderer gets used instead of the standard core_renderer during maintenance
 * tasks such as installation and upgrade.
 * We override it in order to style those scenarios consistently with the regular
 * bootstrap look and feel.
 *
 * @package    theme_bootstrapbase
 * @copyright  2014 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_SAVIOTheme_core_renderer_maintenance extends core_renderer_maintenance {

    /**
     * Renders notifications for maintenance scripts.
     *
     * We need to override this method in the same way we do for the core_renderer maintenance method
     * found above.
     * Please note this isn't required of every function, only functions used during maintenance.
     * In this case notification is used to print errors and we want pretty errors.
     *
     * @param string $message
     * @param string $classes
     * @return string
     */
    public function notification($message, $classes = 'notifyproblem') {
        $message = clean_text($message);
        $type = '';

        if (($classes == 'notifyproblem') || ($classes == 'notifytiny')) {
            $type = 'alert alert-error';
        }
        if ($classes == 'notifysuccess') {
            $type = 'alert alert-success';
        }
        if ($classes == 'notifymessage') {
            $type = 'alert alert-info';
        }
        if ($classes == 'redirectmessage') {
            $type = 'alert alert-block alert-info';
        }
        return "<div class=\"$type\">$message</div>";
    }

}
