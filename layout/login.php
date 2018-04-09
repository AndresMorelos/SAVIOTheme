<?php
$PAGE->requires->js("/theme/saviotheme/javascript/login/jquery-ui-1.8.22.custom.min.js", false);
$PAGE->requires->js("/theme/saviotheme/javascript/login/jquery.imagesloaded.min.js", false);
$PAGE->requires->js("/theme/saviotheme/javascript/login/bigvideo.js", false);
$PAGE->requires->js("/theme/saviotheme/javascript/login/jquery.transit.min.js", false);
$PAGE->requires->css("/theme/saviotheme/style/login.css");
$PAGE->requires->css("/theme/saviotheme/style/bigvideo.css");

$videos = array();

$hasvideo1 = (!empty($PAGE->theme->settings->videologin1));
$hasvideo1image = (!empty($PAGE->theme->settings->videologin_image1));
if ($hasvideo1image) {
    $video1image = $PAGE->theme->setting_file_url('videologin_image1', 'videologin_image1');
    $videos[] = array(
        "path" => $PAGE->theme->settings->videologin1,
        "image" => $video1image
    );
}

$hasvideo2 = (!empty($PAGE->theme->settings->videologin2));
$hasvideo2image = (!empty($PAGE->theme->settings->videologin_image2));
if ($hasvideo2image) {
    $video2image = $PAGE->theme->setting_file_url('videologin_image2', 'videologin_image2');
    $videos[] = array(
        "path" => $PAGE->theme->settings->videologin2,
        "image" => $video2image
    );
}

$hasvideo3 = (!empty($PAGE->theme->settings->videologin3));
$hasvideo3image = (!empty($PAGE->theme->settings->videologin_image3));
if ($hasvideo3image) {
    $video3image = $PAGE->theme->setting_file_url('videologin_image3', 'videologin_image3');
    $videos[] = array(
        "path" => $PAGE->theme->settings->videologin3,
        "image" => $video3image
    );
}

if (count($videos)) {
    shuffle($videos);
} else {
    $videos[] = array(
        "path" => '',
        "image" => $OUTPUT->image_url('default_bg_login', 'theme')
    );
}

