elgg.provide('elgg.ui');

elgg.ui.init = function () {
	// @todo we need better documentation for this hack
	// iOS Hover Event Class Fix
	$('.elgg-page').attr("onclick", "return true");

	// add user hover menus
	elgg.ui.initHoverMenu();

	//if the user clicks a system message, make it disappear
	$('.elgg-system-messages li').live('click', function() {
		$(this).stop().fadeOut('fast');
	});

	$('.elgg-system-messages li').animate({opacity: 0.9}, 6000);
	$('.elgg-system-messages li.elgg-state-success').fadeOut('slow');

	$('[rel=toggle]').live('click', elgg.ui.toggles);

	$('.elgg-menu-page .elgg-menu-parent').live('click', elgg.ui.toggleMenu);

    $('*[data-confirm], .elgg-requires-confirmation').live('click', elgg.ui.requiresConfirmation);
    if ($('.elgg-requires-confirmation').length > 0) {
        elgg.deprecated_notice('Use of .elgg-requires-confirmation is deprecated by data-confirm', '1.10');
    }

	$('.elgg-autofocus').focus();
	if ($('.elgg-autofocus').length > 0) {
		elgg.deprecated_notice('Use of .elgg-autofocus is deprecated by html5 autofocus', 1.9);
	}

	elgg.ui.initAccessInputs();
};

/**
 * Toggles an element based on clicking a separate element
 *
 * Use rel="toggle" on the toggler element
 * Set the href to target the item you want to toggle (<a rel="toggle" href="#id-of-target">)
 * or use data-toggle-selector="your_jquery_selector" to have an advanced selection method
 * 
 * By default elements perform a slideToggle. 
 * If you want a normal toggle (hide/show) you can add data-toggle-slide="0" on the elements to prevent a slide.
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.toggles = function(event) {
	event.preventDefault();
	var $this = $(this),
		target = $this.data().toggleSelector;
	
	if (!target) {
		// @todo we can switch to elgg.getSelectorFromUrlFragment() in 1.x if
		// we also extend it to support href=".some-class"
		target = $this.attr('href');
	}

	$this.toggleClass('elgg-state-active');

	$(target).each(function(index, elem) {
		var $elem = $(elem);
		if ($elem.data().toggleSlide != false) {
			$elem.slideToggle('medium');
		} else {
			$elem.toggle();
		}
	});
};

/**
 * Toggles a child menu when the parent is clicked
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.toggleMenu = function(event) {
	$(this).siblings().slideToggle('medium');
	$(this).toggleClass('elgg-menu-closed elgg-menu-opened');
	event.preventDefault();
};

/**
 * Initialize the hover menu
 *
 * @param {Object} parent
 * @return void
 */
elgg.ui.initHoverMenu = function(parent) {
	if (!parent) {
		parent = document;
	}

	// avatar image menu link
	$(parent).find(".elgg-avatar").live('mouseover', function() {
		$(this).children(".elgg-icon-hover-menu").show();
	})
	.live('mouseout', function() {
		$(this).children(".elgg-icon-hover-menu").hide();
	});


	// avatar contextual menu
	$(".elgg-avatar > .elgg-icon-hover-menu").live('click', function(e) {
		// check if we've attached the menu to this element already
		var $hovermenu = $(this).data('hovermenu') || null;

		if (!$hovermenu) {
			$hovermenu = $(this).parent().find(".elgg-menu-hover");
			$(this).data('hovermenu', $hovermenu);
		}

		// close hovermenu if arrow is clicked & menu already open
		if ($hovermenu.css('display') == "block") {
			$hovermenu.fadeOut();
		} else {
			$avatar = $(this).closest(".elgg-avatar");

			// @todo Use jQuery-ui position library instead -- much simpler
			var offset = $avatar.offset();
			var top = $avatar.height() + offset.top + 'px';
			var left = $avatar.width() - 15 + offset.left + 'px';

			$hovermenu.appendTo('body')
					.css('position', 'absolute')
					.css("top", top)
					.css("left", left)
					.fadeIn('normal');
		}

		// hide any other open hover menus
		$(".elgg-menu-hover:visible").not($hovermenu).fadeOut();
	});

	// hide avatar menu when user clicks elsewhere
	$(document).click(function(event) {
		if ($(event.target).parents(".elgg-avatar").length === 0) {
			$(".elgg-menu-hover").fadeOut();
		}
	});
};

/**
 * Calls a confirm() and prevents default if denied.
 *
 * @param {Object} e
 * @return void
 */
elgg.ui.requiresConfirmation = function(e) {
	var confirmText = $(this).data('confirm') || elgg.echo('question:areyousure');
	if (!confirm(confirmText)) {
		e.preventDefault();
	}
};

