if (!window["WPAC"]) var WPAC = {};

WPAC._Regex = new RegExp("<body[^>]*>((.|\n|\r)*)</body>", "i");

WPAC._ExtractBody = function(html) {
	try {
		return jQuery("<div>"+WPAC._Regex.exec(html)[1]+"</div>");
	} catch (e) {
		return false;
	}
}

WPAC._ShowMessage = function (message, type) {

	var top = WPAC._Options.popupMarginTop + jQuery("#wpadminbar").outerHeight();

	var backgroundColor = WPAC._Options.popupBackgroundColorLoading;
	var textColor = WPAC._Options.popupTextColorLoading;
	if (type == "error") {
		backgroundColor = WPAC._Options.popupBackgroundColorError;
		textColor = WPAC._Options.popupTextColorError;
	} else if (type == "success") {
		backgroundColor = WPAC._Options.popupBackgroundColorSuccess;
		textColor = WPAC._Options.popupTextColorSuccess;
	}
	
	jQuery.blockUI({ 
		message: message, 
		fadeIn: WPAC._Options.popupFadeIn, 
		fadeOut: WPAC._Options.popupFadeOut, 
		timeout:(type == "loading") ? 0 : WPAC._Options.popupTimeout,
		centerY: false,
		centerX: true,
		showOverlay: (type == "loading"),
		css: { 
			width: WPAC._Options.popupWidth + "%",
			left: ((100-WPAC._Options.popupWidth)/2) + "%",
			top: top + "px",
			border: "none", 
			padding: WPAC._Options.popupPadding + "px", 
			backgroundColor: backgroundColor, 
			"-webkit-border-radius": WPAC._Options.popupCornerRadius + "px",
			"-moz-border-radius": WPAC._Options.popupCornerRadius + "px",
			"border-radius": WPAC._Options.popupCornerRadius + "px",
			opacity: WPAC._Options.popupOpacity/100, 
			color: textColor,
			textAlign: WPAC._Options.popupTextAlign,
			cursor: (type == "loading") ? "wait" : "default",
			"font-size": WPAC._Options.popupTextFontSize
		},
		overlayCSS:  { 
			backgroundColor: "#000", 
			opacity: 0
		},
		baseZ: WPAC._Options.popupZindex
	}); 
	
}

WPAC._DebugErrorShown = false;
WPAC._Debug = function(level, message) {

	if (!WPAC._Options.debug) return;

	// Fix console.log.apply for IE9
	// see http://stackoverflow.com/a/5539378/516472
	if (Function.prototype.call && Function.prototype.call.bind && typeof window["console"] != "undefined" && console && typeof console.log == "object" && typeof window["console"][level].apply === "undefined") {
		console[level] = Function.prototype.call.bind(console[level], console);
	}

	if (typeof window["console"] === "undefined" || typeof window["console"][level] === "undefined" || typeof window["console"][level].apply === "undefined") {
		if (!WPAC._DebugErrorShown) alert("Unfortunately the console object is undefined or is not supported in your browser, debugging wp-ajaxify-comments is disabled! Please use Firebug, Google Chrome or Internet Explorer 9 or above with enabled Developer Tools (F12) for debugging wp-ajaxify-comments.");
		WPAC._DebugErrorShown = true;
		return;
	}

	var args = jQuery.merge(["[WP-Ajaxify-Comments] " + message], jQuery.makeArray(arguments).slice(2));
	console[level].apply(console, args);
}

WPAC._DebugSelector = function(elementType, selector, optional) {
	if (!WPAC._Options.debug) return;

	var element = jQuery(selector);
	if (!element.length) {
		WPAC._Debug(optional ? "info" : "error", "Search %s (selector: '%s')... Not found", elementType, selector);
	} else {
		WPAC._Debug("info", "Search %s (selector: '%s')... Found: %o", elementType, selector, element);
	}
}

WPAC._AddQueryParamStringToUrl = function(url, param, value) {
	return new Uri(url).replaceQueryParam(param, value).toString();
}

