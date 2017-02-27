'use strict';

/*
 * Contains a service to communicate with the TRACK TV API
 */
angular
    .module('app.services')
    .value('API_TOKEN', { 'value': '' })
    .constant('BASE_URL', 'http://demo.ondai.com/survey/webservices')
    .factory('SurveyAppService', dataService);

function dataService($http, API_TOKEN, BASE_URL, $log) {

    var data = {
        'authenticateUser': authenticateUser,
        'getSurveyDetail': getSurveyDetail,
        'getNextSurveyQuestion': getNextSurveyQuestion,
        'setSurveyIterationAnswer': setSurveyIterationAnswer,
        'getSurveyQuestions': getSurveyQuestions,
        'setSurveyQuestions': setSurveyQuestions,
        'getThankyouDetail': getThankyouDetail,
        'setSurveyComplete': setSurveyComplete,
        'setApiToken': setApiToken,
        'getApiToken': getApiToken

    };


    function getRequest(url, params) {
        console.log(API_TOKEN);
        var requestUrl = BASE_URL + '/' + url;//+ '?token=' + token;
        angular.forEach(params, function (value, key) {
            requestUrl = requestUrl + '/' + value;
        });
        return $http({
            'url': requestUrl,
            'method': 'GET',
        }).then(function successCallback(response) {
            return response.data;
        }).catch(dataServiceError);
    }

    //function postRequest(url, params) {
    //    var requestUrl = BASE_URL + '/' + url;//+ '?token=' + API_KEY;
    //    angular.forEach(params, function (value, key) {
    //        requestUrl = requestUrl + '&' + key + '=' + value;
    //    });
    //    return $http({
    //        'url': requestUrl,
    //        'method': 'POST',
    //        'headers': {
    //            'Content-Type': 'application/json',
    //            'Access-Control-Allow-Origin': '*'
    //        }
    //    }).then(function (response) {
    //        return response.data;
    //    }).catch(dataServiceError);
    //}

    function postRequest(url, params) {
        var requestUrl = BASE_URL + '/' + url;

        return $http({
            'url': requestUrl,
            'method': 'POST',
            'headers': {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            'data': params,
            'cache': true
        }).then(function (response) {
            return response.data;
        }).catch(dataServiceError);
    }

    function dataServiceError(errorResponse) {
        $log.error('XHR Failed for SurveyAppService');
        $log.error(errorResponse);
        return errorResponse;
    }



    //-- API LOGIC ---//
    function setApiToken(token) {
        API_TOKEN.value = token;
        
    }

    function getApiToken() {
       return API_TOKEN.value;

    }

    function authenticateUser(token) {
        return getRequest('AuthenticateUser/' + API_TOKEN.value, {});

    }

    function getSurveyDetail(token) {
        return getRequest('GetSurveyDetail/' + API_TOKEN.value, {});

    }

    function getNextSurveyQuestion(token) {
        return getRequest('GetNextSurveyQuestion/' + API_TOKEN.value, {});

    }

    function setSurveyIterationAnswer(showComment, params) {
        var requestUrl = 'SetSurveyIterationAnswer1/' + API_TOKEN.value;
        var parameters = '';
        if (showComment == false) {
            params.commentText = "";
        }
        parameters = 'surveyQuestionId=' + params.surveyQuestionId + '&surveyPropositionId=' + params.surveyPropositionId + '&rating=' + params.rating + '&reason=' + params.reason;
        return postRequest(requestUrl, parameters);
    }

    function setSurveyQuestions(questionIdsArray) {

        var requestUrl = 'SetSurveyQuestions/' + API_TOKEN.value;
        $.each(questionIdsArray, function (index, item) {
            requestUrl = requestUrl + '/' + item;

        });
        return getRequest(requestUrl, {});
    }


    function getSurveyQuestions(token) {
        return getRequest('GetSurveyQuestions/' + API_TOKEN.value, {});
    }

    function getThankyouDetail(token) {
        return getRequest('GetThankyouDetail/' + API_TOKEN.value, {});
    }


    function setSurveyComplete(token) {
        return getRequest('SetSurveyComplete/' + API_TOKEN.value, {});
    }
    return data;
}