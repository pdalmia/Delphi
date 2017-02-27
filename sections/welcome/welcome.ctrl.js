'use strict';
angular
    .module('app.core', ['ngSanitize', 'ngDialog'])
    .config(['ngDialogProvider', function (ngDialogProvider) {
        ngDialogProvider.setDefaults({
            className: 'ngdialog-theme-default',
            plain: false,
            showClose: true,
            closeByDocument: false,
            closeByEscape: false,
            appendTo: false,
            preCloseCallback: function () {
                console.log('default pre-close callback');
            }
        });
    }])

    .controller('WelcomeController', function ($scope, $location, SurveyAppService, $routeParams, $http, PageValues, $rootScope, ngDialog) {

        //Setup view model object

        var vm = this;
        vm.SurveyId = 0;
        vm.Heading = '';
        vm.SubHeading = '';
        vm.Title = '';
        vm.WelcomeParagraph = '';
        vm.Subtitle = '';
        vm.SummaryNote = '';
        vm.Status = '';
        vm.location = $location;
        vm.showPopup = true;

        vm.user = {};
        vm.user.Email = '';
        vm.user.LibraryName = '';
        vm.user.NumberofQuestions = '';
        vm.user.SurveyGroupUser_Id = '';
        vm.user.SurveyGroup_Id = '';
        vm.user.SurveyIteration_Id = '';
        vm.user.SurveyGroup_Id = '';
        vm.user.SurveyStatus = '';
        vm.user.UserName = '';
        vm.user.ConfirmationText = '';
        vm.user.TextCancel = '';
        vm.isDataLoad = false;

        //reset token
        var token = SurveyAppService.getApiToken();
        if (token == '' && token != $routeParams.token)
        {
            PageValues.userConfirmationFlag = false;
        }

        //Api Call
        if (PageValues.userConfirmationFlag == false) {
            SurveyAppService.setApiToken($routeParams.token);

            SurveyAppService.getSurveyDetail('').then(function (response) {
                console.log(response);
                var data = response.survey;
                vm.SurveyId = data.Survey_Id;
                vm.Heading = data.Heading;
                vm.SubHeading = data.SubHeading;
                vm.Title = data.Heading;
                vm.WelcomeParagraph = data.WelcomeParagraph;
            });

            SurveyAppService.authenticateUser('').then(function (response) {
                console.log(response);
                var user = response.user;
                vm.user.Email = user.Email;
                vm.user.LibraryName = user.LibraryName;
                vm.user.NumberofQuestions = user.NumberofQuestions;
                vm.user.SurveyGroupUser_Id = user.SurveyGroupUser_Id;
                vm.user.SurveyGroup_Id = user.SurveyGroup_Id;
                vm.user.SurveyIteration_Id = user.SurveyIteration_Id;
                vm.user.SurveyGroup_Id = user.SurveyGroup_Id;
                vm.user.SurveyStatus = user.SurveyStatus;
                vm.user.UserName = user.UserName;
                vm.user.ConfirmationText = user.ConfirmationText;
                vm.user.TextCancel = user.TextCancel;
                $rootScope.user = vm.user;
                vm.isDataLoad = true;
                $scope.openConfirm();
            });


            $scope.openConfirm = function () {
                ngDialog.openConfirm({
                    template: 'userDialogTemplateId',
                    className: 'ngdialog-theme-default',
                    preCloseCallback: 'preCloseCallbackOnScope',
                }).then(function (value) {
                    PageValues.userConfirmationFlag = true;
                    //just close 
                    //console.log('Modal promise resolved. Value: ', value);
                }, function (reason) {
                    console.log('Modal promise rejected. Reason: ', reason);
                    PageValues.userConfirmationFlag = true;
                    window.location = '#/App/UnAuthorized';
                });
            };


           
            

            vm.surveyStart = function () {

                if (vm.user.SurveyStatus == '0') {
                    //Survey Startup
                    window.location = '#/App/Survey';
                }
                else if (vm.user.SurveyStatus == '1') {
                    window.location = '#/App/Survey';
                }
                else if (vm.user.SurveyStatus == '2') {
                    window.location = '#/App/ThankYou';
                }
                else if (vm.user.SurveyStatus == '3') {
                    window.location = '#/App/ThankYou';
                }
                else if (vm.user.SurveyStatus == '4') {
                    PageValues.Status = '4';
                    window.location = '#/App/ThankYou';
                }
            }
        }
    });