function KuasShowRequestPost(e, t, n) {
    kuasBackToTop("#web_wrapper", 10, 10, 500);
    NProgress.start();
    NProgress.inc();
    jQuery(this).find('input[type="submit"]').attr("disabled", "disabled")
}

function KuasShowResponsePost(e, t, n, r) {
    if (e.code == 2) {
        type_code = "attention_message"
    } else if (e.code == 1) {
        type_code = "success_message";
        r.resetForm();
        kuasResetImageRead(r, "#KuasImagePreview")
    } else {
        type_code = "error_message"
    }
    jQuery(".message_frontpost").html('<div id="' + type_code + '">' + e.msg + "</div>");
    r.find('input[type="submit"]').removeAttr("disabled");
    NProgress.done()
}

function kuasReadImageUpload(e, t) {
    if (e.files && e.files[0]) {
        var n = new FileReader;
        n.onload = function (e) {
            jQuery(t).css({
                "background-image": "url(" + e.target.result + ")"
            }).addClass("valid_img")
        };
        n.readAsDataURL(e.files[0])
    }
}

function kuasResetImageRead(e, t) {
    e.find(t).css({
        "background-image": "url(" + kuas_ajax_var.kuas_domain + "/assets/images/photo-placeholder.jpg)"
    }).removeClass("valid_img")
}

function kuasBackToTop(e, t, n, r) {
    if (kuas_ajax_var.kuas_is_mobile == 0) {
        if (!t) {
            t = 10
        }
        if (!n) {
            n = 10
        }
        if (!r) {
            r = 500
        }
        if (jQuery(e).offset()) {
            if (jQuery(e).offset().top < jQuery(window).scrollTop()) {
                jQuery("html,body").animate({
                    scrollTop: jQuery(e).offset().top - t
                }, r)
            } else if (jQuery(e).offset().top + jQuery(e).height() > jQuery(window).scrollTop() + (window.innerHeight || document.documentElement.clientHeight)) {
                jQuery("html,body").animate({
                    scrollTop: jQuery(e).offset().top - (window.innerHeight || document.documentElement.clientHeight) + jQuery(e).height() + n
                }, r)
            }
        }
    }
}

function ucwords(e) {
    return (e + "").replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function (e) {
        return e.toUpperCase()
    })
}

function maps_loading(e, t) {
    if (!t) {
        t = search_venue_text
    }
    switch (e) {
    case "start":
        jQuery(loader_maps).text(t).prependTo("#containner_top");
        break;
    case "stop":
        jQuery("span.maps_loading").remove();
        break;
    default:
        jQuery(loader_maps).text(t).prependTo("#containner_top")
    }
}

function getRandomArr(e) {
    var t = Math.floor(Math.random() * e.length);
    return e[t]
}