/**
 * Initialize the date picker
 *
 * Uses the class .elgg-input-date as the selector.
 *
 * If the class .elgg-input-timestamp is set on the input element, the onSelect
 * method converts the date text to a unix timestamp in seconds. That value is
 * stored in a hidden element indicated by the id on the input field.
 *
 * @return void
 * @requires jqueryui.datepicker
 */
elgg.ui.initDatePicker = function() {
	function loadDatePicker() {
		$('.elgg-input-date').datepicker({
			// ISO-8601
			dateFormat: 'yy-mm-dd',
			onSelect: function(dateText) {
				if ($(this).is('.elgg-input-timestamp')) {
					// convert to unix timestamp
					var dateParts = dateText.split("-");
					var timestamp = Date.UTC(dateParts[0], dateParts[1] - 1, dateParts[2]);
					timestamp = timestamp / 1000;

					var id = $(this).attr('id');
					$('input[name="' + id + '"]').val(timestamp);
				}
			},
			nextText: '&#xBB;',
			prevText: '&#xAB;',
			changeMonth: true,
			changeYear: true
		});
	}

	if (!$('.elgg-input-date').length) {
		return;
	}

	if (elgg.get_language() == 'en') {
		loadDatePicker();
	} else {
		// load language first
		elgg.get({
			url: elgg.config.wwwroot + 'vendors/jquery/i18n/jquery.ui.datepicker-'+ elgg.get_language() +'.js',
			dataType: "script",
			cache: true,
			success: loadDatePicker,
			error: loadDatePicker // english language is already loaded.
		});
	}
};

/**
 * This function registers two menu items that are actions that are the opposite
 * of each other and ajaxifies them. E.g. like/unlike, friend/unfriend, ban/unban, etc.
 *
 * Note the menu item names must be given in their normalized form. So if the
 * name is remove_friend, you should call this function with "remove-friend" instead.
 */
elgg.ui.registerTogglableMenuItems = function(menuItemNameA, menuItemNameB) {
	// Handles clicking the first button.
	$('.elgg-menu-item-' + menuItemNameA + ' a').live('click', function() {
		var $menu = $(this).closest('.elgg-menu');

		// Be optimistic about success
		elgg.ui.toggleMenuItems($menu, menuItemNameB, menuItemNameA);

		// Send the ajax request
		elgg.action($(this).attr('href'), {
			success: function(json) {
				if (json.system_messages.error.length) {
					// Something went wrong, so undo the optimistic changes
					elgg.ui.toggleMenuItems($menu, menuItemNameA, menuItemNameB);
				}
			},
			error: function() {
				// Something went wrong, so undo the optimistic changes
				elgg.ui.toggleMenuItems($menu, menuItemNameA, menuItemNameB);
			}
		});

		// Don't want to actually click the link
		return false;
	});

	// Handles clicking the second button
	$('.elgg-menu-item-' + menuItemNameB + ' a').live('click', function() {
		var $menu = $(this).closest('.elgg-menu');

		// Be optimistic about success
		elgg.ui.toggleMenuItems($menu, menuItemNameA, menuItemNameB);

		// Send the ajax request
		elgg.action($(this).attr('href'), {
			success: function(json) {
				if (json.system_messages.error.length) {
					// Something went wrong, so undo the optimistic changes
					elgg.ui.toggleMenuItems($menu, menuItemNameB, menuItemNameA);
				}
			},
			error: function() {
				// Something went wrong, so undo the optimistic changes
				elgg.ui.toggleMenuItems($menu, menuItemNameB, menuItemNameA);
			}
		});

		// Don't want to actually click the link
		return false;
	});
};

elgg.ui.toggleMenuItems = function($menu, nameOfItemToShow, nameOfItemToHide) {
    $menu.find('.elgg-menu-item-' + nameOfItemToShow).removeClass('hidden').find('a').focus();
    $menu.find('.elgg-menu-item-' + nameOfItemToHide).addClass('hidden');
};

/**
 * Initialize input/access for dynamic display of members only warning
 *
 * If a select.elgg-input-access is accompanied by a note (.elgg-input-access-membersonly),
 * then hide the note when the select value is PRIVATE or group members.
 *
 * @return void
 * @since 1.9.0
 */
elgg.ui.initAccessInputs = function () {
	$('.elgg-input-access').each(function () {
		function updateMembersonlyNote() {
			var val = $select.val();
			if (val != acl && val !== 0) {
				// .show() failed in Chrome. Maybe a float/jQuery bug
				$note.css('visibility', 'visible');
			} else {
				$note.css('visibility', 'hidden');
			}
		}
		var $select = $(this),
			acl = $select.data('group-acl'),
			$note = $('.elgg-input-access-membersonly', this.parentNode);
		if ($note) {
			updateMembersonlyNote();
			$select.change(updateMembersonlyNote);
		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.ui.init);
elgg.register_hook_handler('init', 'system', elgg.ui.initDatePicker);
elgg.ui.registerTogglableMenuItems('add-friend', 'remove-friend');
