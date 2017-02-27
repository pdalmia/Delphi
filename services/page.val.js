'use strict';

angular
    .module('app.core')
    .value('PageValues', {
        'Status': '', 
        'loading': false,
        'userConfirmationFlag': false
    });