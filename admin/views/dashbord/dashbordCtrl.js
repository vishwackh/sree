(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('dashbordCtrl', dashbordCtrl);

        dashbordCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state'];

    /* @ngInject */
    function dashbordCtrl($scope, $http, $rootScope, localStorageService, toaster, $state) {
        $scope.logout = function () {            
           
            localStorageService.remove('authorizationData');
            $rootScope.userinfo = [];
            $('#logoutModal').modal('hide');
            $state.go('Login');
        }
  
    }
})();