(function (ReactDOM, React, EbscoWidget) {
    'use strict';
    document.onreadystatechange = function () {
        if (document.readyState === 'complete') {
            var rootElement = document.getElementById('ebsco_widget');
            var scriptTag = document.getElementById('ebsco_widget-index');
            var url = scriptTag.getAttribute('data-url');
            var domain = scriptTag.getAttribute('data-domain');
            var language = scriptTag.getAttribute('data-language');
            var publicationSort = scriptTag.getAttribute('data-publication_sort');

            ReactDOM.render(React.createElement(EbscoWidget, {
                url: url,
                domain: domain,
                language: language,
                publicationSort: publicationSort == 1 ? true : false
            }), rootElement);
        }
    };
})(ReactDOM, React, window.EbscoWidget);
