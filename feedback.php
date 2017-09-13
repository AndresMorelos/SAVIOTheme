<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("../../config.php");

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $rate = optional_param('feedback_rate',0, PARAM_FLOAT);
    $subject = optional_param('feedback_subject', "", PARAM_TEXT);
    $comment = optional_param('feedback_comment', "", PARAM_TEXT);
    $url = required_param('feedback_current',  PARAM_TEXT);
    
    if( !trim( $subject ) && !trim($comment) ){
        echo get_string("feedback_required", "theme_SAVIOTheme");
        echo "<a class='btn btn-primary' href='javascript:void(0);' onclick='SAVIO.load_feedback_form();'>".get_string("feedback_return", "theme_SAVIOTheme")."</a>";
        return;
    }
    
    try{
        $fb_entry = new stdClass();
        $fb_entry->user = $USER->id;
        $fb_entry->rate = $rate;
        $fb_entry->type = $subject;
        $fb_entry->comment = $comment;
        $fb_entry->url = $url;
        $fb_entry->created_at = time();
        $DB->insert_record('savio3_feedback', $fb_entry);
        echo get_string("feedback_success", "theme_SAVIOTheme");
        echo "<a class='btn btn-primary' href='javascript:void(0);' onclick='SAVIO.remove_feedback();'>".get_string("feedback_close", "theme_SAVIOTheme")."</a>";
 
    } catch (Exception $ex) {
        echo get_string("feedback_error", "theme_SAVIOTheme");
    }
        
} else {
    $sql = "SELECT fb.rate
            FROM {savio3_feedback} fb
            WHERE fb.user = :uid AND (fb.rate <> 0 OR fb.rate IS NOT NULL )";
    
    $params = array('uid' => $USER->id);
    $showrate = true;
    if ($results = $DB->get_records_sql($sql, $params)) {
       $showrate = false; 
    }
?>
    <form method="POST" action="<?php echo $CFG->wwwroot . "/theme/SAVIOTheme/feedback.php" ?>" class="ajax-form">
        <?php if($showrate): ?>
        <div class="form-group">
            <label for=""><?php echo get_string("feedback_rate", "theme_SAVIOTheme") ?></label>
            <input type="hidden" id="feedback_rate" name="feedback_rate" >
            <div id="feedback_rate_rating" data-average="0" data-id="1"></div>
        </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="feedback_subject"><?php echo get_string("feedback_subject", "theme_SAVIOTheme") ?></label>
            <select id="feedback_subject" name="feedback_subject" class="form-control">
                <option value=""><?php echo get_string("feedback_none", "theme_SAVIOTheme") ?></option>
                <option value="feedback_suggestions" ><?php echo get_string("feedback_suggestions", "theme_SAVIOTheme") ?></option>
                <option value="feedback_compliment"><?php echo get_string("feedback_compliment", "theme_SAVIOTheme") ?></option>
                <option value="feedback_bug" ><?php echo get_string("feedback_bug", "theme_SAVIOTheme") ?></option>
            </select>
        </div>
        <div class="form-group">
            <label for="feedback_comment"><?php echo get_string("feedback_comment", "theme_SAVIOTheme") ?></label>
            <textarea class="form-control" id="feedback_comment" name="feedback_comment" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo get_string("submit") ?></button>
    </form>
    <?php
    
    if(  is_siteadmin() ){
        echo "<a class='btn btn-primary' href='".$CFG->wwwroot."/theme/SAVIOTheme/feedback_overview.php'>".get_string("feedback_overview", "theme_SAVIOTheme")."</a>";
    }
}