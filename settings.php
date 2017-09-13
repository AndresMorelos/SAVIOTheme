<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    
    global $PAGE;
    
    $settings->add(new admin_setting_configcheckbox('theme_SAVIOTheme/feedback', get_string('active_feedback', 'theme_SAVIOTheme'), get_string('active_feedback_help', 'theme_SAVIOTheme'), '1'));

    $settings->add(new admin_setting_configcheckbox('theme_SAVIOTheme/notifications', get_string('active_notifications', 'theme_SAVIOTheme'), get_string('active_notifications_help', 'theme_SAVIOTheme'), '1'));

    $settings->add(new admin_setting_configtext('theme_SAVIOTheme/notifications_time', get_string('active_notifications_time', 'theme_SAVIOTheme'), get_string('active_notifications_time_help', 'theme_SAVIOTheme'), 60000));
    
      // Copyright setting.
    $name = 'theme_SAVIOTheme/copyright';
    $title = get_string('copyright', 'theme_SAVIOTheme');
    $description = get_string('copyrightdesc', 'theme_SAVIOTheme');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
    // Logo file setting.
    $name = 'theme_SAVIOTheme/logo';
    $title = get_string('logo','theme_SAVIOTheme');
    $description = get_string('logodesc', 'theme_SAVIOTheme');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Custom CSS file.
    $name = 'theme_SAVIOTheme/customcss';
    $title = get_string('customcss', 'theme_SAVIOTheme');
    $description = get_string('customcssdesc', 'theme_SAVIOTheme');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Footnote setting.
    $name = 'theme_SAVIOTheme/footnote';
    $title = get_string('footnote', 'theme_SAVIOTheme');
    $description = get_string('footnotedesc', 'theme_SAVIOTheme');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    
    // Video login info
    $settings->add(new admin_setting_heading('logininfoheading', get_string('logininfoheading', 'theme_SAVIOTheme'), get_string('logininfoheading', 'theme_SAVIOTheme')));
    
    // Vdeologin 1 setting.
    $name = 'theme_SAVIOTheme/videologin1';
    $title = get_string('videologin', 'theme_SAVIOTheme');
    $description = get_string('videologin_desc', 'theme_SAVIOTheme');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
    $name = 'theme_SAVIOTheme/videologin_image1';
    $title = get_string('videologinimage', 'theme_SAVIOTheme');
    $description = get_string('videologinimage_desc', 'theme_SAVIOTheme');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'videologin_image1');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // Vdeologin 2 setting.
    $name = 'theme_SAVIOTheme/videologin2';
    $title = get_string('videologin', 'theme_SAVIOTheme');
    $description = get_string('videologin_desc', 'theme_SAVIOTheme');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
    $name = 'theme_SAVIOTheme/videologin_image2';
    $title = get_string('videologinimage', 'theme_SAVIOTheme');
    $description = get_string('videologinimage_desc', 'theme_SAVIOTheme');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'videologin_image2');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // Vdeologin 3 setting.
    $name = 'theme_SAVIOTheme/videologin3';
    $title = get_string('videologin', 'theme_SAVIOTheme');
    $description = get_string('videologin_desc', 'theme_SAVIOTheme');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $settings->add($setting);
    
    $name = 'theme_SAVIOTheme/videologin_image3';
    $title = get_string('videologinimage', 'theme_SAVIOTheme');
    $description = get_string('videologinimage_desc', 'theme_SAVIOTheme');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'videologin_image3');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    

    // Banner info
    $settings->add(new admin_setting_heading('bannerinfoheading', get_string('bannerinfoheading', 'theme_SAVIOTheme'), get_string('bannerinfoheading', 'theme_SAVIOTheme')));
    
            // Primary Slider 1 setting.
           $name = 'theme_SAVIOTheme/primaryslider1';
           $title = get_string('primaryslide', 'theme_SAVIOTheme');
           $description = get_string('primaryslide_desc', 'theme_SAVIOTheme');
           $default = '';
           $setting = new admin_setting_configtext($name, $title, $description, $default);
           $settings->add($setting);

           $name = 'theme_SAVIOTheme/primaryslider_image1';
           $title = get_string('primaryslide_image', 'theme_SAVIOTheme');
           $description = get_string('primaryslide_image_desc', 'theme_SAVIOTheme');
           $setting = new admin_setting_configstoredfile($name, $title, $description, 'primaryslide_image1');
           $setting->set_updatedcallback('theme_reset_all_caches');
           $settings->add($setting);

           // Primary Slider 2 setting.
           $name = 'theme_SAVIOTheme/primaryslider2';
           $title = get_string('primaryslide', 'theme_SAVIOTheme');
           $description = get_string('primaryslide_desc', 'theme_SAVIOTheme');
           $default = '';
           $setting = new admin_setting_configtext($name, $title, $description, $default);
           $settings->add($setting);

           $name = 'theme_SAVIOTheme/primaryslider_image2';
           $title = get_string('primaryslide_image', 'theme_SAVIOTheme');
           $description = get_string('primaryslide_image_desc', 'theme_SAVIOTheme');
           $setting = new admin_setting_configstoredfile($name, $title, $description, 'primaryslide_image2');
           $setting->set_updatedcallback('theme_reset_all_caches');
           $settings->add($setting);
    
    // Banner number
    $name = 'theme_SAVIOTheme/slidenumber';
    $title = get_string('slidenumber', 'theme_SAVIOTheme');
    $description = get_string('slidenumberdesc', 'theme_SAVIOTheme');
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
    $name = 'theme_SAVIOTheme/slidespeed';
    $title = get_string('slidespeed', 'theme_SAVIOTheme');
    $description = get_string('slidespeeddesc', 'theme_SAVIOTheme');
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
        $name = 'theme_SAVIOTheme/banner';
        $title = get_string('bannerindicator', 'theme_SAVIOTheme');
        $information = get_string('bannerindicatordesc', 'theme_SAVIOTheme');
        $setting = new admin_setting_heading($name . $bannernumber, $title . $bannernumber, $information);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Enables the slide.
        $name = 'theme_SAVIOTheme/enablebanner' . $bannernumber;
        $title = get_string('enablebanner', 'theme_SAVIOTheme', $bannernumber);
        $description = get_string('enablebannerdesc', 'theme_SAVIOTheme', $bannernumber);
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Slide Title.
        $name = 'theme_SAVIOTheme/bannertitle' . $bannernumber;
        $title = get_string('bannertitle', 'theme_SAVIOTheme', $bannernumber);
        $description = get_string('bannertitledesc', 'theme_SAVIOTheme', $bannernumber);
        $default = $bannertitle[$bannernumber - 1];
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Slide text.
        $name = 'theme_SAVIOTheme/bannertext' . $bannernumber;
        $title = get_string('bannertext', 'theme_SAVIOTheme', $bannernumber);
        $description = get_string('bannertextdesc', 'theme_SAVIOTheme', $bannernumber);
        $default = 'Bacon ipsum dolor sit amet turducken jerky beef ribeye boudin t-bone shank fatback pork loin pork short loin jowl flank meatloaf venison. Salami meatball sausage short loin beef ribs';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Text for Slide Link.
        $name = 'theme_SAVIOTheme/bannerlinktext' . $bannernumber;
        $title = get_string('bannerlinktext', 'theme_SAVIOTheme', $bannernumber);
        $description = get_string('bannerlinktextdesc', 'theme_SAVIOTheme', $bannernumber);
        $default = 'Read More';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Destination URL for Slide Link
        $name = 'theme_SAVIOTheme/bannerlinkurl' . $bannernumber;
        $title = get_string('bannerlinkurl', 'theme_SAVIOTheme', $bannernumber);
        $description = get_string('bannerlinkurldesc', 'theme_SAVIOTheme', $bannernumber);
        $default = '#';
        $previewconfig = null;
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Slide Image.
        $name = 'theme_SAVIOTheme/bannerimage' . $bannernumber;
        $title = get_string('bannerimage', 'theme_SAVIOTheme', $bannernumber);
        $description = get_string('bannerimagedesc', 'theme_SAVIOTheme', $bannernumber);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'bannerimage' . $bannernumber);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Slide Background Color.
        $name = 'theme_SAVIOTheme/bannercolor' . $bannernumber;
        $title = get_string('bannercolor', 'theme_SAVIOTheme', $bannernumber);
        $description = get_string('bannercolordesc', 'theme_SAVIOTheme', $bannernumber);
        $default = '#000';
        $previewconfig = null;
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);
    }
}