WPAC._LoadFallbackUrl = function(fallbackUrl) {

	WPAC._ShowMessage(WPAC._Options.textReloadPage, "loading");
	
	var url = WPAC._AddQueryParamStringToUrl(fallbackUrl, "WPACRandom", (new Date()).getTime());
	WPAC._Debug("info", "Something went wrong. Reloading page (URL: '%s')...", url);
	
	var reload = function() { location.href = url; };
	if (!WPAC._Options.debug) {
		reload();
	} else {
		WPAC._Debug("info", "Sleep for 5s to enable analyzing debug messages...");
		window.setTimeout(reload, 5000);
	}
}

WPAC._ScrollToAnchor = function(anchor, updateHash, scrollComplete) {
	scrollComplete = scrollComplete || function() {};
	var anchorElement = jQuery(anchor)
	if (anchorElement.length) {
		WPAC._Debug("info", "Scroll to anchor element %o (scroll speed: %s ms)...", anchorElement, WPAC._Options.scrollSpeed);
		var animateComplete = function() {
			if (updateHash) window.location.hash = anchor; 
			scrollComplete();
		}
		var scrollTargetTopOffset = anchorElement.offset().top
		if (jQuery(window).scrollTop() == scrollTargetTopOffset) {
			animateComplete();
		} else {
			jQuery("html,body").animate({scrollTop: scrollTargetTopOffset}, {
				duration: WPAC._Options.scrollSpeed,
				complete: animateComplete
			});
		}
		return true;
	} else {
		WPAC._Debug("error", "Anchor element not found (selector: '%s')", anchor);
		return false;
	}
}

WPAC._UpdateUrl= function(url) {
	if (url.split("#")[0] == window.location.href.split("#")[0]) {
		return;
	}
	if (window.history.replaceState) {
		window.history.replaceState({}, window.document.title, url);
	} else {
		WPAC._Debug("info", "Browser does not support window.history.replaceState() to update the URL without reloading the page", anchor);
	}
}

WPAC._ReplaceComments = function(data, fallbackUrl, formData) {
	
	var oldCommentsContainer = jQuery(WPAC._Options.selectorCommentsContainer);
	if (!oldCommentsContainer.length) {
		WPAC._Debug("error", "Comment container on current page not found (selector: '%s')", WPAC._Options.selectorCommentsContainer);
		WPAC._LoadFallbackUrl(fallbackUrl);
		return false;
	}
	
	var extractedBody = WPAC._ExtractBody(data);
	if (extractedBody === false) {
		WPAC._Debug("error", "Unsupported server response, unable to extract body (data: '%s')", data);
		WPAC._LoadFallbackUrl(fallbackUrl);
		return false;
	}
	
	WPAC._Callbacks["onBeforeSelectElements"](extractedBody);
	
	var newCommentsContainer = extractedBody.find(WPAC._Options.selectorCommentsContainer);
	if (!newCommentsContainer.length) {
		WPAC._Debug("error", "Comment container on requested page not found (selector: '%s')", WPAC._Options.selectorCommentsContainer);
		WPAC._LoadFallbackUrl(fallbackUrl);
		return false;
	}

	WPAC._Callbacks["onBeforeUpdateComments"]();

	// Update comments container
	oldCommentsContainer.replaceWith(newCommentsContainer);
	
	var form = jQuery(WPAC._Options.selectorCommentForm);
	if (form.length) {

		// Replace comment form (for spam protection plugin compatibility) if comment form is not nested in comments container
		// If comment form is nested in comments container comment form has already been replaced
		if (!newCommentsContainer.find(WPAC._Options.selectorCommentForm).length) {

			WPAC._Debug("info", "Replace comment form...");
			var newCommentForm = extractedBody.find(WPAC._Options.selectorCommentForm);
			if (newCommentForm.length == 0) {
				WPAC._Debug("error", "Comment form on requested page not found (selector: '%s')", WPAC._Options.selectorCommentForm);
				WPAC._LoadFallbackUrl(fallbackUrl);
				return false;
			}
			form.replaceWith(newCommentForm);
		}
		
	} else {

		WPAC._Debug("info", "Try to re-inject comment form...");
	
		// "Re-inject" comment form, if comment form was removed by updating the comments container; could happen 
		// if theme support threaded/nested comments and form tag is not nested in comments container
		// -> Replace WordPress placeholder <div> (#wp-temp-form-div) with respond <div>
		var wpTempFormDiv = jQuery("#wp-temp-form-div");
		if (!wpTempFormDiv.length) {
			WPAC._Debug("error", "WordPress' #wp-temp-form-div container not found", WPAC._Options.selectorRespondContainer);
			WPAC._LoadFallbackUrl(fallbackUrl);
			return false;
		}
		var newRespondContainer = extractedBody.find(WPAC._Options.selectorRespondContainer);
		if (!newRespondContainer.length) {
			WPAC._Debug("error", "Respond container on requested page not found (selector: '%s')", WPAC._Options.selectorRespondContainer);
			WPAC._LoadFallbackUrl(fallbackUrl);
			return false;
		}
		wpTempFormDiv.replaceWith(newRespondContainer);

	}

	if (formData) {
		// Re-inject saved form data
		jQuery.each(formData, function(key, value) {
			var formElement = jQuery("[name='"+value.name+"']", WPAC._Options.selectorCommentForm);
			if (formElement.length != 1 || formElement.val()) return;
			formElement.val(value.value);
		});
	}

	WPAC._Callbacks["onAfterUpdateComments"]();

	return true;
}

