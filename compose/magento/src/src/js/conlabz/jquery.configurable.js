;(function ($, window, document, undefined) {

    "use strict";

    var pluginName = "configurable",
        self,
        defaults = {
            selects: '.super-attribute-select',
            url: null
        };

    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        self = this;
        this.init();
    }

    $.extend(Plugin.prototype, {
        init: function () {
            $(this.element).on('change', this.settings.selects, function (e) {
                var selected = true;
                $.each($(self.settings.selects), function() {
                    if (!$(this).val()) {
                        selected = false;
                        return false;
                    }
                });
                if (selected) {
                    $.ajax({
                        dataType: 'json',
                        url: self.settings.url,
                        data:  $(self.element).serialize(),
                        beforeSend: function(e) {
                            $(self.element).trigger('configurable.load.simple.before', [e]);
                        },
                        success: function (json) {
                            $(self.element).trigger('configurable.load.simple', [json]);
                        }
                    });
                }
            });
        }
    });

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);
