/**
 * Copyright © 2018 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */
function setSelectionRange(input, selectionStart, selectionEnd) {
    if (input.setSelectionRange) {
        input.focus();
        input.setSelectionRange(selectionStart, selectionEnd);
    } else if (input.createTextRange) {
        var range = input.createTextRange();
        range.collapse(true);
        range.moveEnd('character', selectionEnd);
        range.moveStart('character', selectionStart);
        range.select();
    }
}

function replaceSelection(input, replaceString) {
    if (input.setSelectionRange) {
        var selectionStart = input.selectionStart;
        var selectionEnd = input.selectionEnd;
        input.value = input.value.substring(0, selectionStart) + replaceString + input.value.substring(selectionEnd);

        if (selectionStart != selectionEnd) {
            setSelectionRange(input, selectionStart, selectionStart + replaceString.length);
        } else {
            setSelectionRange(input, selectionStart + replaceString.length, selectionStart + replaceString.length);
        }

    } else if (document.selection) {
        var range = document.selection.createRange();

        if (range.parentElement() == input) {
            var isCollapsed = range.text == '';
            range.text = replaceString;

            if (!isCollapsed) {
                range.moveStart('character', -replaceString.length);
                range.select();
            }
        }
    }
}

// We are going to catch the TAB key so that we can use it, Hooray!
function catchTab(item, e) {
    if (navigator.userAgent.match("Gecko")) {
        c = e.which;
    } else {
        c = e.keyCode;
    }
    if (c == 9) {
        replaceSelection(item, String.fromCharCode(9));
        setTimeout("document.getElementById('" + item.id + "').focus();", 0);
        return false;
    }
}