WPAC._TestCrossDomainScripting = function(url) {
	if (url.indexOf("http") != 0) return false;
	var domain = window.location.protocol + "//" + window.location.host;
	return (url.indexOf(domain) != 0);
}

WPAC._TestFallbackUrl = function(url) {
	var url = new Uri(location.href); 
	return (url.getQueryParamValue("WPACFallback") && url.getQueryParamValue("WPACRandom"));
}

WPAC._Initialized = false;
WPAC.Init = function() {

	// Test if plugin already has been initialized
	if (WPAC._Initialized) {
		WPAC._Debug("info", "Abort initialization (plugin already initialized)");
		return false;
	}
	WPAC._Initialized = true;
	
	// Assert that environment is set up correctly
	if (!WPAC._Options || !WPAC._Callbacks) {
		WPAC._Debug("error", "Something unexpected happened, initialization failed. Please try to reinstall the plugin.");
		return false;
	}

	// Debug infos
	WPAC._Debug("info", "Initializing version %s", WPAC._Options.version);

	WPAC._Callbacks["onBeforeSelectElements"](jQuery(document));
	
	// Skip initialization if comments are not enabled
	if (!WPAC._Options.commentsEnabled) {
		WPAC._Debug("info", "Abort initialization (comments are not enabled on current page)");
		return false;
	}
	
	// Debug infos
	if (WPAC._Options.debug) {
		if (!jQuery || !jQuery.fn || !jQuery.fn.jquery) {
			WPAC._Debug("error", "jQuery not found, abort initialization. Please try to reinstall the plugin.");
			return false;
		}
		WPAC._Debug("info", "Found jQuery %s", jQuery.fn.jquery);
		if (!jQuery.blockUI || !jQuery.blockUI.version) {
			WPAC._Debug("error", "jQuery blockUI not found, abort initialization. Please try to reinstall the plugin.");
			return false;
		}
		WPAC._Debug("info", "Found jQuery blockUI %s", jQuery.blockUI.version);
		if (!jQuery.idleTimer) {
			WPAC._Debug("error", "jQuery Idle Timer plugin not found, abort initialization. Please try to reinstall the plugin.");
			return false;
		}
		WPAC._Debug("info", "Found jQuery Idle Timer plugin");
		WPAC._DebugSelector("comment form", WPAC._Options.selectorCommentForm);
		WPAC._DebugSelector("comments container", WPAC._Options.selectorCommentsContainer);
		WPAC._DebugSelector("respond container", WPAC._Options.selectorRespondContainer);
		WPAC._DebugSelector("comment paging links", WPAC._Options.selectorCommentPagingLinks, true);
		if (WPAC._Options.autoUpdateIdleTime > 0) WPAC._Debug("info", "Auto updating comments enabled (idle time: %s)", WPAC._Options.autoUpdateIdleTime);
		WPAC._Debug("info", "Initialization completed");
	}
	
	// Intercept comment form submit
	var formSubmitHandler = function (event) {
		
		var form = jQuery(this);

		var submitUrl = form.attr("action");

		// Cancel AJAX request if cross-domain scripting is detected
		if (WPAC._TestCrossDomainScripting(submitUrl)) {
			if (WPAC._Options.debug && !form.data("submitCrossDomain")) {
				WPAC._Debug("error", "Cross-domain scripting detected (submit url: '%s'), cancel AJAX request", submitUrl);
				WPAC._Debug("info", "Sleep for 5s to enable analyzing debug messages...");
				event.preventDefault();
				form.data("submitCrossDomain", true)
				window.setTimeout(function() { jQuery('#submit', form).remove(); form.submit(); }, 5000);
			}
			return;
		}
		
		// Stop default event handling
		event.preventDefault();
	
		// Show loading info
		WPAC._ShowMessage(WPAC._Options.textPostComment, "loading");

		WPAC._Callbacks["onBeforeSubmitComment"]();
		
		var request = jQuery.ajax({
			url: submitUrl,
			type: "POST",
			data: form.serialize(),
			beforeSend: function(xhr){ xhr.setRequestHeader('X-WPAC-REQUEST', '1'); },
			success: function (data) {
 
				WPAC._Debug("info", "Comment has been posted");

				// Get info from response header
				var commentUrl = request.getResponseHeader("X-WPAC-URL");
				WPAC._Debug("info", "Found comment URL '%s' in X-WPAC-URL header.", commentUrl);
				var unapproved = request.getResponseHeader("X-WPAC-UNAPPROVED");
				WPAC._Debug("info", "Found unapproved state '%s' in X-WPAC-UNAPPROVED", unapproved);
				
				// Show success message
				WPAC._ShowMessage(unapproved == '1' ? WPAC._Options.textPostedUnapproved : WPAC._Options.textPosted, "success");

				// Replace comments (and return if replacing failed)
				if (!WPAC._ReplaceComments(data, commentUrl)) return;
				
				// Smooth scroll to comment url and update browser url
				if (commentUrl) {
						
					if (!WPAC._Options.disableUrlUpdate)
						WPAC._UpdateUrl(commentUrl);
					
					var anchor = commentUrl.indexOf("#") >= 0 ? commentUrl.substr(commentUrl.indexOf("#")) : null;
					if (anchor) {
						WPAC._Debug("info", "Anchor '%s' extracted from comment URL '%s'", anchor, commentUrl);
						WPAC._ScrollToAnchor(anchor, !WPAC._Options.disableUrlUpdate);
					}
				}
				
			},
			error: function (jqXhr, textStatus, errorThrown) {

				WPAC._Debug("info", "Comment has not been posted");
				WPAC._Debug("info", "Try to extract error message (selector: '%s')...", WPAC._Options.selectorErrorContainer);
			
				// Extract error message
				var extractedBody = WPAC._ExtractBody(jqXhr.responseText);
				if (extractedBody !== false) {
					var errorMessage = extractedBody.find(WPAC._Options.selectorErrorContainer);
					if (errorMessage.length) {
						errorMessage = errorMessage.html();
						WPAC._Debug("info", "Error message '%s' successfully extracted", errorMessage);
						WPAC._ShowMessage(errorMessage, "error");
						return;
					}
				}

				WPAC._Debug("error", "Error message could not be extracted, use error message '%s'.", WPAC._Options.textUnknownError);
				WPAC._ShowMessage(WPAC._Options.textUnknownError, "error");
			}
  	    });
	  
	};

	var pagingSubmitHandler = function(event) {
		var href = jQuery(this).attr("href");
		if (href) {
			event.preventDefault();
			WPAC.LoadComments(href);
		}
	}
	
	// Get addHandler method
	if (jQuery(document).on) {
		// jQuery 1.7+
		var addHandler = function(event, selector, handler) {
			jQuery(document).on(event, selector, handler)
		}
	} else if (jQuery(document).delegate) {
		// jQuery 1.4.3+
		var addHandler = function(event, selector, handler) {
			jQuery(document).delegate(selector, event, handler)
		}
	} else {
		// jQuery 1.3+
		var addHandler = function(event, selector, handler) {
			jQuery(selector).live(event, handler)
		}
	}
	
	// Add handlers
	addHandler("submit", WPAC._Options.selectorCommentForm, formSubmitHandler)
	addHandler("click", WPAC._Options.selectorCommentPagingLinks, pagingSubmitHandler);
	
	// Set up idle timer
	WPAC._InitIdleTimer();
	
	return true;
}

