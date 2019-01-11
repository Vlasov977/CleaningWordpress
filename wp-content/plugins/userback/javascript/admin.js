jQuery(document).ready(function($) {
    Userback_Admin = {
        _default: {
            is_active:   0,
            page:        [0],
            widget_code: ''
        },


        init: function() {
            this.bindEvents();

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            // fetch data
            $.post(ajaxurl, {action: 'get_userback'}, $.proxy(function(response) {
                data = $.extend({}, this._default, response.data);

                this.populatePageData(response.page);
                this.setDefaultValue(data);
            }, this));
        },

        bindEvents: function() {
            $('form.setting').on('submit', $.proxy(this.save, this));
        },

        populatePageData: function(data) {
            var select = $('[name="rp-page"]');

            $.each(data, function(index, page) {
                $('<option>').val(page.ID).text(page.post_title || '(no title)').appendTo(select);
            });
        },

        setDefaultValue: function(data) {
            data = $.extend({}, this._default, data);

            $('[name="rp-is-active"]').prop('checked', data.is_active);
            $('[name="rp-page"]').val(data.page);
            $('[name="rp-widget-code"]').val(data.widget_code);
        },

        save: function(e) {
            e.preventDefault();

            var data = this.getSettings();

            // trim
            data.widget_code = $.trim(data.widget_code);

            // add script tags
            if (data.widget_code.substring(0, 7) !== '<script') {
                data.widget_code = '<script>\r\n' + data.widget_code;
            }

            if (data.widget_code.substring(data.widget_code.length - 7, data.widget_code.length) !== 'script>') {
                data.widget_code = data.widget_code + '\r\n</script>';
            }

            $('[name="rp-widget-code"]').val(data.widget_code);

            $('#save').prop('disabled', true);

            $('.save-success').remove();

            $.post(ajaxurl, {action: 'save_userback', data: data}, function(response) {
                $('#save').prop('disabled', false);
                $('<span>').addClass('save-success').text('Saved!').insertAfter($('#save'));
            });
        },

        getSettings: function() {
            return {
                is_active   : $('[name="rp-is-active"]').prop('checked') ? 1 : 0,
                page        : !$('[name="rp-page"]').val() || $.inArray(0, $('[name="rp-page"]').val()) !== -1 ? [0] : $('[name="rp-page"]').val(),
                widget_code : $('[name="rp-widget-code"]').val() ? $('[name="rp-widget-code"]').val() : ''
            };
        }
    };

    Userback_Admin.init();
});