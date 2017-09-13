/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var SAVIOTheme = SAVIOTheme || {};

SAVIOTheme.notify_time = 0;

/**
 * Init all function for this theme
 * @returns void
 */
SAVIOTheme.init = function () {
    this.init_side_bar();
    this.register_handles();
    this.config_login_password();
    this.init_feedback();
};

/**
 * Register all event for pages elements
 * @returns void
 */
SAVIOTheme.register_handles = function(){
    var heigth = $("#top-header").outerHeight( true );

    $(window).scroll(function () {
        winScroll = $(window).scrollTop();

        if (winScroll >= heigth ){
            $('body').addClass('reduced').css("padding-top",heigth);
            if (winScroll > heigth + 40 )
                $('body').addClass('view');
        }else{
            $('body').removeClass('view')
                    .removeClass('reduced')
                    .css("padding-top",0);
        }

    });

    $("[rel=popover-ele]").popover({
            html : true,
            trigger: "hover"
    });

    $("#btn-sidebar-show").click(function(){
        $("body").addClass("active-sidebar");
    });
    $("#btn-sidebar-hide").click(function(){
        $("body").removeClass("active-sidebar");
    });

    /***AFFIX COUNTER AND NAV QUIZ ***/
    var block_quiz_nav = $("#mod_quiz_navblock");
    if( block_quiz_nav.length > 0  ){
        block_quiz_nav.attr("data-spy","affix").attr("data-offset-top","200").css("top","50px");
    }

    /**Effect wave on button, Material Design */
    if( !$("#page-login-index").length   ){
        Waves.attach('span.asidebar-item-menu', ['waves-block', 'waves-float','waves-light']);
        Waves.attach('button', ['waves-button', 'waves-float']);
        Waves.init();
    }

    /* Addons for Mod Lesson **/
    if( $("#page-mod-lesson-view").length   ){
        $(".clock.block.asidebar-item-menu").addClass("open-tab");
    }
}

/**
 * init side bar functionality
 *
 * @returns void
 */
SAVIOTheme.init_side_bar = function () {
    var items = $("#block-region-side-pre .asidebar-item-menu-wrap > span.asidebar-item-menu");
    var timer;
    $(".asidebar-item-menu-wrap > span.asidebar-item-menu").on('click', function () {
        var i = $(this);
        if (i.hasClass("open-tab")) {
            i.removeClass("open-tab").next(".asidebar-item-menu-wrap-block").hide();
        } else {
            items.removeClass("open-tab").next(".asidebar-item-menu-wrap-block").hide();
            i.addClass("open-tab").next(".asidebar-item-menu-wrap-block").show();
        }
    }).mouseenter(function () {
        i = $(this);
        timer = setTimeout(function () {
            if (!i.hasClass("open-tab")) {
                items.removeClass("open-tab").next(".asidebar-item-menu-wrap-block").hide();
                i.addClass("open-tab").next(".asidebar-item-menu-wrap-block").show();
            }
        }, 5000);
    }).mouseleave(function () {
        clearTimeout(timer);
    });

    $("header,.navbar,#page,footer,#btn-sidebar-hide").click(function () {
        $("#block-region-side-pre .asidebar-item-menu-wrap > span.asidebar-item-menu").removeClass("open-tab").next(".asidebar-item-menu-wrap-block").hide();
    });
};

/**
 *
 * init notification system, with timeout setted
 *
 * @param YUI Object Y
 * @param string url
 * @param timer it
 * @returns void
 */
SAVIOTheme.notification_init = function (Y, url, it) {
    var inst = this;
    inst.notification_ajax(url);
    window.setInterval(function () {
        inst.notification_ajax(url);

    }, it);
    setTimeout(function () {
        $("#flag_messages_wrapper,#flag_notification_wrapper").removeClass("important")
    }, 10000);
};

/**
 *
 * Function call notification system for message, upcoming and updates
 *
 * @param string url
 * @returns {undefined}
 */
SAVIOTheme.notification_ajax = function (url) {
    var inst = this;
    $.ajax({
        url: url,
        dataType: "json",
        data: {ntime: inst.notify_time},
        timeout: 15000,
        success: function (response) {
            //Set Last Sync
            inst.notification_proccess_messages(response.data.message);
            inst.notification_proccess_upcomming(response.data.upcoming);
            inst.notification_proccess_recent(response.data.event);
            inst.notify_time = response.time;
        }
    });
};

/**
 * Proccess new messages.
 *
 * @param Object m
 * @returns {undefined}
 */
