/*
    Custom Selectbox jQuery Plugin
    Tirien.com
    Ver: 2026-03-13

    Examples:
    $("select").tSelectbox({firstIsPlaceholder: true});
*/

$.fn.tSelectbox = function (userConfig) {

    return this.each(function (ind, el) {

        var inputSelect = $(el);

        var config = {
            firstIsPlaceholder: false
        }

        $.extend(config, userConfig);

        var tSelectbox;
        createSelectbox();

        var select = tSelectbox.children(".cs-select");
        var dropdown = tSelectbox.children(".cs-dropdown");
        var searchInput = dropdown.find(".cs-search");
        var optionsList = dropdown.find(".cs-options-list");
        var options = optionsList.children();
        var selected = select.children(".cs-selected");
        var isOpened = false;

        init();

        function inputChanged() {
            var changedValue = $(this).val();

            options.each(function () {

                var t = $(this);

                if (t.attr('value') == changedValue) {
                    selectOption(t);
                }

            });
        }

        function init() {
            inputSelect.change(inputChanged);

            if (inputSelect.find('[selected]').length) {
                selectOption(options.filter('[value="' + inputSelect.find('[selected]').val() + '"]'));
            }
            else if (config.firstIsPlaceholder) {
                let placeholder = options.filter(':first-child');
                selectOption(placeholder);
                placeholder.hide();

                inputSelect.find('[value="' + placeholder.attr('value') + '"]').val('');
                placeholder.attr('value', '')
            }

            $(document).click(closeAll);
        }

        function closeAll() {
            $(".cs-dropdown").slideUp();
        }

        function createSelectbox() {
            var options = "";
            var selectedText = inputSelect.data('placeholder') != undefined ? inputSelect.data('placeholder') : 'select';

            inputSelect.children("option, optgroup").each(function (ind, el) {
                var e = $(el);

                // Handle optgroups
                if (e.is('optgroup')) {
                    options += '<div class="cs-optgroup-label">' + e.attr('label') + '</div>';
                    e.children("option").each(function(i, opt) {
                        var o = $(opt);
                        var data = "";
                        data += o.data('url') ? ' data-url = "' + o.data('url') + '"' : "";
                        var disabled = o.prop('disabled') ? ' disabled' : '';
                        var disabledClass = o.prop('disabled') ? ' cs-disabled' : '';
                        options += '<a ' + data + disabled + ' href="#" class="cs-option' + disabledClass + '" value="' + o.prop('value') + '" >' + o.text() + '</a>';
                    });
                } else {
                    // Handle regular options
                    var data = "";
                    data += e.data('url') ? ' data-url = "' + e.data('url') + '"' : "";
                    var disabled = e.prop('disabled') ? ' disabled' : '';
                    var disabledClass = e.prop('disabled') ? ' cs-disabled' : '';
                    options += '<a ' + data + disabled + ' href="#" class="cs-option' + disabledClass + '" value="' + e.prop('value') + '" >' + e.text() + '</a>';
                }
            });

            tSelectbox = $('<div class="custom-combobox"><div class="cs-select"><span class="cs-left"></span><a href="#" class="cs-selected">' + selectedText + '</a><span class="cs-right"></span><div class="cs-clear"></div></div><div style="display:none" class="cs-dropdown"><input type="text" class="cs-search" placeholder="Search..." /><div class="cs-options-list">' + options + '</div></div></div>');

            inputSelect.after(tSelectbox);
            inputSelect.css({ position: "fixed", left: "100%", opacity: 0 });
            tSelectbox.append(inputSelect);
        }

        function toggleDropdown() {
            if ((isOpened = !dropdown.is(':visible'))) {
                closeAll();
                dropdown.slideDown();
                searchInput.val('').focus();
                options.show();
                focusOption(null, options.filter('[selected]'));
            }
            else {
                closeAll();
            }
        }

        // Search functionality
        searchInput.on('input', function (e) {
            e.stopPropagation();
            var searchTerm = $(this).val().toLowerCase();

            options.each(function () {
                var optionText = $(this).text().toLowerCase();
                if (optionText.indexOf(searchTerm) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        searchInput.on('click', function (e) {
            e.stopPropagation();
        });

        searchInput.on('keydown', function (e) {
            if (e.which === 40) { // Down arrow
                e.preventDefault();
                var firstVisible = options.filter(':visible').first();
                if (firstVisible.length) {
                    firstVisible.focus().addClass('cs-focused');
                }
            } else if (e.which === 27) { // Escape
                closeAll();
                selected.focus();
            }
        });

        tSelectbox.click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown();
        });

        selected.keydown(function (e) {
            var s = options.filter("[selected]");
            var t = $(this);

            switch (e.which) {
                case 38: //up arrow
                    e.preventDefault();
                    selectOption(s.prev());
                    break;
                case 40: //down arrow
                    e.preventDefault();
                    if (isOpened) {
                        focusOption(null, options.filter(':first-child'));
                    }
                    else {
                        e.altKey ? toggleDropdown() : selectOption(s.next());
                    }
                    break;
            }
        });

        options.keydown(function (e) {
            var t = $(this);

            switch (e.which) {
                case 38:
                    e.preventDefault();
                    var n = t.prev();
                    focusOption(t, n);
                    break;
                case 40:
                    e.preventDefault();
                    var n = t.next();
                    focusOption(t, n);
                    break;
            }
        });

        options.click(function (e) {
            e.preventDefault();

            // Don't select disabled options
            if ($(this).hasClass('cs-disabled') || $(this).attr('disabled')) {
                return false;
            }

            selectOption($(this));
            $(".cs-focused").removeClass('cs-focused');
            selected.focus();

            if ($(this).data('url') !== undefined) {
                window.location = $(this).data('url');
            }
        });

        function focusOption(p, n) {
            if (n.length) {
                if (p !== null) {
                    p.removeClass("cs-focused");
                }
                n.focus().addClass("cs-focused");
            }
        }

        function selectOption(o) {
            if (o.length) {
                var v = o.attr('value');

                var other_options = o.attr('selected', true).addClass('cs-hilighted').siblings();
                other_options.attr('selected', false).removeClass('cs-hilighted');

                selected.attr('value', v);
                selected.text(o.text());

                if (inputSelect.val() !== v) {
                    inputSelect.val(v).trigger('change');
                }
            }
        }

    });
}
