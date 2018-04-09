<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    
    global $PAGE;
    
    $settings->add(new admin_setting_configcheckbox('theme_saviotheme/feedback', get_string('active_feedback', 'theme_saviotheme'), get_string('active_feedback_help', 'theme_saviotheme'), '1'));

    $settings->add(new admin_setting_configcheckbox('theme_saviotheme/notifications', get_string('active_notifications', 'theme_saviotheme'), get_string('active_notifications_help', 'theme_saviotheme'), '1'));

    $settings->add(new admin_setting_configtext('theme_saviotheme/notifications_time', get_string('active_notifications_time', 'theme_saviotheme'), get_string('active_notifications_time_help', 'theme_saviotheme'), 60000));
    
      // Copyright setting.
    $name = 'theme_saviotheme/copyright';
    $title = get_string('copyright', 'theme_saviotheme');
    $description = get_string('copyrightdesc', 'theme_saviotheme');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
    // Logo file setting.
    $name = 'theme_saviotheme/logo';
    $title = get_string('logo','theme_saviotheme');
    $description = get_string('logodesc', 'theme_saviotheme');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Custom CSS file.
    $name = 'theme_saviotheme/customcss';
    $title = get_string('customcss', 'theme_saviotheme');
    $description = get_string('customcssdesc', 'theme_saviotheme');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Footnote setting.
    $name = 'theme_saviotheme/footnote';
    $title = get_string('footnote', 'theme_saviotheme');
    $description = get_string('footnotedesc', 'theme_saviotheme');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    
    // Video login info
    $settings->add(new admin_setting_heading('logininfoheading', get_string('logininfoheading', 'theme_saviotheme'), get_string('logininfoheading', 'theme_saviotheme')));
    
    // Vdeologin 1 setting.
    $name = 'theme_saviotheme/videologin1';
    $title = get_string('videologin', 'theme_saviotheme');
    $description = get_string('videologin_desc', 'theme_saviotheme');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
    $name = 'theme_saviotheme/videologin_image1';
    $title = get_string('videologinimage', 'theme_saviotheme');
    $description = get_string('videologinimage_desc', 'theme_saviotheme');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'videologin_image1');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // Vdeologin 2 setting.
    $name = 'theme_saviotheme/videologin2';
    $title = get_string('videologin', 'theme_saviotheme');
    $description = get_string('videologin_desc', 'theme_saviotheme');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
    $name = 'theme_saviotheme/videologin_image2';
    $title = get_string('videologinimage', 'theme_saviotheme');
    $description = get_string('videologinimage_desc', 'theme_saviotheme');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'videologin_image2');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // Vdeologin 3 setting.
    $name = 'theme_saviotheme/videologin3';
    $title = get_string('videologin', 'theme_saviotheme');
    $description = get_string('videologin_desc', 'theme_saviotheme');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
    $name = 'theme_saviotheme/videologin_image3';
    $title = get_string('videologinimage', 'theme_saviotheme');
    $description = get_string('videologinimage_desc', 'theme_saviotheme');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'videologin_image3');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    

    // Banner info
    $settings->add(new admin_setting_heading('bannerinfoheading', get_string('bannerinfoheading', 'theme_saviotheme'), get_string('bannerinfoheading', 'theme_saviotheme')));
    
            // Primary Slider 1 setting.
           $name = 'theme_saviotheme/primaryslider1';
           $title = get_string('primaryslide', 'theme_saviotheme');
           $description = get_string('primaryslide_desc', 'theme_saviotheme');
           $default = '';
           $setting = new admin_setting_configtext($name, $title, $description, $default);
           $settings->add($setting);

           $name = 'theme_saviotheme/primaryslider_image1';
           $title = get_string('primaryslide_image', 'theme_saviotheme');
           $description = get_string('primaryslide_image_desc', 'theme_saviotheme');
           $setting = new admin_setting_configstoredfile($name, $title, $description, 'primaryslide_image1');
           $setting->set_updatedcallback('theme_reset_all_caches');
           $settings->add($setting);

           // Primary Slider 2 setting.
           $name = 'theme_saviotheme/primaryslider2';
           $title = get_string('primaryslide', 'theme_saviotheme');
           $description = get_string('primaryslide_desc', 'theme_saviotheme');
           $default = '';
           $setting = new admin_setting_configtext($name, $title, $description, $default);
           $settings->add($setting);

           $name = 'theme_saviotheme/primaryslider_image2';
           $title = get_string('primaryslide_image', 'theme_saviotheme');
           $description = get_string('primaryslide_image_desc', 'theme_saviotheme');
           $setting = new admin_setting_configstoredfile($name, $title, $description, 'primaryslide_image2');
           $setting->set_updatedcallback('theme_reset_all_caches');
           $settings->add($setting);
    
    // Banner number
    $name = 'theme_saviotheme/slidenumber';
    $title = get_string('slidenumber', 'theme_saviotheme');
    $description = get_string('slidenumberdesc', 'theme_saviotheme');
    $default = '1';
    $choices = array(
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // Set the Slide Speed.
    $name = 'theme_saviotheme/slidespeed';
    $title = get_string('slidespeed', 'theme_saviotheme');
    $description = get_string('slidespeeddesc', 'theme_saviotheme');
    $default = '600';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    $hasslidenum = (!empty($PAGE->theme->settings->slidenumber));
    if ($hasslidenum) {
        $slidenum = $PAGE->theme->settings->slidenumber;
    } else {
        $slidenum = '1';
    }
    
    $bannertitle = array('Slide One', 'Slide Two', 'Slide Three', 'Slide Four', 'Slide Five', 'Slide Six', 'Slide Seven', 'Slide Eight', 'Slide Nine', 'Slide Ten');

    foreach (range(1, $slidenum) as $bannernumber) {

        // This is the descriptor for the Banner Settings.
        $name = 'theme_saviotheme/banner';
        $title = get_string('bannerindicator', 'theme_saviotheme');
        $information = get_string('bannerindicatordesc', 'theme_saviotheme');
        $setting = new admin_setting_heading($name . $bannernumber, $title . $bannernumber, $information);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Enables the slide.
        $name = 'theme_saviotheme/enablebanner' . $bannernumber;
        $title = get_string('enablebanner', 'theme_saviotheme', $bannernumber);
        $description = get_string('enablebannerdesc', 'theme_saviotheme', $bannernumber);
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Slide Title.
        $name = 'theme_saviotheme/bannertitle' . $bannernumber;
        $title = get_string('bannertitle', 'theme_saviotheme', $bannernumber);
        $description = get_string('bannertitledesc', 'theme_saviotheme', $bannernumber);
        $default = $bannertitle[$bannernumber - 1];
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Slide text.
        $name = 'theme_saviotheme/bannertext' . $bannernumber;
        $title = get_string('bannertext', 'theme_saviotheme', $bannernumber);
        $description = get_string('bannertextdesc', 'theme_saviotheme', $bannernumber);
        $default = 'Bacon ipsum dolor sit amet turducken jerky beef ribeye boudin t-bone shank fatback pork loin pork short loin jowl flank meatloaf venison. Salami meatball sausage short loin beef ribs';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Text for Slide Link.
        $name = 'theme_saviotheme/bannerlinktext' . $bannernumber;
        $title = get_string('bannerlinktext', 'theme_saviotheme', $bannernumber);
        $description = get_string('bannerlinktextdesc', 'theme_saviotheme', $bannernumber);
        $default = 'Read More';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Destination URL for Slide Link
        $name = 'theme_saviotheme/bannerlinkurl' . $bannernumber;
        $title = get_string('bannerlinkurl', 'theme_saviotheme', $bannernumber);
        $description = get_string('bannerlinkurldesc', 'theme_saviotheme', $bannernumber);
        $default = '#';
        $previewconfig = null;
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Slide Image.
        $name = 'theme_saviotheme/bannerimage' . $bannernumber;
        $title = get_string('bannerimage', 'theme_saviotheme', $bannernumber);
        $description = get_string('bannerimagedesc', 'theme_saviotheme', $bannernumber);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'bannerimage' . $bannernumber);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Slide Background Color.
        $name = 'theme_saviotheme/bannercolor' . $bannernumber;
        $title = get_string('bannercolor', 'theme_saviotheme', $bannernumber);
        $description = get_string('bannercolordesc', 'theme_saviotheme', $bannernumber);
        $default = '#000';
        $previewconfig = null;
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);
    }
}
