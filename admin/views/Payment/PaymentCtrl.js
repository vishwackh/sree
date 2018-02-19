(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('PaymentCtrl', PaymentCtrl);

    PaymentCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state'];

    /* @ngInject */
    function PaymentCtrl($scope, $http, $rootScope, localStorageService, toaster, $state) {
  

    }
})();