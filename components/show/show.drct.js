angular
    .module('app.core')
    .directive('show', show);
function show(SurveyAppService) {
    var directive = {
        controller: controller,
        templateUrl: 'components/show/show.tpl.html',
        restrict: 'E',
        scope: {
            show: '='
        }
    };
    return directive;
    function controller($scope) {
        $scope.genres = [];
        SurveyAppService.getQuestion($scope.show.id).then(function (response) {
            $scope.genres = response.genres;
        });
    }
}