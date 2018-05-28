<header id="top-header" role="banner" class="navbar navbar-inverse moodle-has-zindex">
    <nav role="navigation" class="navbar-inner">
        <div class="container">
            <div class="">
                <span id="btn-sidebar-show"><i class="fa fa-ellipsis-v"></i></span>
                <span id="btn-sidebar-hide"><i class="fa fa-arrow-left"></i></span>

                <div id="logo" class="site-logo brand">
                    <a title="<?php echo $SITE->fullname; ?>" href="<?php echo $CFG->wwwroot; ?>" class=""><img src="<?php echo $OUTPUT->pix_url('logoutb', 'theme') ?>" role="presentation" alt="Universidad Tecnológica de Bolívar"></a>
                </div>
                <div id="name-and-slogan">
                    <a title="<?php echo $SITE->fullname; ?>" href="<?php echo $CFG->wwwroot; ?>" class=""><?php echo $SITE->fullname; ?></a>
                </div>
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div id="moodle-navbar" class="nav-collapse collapse">
                    <?php echo $OUTPUT->user_menu(); ?>
                    <?php echo $OUTPUT->custom_menu(); ?>
                    <ul class="nav pull-right">
                        <li><?php echo $OUTPUT->page_heading_menu(); ?></li>
                    </ul>
                </div>
                <?php if (isloggedin()): ?>
                    <div id="user-pic">
                        <?php echo html_writer::tag('div', $OUTPUT->user_picture($USER, array('size' => 50, 'class' => 'img-circle'))); ?>
                        <h4 class="username"><?php echo get_string('usergreeting', 'theme_saviotheme', $USER->firstname) ?></h4>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </nav>
</header>
