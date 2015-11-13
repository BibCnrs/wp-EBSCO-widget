(function () {
    'use strict';
    document.onreadystatechange = function () {
        if (document.readyState === 'complete') {
            var rootElement = document.getElementById('ebsco_widget');
            var scriptTag = document.getElementById('ebsco_widget-index');
            var url = scriptTag.getAttribute('data-url');
            var token = scriptTag.getAttribute('data-token');
            var term = scriptTag.getAttribute('data-term');

            React.render(React.createElement(EbscoWidget, { url: url, term: term, token: token }), rootElement);
        }
    };
})(React, EbscoWidget);
