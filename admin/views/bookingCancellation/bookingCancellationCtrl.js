(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('bookingCancellationCtrl', bookingCancellationCtrl);

    bookingCancellationCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state'];

    /* @ngInject */
    function bookingCancellationCtrl($scope, $http, $rootScope, localStorageService, toaster, $state) {
  

    }
})();