WPAC._OnIdle = function() {
	WPAC.RefreshComments({ success: WPAC._InitIdleTimer, scrollToAnchor: false	});
};

WPAC._InitIdleTimer = function() {
	if (WPAC._Options.autoUpdateIdleTime <= 0) return;

	if (WPAC._TestFallbackUrl(location.href)) {
		WPAC._Debug("error", "Fallback URL was detected (url: '%s'), cancel init idle timer", location.href);
		return;
	}
	
	jQuery(document).idleTimer("destroy");
	jQuery(document).idleTimer(WPAC._Options.autoUpdateIdleTime);
	jQuery(document).on("idle.idleTimer", WPAC._OnIdle);
}

WPAC.RefreshComments = function(options) {
	var url = location.href;
	
	if (WPAC._TestFallbackUrl(location.href)) {
		WPAC._Debug("error", "Fallback URL was detected (url: '%s'), cancel AJAX request", url);
		return false;   
	}
	
	return WPAC.LoadComments(url, options)
}

WPAC.LoadComments = function(url, options) {

	// Cancel AJAX request if cross-domain scripting is detected
	if (WPAC._TestCrossDomainScripting(url)) {
		WPAC._Debug("error", "Cross-domain scripting detected (url: '%s'), cancel AJAX request", url);
		return false;
	}

	// Convert boolean parameter (used in version <0.14.0
	if (typeof(options) == "boolean")
		options = {scrollToAnchor: options}

	// Set default options
	options = jQuery.extend({
		scrollToAnchor: true,
		showLoadingInfo: true,
		updateUrl: !WPAC._Options.disableUrlUpdate,
		success: function() {}
	}, options || {});	
	
	// Save form data
	var formData = jQuery(WPAC._Options.selectorCommentForm).serializeArray();
	
	// Show loading info
	if (options.showLoadingInfo)
		WPAC._ShowMessage(WPAC._Options.textRefreshComments, "loading");
	
	var request = jQuery.ajax({
		url: url,
		type: "POST",
		beforeSend: function(xhr){ xhr.setRequestHeader('X-WPAC-REQUEST', '1'); },
		success: function (data) {

			// Replace comments (and return if replacing failed)
			if (!WPAC._ReplaceComments(data, WPAC._AddQueryParamStringToUrl(url, "WPACFallback", 1), formData)) return;
			
			if (options.updateUrl) WPAC._UpdateUrl(url);

			// Scroll to anchor
			var waitForScrollToAnchor = false;
			if (options.scrollToAnchor) {
				var anchor = url.indexOf("#") >= 0 ? url.substr(url.indexOf("#")) : null;
				if (anchor) {
					WPAC._Debug("info", "Anchor '%s' extracted from current URL", anchor);
					if (WPAC._ScrollToAnchor(anchor, options.updateUrl, function() { options.success(); } )) {
						waitForScrollToAnchor = true;
					}
				}
			}
			
			// Unblock UI
			jQuery.unblockUI();
			
			if (!waitForScrollToAnchor) options.success();
		},
		error: function() {
			WPAC._LoadFallbackUrl(WPAC._AddQueryParamStringToUrl(window.location.href, "WPACFallback", "1"))
		} 
		
	});
	
	return true;
}

jQuery(function() {
	var initSuccesful = WPAC.Init();
	if (WPAC._Options.loadCommentsAsync) {
		if (!initSuccesful) {
			WPAC._LoadFallbackUrl(WPAC._AddQueryParamStringToUrl(window.location.href, "WPACFallback", "1"))
			return;
		} 
		WPAC._Debug("info", "Loading comments asynchronously with secondary AJAX request");
		WPAC.RefreshComments();
	} 
});

function wpac_init() {
	WPAC._Debug("info", "wpac_init() is deprecated, please use WPAC.Init()");
	WPAC.Init();
}