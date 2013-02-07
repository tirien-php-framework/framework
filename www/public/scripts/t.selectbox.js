/*
	Custom Selectbox jQuery Plugin
	Tirien.com
	$Rev$
	
	$("#select").customSelectbox();
*/

$.fn.customSelectbox = function(config) {
    return this.each(function(ind, el) {
        var inputSelect = $(el);
        var customSelectbox;
        createCustomSelectbox();
        var select = customSelectbox.children(".cs-select");
        var dropdown = customSelectbox.children(".cs-dropdown");
        var options = dropdown.children();
        var selected = select.children(".cs-selected");
        var isOpened = false;

        init();

        function inputChanged() {
            var changedValue = $(this).val();
            options.each(function() {
                var t = $(this);
                if (t.attr('value') == changedValue) {
                    selectOption(t);
                }
            });
        }

        function init() {
            inputSelect.css({position: 'absolute', left: -9999, top: -9999, visibility: 'hidden'});
            inputSelect.change(inputChanged);
            if (inputSelect.find(':selected').length){
                selectOption(options.filter('[value="'+inputSelect.find(':selected').val()+'"]'));
            }else{
                selectOption(options.filter(':first-child'));
            }
            $(document).click(closeAll);
        }
        function closeAll() {
            $(".cs-dropdown").slideUp();
        }
        function createCustomSelectbox()
        {
            var options = "";
            inputSelect.children("option").each(function(ind, el) {
                var e = $(el);
                options += '<a href="#" class="cs-option" value="' + e.prop('value') + '" >' + e.text() + '</a>';
            });
            customSelectbox = $('<div class="custom-combobox"><div class="cs-select"><span class="cs-left"></span><a href="#" class="cs-selected">select</a><span class="cs-right"></span><div class="cs-clear"></div></div><div style="display:none" class="cs-dropdown">' + options + '</div></div>');
            inputSelect.after(customSelectbox);
        }
        function toggleDropdown() {

            if ((isOpened = !dropdown.is(':visible')))
            {
                closeAll();
                dropdown.slideDown();
                focusOption(null, options.filter('[selected]'));

            }
            else
            {
                closeAll();

            }

        }
        customSelectbox.click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown();
        });
        selected.keydown(function(e) {
            var s = options.filter("[selected]");
            var t = $(this);
            switch (e.which)
            {
                case 38: //up arrow
                    e.preventDefault();
                    selectOption(s.prev());
                    break;
                case 40: //down arrow
                    e.preventDefault();
                    if (isOpened)
                    {
                        focusOption(null, options.filter(':first-child'));
                    }
                    else
                    {
                        e.altKey ? toggleDropdown() : selectOption(s.next());
                    }
                    break;
            }
        });
        options.keydown(function(e) {

            var t = $(this);
            switch (e.which)
            {
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
        options.click(function(e) {

            e.preventDefault();
            selectOption($(this));
            $(".cs-focused").removeClass('cs-focused');
            selected.focus();
        });
        function focusOption(p, n) {
            if (n.length)
            {
                if (p !== null) {
                    p.removeClass("cs-focused");
                }
                n.focus().addClass("cs-focused");
            }
        }
        function selectOption(o) {
            if (o.length)
            {
                var v = o.attr('value');
                o.attr('selected', true).addClass('cs-hilighted').siblings().attr('selected', false).removeClass('cs-hilighted');

                selected.attr('value', v);
                selected.text(o.text());
                if (inputSelect.val() !== v){
                    inputSelect.val(v).trigger('change');
                }
            }
        }
    });
}
