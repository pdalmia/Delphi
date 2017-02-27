'use strict';
angular
    .module('app.core')
    .controller('UnAuthorizedController', function ($scope, $location, SurveyAppService, $routeParams, PageValues) {
        //Setup view model object
        var vm = this;
        vm.Heading = '';
        vm.ThankyouText = '';
        vm.InValidUserNote = '';

        SurveyAppService.getThankyouDetail('').then(function (response) {
            var data = response.surveyThanks;
            vm.Heading = data.Heading;
            vm.ThankyouText = data.ThankyouText;
            vm.InValidUserNote = data.InValidUserNote;
        });

    });