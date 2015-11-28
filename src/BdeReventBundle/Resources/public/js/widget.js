var widgets = {

    'list': document.getElementsByTagName('a'),

    'classWeez': "weezevent-widget-integration",

    'display': function (a, iframe) {
        a.parentNode.insertBefore(iframe, a);
        a.parentNode.removeChild(a);
    },

    'resize': function () {
        function resizeEvent(event) {
            var res = event.data.split("-");
            if (res[0] == 'weezmulti' || res[0].indexOf('weezuniq') == 0)
                if (document.getElementById(res[0]))
                    document.getElementById(res[0]).height = res[1] + 'px';
        }

        if (!window.addEventListener) window.attachEvent("onmessage", resizeEvent);
        else window.addEventListener("message", resizeEvent, false);
    },

    'getIframeParams': function (el) {
        /*var scrolling = 'yes';
         if(el.attributes["data-noScroll"].value == 1) {
         scrolling = 'no';
         }*/
        var scrolling = 'no';

        var out = {
            'width': (el.attributes["data-width_auto"] && el.attributes["data-width_auto"].value == 1) ? "100%" : el.attributes["data-width"].value,
            'height': el.attributes["data-height"].value,
            'id': 'weezuniq' + el.attributes["data-id"].value,
            'src': el.attributes["data-src"].value + '&v=2',
            'resize': el.attributes["data-resize"].value,
            'scrolling': scrolling,
            'hidePoweredBy': (el.attributes["data-nopb"]) ? (el.attributes["data-nopb"].value) : 0,
            'frameborder': '0'
        };
        return out;
    },

    'setIframeParams': function (el, params) {
        for (var key in params) el.setAttribute(key, params[key]);
    },

    'wrapIframe': function (iframe, link) {
        var out = document.createElement("div");
        out.innerHTML = iframe.outerHTML + link.outerHTML;
//    out.style.width = this.getIframeParams(link).width + 'px';
        return out;
    },

    'styleLink': function (el) {
        var css = 'float: right !important;';
        css += 'margin: 10px 0 15px 0 !important;';
        css += 'font-size: 11px !important;';
        css += 'font-family: Arial, sans-serif !important;';
        css += 'text-decoration: none !important;';
        css += 'color: #333333 !important;';
        el.style.cssText = css;
    },

    'styleIframe': function (el) {
        var css = 'border: 0px solid #EEEEEE !important;box-sizing:initial; -moz-box-sizing:initial; -webkit-box-sizing:initial;';
        el.style.cssText = css;
    },

    'init': function () {
        // For all <a> tag in the page
        for (var i = 0; i < this.list.length; i++) {
            var a = this.list[i];
            // if the tag is our widget, and if the tag isn't already rendered
            if (this.list[i].className.indexOf(this.classWeez) !== -1 && this.list[i].className.indexOf("rendered") === -1) {
                // Create iframe
                var iframe = document.createElement("iframe");
                // Set params
                this.setIframeParams(iframe, this.getIframeParams(a));
                // Active or not Auto-resize
                if (this.getIframeParams(a).resize == 1) this.resize();
                // Style the SEO link
                this.styleLink(a);
                // Style the iframe
                this.styleIframe(iframe);
                // Set the link as already rendered
                a.className = a.className + " rendered";
                // Set the link as already rendered
                var pb = "";
                if (this.getIframeParams(a).hidePoweredBy != "1") var pb = "Powered by Weezevent";
                a.innerHTML = pb;
                // Wrap the iframe & link
                var iframeWrapper = this.wrapIframe(iframe, a);
                // Display All
                this.display(a, iframeWrapper);
            }
        }
    }

};

widgets.init();