var simplegoogleshopping = {
    setValues: function (selector) {
        selection = new Array;
        selector.select('INPUT[type=checkbox]').each(
            function (i) {
                if (selector.id == 'attributes-selector') {
                    attribute = {};
                    attribute.line = i.readAttribute('identifier');
                    attribute.checked = i.checked;
                    attribute.statement = i.next().value;
                    attribute.parenthesis_open = i.next().next().value;
                    attribute.code = i.next().next().next().value;
                    attribute.condition = i.next().next().next().next().value;
                    attribute.value = i.next().next().next().next().next().next().value;
                    attribute.parenthesis_close = i.next().next().next().next().next().next().next().value;
                    selection.push(attribute);
                } else if (i.checked == true)
                    selection.push(i.readAttribute('identifier'));
            }
        );
        switch (selector.id) {
            case 'type-ids-selector':
                $('simplegoogleshopping_type_ids').value = selection.join(',');
                break;
            case 'visibility-selector':
                $('simplegoogleshopping_visibility').value = selection.join(',');
                break;
            case 'attribute-sets-selector':
                $('simplegoogleshopping_attribute_sets').value = selection.join(',');
                break;
            case 'attributes-selector' :
                $('simplegoogleshopping_attributes').value = Object.toJSON(selection);
                break;
        }
    },
    mappingStr: "your google product category",
    // Set mode txt / csv
    clearFields: function () {
        $('simplegoogleshopping_header').value = '';
        $('simplegoogleshopping_xmlitempattern').value = '';
        $('simplegoogleshopping_footer').value = '';
    },
    switchStatus: function () {
        if ($('blackbox-console').hasClassName('arr_down')) {
            $('blackbox-console').removeClassName('arr_down');
            $('blackbox-console').addClassName('arr_up');
            $$('#blackbox-console #page')[0].setStyle({"visibility": "visible"});
            setCookie('blackboxConsole-status', "visible", 7);
        } else {
            $('blackbox-console').removeClassName('arr_up');
            $('blackbox-console').addClassName('arr_down');
            $$('#blackbox-console #page')[0].setStyle({"visibility": "hidden"});
            setCookie('blackboxConsole-status', "hidden", 7);
        }
    },
    // Fold/unfold the preview
    storage: {
        top: null,
        left: null,
        width: null,
        height: null
    },
    checkLibrary: function () {
        $('page').addClassName('loader');
        $('page').childElements()[0].setStyle({display: 'none'});
        url = $('library_url').getValue();

        // Update textarea if mode text
        new Ajax.Request(
            url, {
                onSuccess: function (response) {
                    $('page').childElements()[0].setStyle({display: 'block'});
                    $('page').removeClassName('loader');

                    $$('.CodeMirror')[0].update(response.responseText);
                }
            }
        );
    },
    checkReport: function () {
        $('page').addClassName('loader');
        $('page').childElements()[0].setStyle({display: 'none'});
        url = $('report_url').getValue();

        data = Form.serialize($$('FORM')[0], true);

        new Ajax.Request(
            url, {
                parameters: data,
                method: 'post',
                onSuccess: function (response) {
                    $('page').childElements()[0].setStyle({display: 'block'});
                    $('page').removeClassName('loader');

                    $$('.CodeMirror')[0].update(response.responseText);
                }
            }
        );
    },
    switchSize: function () {
        $('blackbox-console').addClassName('resize');
        if ($('blackbox-console').hasClassName('reduce')) {
            $('blackbox-console').removeClassName('reduce');
            $('blackbox-console').addClassName('full');
            simplegoogleshopping.storage.top = $('blackbox-console').getStyle('top');
            simplegoogleshopping.storage.left = $('blackbox-console').getStyle('left');
            $('blackbox-console').setStyle({top: '10px', left: '10px'});
            simplegoogleshopping.storage.width = $('page').getStyle('width');
            simplegoogleshopping.storage.height = $('page').getStyle('height');
            $('page').setStyle({
                width: (document.viewport.getDimensions().width - 40) + 'px',
                height: (document.viewport.getDimensions().height - 150) + 'px'
            });
            setCookie('blackboxConsole-screen', "full", 7);
        } else {
            $('blackbox-console').removeClassName('full');
            $('blackbox-console').addClassName('reduce');
            $('blackbox-console').setStyle({
                top: simplegoogleshopping.storage.top,
                left: simplegoogleshopping.storage.left
            });
            $('page').setStyle({
                width: simplegoogleshopping.storage.width,
                height: simplegoogleshopping.storage.height
            });
            setCookie('blackboxConsole-screen', "mini", 7);
        }
        setTimeout(
            function () {
                $('blackbox-console').removeClassName('resize');
            }, 300
        );
    },
    changeSize: function () {
        __width = $('blackbox-console').select("#page")[0].getStyle('width').replace("px", "");
        __height = $('blackbox-console').select("#page")[0].getStyle('height').replace("px", "");
        setCookie('blackboxConsole-width', __width, 7);
        setCookie('blackboxConsole-height', __height, 7);
    },
    changePosition: function () {
        __top = $('blackbox-console').getStyle('top').replace("px", "");
        __left = $('blackbox-console').getStyle('left').replace("px", "");
        setCookie('blackboxConsole-top', __top, 7);
        setCookie('blackboxConsole-left', __left, 7);
    },
    // Update data
    checkSyntax: function () {
        // nom du fichier
        $('blackbox-console').select('.feedname')[0].update($('simplegoogleshopping_filename').value);

        header = '<?xml version="1.0" encoding="utf-8" ?>' + "\n";
        header += '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' + "\n";
        header += '<channel>' + "\n";
        header += '<title>' + $('simplegoogleshopping_title').value + "</title>\n";
        header += '<link>' + $('simplegoogleshopping_url').value + "</link>\n";
        header += '<description>' + $('simplegoogleshopping_description').value + "</description>\n";

        footer = '</channel>\n';
        footer += '</rss>';

        simplegoogleshopping.CodeMirror = CodeMirror(
            function (elt) {
                $$('.CodeMirror')[0].parentNode.replaceChild(elt, $$('.CodeMirror')[0]);
            }, {
                value: header + "<item>\n" + $('simplegoogleshopping_xmlitempattern').value + "\n</item>\n" + footer,
                mode: 'xml',
                readOnly: true
            }
        );

        simplegoogleshopping.enlightSyntax();
    },
    enlightSyntax: function () {
        clearTimeout(simplegoogleshopping.timer);
        simplegoogleshopping.timer = setTimeout(
            function () {
                $$('.cm-wyomind').each(
                    function (cm) {
                        cm.update(simplegoogleshopping.enlighter(cm.innerHTML));
                    }
                );
            }, 150
        );
    },
    updatePreview: function (is_report) {
        // nom du fichier
        $('blackbox-console').select('.feedname')[0].update($('simplegoogleshopping_filename').value);

        $('page').addClassName('loader');
        $('page').childElements()[0].setStyle({display: 'none'});

        url = $('sample_url').getValue();
        data = Form.serialize($$('FORM')[0], true);

        new Ajax.Request(
            url, {
                parameters: data,
                method: 'post',
                onSuccess: function (response) {
                    $('page').childElements()[0].setStyle({display: 'block'});
                    $('page').removeClassName('loader');

                    if (response.responseText.indexOf("<!DOCTYPE html") == -1) {
                        simplegoogleshopping.CodeMirror = CodeMirror(
                            function (elt) {
                                $$('.CodeMirror')[0].parentNode.replaceChild(elt, $$('.CodeMirror')[0]);
                            }, {
                                value: response.responseText,
                                mode: 'xml',
                                readOnly: true
                            }
                        );
                    } else {
                        $$('.CodeMirror')[0].update(response.responseText);
                    }
                }
            }
        );
    },
    enlighter: function (text) {
        text = text.replace(/<([^?^!]{1}|[\/]{1})(.[^>]*)>/g, "<span class='blue'>" + "<$1$2>".escapeHTML() + "</span>");

        // comments
        text = text.replace(/<!--/g, "Â¤");
        text = text.replace(/-->/g, "Â¤");
        text = text.replace(/Â¤([^Â¤]*)Â¤/g, "<span class='green'>" + "<!--$1-->".escapeHTML() + "</span>");
        // php code
        text = text.replace(/<\?/g, "Â¤");
        text = text.replace(/\?>/g, "Â¤");
        text = text.replace(/Â¤([^Â¤]*)Â¤/g, "<span class='orange'>" + "<?$1?>".escapeHTML() + "</span>");
        // superattribute
        text = text.replace(/\{(G:[^\s}[:]*)(\sparent|\sgrouped|\sconfigurable|\sbundle)?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?\}/g, "<span class='purple'>{$1<span class='grey'>$2</span>$4<span class='green'>$5</span>$7<span class='green'>$8</span>$10<span class='green'>$11</span>$13<span class='green'>$14</span>$16<span class='green'>$17</span>$19<span class='green'>$20</span>}</span>");
        // superattribute
        text = text.replace(/\{(SC:[^\s}[:]*)(\sparent|\sgrouped|\sconfigurable|\sbundle)?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?\}/g, "<span class='orangered '>{$1<span class='grey'>$2</span>$4<span class='green'>$5</span>$7<span class='green'>$8</span>$10<span class='green'>$11</span>$13<span class='green'>$14</span>$16<span class='green'>$17</span>$19<span class='green'>$20</span>}</span>");
        text = text.replace(/\{(sc:[^\s}[:]*)(\sparent|\sgrouped|\sconfigurable|\sbundle)?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?\}/g, "<span class='orangered '>{$1<span class='grey'>$2</span>$4<span class='green'>$5</span>$7<span class='green'>$8</span>$10<span class='green'>$11</span>$13<span class='green'>$14</span>$16<span class='green'>$17</span>$19<span class='green'>$20</span>}</span>");
        // attributes + 6 options
        regexp = "\\{([^}[:|]*)(\\sparent|\\sgrouped|\\sconfigurable|\\sbundle)?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?";
        _regexp = "((\\s?\\|\\s?)([^}[:|]*))?(\\sparent|\\sgrouped|\\sconfigurable|\\sbundle)?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?((,)(\\[.[^\\]]*\\]))?";
        pattern = "<span class='pink'>{$1<span class='grey'>$2</span>$4<span class='green'>$5</span>$7<span class='green'>$8</span>$10<span class='green'>$11</span>$13<span class='green'>$14</span>$16<span class='green'>$17</span>$19<span class='green'>$20</span>";
        for (i = 1; i < 4; i++) {
            x = 22 * i;
            regexp += _regexp;
            pattern += "<span class='or'>$" + x + "</span>$" + (x + 1) + "<span class='grey'>$" + (x + 2) + "</span>$" + (x + 4) + "<span class='green'>$" + (x + 5) + "</span>$" + (x + 7) + "<span class='green'>$" + (x + 8) + "</span>$" + (x + 10) + "<span class='green'>$" + (x + 11) + "</span>$" + (x + 13) + "<span class='green'>$" + (x + 14) + "</span>$" + (x + 16) + "<span class='green'>$" + (x + 17) + "</span>$" + (x + 19) + "<span class='green'>$" + (x + 20) + "</span>";

        }
        pattern += "}</span>";
        regexp += "\\}";
        regexp = new RegExp(regexp, "g");

        text = text.replace(regexp, pattern); // attributs + options bool
        text = text.replace(/\{([^}[:]*)(\sparent|\sgrouped|\sconfigurable|\sbundle)?(\?)(\[[^\]]*\])(:)(\[[^\]]*\])?(:)?(\[[^\]]*\])\}/g, "<span class='pink'>{$1<span class='grey'>$2</span>$3<span class='green'>$4</span>$5<span class='red'>$6</span>$7<span class='orangered'>$8</span>}</span>");
        return text;
    },
    currentMode: 1
};

