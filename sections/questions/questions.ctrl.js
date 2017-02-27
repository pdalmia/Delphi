'use strict';
angular
    .module('app.core')

    .controller('QuestionsController', function ($scope, $location, SurveyAppService, $routeParams, PageValues) {
        //Setup view model object
        var vm = this;
        vm.ratingOptions = [1, 2, 3, 4, 5, 6, 7];
        vm.surveyQuestionId = 0;
        vm.previousQuestionId = -1;
        vm.previousPropositionId = -1;
        vm.surveyPropositionId = 0;
        vm.surveyPropositionId_db = 0;
        vm.surveyQuestionId_db = 0;
        vm.heading = '';
        vm.title = '';
        vm.subTitle = '';
        vm.questionText = '';
        vm.leftText = '';
        vm.rightText = '';
        vm.currentRating = 0;
        vm.comment = '';
        vm.questionGroupText = '';
        vm.showCommentText = false;
        vm.totalQuestions = 0;
        vm.checked = false;
        vm.progress = 0;
        vm.textOf = "";
        vm.textSave = "";
        vm.textYourComment = "";
        vm.propOneLeftText = "";
        vm.propOneRightText = "";
        vm.propOneRating = "";

        vm.updateProgress = function () {
            var currentProgress = 0;
            if (vm.totalQuestions > 0) {
                var perQuestionPercentage = (100 / vm.totalQuestions);
                if (vm.surveyPropositionId == 1) {
                    currentProgress = (perQuestionPercentage * (vm.surveyQuestionId - 1)) + (((perQuestionPercentage * vm.surveyQuestionId) - (perQuestionPercentage * (vm.surveyQuestionId - 1)))/2);
                }
                else {
                    currentProgress = perQuestionPercentage * vm.surveyQuestionId;
                }
                vm.progress = currentProgress;
            }
            return currentProgress + '%';
        }

        //vm.updateProgress();

        var isSaveEnabled = function () {
            var isSaveEnabled = false;
            if (vm.showCommentText == false && vm.currentRating != 0) {
                isSaveEnabled = true;
            }
            else if (vm.showCommentText == true) {
                isSaveEnabled = true;
            }
            return isSaveEnabled;
        }

        var isRadioDisabled = function () {
            var isRadioDisabled = false;
            if (vm.showCommentText == true) {
                isRadioDisabled = true;
            }
            return isRadioDisabled;
        }

        vm.getNextSurveyQuestion = function () {
            //console.log("starting getNextSurveyQuestion");
            SurveyAppService.getNextSurveyQuestion('').then(function (response) {
                //console.log(response);
                if (response.status == "true") {
                    if (response.surveyStatus == 2) {
                        window.location = '#/App/ThankYou';
                        return;
                    }
                    else {
                        var questionDetail = response.questionDetail;
                        vm.heading = questionDetail.Heading;
                        vm.title = questionDetail.Title;
                        vm.subTitle = questionDetail.SubTitle;
                        vm.questionGroupText = questionDetail.QuestionGroupText;
                        vm.leftText = questionDetail.LeftText;
                        vm.rightText = questionDetail.RightText;
                        vm.questionText = questionDetail.QuestionText;
                        vm.surveyQuestionId = questionDetail.QuestionOrder;
                        vm.surveyPropositionId = questionDetail.PrepositionType;
                        vm.surveyQuestionId_db = questionDetail.SurveyQuestion_Id;
                        vm.surveyPropositionId_db = questionDetail.SurveyProposition_Id;
                        vm.showCommentText = response.showCommentText;
                        vm.currentRating = response.currentRating;
                        vm.commentText = questionDetail.CommentText;
                        vm.textOf = questionDetail.TextOf;
                        vm.textSave = questionDetail.TextSave;
                        vm.textYourComment = questionDetail.TextYourComment;
                        vm.comment = "";
                        vm.checked = false;
                        vm.isSaveEnabled = isSaveEnabled();
                        vm.isRadioDisabled = isRadioDisabled();

                        var propositionDetail = response.propositionDetail;
                        if (vm.surveyPropositionId == 2)
                        {
                            vm.propOneLeftText = propositionDetail.LeftText;
                            vm.propOneRightText = propositionDetail.RightText;
                            vm.propOneRating = propositionDetail.Rating;
                        }
                        vm.updateProgress();
                    }
                
                }
                
            });
        }

        //        vm.getNextSurveyQuestion();

        vm.selectRating = function (option) {
            this.currentRating = option;
            vm.isSaveEnabled = true;
        }

        vm.isCommentRequired = function () {
            return vm.showCommentText;
        }

        var totalQuestions = function () {
            //vm.totalQuestions = 26;
            SurveyAppService.getSurveyQuestions('').then(function (response) {
                //console.log(response);
                vm.totalQuestions = response.surveyQuestions.length;
                //vm.totalQuestions = 26;
                vm.getNextSurveyQuestion();
            });
        }

        totalQuestions();

        vm.save = function () {
            var saveResponse;
            ///logic to show the comment box if all is set move to second part
            if (vm.showCommentText == false && vm.currentRating != 0) {
                var param = { surveyQuestionId: vm.surveyQuestionId_db, surveyPropositionId: vm.surveyPropositionId_db, rating: vm.currentRating, reason: vm.comment };
                SurveyAppService.setSurveyIterationAnswer(vm.showCommentText, param).then(function (response) {
                    //call again next question
                    //console.log(response);
                    if (response.status == true) {
                        vm.getNextSurveyQuestion();
                    }
                });
            }
            else if (vm.showCommentText == true && vm.comment.trim() != '') {
                var param = { surveyQuestionId: vm.surveyQuestionId_db, surveyPropositionId: vm.surveyPropositionId_db, rating: 0, reason: vm.comment };
                SurveyAppService.setSurveyIterationAnswer(vm.showCommentText, param).then(function (response) {
                    //call again next question
                    if (response.status == true) {
                        vm.getNextSurveyQuestion();
                    }
                });
            }
        }
    });
    