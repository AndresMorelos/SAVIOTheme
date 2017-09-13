<?php

/**
 * Init Jquery / Js plugins
 *
 * @param moodle_page $page
 */

function theme_saviotheme_page_init(moodle_page $page) {
    $page->requires->jquery();
    $page->requires->jquery_plugin('fitvids', 'theme_saviotheme');
    $page->requires->jquery_plugin('modernizr', 'theme_saviotheme');
    $page->requires->jquery_plugin('nicescroll', 'theme_saviotheme');
    if( SITEID != $page->course->id ){
        $courseformat = course_get_format( $page->course);
        $page->requires->strings_for_js(array('sectionname'),'format_'.$courseformat->get_format());
        $page->requires->strings_for_js(array('go'),'theme_saviotheme');
    }

}

/**
 * Render NAV fixed to all sections course
 */

function theme_saviotheme_course_nav(moodle_page $page) {
    if( SITEID != $page->course->id ){
        $courseformat = course_get_format( $page->course);
        $format = 'format_'.$courseformat->get_format();
        $page->requires->strings_for_js(array('sectionname'),$format);
        $page->requires->strings_for_js(array('goto'),'theme_saviotheme');
        $page->requires->js_init_call('SAVIOTheme.show_nav_course_affix', array($format));
    }
}

/**
 * get configuration for regions layout
 *
 * @param type $hassidepost
 * @return array
 */

function SAVIOTheme_bootstrap_grid($hassidepost) {
    if (!$hassidepost) {
        $regions = array('content' => 'span12');
    } else {
        $regions = array('content' => 'span9');
    }

    $regions['pre'] = 'empty';
    $regions['post'] = 'span3';

    return $regions;
}

/**
 * Parses CSS before it is cached.
 *
 * This function can make alterations and replace patterns within the CSS.
 *
 * @param string $css The CSS
 * @param theme_config $theme The theme config object.
 * @return string The parsed CSS The parsed CSS.
 */
function theme_saviotheme_process_css($css, $theme) {

    // Set the background image for the logo.
    $logo = $theme->setting_file_url('logo', 'logo');
    $css = theme_saviotheme_set_logo($css, $logo);

    // Set custom CSS.
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = theme_saviotheme_set_customcss($css, $customcss);

    return $css;
}

/**
 * Adds the logo to CSS.
 *
 * @param string $css The CSS.
 * @param string $logo The URL of the logo.
 * @return string The parsed CSS
 */
