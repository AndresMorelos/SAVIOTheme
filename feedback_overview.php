<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config.php';

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/theme/SAVIOTheme/feedback_overview.php');
$title = get_string('feedback_overview', 'theme_SAVIOTheme');
$PAGE->set_pagelayout('print');
$PAGE->set_title($title);
$PAGE->set_heading($title);

require_login();

if (!is_siteadmin()) {
    redirect($CFG->wwwroot);
}
$feedbacks = $DB->get_records('savio3_feedback');
echo $OUTPUT->header();

$media = $total = 0;
?>

<div class="feedback_results">
    <table class="table table-bordered">
        <tr>
            <th>User</th>
            <th>Rate</th>
            <th>Type</th>
            <th>Comment</th>
            <th>URL</th>
            <th>Created at</th>
        </tr>
        <?php foreach ($feedbacks as $feedback):     ?>
        <tr>
            <td><?php echo $feedback->user ?></td>
            <td><?php echo $feedback->rate ?></td><?php if($feedback->rate>0){$media += $feedback->rate;$total++;} ?>
            <td><?php echo $feedback->type ?></td>
            <td><?php echo $feedback->comment ?></td>
            <td><a href="<?php echo $feedback->url ?>">Ver url</a></td>
            <td><?php echo date("d-m-Y h:m:s", $feedback->created_at) ?></td>
        </tr>
        <?php endforeach;?>

        <tr>
            <th>Promedio</th>
            <th><?php echo number_format(( $media/$total ), 2); ?></th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>

<?php
echo $OUTPUT->footer();
