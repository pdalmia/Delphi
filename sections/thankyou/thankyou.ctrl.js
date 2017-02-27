'use strict';
angular
    .module('app.core')
    .controller('ThankYouController', function ($scope, $location, SurveyAppService, $routeParams, PageValues) {
        //Setup view model object
        var vm = this;
        vm.Heading = '';
        vm.SubHeading = '';
        vm.ThankyouText = '';
        vm.ThankyouNote = '';
        vm.TextSave = '';
        vm.showThanksText = false;
        vm.showSubmitButton = true;

        if (PageValues.Status == '4') {
            vm.showThanksText = true;
            vm.showSubmitButton = false;
        }

        SurveyAppService.getThankyouDetail('').then(function (response) {
            var data = response.surveyThanks;
            vm.Heading = data.Heading;
            vm.SubHeading = data.ThankyouSubHeading;
            vm.TextSave = data.TextSave;
            vm.ThankyouText = data.ThankyouText;
            vm.ThankyouNote = data.ThankyouNote;
        });

        vm.submit = function () {
            SurveyAppService.setSurveyComplete('').then(function (response) {
                vm.showThanksText = true;
                vm.showSubmitButton = false;
            });
        }

    });