echo $OUTPUT->doctype()
?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
    <head>
        <title><?php echo $OUTPUT->page_title(); ?></title>
        <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php echo $OUTPUT->standard_head_html() ?>
        <script src="//vjs.zencdn.net/4.3/video.js"></script>
        <!--[if lte IE 8]>
        <style>
            /* Rotation of the arrow element for IE < 9 */
            .next-icon { /* IE Matrix Calculator - http: //www.boogdesign.com/examples/transforms/matrix-calculator.html */;
                -ms-filter: "progid:DXImageTransform.Microsoft.Matrix(M11=0.70710678, M12=-0.70710678, M21=0.70710678, M22=0.70710678,sizingMethod='auto expand')";
                filter: progid:DXImageTransform.Microsoft.Matrix(M11=0.70710678, M12=-0.70710678, M21=0.70710678, M22=0.70710678,sizingMethod='auto expand');
            }
        </style>
        <![endif]-->
    </head>

    <body <?php echo $OUTPUT->body_attributes(); ?>>

        <?php echo $OUTPUT->standard_top_of_body_html() ?>

        <div class="wrapper">
            <?php foreach ($videos as $k => $video): ?>
                <div class="screen" id="screen-<?php echo $k + 1 ?>" data-video="<?php echo $video["path"] ?>">
                    <img src="<?php echo $video["image"] ?>" class="big-image" />
                </div>
            <?php endforeach; ?>
        </div>
        <div class="overlay"></div>

        <nav id="next-btn">
            <a href="#" class="next-icon"></a>
        </nav>

        <div class="page-wrapper">
            <div id="page" class="container-fluid">
                <div id="region-main" class="row-fluid">
                    <div class="span6 offset3">
                        <div class="login-wrapper">
                            <div class="login-logo" >
                                <div id="logo" class="site-logo"> 
                                    <a title="<?php echo $SITE->fullname; ?>" href="<?php echo $CFG->wwwroot; ?>" class=""><img src="<?php echo $OUTPUT->image_url('logoutb_green', 'theme') ?>" role="presentation" alt="Universidad Tecnológica de Bolívar"></a>
                                </div>
                                <div id="name-and-slogan">
                                    <a title="<?php echo $SITE->fullname; ?>" href="<?php echo $CFG->wwwroot; ?>" class=""><?php echo $SITE->fullname; ?></a>
                                </div>
                            </div>
                            <?php echo $OUTPUT->main_content(); ?>
                        </div>

                    </div>      

                </div>
            </div>
        </div>

        <?php echo $OUTPUT->standard_end_of_body_html() ?>

        <script>

            $(function () {

                // Use Modernizr to detect for touch devices, 
                // which don't support autoplay and may have less bandwidth, 
                // so just give them the poster images instead
                var screenIndex = 1,
                        numScreens = $('.screen').length,
                        isTransitioning = false,
                        transitionDur = 1000,
                        BV,
                        videoPlayer,
                        isTouch = Modernizr.touch,
                        $bigImage = $('.big-image'),
                        $window = $(window);

                //console.log(screenIndex);

                if (!isTouch) {
                    // initialize BigVideo
                    BV = new $.BigVideo({forceAutoplay: isTouch});
                    BV.init();
                    showVideo();
                    console.log(BV.getPlayer());
                    BV.getPlayer().on('loadeddata', function () {
                        onVideoLoaded();
                    });
                    BV.getPlayer().on('ended', function () {
                        next();
                    });

                    // adjust image positioning so it lines up with video
                    $bigImage
                            .css('position', 'relative')
                            .imagesLoaded(adjustImagePositioning);
                    // and on window resize
                    $window.on('resize', adjustImagePositioning);
                }

                // Next button click goes to next div
                $('#next-btn').on('click', function (e) {
                    e.preventDefault();
                    if (!isTransitioning) {
                        next();
                    }
                });

                function showVideo() {
                    BV.show($('#screen-' + screenIndex).attr('data-video'), {ambient: true});
                }

                function next() {
                    isTransitioning = true;

                    // update video index, reset image opacity if starting over
                    if (screenIndex === numScreens) {
                        $bigImage.css('opacity', 1);
                        screenIndex = 1;
                    } else {
                        screenIndex++;
                    }

                    if (!isTouch) {
                        $('#big-video-wrap').transit({'left': '-100%'}, transitionDur)
                    }

                    (Modernizr.csstransitions) ?
                            $('.wrapper').transit(
                            {'left': '-' + (100 * (screenIndex - 1)) + '%'},
                    transitionDur,
                            onTransitionComplete) :
                            onTransitionComplete();
                }

                function onVideoLoaded() {
                    $('#screen-' + screenIndex).find('.big-image').transit({'opacity': 0}, 500)
                }

                function onTransitionComplete() {
                    isTransitioning = false;
                    if (!isTouch) {
                        $('#big-video-wrap').css('left', 0);
                        showVideo();
                    }
                }

                function adjustImagePositioning() {
                    $bigImage.each(function () {
                        var $img = $(this),
                                img = new Image();

                        img.src = $img.attr('src');

                        var windowWidth = $window.width(),
                                windowHeight = $window.height(),
                                r_w = windowHeight / windowWidth,
                                i_w = img.width,
                                i_h = img.height,
                                r_i = i_h / i_w,
                                new_w, new_h, new_left, new_top;

                        if (r_w > r_i) {
                            new_h = windowHeight;
                            new_w = windowHeight / r_i;
                        }
                        else {
                            new_h = windowWidth * r_i;
                            new_w = windowWidth;
                        }

                        $img.css({
                            width: new_w,
                            height: new_h,
                            left: (windowWidth - new_w) / 2,
                            top: (windowHeight - new_h) / 2
                        })

                    });

                }
            });

        </script>

    </body>
</html>
