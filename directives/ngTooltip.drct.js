angular
    .module('app.core')
    .directive('tooltip', ngTooltip);

function ngTooltip() {
    return function (scope, element, attrs) {
        $(element)
            .attr('title', scope.$eval(attrs.tooltip))
            .tooltip({ placement: "right" });
    }

}