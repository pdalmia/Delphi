'use strict';

angular
    .module('app.routes', ['ngRoute'])
    .config(config);


function config($routeProvider, $locationProvider) {
    $routeProvider.
        when('/:token', {
            templateUrl: 'sections/welcome/welcome.tpl.html',
            controller: 'WelcomeController as WelcomeCtrl'
        })
         .when('/App/Questions', {
             templateUrl: 'sections/questionslist/questionslist.tpl.html',
             controller: 'QuestionsListController as QuestionsListCtrl'
         })
         .when('/App/Survey', {
             templateUrl: 'sections/questions/questions.tpl.html',
             controller: 'QuestionsController as QuestionCtrl'
         })
        .when('/App/ThankYou', {
            templateUrl: 'sections/thankyou/thankyou.tpl.html',
            controller: 'ThankYouController as ThankyouCtrl'
        })
        .when('/App/UnAuthorized', {
            templateUrl: 'sections/UnAuthorized/unauthorized.tpl.html',
            controller: 'UnAuthorizedController as UnAuthorizedCtrl'
        })
        .otherwise({
            redirectTo: '/'
        });

    // enable html5Mode for pushstate ('#'-less URLs)
    //$locationProvider.html5Mode({
    //    enabled: true,
    //    requireBase: false
    //});
    //$locationProvider.hashPrefix('!');
}

