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
        };
        $scope.highlightDays = [
            {date: moment().date(2).valueOf(), css: 'holiday', selectable: false,title: 'Holiday time !'},
            {date: moment().date(14).valueOf(), css: 'birthday', selectable: false,title: 'We don\'t work today'},
            {date: moment().date(25).valueOf(), css: 'birthday', selectable: false,title: 'I\'m thir... i\'m 28, seriously, I mean ...'}
        ];        
  
    }
})();