SAVIOTheme.notification_proccess_messages = function (m) {
    var badget = $("#flag_messages_wrapper");
    var menu_items = badget.parent("a").next("#message-list");
    var inst = this;
    if (m != "undefined") {
        //Set Message news
        var new_unread = 0;
        try {
            if (badget.text().length > 0) {
                new_unread = parseInt(badget.text());
            }
        } catch (e) {
            new_unread = 0;
        }

        new_unread = new_unread + m.new_unread;

        if (m.new_unread > 0) {
            badget.addClass("important");
        }

        badget.text(new_unread);

        $.each(m.messages, function (i, o) {
            var content = '<li class="' + o.news + '">\n\
                            <a href="' + o.linkto + '" title="' + o.fullname + '">\n\
                                <span class="image-message">' + o.userimage + '</span>\n\
                                <span class="body-message">\n\
                                    <span class="name-message">' + o.fullname + '</span>\n\
                                    <span class="created">' + o.created + '</span>\n\
                                    <span>' + o.smallmessage + '</span>\n\
                                </span>\n\
                            </a>\n\
                            </li>';
            if (inst.notify_time > 0) {
                menu_items.prepend(content);
            } else {
                menu_items.append(content);
            }

        });

    }
};

/**
 * Process new upcomming events.
 *
 * @param Object u
 * @returns void
 */
SAVIOTheme.notification_proccess_upcomming = function (u) {
    var badget = $("#flag_notification_wrapper");
    var menu_items = $("#upcoming-notification");
    var inst = this;
    if (u != "undefined") {
        //Set Upcoming news
        var new_unread = 0;
        try {
            if (badget.text().length > 0) {
                new_unread = parseInt(badget.text());
            }
        } catch (e) {
            new_unread = 0;
        }

        new_unread = new_unread + u.new_unread;

        if (u.new_unread > 0) {
            badget.addClass("important");
        }

        badget.text(new_unread);

        $.each(u.upcomings, function (i, o) {

            var li = $("#" + o.cmid + "_" + o.courseid + "");
            var content = "";
            if (li.length == 0) {
                content += '<li id="' + o.cmid + "_" + o.courseid + '" class="' + o.news + '"><span class="icon c0">' + o.icon + '</span>';
                content += '<span class="wrap-text"><span class="date">' + o.time + ', </span>' + o.name + ' ';
                if (o.courselink != "undefined") {
                    content += ' ( ' + o.courselink + ' )';
                }
                content += '</span></li>';
                if (inst.notify_time > 0) {
                    menu_items.prepend(content);
                } else {
                    menu_items.append(content);
                }
            } else {
                li.removeClass().addClass(o.news);
                li.empty();
                content += '<span class="icon c0">' + o.icon + '</span><span class="wrap-text"><span class="date">' + o.time + ', </span>' + o.name + ' ';
                if (o.courselink != "undefined") {
                    content += ' ( ' + o.courselink + ' )';
                }
                content += '</span></li>';
                li.append(content);
            }

        });

    }
};

/**
 * Process new courses updates
 *
 * @param Object u
 * @returns void
 */
SAVIOTheme.notification_proccess_recent = function (u) {
    var badget = $("#flag_notification_wrapper");
    var menu_items = $("#recent-notification");
    var inst = this;
    if (u != "undefined") {
        //Set Recents news
        var new_unread = 0;
        try {
            if (badget.text().length > 0) {
                new_unread = parseInt(badget.text());
            }
        } catch (e) {
            new_unread = 0;
        }

        new_unread = new_unread + u.new_unread;

        if (u.new_unread > 0) {
            badget.addClass("important");
        }

        badget.text(new_unread);

        $.each(u.events, function (i, o) {
            var content = '<li class="' + o.news + '"><span class="icon c0"><img src="' + o.icon + '" /></span>';
            content += "<span class='wrap-text'>" + o.text + " ";
            content += o.link;
            content += '</span></li>';
            if (inst.notify_time > 0) {
                menu_items.prepend(content);
            } else {
                menu_items.append(content);
            }
        });

    }
};

/**
 * Function to validate usernama and password of the SIRIUS UTB
 * @returns void
 */
SAVIOTheme.config_login_password = function () {
    $("#page-login-index #username").blur(function () {
        //expresion regular para validar que empieze por t, que tenga luego 3 ceros, y luego alfanumericos
        var RegEx = /^(T|t){1}[0]{3}([0-9]{5})+/i;
        var input = $(this);
        var pass = $("#password");
        if (input.val().length > 3) {
            if (RegEx.test(input.val())) {
                if (input.val().length == 9) {
                    pass.attr("maxlength", 6);
                    var _pass = pass.val();
                    if (_pass.length > 6) {
                        _pass = _pass.substr(0, 6);
                        pass.val(_pass);
                    }
                } else {
                    //pass.style.borderColor("red");
                    alert("Los IDs de SIRIUS solo tienen nueve caractares.\nEjemplo: 'T00025455'");
                    input.focus();
                }
            } else {
                if (pass.attr("maxlength") !== "undefined" && pass.attr("maxlength") == 6)
                    pass.attr("maxlength", "");
            }
        }
    });
};

/*********** FEEDBACK SYSTEM ****************/

/**
 * Init feedback form
 * @returns {undefined}
 */