var SGS_categories = {
    autoCompleteUrl: "",
    tree: {},
    waitFor: function (elt, callback) {
        var initializer = null;
        initializer = setInterval(function () {
            if ($(elt)) {
                setTimeout(callback, 500);
                clearInterval(initializer);
            }
        }, 200);
    },
    init: function () {
        this.waitFor("cat-json-tree", function () {
            this.tree = $("cat-json-tree").value.evalJSON();
            var root = this.tree[1];
            this.displayChildren(root);
        }.bind(this));
    },
    displayChildren: function (node, parentElement) {
        if (typeof parentElement !== "undefined") {
            $(parentElement).up().select('ul').remove();
        }
        var children = node['children'];
        children.each(function (child) {
            this.constructNode(this.tree[child], parentElement);
            this.initAutoComplete(child);
        }.bind(this));
        this.updateSelectionAndMapping(children);
    },
    constructNode: function (node, parentElement) {
        var content = "<ul class='tv-mapping closed'>";
        content += "<li>";
        content += "<div class='selector' id='main-cat-" + node['id'] + "'>";
        if (node['children'].length > 0) {
            content += "<span class='tv-switcher closed' id='" + node["id"] + "'></span>";
        } else {
            content += "<span class='empty'></span>";
        }
        content += "<input type='checkbox' class='category' id='cat_id_" + node['id'] + "' name='cat_id_" + node['id'] + "'/>";
        content += node['text'];
        content += "<span class='small'>[ID:" + node['id'] + "]</span>";
        content += "<span class='mapped'>";
        content += "<br/>";
        content += "<span>mapped as :</span>";
        content += "</span>&nbsp;";
        content += "<label class='mage-suggest-search-label'>";
        content += "<input placeholder='your google product category' title='Press `End.` on your keyboard in order to apply this value to all the sub-categories' type='text' class='mapping' id='category_mapping_" + node['id'] + "' class='mapping' />";
        content += "</label>";
        content += "</div>";
        // enfants
        content += "</li>";
        content += "</ul>";
        if (typeof parentElement === "undefined") {
            $("cat-json-tree").insert({"after": content});
        } else {
            $(parentElement).insert({"after": content});
        }
    },
    /**
     * When a mapping change or when a category is (un)selected
     */
    updateSelection: function () {
        var selection = {};
        if ($('simplegoogleshopping_categories').value !== '*' && $('simplegoogleshopping_categories').value !== '') {
            selection = $('simplegoogleshopping_categories').value.evalJSON();
        }
        $$('input.category').each(function (elt) {
            var id = elt.id.replace('cat_id_', '');
            var mapping = $('category_mapping_' + id).value;
            selection[id] = {c: (elt.checked === true ? '1' : '0'), m: mapping};
        });
        $('simplegoogleshopping_categories').value = JSON.stringify(selection);
    },
    /**
     * Select all children categories
     */
    selectChildren: function (parentId, cats) {
        var categories = {};
        if (typeof cats === "undefined") {
            categories = $('simplegoogleshopping_categories').value.evalJSON();
        } else {
            categories = cats;
        }
        var checked = categories[parentId]['c'];
        var children = this.tree[parentId]['children'];

        children.each(function (child) {
            if (typeof categories[child] === "undefined") {
                categories[child] = {"c": false, "m": ""};
            }
            categories[child]['c'] = checked;
            if ($('cat_id_' + child)) {
                $('cat_id_' + child).checked = checked === "1";
                if (checked === "1") {
                    $('cat_id_' + child).up().addClassName('selected');
                } else {
                    $('cat_id_' + child).up().removeClassName('selected');
                }
            }
            this.selectChildren(child, categories);
        }.bind(this));
        if (typeof cats === "undefined") {
            $('simplegoogleshopping_categories').value = JSON.stringify(categories);
        }
    },
    updateSelectionAndMapping: function (children) {
        var categories = {};
        if ($('simplegoogleshopping_categories').value !== '*' && $('simplegoogleshopping_categories').value !== '' && $('simplegoogleshopping_categories').value !== '[]') {
            categories = $('simplegoogleshopping_categories').value.evalJSON();
        }
        for (var i in children) {
            var id = children[i];
            if (typeof categories[id] !== "undefined") {
                var cat = categories[id];
                if (cat['c'] === "1") { // if checked
                    $('cat_id_' + id).checked = true;
                    $('cat_id_' + id).up().addClassName('selected');
                }
                // set the category mapping
                $('category_mapping_' + id).value = cat['m'];
            }
        }
    },
    /**
     * Update all children with the parent mapping
     */
    updateChildrenMapping: function (mapping, parentId, cats) {
        var categories = {};
        if (typeof cats === "undefined") {
            categories = $('simplegoogleshopping_categories').value.evalJSON();
        } else {
            categories = cats;
        }
        var children = this.tree[parentId]['children'];
        children.each(function (child) {
            if (typeof categories[child] === "undefined") {
                categories[child] = {"c": 0, "m": ""};
            }
            categories[child]['m'] = mapping;
            if ($('category_mapping_' + child)) {
                $('category_mapping_' + child).value = mapping;
            }
            this.updateChildrenMapping(mapping, child, categories);
        }.bind(this));
        if (typeof cats === "undefined") {
            $('simplegoogleshopping_categories').value = JSON.stringify(categories);
        }
    },
    /**
     * Initialize autocomplete fields for the mapping
     */
    initAutoComplete: function (id) {
        new AutoComplete('category_mapping_' + id, this.autoCompleteUrl + '?s=', {cssClass: 'autocomplete', delay: 0.25, resultFormat: AutoComplete.Options.RESULT_FORMAT_TEXT});
    }
};

