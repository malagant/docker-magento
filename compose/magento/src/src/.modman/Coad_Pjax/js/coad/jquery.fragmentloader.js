;(function ($, window, document, undefined) {

    "use strict";

    var self,
        pluginName = "fragmentLoader",
        cache = {},
        context,
        defaults = {
            fragments: [],
            beforeSend: function() {},
            success: function() {}
    };

    function Plugin(element, selector, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this.selector = selector;
        this._defaults = defaults;
        this._name = pluginName;
        self = this;
        this.init();
    }

    $.extend(Plugin.prototype, {
        init: function () {            
            this.bindHistoryEvent();
            $(this.element).on('click', this.selector, function(e) {
                e.preventDefault();
                History.pushState({
                    type: 'loadFragments'
                }, $('title').text(), $(this).attr('href'));
                self.context = this;
            });
        },
        bindHistoryEvent: function() {
            History.Adapter.bind(window, 'statechange', function() {
                var state = History.getState();
                if (state.data.hasOwnProperty('type') && state.data.type === 'loadFragments') {
                    self.loadFragments(state);
                }
            });
        },
        loadFragments: function(state) {
            var fragments = this.settings.fragments;
            if (this.isCached(state.id)) {
                return self.applyBlocks(this.getCacheItem(state.id));
            }
            $.ajax({
                url: state.url,
                dataType: 'json',
                headers: {
                    'X-Fragment-Loader': 1,
                    'X-Fragments': fragments.join(',')
                },
                beforeSend: self.settings.beforeSend,
                success: function(response, status) {
                    self.applyBlocks(response);
                    self.setCacheItem(state.id, response);                    
                }
            });
        },
        applyBlocks: function(response) {
            for (var blockName in response) {
                $('[data-pjax-block="' + blockName + '"]').html(
                    $(response[blockName]).html()
                );
            }
            self.settings.success(response, self.context);
        },
        setCacheItem: function(key, data) {
            cache[key] = data;
        },
        isCached: function(key) {
            return cache.hasOwnProperty(key);
        },
        getCacheItem: function(key) {
            return cache[key];
        }
    });

    $.fn[pluginName] = function (selector, options) {
        return this.each(function () {
            $.data(this, "plugin_" + pluginName, new Plugin(this, selector, options));
        });
    };

})(jQuery, window, document);