function theme_saviotheme_set_logo($css, $logo) {
    $tag = '[[setting:logo]]';
    $replacement = $logo;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
/*
function theme_saviotheme_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'backgroundimage')) {
        $theme = theme_config::load('SAVIOTheme');
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}*/

function theme_saviotheme_pluginfile($course, $cm, $context, $filearea, $args, $forcedownloadarray,array $options = array()) {

    if ($context->contextlevel == CONTEXT_SYSTEM
            && ($filearea== "logo" || preg_match("/^bannerimage[0-9]+$/",$filearea) || preg_match("/^videologin_image[0-9]+$/",$filearea) || preg_match("/^primaryslide_image[0-9]+$/",$filearea)   ) ) {

            $theme = theme_config::load('theme_saviotheme');
            return $theme->setting_file_serve($filearea, $args, $forcedownloadarray, $options);

    } else {
        send_file_not_found();
    }

}


/**
 * Adds any custom CSS to the CSS before it is cached.
 *
 * @param string $css The original CSS.
 * @param string $customcss The custom CSS to add.
 * @return string The CSS which now contains our custom CSS.
 */
function theme_saviotheme_set_customcss($css, $customcss) {
    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

/**
 *
 * Init notification system for moodle,
 * init Javascript function for these.
 *
 * @global type $PAGE
 * @global type $CFG
 * @param type $time_request
 * @return null
 */

function SAVIOTheme_init_notifications($time_request) {
    global $PAGE, $CFG;
    if (!isloggedin() || isguestuser()) {
        return;
    }
    $url = $CFG->wwwroot . '/theme/SAVIOTheme/notifications_responses.php';
    $page_noallow_view = array(
        "page-mod-quiz-attempt"
    );
    //$this->content->text = $PAGE->bodyid;

    if (!in_array($PAGE->bodyid, $page_noallow_view)) {
        $PAGE->requires->js_init_call('SAVIOTheme.notification_init', array($url,$time_request));
    }
}

/**
 * Get all information for notification
 *
 * @global type $CFG
 * @global type $PAGE
 * @global type $USER
 * @param type $lasttime
 * @return array
 */
function SAVIOTheme_get_notifications($lasttime){
    global $CFG,$PAGE,$USER;

     if (!isloggedin() || isguestuser()) {
        return;
    }
    $PAGE->set_context(context_user::instance($USER->id));

    require_once($CFG->dirroot . '/course/lib.php');
    $courses = enrol_get_my_courses( );
    $data = array(
        "upcoming"=>SAVIOTheme_get_upcomming($lasttime),
        "event"=>SAVIOTheme_get_events($courses,$lasttime),
        "message"=>SAVIOTheme_get_user_messages($lasttime),
    );
    return $data;

}

/**
 * Get unread message
 *
 * @global type $USER
 * @global type $DB
 * @global type $CFG
 * @global type $OUTPUT
 * @param type $time
 * @return array
 */

function SAVIOTheme_get_user_messages($time=null) {
    global $USER, $DB, $CFG, $OUTPUT;
    $data = array(
        "total_messages" => 0,
        "messages" => array(),
        "showalllink" => $CFG->wwwroot . '/message/index.php',
        "showall" => false,
        "new_unread"=>0
    );

    if (empty($CFG->messaging)) {
        return $data ;
    }

    if (!isloggedin() || isguestuser()) {
        return $data;
    }

    //a quick query to check whether the user has new messages
    $messagecount = $DB->count_records('message', array('useridto' => $USER->id));
    if ($messagecount < 1) {
        return $data;
    }

    $data["total_messages"] = $messagecount;
    //got unread messages so now do another query that joins with the user table
    $messagesql = "SELECT m.id, m.smallmessage, m.fullmessageformat,m.timecreated, m.notification,u.id uid, u.firstname, u.lastname,u.picture,u.email,u.imagealt,u.firstnamephonetic, u.lastnamephonetic,u.middlename, u.alternatename
                     FROM {message} m
                     JOIN {message_working} mw ON m.id=mw.unreadmessageid
                     JOIN {message_processors} p ON mw.processorid=p.id
                     JOIN {user} u ON m.useridfrom=u.id
                    WHERE m.useridto = :userid AND m.useridfrom <> :userid2
                      AND p.name='popup' ";

    $param = array('userid' => $USER->id,'userid2' => $USER->id);

    if( $time ){
        $messagesql .= 'AND m.timecreated > :lastpopuptime';
        $param["lastpopuptime"] = $time;
    }

    $messagesql .= " ORDER BY m.timecreated DESC";

    $message_users = $DB->get_records_sql($messagesql,$param );


    //if we have new messages to notify the user about

    if (!empty($message_users)) {
        $showall = "";
        $limit = 4;
        if (count($message_users) > 4) {
            $data["showall"] = true;
        } else {
            $limit = count($message_users);
        }

        $data["new_unread"] = count($message_users);

        $i = 0;
        foreach ($message_users as $k => $message) {
            $data["messages"][$k]["fullname"] = $message->firstname . " " . $message->lastname;

            //try to display the small version of the message
            $smallmessage = null;
            if (!empty($message->smallmessage)) {
                //display the first 200 chars of the message in the popup
                $smallmessage = null;
                if (core_text::strlen($message->smallmessage) > 100) {
                    $smallmessage = core_text::substr($message->smallmessage, 0, 100) . '...';
                } else {
                    $smallmessage = $message->smallmessage;
                }

                //prevent html symbols being displayed
                if ($message->fullmessageformat == FORMAT_HTML) {
                    $smallmessage = html_to_text($smallmessage);
                } else {
                    $smallmessage = s($smallmessage);
                }
            }
            $data["messages"][$k]["smallmessage"] = $smallmessage;
            $u = new stdClass();
            $u->id = $message->uid;
            $u->firstname = $message->firstname;
            $u->lastname = $message->lastname;
            $u->email = $message->email;
            $u->picture = $message->picture;
            $u->imagealt = $message->imagealt;
            $u->firstnamephonetic = $message->firstnamephonetic;
            $u->lastnamephonetic = $message->lastnamephonetic;
            $u->middlename = $message->middlename;
            $u->alternatename =  $message->alternatename;
            $data["messages"][$k]["created"] = date("d/m/Y h:i:s a", $message->timecreated)." ".(($time)?"<span class='label label-danger'>".  get_string('new', 'theme_saviotheme')."</span>":"");
            $data["messages"][$k]["news"] = ($time)?"new":"old";
            $data["messages"][$k]["linkto"] = $CFG->wwwroot . '/message/index.php?viewing=unread&user2=' . $message->uid;
            $data["messages"][$k]["userimage"] = $OUTPUT->user_picture($u, array('size' => 30, 'link' => false,'class'=>'media-object img-circle'));

            $i++;
            if ($i == $limit) {
                break;
            }
        }
    }

    return $data;
}

/**
 * Get upcomming event for user in calendar.
 *
 * @global type $USER
 * @global type $CFG
 * @global type $SESSION
 * @param type $time
 *
 * @return array
 */
function SAVIOTheme_get_upcomming($time=0) {
    global $USER, $CFG, $SESSION;
    $data = array("upcomings"=>array(), "new_unread"=>0);
    require_once($CFG->dirroot . '/calendar/lib.php');

    // Being displayed at site level. This will cause the filter to fall back to auto-detecting
    // the list of courses it will be grabbing events from.
    $filtercourse = calendar_get_default_courses();
    list($courses, $group, $user) = calendar_set_filters($filtercourse);

    $defaultlookahead = CALENDAR_DEFAULT_UPCOMING_LOOKAHEAD;
    if (isset($CFG->calendar_lookahead)) {
        $defaultlookahead = intval($CFG->calendar_lookahead);
    }
    $lookahead = get_user_preferences('calendar_lookahead', $defaultlookahead);

    $defaultmaxevents = CALENDAR_DEFAULT_UPCOMING_MAXEVENTS;
    if (isset($CFG->calendar_maxevents)) {
        $defaultmaxevents = intval($CFG->calendar_maxevents);
    }
    $maxevents = 4;

    $events = calendar_get_upcoming($courses, $group, $user, $lookahead, $maxevents,$time);

    if ($events) {

        $lines = count($events);
        for ($i = 0; $i < $lines; ++$i) {
            if( (double)$events[$i]->timemodified < $time  ){
                continue;
            }

            if (!isset($events[$i]->time)) {   // Just for robustness
                continue;
            }

            $events[$i] = calendar_add_event_metadata($events[$i]);
            if (!empty($events[$i]->referer)) {
                $events[$i]->name = $events[$i]->referer;
                // That's an activity event, so let's provide the hyperlink
            }
            $events[$i]->time = (($time)?"<span class='label label-danger'>".  get_string('new', 'theme_saviotheme')."</span>":"").str_replace('&raquo;', '<br />&raquo;', $events[$i]->time);
            $events[$i]->news = ($time)?"new":"old";
            $data["upcomings"][]= $events[$i];
        }
    } else {
        $events = array();
    }
    $data["new_unread"]= count($data["upcomings"]);
    return $data;
}

/**
 * get updates for courses enroled user
 *
 * @global type $CFG
 * @global type $USER
 * @global type $DB
 * @global type $OUTPUT
 * @param type $courses
 * @param type $timestart
 * @return array
 */
function SAVIOTheme_get_events($courses,$timestart) {

    global $CFG, $USER, $DB, $OUTPUT;

    if (isguestuser()) {
        return null;
    }

    $data = array("new_unread"=>0,"events"=>array(),"since"=>0);
    $content = false;

    if ($courses) {
        $courses_ids = array();
        $courses_array = array();
        $modifo_array = array();


        if( ! $timestart ){
            $timestart = $USER->lastlogin; // better db caching for guests - 100 seconds
        }

        $data["since"] = $timestart;

        foreach ($courses as $course) {
            array_push($courses_ids, $course->id);
            $courses_array[$course->id] = $course;
            $modifo_array[$course->id] = get_fast_modinfo($course);
        }

        $changelist = array();

        try {

            $sql = "SELECT
                    cmid, MIN(action) AS minaction, MAX(action) AS maxaction, MAX(modname) AS modname, courseid
                FROM {block_recent_activity}
                WHERE timecreated > ? AND courseid IN (".implode(",", $courses_ids).")
                GROUP BY cmid
                ORDER BY MAX(timecreated) DESC";

            $params = array($timestart);
            $logs = $DB->get_records_sql($sql, $params,0,6);

            /*$logs = $DB->get_records_select('log', "time > ? AND course IN (".implode(",", $courses_ids).") AND
                                            module = 'course' AND
                                            (action = 'add mod' OR action = 'update mod' OR action = 'delete mod')", array($timestart), "time DESC", '*', 0, 6);*/
        } catch (Exception $e) {
            echo $e;
            return;
        }

        if ($logs) {
            foreach ($logs as $key => $log) {
                $wasdeleted = ($log->maxaction == block_recent_activity_observer::CM_DELETED);
                $wascreated = ($log->minaction == block_recent_activity_observer::CM_CREATED);

                 if ($wasdeleted && $wascreated) {
                    // Activity was created and deleted within this interval. Do not show it.
                    continue;
                }else if ($wasdeleted ) {
                    continue;
                } else if (!$wasdeleted && isset($modifo_array[$log->courseid]->cms[$log->cmid]) ) {
                    // Module was either added or updated during this interval and it currently exists.
                    // If module was both added and updated show only "add" action.
                    $cm = $modifo_array[$log->courseid]->cms[$log->cmid];
                    if ($cm->has_view() && $cm->uservisible) {
                        //Created
                        if($wascreated ){
                            $stradded = get_string('added', 'theme_saviotheme');
                            $changelist[$log->cmid] = array(
                                'operation' => 'add',
                                'text' => "<span class='action_event add $cm->modname'>$stradded</span><a class='link_event' href=\"".$cm->url."\">" . format_string($cm->name, true) . "</a> (".get_string('modulename', $cm->modname).") ",
                                'course'=>array(
                                    'id'=>$log->courseid,
                                    'fullname'=>$courses_array[$log->courseid]->fullname
                                ),
                                'icon'=>$OUTPUT->pix_url('icon', $cm->modname). '');
                        }//Update
                        else{
                            $strupdated = get_string('updated', 'theme_saviotheme');
                            $changelist[$log->cmid] = array('operation' => 'update', 'text' => "<span class='action_event update $cm->modname'>$strupdated</span><a class='link_event' href=\"".$cm->url."\">" . format_string($cm->name, true) . "</a> (".get_string('modulename', $cm->modname).")",
                                    'course'=>array(
                                                            'id'=>$log->courseid,
                                                            'fullname'=>$courses_array[$log->courseid]->fullname
                                                        ),
                                    'icon'=>$OUTPUT->pix_url('icon', $cm->modname). '' );

                        }
                    }
                }

            }
        }

        if (!empty($changelist)) {
            if (!$content) {
                $content = true;
            }
            $inthecourse = get_string('inthecourse', 'theme_saviotheme');
            foreach ($changelist as $changeinfo => $change) {
                $data["events"][$changeinfo]["text"] = (($timestart != $USER->lastlogin)?"<span class='label label-danger'>".  get_string('new', 'theme_saviotheme')."</span>":"").$change['text']." ".$inthecourse;
                $data["events"][$changeinfo]["course"] = $change['course'];
                $data["events"][$changeinfo]["icon"] = $change['icon'];
                $data["events"][$changeinfo]["link"] ='<a href="'.$CFG->wwwroot .'/course/view.php?id='.$change["course"]["id"].'">'.$change["course"]["fullname"].'</a>';
                $data["events"][$changeinfo]["news"] = ($timestart == $USER->lastlogin)?"old":"new";
                $data["new_unread"]++;
            }
        }
    }




    if (!$content) {
        @$data["empty"] = get_string('nothingnew');
    }

    return $data;

}
