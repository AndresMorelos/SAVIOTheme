<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// If Mail Local is installed
if (file_exists("$CFG->dirroot/local/mail/renderer.php")) {
    include_once($CFG->dirroot . "/local/mail/renderer.php");

    class theme_SAVIOTheme_local_mail_renderer extends local_mail_renderer {

        public function mail($message, $reply = false, $offset = 0) {
            global $CFG, $USER;

            $totalusers = 0;
            $output = '';

            if (!$reply) {
                $output .= html_writer::empty_tag('input', array(
                            'type' => 'hidden',
                            'name' => 'm',
                            'value' => $message->id(),
                ));

                $output .= html_writer::empty_tag('input', array(
                            'type' => 'hidden',
                            'name' => 'offset',
                            'value' => $offset,
                ));
            }

            $output .= $this->output->container_start('mail_header');
            $output .= $this->output->container_start('left');
            $output .= $this->output->user_picture($message->sender());
            $output .= $this->output->container_end();
            $output .= $this->output->container_start('mail_info');
            $output .= html_writer::link(new moodle_url('/user/view.php', array(
                        'id' => $message->sender()->id,
                        'course' => $message->course()->id
                            )), fullname($message->sender()) . " (" . $message->course()->fullname . ")", array('class' => 'user_from'));
            $output .= $this->date($message, true);
            if (!$reply) {
                $output .= $this->output->container_start('mail_recipients');
                foreach (array('to', 'cc', 'bcc') as $role) {
                    $recipients = $message->recipients($role);
                    if (!empty($recipients)) {
                        if ($role == 'bcc' and $message->sender()->id !== $USER->id) {
                            continue;
                        }
                        $output .= html_writer::start_tag('div');
                        $output .= html_writer::tag('span', get_string($role, 'local_mail'), array('class' => 'mail_role'));
                        $numusers = count($recipients);
                        $totalusers += $numusers;
                        $cont = 1;
                        foreach ($recipients as $user) {
                            $output .= html_writer::link(new moodle_url('/user/view.php', array(
                                        'id' => $user->id,
                                        'course' => $message->course()->id
                                            )), fullname($user));
                            if ($cont < $numusers) {
                                $output .= ', ';
                            }
                            $cont += 1;
                        }
                        $output .= ' ';
                        $output .= html_writer::end_tag('div');
                    }
                }
                $output .= $this->output->container_end();
            } else {
                $output .= html_writer::tag('div', '', array('class' => 'mail_recipients'));
            }
            $output .= $this->output->container_end();
            $output .= $this->output->container_end();

            $output .= $this->output->container_start('mail_body');
            $output .= $this->output->container_start('mail_content');
            $output .= local_mail_format_content($message);
            $attachments = local_mail_attachments($message);
            if ($attachments) {
                $output .= $this->output->container_start('mail_attachments');
                if (count($attachments) > 1) {
                    $text = get_string('attachnumber', 'local_mail', count($attachments));
                    $output .= html_writer::tag('span', $text, array('class' => 'mail_attachment_text'));
                    $downloadurl = new moodle_url($this->page->url, array('downloadall' => '1'));
                    $iconimage = $this->output->pix_icon('a/download_all', get_string('downloadall', 'local_mail'), 'moodle', array('class' => 'icon'));
                    $output .= html_writer::start_div('mail_attachment_downloadall');
                    $output .= html_writer::link($downloadurl, $iconimage);
                    $output .= html_writer::link($downloadurl, get_string('downloadall', 'local_mail'), array('class' => 'mail_downloadall_text'));
                    $output .= html_writer::end_div();
                }
                foreach ($attachments as $attach) {
                    $filename = $attach->get_filename();
                    $filepath = $attach->get_filepath();
                    $mimetype = $attach->get_mimetype();
                    $iconimage = $this->output->pix_icon(file_file_icon($attach), get_mimetype_description($attach), 'moodle', array('class' => 'icon'));
                    $path = '/' . $attach->get_contextid() . '/local_mail/message/' . $attach->get_itemid() . $filepath . $filename;
                    $fullurl = moodle_url::make_file_url('/pluginfile.php', $path, true);
                    $output .= html_writer::start_tag('div', array('class' => 'mail_attachment'));
                    $output .= html_writer::link($fullurl, $iconimage);
                    $output .= html_writer::link($fullurl, s($filename));
                    $output .= html_writer::tag('span', display_size($attach->get_filesize()), array('class' => 'mail_attachment_size'));
                    $output .= html_writer::end_tag('div');
                }
                $output .= $this->output->container_end();
            }
            $output .= $this->output->container_end();
            $output .= $this->newlabelform();
            if (!$reply) {
                if ($message->sender()->id !== $USER->id) {
                    $output .= $this->toolbar('reply', $message->course()->id, array('replyall' => ($totalusers > 1)));
                } else {
                    $output .= $this->toolbar('forward', $message->course()->id);
                }
            }
            $output .= $this->output->container_end();
            return $output;
        }

        public function view($params) {
            global $USER, $COURSE;

            $content = '';

            $type = $params['type'];
            $itemid = !empty($params['itemid']) ? $params['itemid'] : 0;
            $userid = $params['userid'];
            $messages = $params['messages'];
            $count = count($messages);
            $offset = $params['offset'];
            $totalcount = $params['totalcount'];
            $ajax = !empty($params['ajax']);
            $mailpagesize = get_user_preferences('local_mail_mailsperpage', MAIL_PAGESIZE, $USER->id);

            if (!$ajax) {
                $url = new moodle_url($this->page->url);
                $content .= html_writer::start_tag('form', array('method' => 'post', 'action' => $url, 'id' => 'local_mail_main_form'));
            }
            $paging = array(
                'offset' => $offset,
                'count' => $count,
                'totalcount' => $totalcount,
                'pagesize' => $mailpagesize,
            );
            if (!$messages) {
                $paging['offset'] = false;
            }

            $content .= $this->toolbar($type, 0, array('paging' => $paging, 'trash' => ($type === 'trash'), 'labelid' => $itemid));
            $content .= html_writer::start_tag('div', array('id' => 'mail_loading_small', 'class' => 'mail_hidden mail_loading_small'));
            $content .= $this->output->pix_icon('i/loading_small', '', 'moodle');
            $content .= html_writer::end_tag('div');
            $content .= $this->notification_bar();
            if ($messages) {
                $content .= $this->messagelist($messages, $userid, $type, $itemid, $offset);
            } else {
                $content .= $this->output->container_start('mail_list');
                $string = get_string('nomessagestoview', 'local_mail');
                $initurl = new moodle_url('/local/mail/view.php');
                $initurl->param('t', $type);
                if ($type === 'label') {
                    $initurl->param('l', $itemid);
                }
                $link = html_writer::link($initurl, get_string('showrecentmessages', 'local_mail'));
                $content .= html_writer::tag('div', $string . ' ' . $link, array('class' => 'mail_item'));
                $content .= $this->output->container_end();
            }
            $content .= html_writer::start_tag('div', array('class' => 'mail_hidden mail_search_loading'));
            $content .= $this->output->pix_icon('i/loading', get_string('actions'), 'moodle', array('class' => 'loading_icon'));
            $content .= html_writer::end_tag('div');
            $content .= html_writer::start_tag('div');
            $content .= html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'type',
                        'value' => $type,
            ));
            $content .= html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'sesskey',
                        'value' => sesskey(),
            ));
            $content .= html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'offset',
                        'value' => $offset,
            ));
            $content .= html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'itemid',
                        'value' => $itemid,
            ));
            $content .= $this->editlabelform();
            $content .= $this->newlabelform();
            $content .= html_writer::end_tag('div');
            $content .= html_writer::start_tag('div', array('class' => 'mail_perpage'));
            $content .= $this->perpage($offset, $mailpagesize);
            $content .= html_writer::end_tag('div');

            //Add create nuew message in bottom inbox page
            $content .= html_writer::start_tag('div', array('class' => 'mail_bottom_actions'));
            $url = new moodle_url('/local/mail/create.php');
            if ($COURSE->id != $SITE->id) {
                $url->param('c', $COURSE->id);
                $url->param('sesskey', sesskey());
            }
            $content .= html_writer::link($url, get_string('compose', 'local_mail'), array('class' => 'btn btn-success'));
            $content .= html_writer::end_tag('div');

            if (!$ajax) {
                $content .= html_writer::end_tag('form');
            }



            return $this->output->container($content);
        }

    }

}