(function (ReactDOM, React, EbscoWidget) {
    'use strict';
    document.onreadystatechange = function () {
        if (document.readyState === 'complete') {
            var rootElement = document.getElementById('ebsco_widget');
            var scriptTag = document.getElementById('ebsco_widget-index');
            var url = scriptTag.getAttribute('data-url');
            var dbUrl = scriptTag.getAttribute('data-db_url');
            var domain = scriptTag.getAttribute('data-domain');
            var language = scriptTag.getAttribute('data-language');
            ReactDOM.render(React.createElement(EbscoWidget, {
                url: url,
                dbUrl: dbUrl,
                domain: domain,
                language: language
            }), rootElement);
        }
    };
})(ReactDOM, React, window.EbscoWidget);
