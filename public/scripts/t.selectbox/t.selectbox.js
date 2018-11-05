/*
    Custom Selectbox jQuery Plugin
    Tirien.com
    $Rev$
    
    $("#select").tSelectbox([options]);
*/

$.fn.tSelectbox = function(userConfig) {

    return this.each(function(ind, el) {

        var inputSelect = $(el);

        var config = {
            firstIsEmptyText: false
        }

        $.extend(config, userConfig);

        var tSelectbox;
        createSelectbox();

        var select = tSelectbox.children(".cs-select");
        var dropdown = tSelectbox.children(".cs-dropdown");
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
            inputSelect.change(inputChanged);
 
            if (inputSelect.find('[selected]').length){
                selectOption(options.filter('[value="'+inputSelect.find('[selected]').val()+'"]'));
            }
            else if(config.firstIsEmptyText){
                selectOption(options.filter(':first-child'));
                options.filter(':first-child').hide();
            }

            $(document).click(closeAll);
        }

        function closeAll() {
            $(".cs-dropdown").slideUp();
        }

        function createSelectbox()
        {
            var options = "";
            var selectedText = inputSelect.data('placeholder')!=undefined ? inputSelect.data('placeholder') : 'select';

            inputSelect.children("option").each(function(ind, el) {
                var e = $(el);
                var data = "";
                data += e.data('url') ? ' data-url = "'+ e.data('url')+'"' : "";
                options += '<a '+ data +' href="#" class="cs-option" value="' + e.prop('value') + '" >' + e.text() + '</a>';
            });

            tSelectbox = $('<div class="custom-combobox"><div class="cs-select"><span class="cs-left"></span><a href="#" class="cs-selected">' + selectedText + '</a><span class="cs-right"></span><div class="cs-clear"></div></div><div style="display:none" class="cs-dropdown">' + options + '</div></div>');
            
            inputSelect.after(tSelectbox);
            inputSelect.css({position:"absolute", left:"50%", opacity:0});
            tSelectbox.append(inputSelect);
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

        tSelectbox.click(function(e) {
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

            switch (e.which)            {
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
            
            if ( $(this).data('url') !==undefined) {
                window.location = $(this).data('url');
            } 
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
            if (o.length)            {
                var v = o.attr('value');
                var other_options = o.attr('selected', true).addClass('cs-hilighted').siblings();
                other_options.attr('selected', false).removeClass('cs-hilighted');

                selected.attr('value', v);
                selected.text(o.text());

                if (inputSelect.val() !== v){
                    inputSelect.val(v).trigger('change');
                }
            }
        }

    });
}