document.observe(
    'click', function (e) {
        if (e.findElement('input[type=checkbox]')) {
            i = e.findElement('input[type=checkbox]');
            if (!i.hasClassName('check_all')) {
                i.ancestors().each(
                    function (a) {
                        if (a.hasClassName('fieldset')) {
                            selector = $(a.id);
                        }
                    }
                );
                if (selector.id == 'attributes-selector') {
                    if (i.checked == true) {
                        i.ancestors()[1].select('div')[0].select('INPUT:not(INPUT[type=checkbox]),SELECT').each(
                            function (h) {
                                h.disabled = false;
                            }
                        );
                    } else {
                        i.ancestors()[1].select('div')[0].select('INPUT:not(INPUT[type=checkbox]),SELECT').each(
                            function (h) {
                                h.disabled = true;
                            }
                        );
                    }
                }

                i.ancestors()[1].select('li').each(
                    function (li) {
                        if (i.checked == true) {
                            li.select('INPUT')[0].checked = true;
                        } else {
                            li.select('INPUT')[0].checked = false;
                        }
                    }
                );

                simplegoogleshopping.setValues(selector);

                if (selector.id != "category-selector") {
                    selector.select('.selected').each(
                        function (s) {
                            s.removeClassName('selected');
                        }
                    );
                }
                selector.select('.node').each(
                    function (li) {
                        if (li.select('INPUT')[0].checked == true) {
                            li.addClassName('selected');

                        }
                    }
                );
                cnt = 0;
                selector.select('input[type=checkbox][class!=check_all]').each(
                    function (e) {
                        if (e.checked) {
                            cnt++;
                        }
                    }
                );

                if (selector.id != "category-selector" && selector.id != "cron-setting") {
                    if (cnt == selector.select('input[type=checkbox][class!=check_all]').length) {
                        selector.previous().select('.check_all')[0].checked = true;
                    } else {
                        selector.previous().select('.check_all')[0].checked = false;
                    }
                }
            }
        }
    }
);