SAVIOTheme.init_feedback = function () {
    var inst = this;
    $("#feedback_wrap .feedback_buttom a").click(function (e) {
        var form = $(this).parent().next();
        if (form.is(":visible")) {
            form.hide()
        } else {
            if (!form.find("form").length) {
                inst.load_feedback_form();
            }
            form.show();
        }

        e.preventDefault();
    });
};

/**
 * Load feedback form in pages.
 * @returns void
 */
SAVIOTheme.load_feedback_form = function () {
    $("#feedback_wrap .feedback_form").load(M.cfg.wwwroot + '/theme/SAVIOTheme/feedback.php', function () {
        var fbrate = $("#feedback_rate_rating");
        var rate = 0;
        if (fbrate.length > 0) {

            fbrate.jRating({
                sendRequest: false,
                canRateAgain: true,
                bigStarsPath: M.cfg.wwwroot + '/theme/SAVIOTheme/jquery/jrating/icons/stars.png', // path of the icon stars.png
                smallStarsPath: M.cfg.wwwroot + '/theme/SAVIOTheme/jquery/jrating/icons/small.png', // path of the icon small.png
                nbRates: 5,
                rateMax: 5,
                decimalLength: 1,
                onClick: function (element, r) {
                    $("#feedback_rate").val(r);
                    $("#feedback_rate").prev().removeClass("error")
                }
            });
        }

        $("#feedback_wrap .feedback_form form").submit(function () {
            var url = $(this).attr("action");
            var fbtype = $("#feedback_subject");
            var fbcomment = $("#feedback_comment");
            var valid = true;
            if (fbtype.val().length > 0) {
                if ($.trim(fbcomment.val()).length <= 0) {
                    fbcomment.prev().addClass("error")
                    valid = false;
                }
            }
            if (valid) {
                $.post(url, $(this).serialize() + "&feedback_current=" + window.location.href, function (data) {
                    $("#feedback_wrap .feedback_form").html(data);
                });
            }

            return false;
        })
    });
};

/**
 * Remove feedback form form page
 * @returns void
 */
SAVIOTheme.remove_feedback = function () {
    $("#feedback_wrap").remove();
};


SAVIOTheme.show_nav_course_affix = function(Y,format){
    var CSS = {
        COURSECONTENT : 'course-content',
        section_node : 'li',
        WEEKNAME : 'weekname',
        SECTIONNAME : 'content .sectionname',
        SECTIONWEEK : 'weeknumber .num'
    };

    var sectionlist = $('.'+CSS.COURSECONTENT+' '+CSS.section_node+'.section');
    var size = sectionlist.size();

    if(size <= 0)
        return;

    var body = $("body"),
        containernode = $('<div></div>'),
        containerinner = $('<div></div>'),
        containernav = $('<div></div>');

    containernode.addClass('section_nav_affix navbar navbar-fixed-bottom navbar-inverse');
    containerinner.addClass('navbar-inner');
    containernav.addClass('container');

    var list = $('<ul></ul>'), nav = $('<div></div>'), ele, sect, num,name,num_obj;
    nav.addClass('section_nav_affix_nav nav-collapse collapse navbar-responsive-collapse');
    list.addClass('nav');
    list.attr("id",'weeksavio_nav_affix');
    $.each(sectionlist, function(i,o){
        sect = $(o);
        ele = $('<li></li>');

        if (sect.hasClass('current')) {
            ele.addClass('active');
        }

        num = (sect.attr("id")).split("-")[1];

        name = sect.find('.' + CSS.WEEKNAME);

        if( name.length >  0){
            name = name.text();
        }else{
            name = sect.find('.' + CSS.SECTIONNAME);
            if(  name.length >  0 )
                name = name.text();
            else
                name = M.util.get_string('sectionname', format)
        }

        ele.html( '<a title="'+name+'" href="#section-'+num+'" > '+num+' <span class="affix_section_name"> - '+name+'</span></a>' );
        list.append(ele);
    });

    var offset = 220;
    var duration = 500;
    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.section_nav_affix').fadeIn(duration);
        } else {
            jQuery('.section_nav_affix').fadeOut(duration);
        }
    });

    containernav.append('<div class="navbar-header"><a class="btn btn-navbar" data-toggle="collapse" data-target=".section_nav_affix_nav"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a><span class="text_nav_affix">'+M.util.get_string('goto', 'theme_saviotheme') +'</span></div>')
    nav.append(list);
    containernav.append(nav);
    containerinner.append(containernav);
    containernode.append(containerinner);
    body.append(containernode);
}


/**
 * Ready document handle
 */
$(document).ready(function() {
    SAVIOTheme.init();
    //$("html").niceScroll({cursorwidth:'10',cursorborderradius: '0', scrollspeed: '40' });
    $(".block_mycourses:not(.grouping_categories) .content").niceScroll({cursorwidth:'8',cursorborderradius: '0',scrollspeed: '10', autohidemode: false, cursoropacitymin: 1 });
    $("div[id^='ascrail']").show();
});
