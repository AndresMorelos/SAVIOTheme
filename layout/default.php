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
 * A three column layout for the Bootstrapbase theme.
 *
 * @package   theme_bootstrapbase
 * @copyright 2012 Bas Brands, www.basbrands.nl
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
$knownsidepre = $PAGE->blocks->is_known_region('side-pre');
$regions = SAVIOTheme_bootstrap_grid($hassidepost);

echo $OUTPUT->doctype()
?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
   <?php require_once('head.php'); ?>


    <body <?php echo $OUTPUT->body_attributes(); ?>>

        <?php echo $OUTPUT->standard_top_of_body_html() ?>
        <div class="page-wrapper">
            <!-- Include header template -->
            <?php require_once('header.php') ?>

            <!-- Side bar region pre add -->
            <?php if ($knownsidepre): ?>
                <div id="side-pre-wrap" data-spy="affix" data-offset-top="200" >
                    <?php echo $OUTPUT->blocks_side_pre_custom('side-pre', ''); ?>
                </div>
            <?php endif; ?>


            <div id="page" class="container">

                <header id="page-header" class="clearfix">
                    <div id="page-navbar" class="clearfix">
                        <nav class="breadcrumb-nav"><?php echo $OUTPUT->navbar(); ?></nav>

                        <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
                    </div>

                    <?php echo $OUTPUT->page_heading(); ?>
                    <div id="course-header"><?php echo $OUTPUT->course_header(); ?></div>
                </header>



                <div id="page-content" class="row-fluid">
                    <div id="<?php echo $regionbsid ?>" >
                        <section id="region-main" class="<?php echo $regions["content"] ?> ">
                            <div class="region-content">
                            <?php
                            echo $OUTPUT->course_content_header();
                            echo $OUTPUT->main_content();
                            echo $OUTPUT->course_content_footer();
                            ?>
                            <div class="clearfix"></div>
                            </div>
                        </section>
                    </div>
                    <?php  if ($hassidepost) : ?>
                    <?php echo $OUTPUT->blocks('side-post', $regions["post"]); ?>
                    <?php endif; ?>
                </div>

            </div>

             <!-- Include footer template -->
            <?php require_once('footer.php') ?>
	          <?php require_once('charge_bootsjs.php'); ?>
            <?php echo $OUTPUT->standard_end_of_body_html() ?>

        </div>
    </body>
</html>