function limitTitleWords(e, t) {
    var n = e.match(/ /g);
    var r = Array();
    if (n) {
        if (n.length > t) {
            var s = e.split(" ");
            for (i = 0; i < t; i++) {
                r.push(s[i])
            }
        } else {
            r.push(e)
        }
    } else {
        r.push(e)
    }
    return ucwords(r.join(" "))
}
var hashtagRegex = /(#\w+)/g;
var urlRegex = /\(?(?:(http|https|ftp):\/\/)?(?:((?:[^\W\s]|\.|-|[:]{1})+)@{1})?((?:www.)?(?:[^\W\s]|\.|-)+[\.][^\W\s]{2,4}|localhost(?=\/)|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::(\d*))?([\/]?[^\s\?]*[\/]{1})*(?:\/?([^\s\n\?\[\]\{\}\#]*(?:(?=\.)){1}|[^\s\n\?\[\]\{\}\.\#]*)?([\.]{1}[^\s\?\#]*)?)?(?:\?{1}([^\s\n\#\[\]]*))?([\#][^\s\n]*)?\)?/gi;
jQuery(document).ready(function () {
    jQuery("body").on("click", "a", function (e) {
        if (urlRegex.test(jQuery(this).attr("href")) && hashtagRegex.test(jQuery(this).attr("href")) == false) {
            NProgress.start();
            NProgress.inc();
            setTimeout(function () {
                NProgress.done()
            }, 1e3)
        }
    });
    jQuery("form#searchform").on("submit", function () {
        NProgress.start();
        NProgress.inc();
        setTimeout(function () {
            NProgress.done()
        }, 1e3)
    });
    if (kuas_ajax_var.kuas_is_mobile == 1) {
        jQuery(".menu_navigation_site ul.ul-top").mobileMenu({
            defaultText: "Menuju Ke Halaman...",
            className: "sub-menu",
            subMenuDash: "&ndash;"
        });
        jQuery(".menu_navigation_site ul.ul-main").mobileMenu({
            defaultText: "Menuju Ke Halaman..."
        });
        jQuery("select.first-ul-menu").live("change", function () {
            jQuery("option:selected", this).each(function () {
                NProgress.start();
                NProgress.inc();
                setTimeout(function () {
                    NProgress.done()
                }, 1e3)
            })
        })
    } else {
        function e() {
            jQuery(".menu_navigation_site ul.first-ul-menu").superfish({
                delay: 140,
                animation: {
                    opacity: "show",
                    height: "show"
                },
                speed: "normal",
                autoArrows: false,
                dropShadows: true
            })
        }
        e()
    }
    jQuery(".carousel-autotrue").jCarouselLite({
        btnNext: ".next",
        btnPrev: ".prev",
        visible: 3,
        scroll: 3,
        pause: true,
        auto: true,
        speed: 1e3,
        timeout: 1e4,
        easing: "easeOutQuad"
    });
    jQuery(".carousel-autofalse").jCarouselLite({
        btnNext: ".next",
        btnPrev: ".prev",
        visible: 3,
        scroll: 3,
        auto: false,
        speed: 500
    });
    jQuery(".feat-carousel").each(function () {
        var e = jQuery(this).attr("data-limit");
        jQuery(this).jCarouselLite({
            vertical: true,
            visible: e,
            scroll: 1,
            pause: true,
            auto: 1,
            speed: 1e3,
            timeout: 5500,
            easing: "easeOutExpo"
        })
    });
    jQuery(".widget_views .most_viewed").jCarouselLite({
        autoCSS: true,
        vertical: true,
        visible: 5,
        scroll: 2,
        pause: true,
        auto: 2,
        speed: 2e3,
        easing: "easeOutQuint"
    });
    jQuery(".widget_views .most_viewed_category").jCarouselLite({
        autoCSS: true,
        vertical: true,
        visible: 5,
        pause: true,
        auto: 1,
        speed: 1e3,
        timeout: 5500,
        easing: "easeOutQuint"
    });
    jQuery(".gallery-pages-carousel").jCarouselLite({
        autoCSS: true,
        visible: 1,
        scroll: 1,
        pause: false,
        auto: true,
        speed: 1e3,
        timeout: 1e4,
        easing: "easeOutElastic"
    });
    jQuery("textarea").autosize();
    jQuery("#comment textarea").Kuas_Watermark({
        inner_txt: "Apa yang akan anda katakan?"
    });
    jQuery("input.searchfield").Kuas_Watermark();
    jQuery("#wrapper_post_list").on("click", ".pagination a", function (e) {
        e.preventDefault();
        var t = jQuery(this).attr("href");
        jQuery("#wrapper_post_list").animate({
            opacity: "0.5"
        }, 500, function () {
            jQuery(this).load(t + " #wrapper_post_list", function () {
                jQuery(this).animate({
                    opacity: "1"
                }, 500);
                kuasBackToTop("#wrapper_post_list", 50, 10, 500)
            })
        })
    })
});
jQuery(function () {
    function e() {
        jQuery(this).one("click", t);
        jQuery("div.font_emot_glossarium").fadeIn()
    }

    function t() {
        jQuery(this).one("click", e);
        jQuery("div.font_emot_glossarium").fadeOut()
    }
    if (kuas_ajax_var.kuas_is_mobile == 0) {
        jQuery("a.achor-marker-map.by_foursquare, a.checkin_button_foursquare").live("click", function (e) {
            e.preventDefault();
            if (jQuery("body").hasClass("home")) {
                jQuery(parent_venue).gmap("closeInfoWindow")
            }
            jQuery("body").prepend('<div id="overlay_venue_info"></div>');
            jQuery("#web_wrapper").addClass("grayscaleKuasEffect");
            var t = kuas_ajax_var.kuas_ajax_date,
                n = kuas_ajax_var.kuas_locale.split("_"),
                r = jQuery(this).attr("id");
            var s = "https://api.foursquare.com/v2/venues/" + r + "?v=" + t + "&locale=" + n[0] + "&client_id=" + clid + "&client_secret=" + clsc;
            jQuery.getJSON(s).done(function (e) {
                var t = e.response.venue;
                var n = t.location.address ? '<br /><span class="venue_info_address">' + t.location.address + "</span>" : "";
                buildHTML = '<a href="javascript:void(0)" class="venue_info_close">x</a><div class="venue_info_container">';
                buildHTML += '<div class="content_venue_info">';
                if (t.photos.groups.length != 0) {
                    var r = t.photos.groups[0].items;
                    if (t.photos.groups[0].items.length > 1) {
                        buildHTML += '<h2 class="title_venue_info vp_min_type" style="background-image:url(' + r[0].prefix + "800x200/blur20" + r[0].suffix + ');">' + t.name + n + "</h2>";
                        buildHTML += '<div class="content_venue_info_photo">';
                        for (i = 0; i < r.length; i++) {
                            buildHTML += '<img src="' + r[i].prefix + "150x150" + r[i].suffix + '">'
                        }
                        buildHTML += "</div>"
                    } else {
                        buildHTML += '<h2 class="title_venue_info vp_big_type" style="background-image:url(' + r[0].prefix + "800x400/blur5" + r[0].suffix + ');">' + t.name + n + "</h2>"
                    }
                } else {
                    buildHTML += '<h2 class="title_venue_info vp_big_type" style="background-image:url(https://maps.googleapis.com/maps/api/staticmap?center=' + t.location.lat + "," + t.location.lng + "&zoom=15&size=800x400&markers=" + t.location.lat + "," + t.location.lng + '&sensor=false);">';
                    buildHTML += t.name + n + "</h2>"
                }
                buildHTML += "</div>";
                buildHTML += '<div class="meta_venue_info">';
                if (t.tips.groups.length > 0) {
                    buildHTML += '<div class="venue_info_tips"><h3>Tips ' + t.name + "</h3><ul>";
                    var s = t.tips.groups[0].items;
                    for (i = 0; i < s.length; i++) {
                        buildHTML += "<li>" + s[i].text + "</li>"
                    }
                    buildHTML += "</ul></div>"
                }
                var o = t.rating ? t.rating : 0;
                if (o != 0) {
                    buildHTML += '<div class="venue_info_rating"><small>Rating:</small><br /><big>' + o + "</big></div>"
                }
                buildHTML += '<div class="venue_info_stats">' + t.stats.checkinsCount + "+ orang Check-in di:<br />" + t.name + "</div>";
                buildHTML += "</div>";
                buildHTML += '<a class="venue_source" href="' + t.canonicalUrl + '" target="_blank"></a></div>';
                jQuery("#overlay_venue_info").html(buildHTML).css({
                    "background-image": "none",
                    "background-color": "whitesmoke"
                })
            })
        });
        jQuery("#overlay_venue_info .venue_info_close").live("click", function () {
            jQuery(this).parent("#overlay_venue_info").remove();
            jQuery("#web_wrapper").removeClass("grayscaleKuasEffect")
        });
        jQuery(document).keyup(function (e) {
            if (e.keyCode == 27) {
                jQuery("#overlay_venue_info").remove();
                jQuery("#web_wrapper").removeClass("grayscaleKuasEffect")
            }
        })
    }(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/id_ID/all.js#xfbml=1&appId=427637250695040";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    jQuery('#social-count-post[data-post^="post"]').bind("mouseenter", function () {
        var e = jQuery(this).attr("data-post").slice(5);
        var t = jQuery(this).attr("data-permalink");
        var n = jQuery(this).attr("data-title");
        jQuery("#sharing-" + e).css("background", "none");
        var r = '<div class="fb-share-button" data-href="' + t + '" data-width="120" data-type="button_count"></div>';
        jQuery("#fb-kuas-share-" + e).removeClass("facebook").css({
            'width': '120px'
        }).html(r);
        FB.XFBML.parse(document.getElementById("fb-kuas-share-" + e));
        var i = '<span style="float:left;width:100px;margin-right:5px;"><iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?url=' + t + "&text=" + n + '&via=Kuningan_Asri" style="width:120px; height:50px;" allowTransparency="true" frameborder="0"></iframe></span>';
        jQuery("#tweet-kuas-share-" + e).css("width", "100px").removeClass("twitter").html(i);
        jQuery("#gplus-kuas-share-" + e).parent().removeClass("gplus");
        if (typeof gapi != "object") jQuery.getScript("http://apis.google.com/js/plusone.js", function () {
            gapi.plusone.render("gplus-kuas-share-" + e, {
                href: t,
                size: "medium"
            })
        });
        else {
            gapi.plusone.render("gplus-kuas-share-" + e, {
                href: t,
                size: "medium"
            })
        }
        jQuery(this).unbind("mouseenter mouseleave")
    });
    jQuery("a.show_smiley_code_cheat").one("click", e);
    if (kuas_ajax_var.kuas_is_mobile == 0) {
        jQuery(".normalTip").Kuas_ToolTip({
            toolTipClass: "kuas_Tooltip bgColorBaseKuas_y3 borderColorBaseKuas headingColorBaseKuas"
        });
        jQuery(".fixedTip").Kuas_ToolTip({
            fixed: true,
            toolTipClass: "kuas_Tooltip bgColorBaseKuas_y3 borderColorBaseKuas headingColorBaseKuas"
        });
        jQuery(".clickTip").Kuas_ToolTip({
            clickIt: true,
            tipContent: jQuery(this).attr("title")
        })
    }
});
jQuery(document).ready(function (e) {
    jQuery("#FrontPostImage").change(function () {
        kuasReadImageUpload(this, "#KuasImagePreview")
    });
    var t = {
        target: ".message_frontpost",
        beforeSubmit: KuasShowRequestPost,
        success: KuasShowResponsePost,
        dataType: "json",
        url: kuas_ajax_var.kuas_ajax_url
    };
    jQuery("#FrontPressForm").ajaxForm(t)
});
Array.prototype.count = function () {
    return this.length
};
String.prototype.isMatch = function (e) {
    return this.match(e) !== null
};
(function (e) {
    e.fn.clickToggle = function (t, n) {
        var r = [t, n];
        this.data("toggleclicked", 0);
        this.click(function () {
            var t = e(this).data();
            var n = t.toggleclicked;
            e.proxy(r[n], this)();
            t.toggleclicked = (n + 1) % 2
        });
        return this
    };
    e.fn.shuffle = function () {
        return this.each(function () {
            var t = e(this).children();
            return t.length ? e(this).html(e.shuffle(t)) : this
        })
    };
    e.shuffle = function (e) {
        for (var t, n, r = e.length; r; t = parseInt(Math.random() * r), n = e[--r], e[r] = e[t], e[t] = n);
        return e
    }
})(jQuery)