function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value + "; path=/;";
}

function getCookie(c_name) {
    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1) {
        c_start = c_value.indexOf(c_name + "=");
    }
    if (c_start == -1) {
        c_value = null;
    } else {
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1) {
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start, c_end));
    }

    return c_value;
}

document.observe(
    'dom:loaded', function () {
        if (!$('cron_expr').value.isJSON()) {
            $('cron_expr').value = '{"days":[],"hours":[]}';
        }
        cron = $('cron_expr').value.evalJSON();

        cron.days.each(
            function (d) {
                if ($('d-' + d)) {
                    $('d-' + d).checked = true;
                    $('d-' + d).ancestors()[0].addClassName('checked');
                }
            }
        );
        cron.hours.each(
            function (h) {
                if ($('h-' + h.replace(':', ''))) {
                    $('h-' + h.replace(':', '')).checked = true;
                    $('h-' + h.replace(':', '')).ancestors()[0].addClassName('checked');
                }
            }
        );

        $$('.cron-box').each(
            function (e) {
                e.observe(
                    'click', function () {
                        if (e.checked) {
                            e.ancestors()[0].addClassName('checked');
                        } else {
                            e.ancestors()[0].removeClassName('checked');
                        }

                        d = new Array;
                        $$('.cron-d-box INPUT').each(
                            function (e) {
                                if (e.checked) {
                                    d.push(e.value);
                                }
                            }
                        );
                        h = new Array;
                        $$('.cron-h-box INPUT').each(
                            function (e) {
                                if (e.checked) {
                                    h.push(e.value);
                                }
                            }
                        );

                        $('cron_expr').value = Object.toJSON(
                            {
                                days: d,
                                hours: h
                            }
                        );
                    }
                );
            }
        );

        if ($('simplegoogleshopping_type_ids').value != '') {
            $('simplegoogleshopping_type_ids').value.split(',').each(
                function (e) {
                    $('type_id_' + e).checked = true;
                    $('type_id_' + e).ancestors()[1].addClassName('selected');
                }
            );
        }

        if ($('simplegoogleshopping_visibility').value != '') {
            $('simplegoogleshopping_visibility').value.split(',').each(
                function (e) {
                    $('visibility_' + e).checked = true;
                    $('visibility_' + e).ancestors()[1].addClassName('selected');
                }
            );
        }
        if ($('simplegoogleshopping_attribute_sets').value != '') {
            if ($('simplegoogleshopping_attribute_sets').value == '*') {
                $('attribute-sets-selector').select('INPUT').each(
                    function (input) {
                        input.checked = true;
                        input.ancestors()[1].addClassName('selected');
                    }
                );
            } else {
                $('simplegoogleshopping_attribute_sets').value.split(',').each(
                    function (e) {
                        if (e != "") {
                            $('attribute_set_' + e).checked = true;
                            $('attribute_set_' + e).ancestors()[1].addClassName('selected');
                        }
                    }
                );
            }
        }

        if ($('simplegoogleshopping_attributes').value == '') {
            $('simplegoogleshopping_attributes').value = "[]";
        }

        attributes = $('simplegoogleshopping_attributes').value.evalJSON();
        if (attributes.length > 0) {
            attributes.each(
                function (attribute) {
                    if(!$$("#name_attribute_" + attribute.line+" OPTION[value='"+attribute.code+"']").length) {
                        return;
                    }
                    if (attribute.checked) {
                        $('attribute_' + attribute.line).checked = true;
                        $('node_' + attribute.line).addClassName('selected');
                        $('node_' + attribute.line).select('INPUT:not(INPUT[type=checkbox]),SELECT').each(
                            function (h) {
                                h.disabled = false;
                            }
                        );
                    }

                    $('name_attribute_' + attribute.line).value = attribute.code;
                    $('condition_attribute_' + attribute.line).value = attribute.condition;
                    $('value_attribute_' + attribute.line).value = attribute.value;
                    if (typeof attribute.statement != 'undefined') {
                        $('statement_attribute_' + attribute.line).value = attribute.statement;
                    }
                    if (typeof attribute.parenthesis_open != 'undefined') {
                        $('parenthesis_open_attribute_' + attribute.line).value = attribute.parenthesis_open;
                    }
                    if (typeof attribute.parenthesis_close != 'undefined') {
                        $('parenthesis_close_attribute_' + attribute.line).value = attribute.parenthesis_close;
                    }
                }
            );
        }

        $('attributes-selector').select('SELECT').each(
            function (n) {
                if (n.hasClassName('name-attribute')) {
                    prefilledValues = n.next().next();
                    if (n.value != "") {
                        eval("options=_" + n.value);
                    }

                    html = null;
                    custom = true;
                    if (typeof options == "object" && options.length > 0) {
                        options.each(
                            function (o) {
                                if (prefilledValues.next().value.split(',').indexOf(o.value + '') != -1) {
                                    selected = 'selected';
                                    custom = false;
                                } else {
                                    selected = false;
                                }

                                html += "<option value='" + o.value + "' " + selected + ">" + o.label + "</option>";
                            }
                        );

                        if (custom) {
                            selected = "selected";
                        } else {
                            selected = '';
                        }

                        html += "<option value='_novalue_' style='color:#555' " + selected + ">custom value...</option>";

                        if (!custom) {
                            prefilledValues.setStyle({'display': 'inline'});
                            prefilledValues.next().setStyle({'display': 'none'});
                        } else {
                            prefilledValues.setStyle({'display': 'inline'});
                            if (!prefilledValues.next().hasClassName('advanced')) {
                                prefilledValues.next().setStyle({
                                    'display': 'block',
                                    'margin': '0 0 0 422px'
                                });
                            } else {
                                prefilledValues.next().setStyle({
                                    'display': 'block',
                                    'margin': '0 0 0 475px'
                                });
                            }
                        }
                        prefilledValues.update(html);
                    }

                    n.observe(
                        'change', function () {
                            prefilledValues = n.next().next();
                            eval("options=_" + n.value);
                            html = "";
                            options.each(
                                function (o) {
                                    (o.value == prefilledValues.next().value) ? selected = 'selected' : selected = null;

                                    html += "<option value='" + o.value + "' " + selected + ">" + o.label + "</option>";
                                }
                            );

                            html += "<option value='_novalue_' style='color:#555'>custom value...</option>";
                            if (typeof options == "object" && options.length > 0) {
                                prefilledValues.setStyle({'display': 'inline'});
                                prefilledValues.next().setStyle({'display': 'none'});
                                prefilledValues.update(html);
                            } else {
                                prefilledValues.setStyle({'display': 'none'});
                                prefilledValues.next().setStyle({
                                    'display': 'inline',
                                    'margin': '0 0 0 0'
                                });
                                prefilledValues.next().value = null;

                            }
                            prefilledValues.next().value = null;
                            simplegoogleshopping.setValues($("attributes-selector"));
                        }
                    );
                }
            }
        );
        $$('.pre-value-attribute').each(
            function (prefilledValues) {
                prefilledValues.observe(
                    'change', function () {
                        if (prefilledValues.value != '_novalue_') {
                            prefilledValues.setStyle({'display': 'inline'});
                            prefilledValues.next().setStyle({'display': 'none'});
                            r = [];
                            prefilledValues.select('OPTION').each(
                                function (e) {
                                    if (e.selected) {
                                        r.push(e.value);
                                    }
                                }
                            );
                            r = r.join(',');

                            prefilledValues.next().value = r;
                            simplegoogleshopping.setValues($("attributes-selector"));
                        } else {
                            prefilledValues.setStyle({'display': 'inline'});
                            prefilledValues.next().setStyle({
                                'display': 'block',
                                'margin': '0 0 0 422px'
                            });
                        }
                    }
                );
            }
        );
        $$(".check_all").each(
            function (i) {
                i.observe(
                    "click", function () {
                        i.ancestors()[2].select('input[type=checkbox]').each(
                            function (e) {
                                e.checked = i.checked;
                                if (!i.checked) {
                                    e.ancestors()[1].removeClassName('selected');
                                } else {
                                    e.ancestors()[1].addClassName('selected');
                                }
                                if (e.checked == true) {
                                    e.ancestors()[1].select('div')[0].select('INPUT:not(INPUT[type=checkbox]),SELECT').each(
                                        function (h) {
                                            h.disabled = false;
                                        }
                                    );
                                } else {
                                    e.ancestors()[1].select('div')[0].select('INPUT:not(INPUT[type=checkbox]),SELECT').each(
                                        function (h) {
                                            h.disabled = true;
                                        }
                                    );
                                }
                            }
                        );

                        simplegoogleshopping.setValues(i.ancestors()[1].next());
                    }
                );
                cnt = 0;
                i.ancestors()[2].select('input[type=checkbox]').each(
                    function (e) {
                        if (e.checked) {
                            cnt++;
                        }
                    }
                );

                if (cnt == i.ancestors()[2].select('input[type=checkbox][class!=check_all]').length) {
                    i.checked = true;
                }
            }
        );
        $$('.refresh').each(
            function (f) {
                f.observe(
                    'keyup', function () {
                        simplegoogleshopping.checkSyntax();
                    }
                );
            }
        );
        $$('.refresh').each(
            function (f) {
                f.observe(
                    'change', function () {
                        simplegoogleshopping.checkSyntax();
                    }
                );
            }
        );
        $$('TEXTAREA').each(
            function (f) {
                f.observe(
                    'keydown', function (event) {
                        catchTab(f, event);
                    }
                );
            }
        );

        window.onresize = function () {
            simplegoogleshopping.checkSyntax();
        };

        $('loading-mask').remove();

        page = Builder.node(
            'div', {
                id: 'blackbox-console',
                'class': 'arr_up reduce'
            }, [
                Builder.node(
                    'DIV', {
                        id: "handler"
                    }, [
                        Builder.node(
                            'span', {
                                className: 'feedname'
                            }, 'example'
                        ),
                        Builder.node(
                            'BUTTON', {
                                'class': 'scalable',
                                id: 'closer',
                                'onclick': 'javascript:simplegoogleshopping.switchSize()'
                            }, [
                                Builder.node(
                                    'SPAN', {
                                        'class': 'full'
                                    }, '\u271a'
                                ),
                                Builder.node(
                                    'SPAN', {
                                        'class': 'reduce'
                                    }, '\u2716'
                                )
                            ]
                        ),
                        Builder.node(
                            'BUTTON', {
                                'class': 'scalable',
                                id: 'closer',
                                'onclick': 'javascript:simplegoogleshopping.switchStatus()'
                            }, [
                                Builder.node(
                                    'SPAN', {
                                        'class': 'arr_up'
                                    }, '\u25b2'
                                ),
                                Builder.node(
                                    'SPAN', {
                                        'class': 'arr_down'
                                    }, '\u25bc'
                                )
                            ]
                        ),
                        Builder.node('span', '.')
                    ]
                ),
                Builder.node(
                    'div', {
                        id: 'page',
                        'onmouseup': 'javascript:simplegoogleshopping.changeSize()'
                    },
                    Builder.node(
                        'textarea', {
                            'class': 'CodeMirror'
                        }
                    )
                ),
                Builder.node(
                    'BUTTON', {
                        'class': 'scalable save',
                        id: 'refresher',
                        'onclick': 'javascript:simplegoogleshopping.checkReport()'
                    },
                    Builder.node('SPAN', 'Errors report')
                ),
                Builder.node(
                    'BUTTON', {
                        'class': 'scalable save',
                        id: 'refresher',
                        'onclick': 'javascript:simplegoogleshopping.updatePreview()'
                    },
                    Builder.node('SPAN', 'Data preview ')
                ),
                Builder.node(
                    'BUTTON', {
                        'class': 'scalable save',
                        id: 'library',
                        'onclick': 'javascript:simplegoogleshopping.checkLibrary()'
                    },
                    Builder.node('SPAN', 'Attributes library')
                ),
                Builder.node(
                    'BUTTON', {
                        'class': 'scalable save',
                        id: 'syntaxer',
                        'onclick': 'javascript:simplegoogleshopping.checkSyntax()'
                    },
                    Builder.node('SPAN', 'Syntax validation')
                )
            ]
        );

        $$('BODY')[0].insert({top: page});
        new Draggable(
            'blackbox-console', {
                handle: 'handler',
                onEnd: function () {
                    simplegoogleshopping.changePosition();
                }
            }
        );

        $$('.CodeMirror')[0] = $('CodeMirror');

        simplegoogleshopping.checkSyntax();
        __top = getCookie('blackboxConsole-top');
        __left = getCookie('blackboxConsole-left');
        __width = getCookie('blackboxConsole-width');
        __height = getCookie('blackboxConsole-height');
        __status = getCookie('blackboxConsole-status');
        __screen = getCookie('blackboxConsole-screen');

        if (__top !== null) {
            $('blackbox-console').style = 'top:' + __top + 'px!important;left:' + __left + 'px!important';
        }
        if (__width !== null) {
            $('blackbox-console').select("#page")[0].style = 'width:' + __width + 'px!important;height:' + __height + 'px!important';
        }
        if (__status === 'hidden') {
            simplegoogleshopping.switchStatus();
        }
        if (__screen === 'full') {
            simplegoogleshopping.switchSize();
        }
    }
);

document.observe(
    "dom:loaded", function () {
        $$('.need_help_init').each(
            function (h) {
                h.insert({
                    after: Builder.node("A", {"href": "#", "onclick": "javascript:window.open('https://help.wyomind.com/?ext=sgs&h=" + h.id + "', 'wHelp', 'width=400,height=200,menubar=no,toolbar=no')", "class": "need_help"})
                });
            }
        );
    }
);