'use strict';
angular
    .module('app.core')
    .controller('QuestionsListController', function ($scope, $location, SurveyAppService, $routeParams, PageValues) {


        //Setup view model object
        var question = {
            questionId: 0,
            questionText: '',
            isSelected: false,
            hiddenTooltip: true
        }
        var vm = this;
        vm.maxCount = 1;
        vm.questions = [];
        vm.Heading = '';
        vm.ImportantQuestionsNote = '';
        vm.TextFiveStatements = '';
        vm.TextSave = '';


        SurveyAppService.getSurveyQuestions('').then(function (response) {

            var surveyQuestions = response.surveyQuestions;
            if (typeof (response.surveyQuestions) != 'undefined' && response.surveyQuestions.length > 0) {
                vm.Heading = response.surveyQuestions[0].Heading;
                vm.ImportantQuestionsNote = response.surveyQuestions[0].ImportantQuestionsNote;
                vm.TextFiveStatements = response.surveyQuestions[0].TextFiveStatements;
                vm.TextSave = response.surveyQuestions[0].TextSave;
            }

            $.each(surveyQuestions, function (index, surveyQuestion) {

                question.questionText = surveyQuestion.QuestionText;
                question.questionId = surveyQuestion.SurveyQuestion_Id;
                vm.questions.push($.extend({}, question));

            });
        });



        vm.submit = function () {
            var questionToSubmit = '';
            var isMaxCount = 0;
            var questionIdsArray = [];
            $.each(vm.questions, function (index, item) {
                if (item.isSelected == true) {
                    questionIdsArray.push(item.questionId);
                    isMaxCount = isMaxCount + 1;
                }
            });

            //Only now you able to submit
            if (isMaxCount == vm.maxCount) {
                SurveyAppService.setSurveyQuestions(questionIdsArray).then(function (response) {
                    console.log(response);
                    if (response.status == true) {
                        window.location = '#/App/ThankYou';
                    }
                });
            }

        }
        vm.isMaxCountReach = function (questionId, e) {

            var isMaxCount = 0;

            $.each(vm.questions, function (index, item) {
                item.hiddenTooltip = true;
                if (item.isSelected == true)
                    isMaxCount = isMaxCount + 1;
            });

            if (isMaxCount == vm.maxCount) {
                $.each(vm.questions, function (index, item) {
                    if (item.isSelected == false) {
                        item.hiddenTooltip = false;
                    }
                });
            }

            if (isMaxCount >= vm.maxCount + 1) {
                $.each(vm.questions, function (index, item) {
                    if (item.questionId == questionId) {
                        item.isSelected = !item.isSelected;
                        return;
                    }
                });

            }
        }

    });