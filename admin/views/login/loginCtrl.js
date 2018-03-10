(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('loginCtrl', loginCtrl);

    loginCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state'];

    /* @ngInject */
    function loginCtrl($scope, $http, $rootScope, localStorageService, toaster, $state) {

        $rootScope.userinfo = [];
        $scope.reset={
            'currentpassword':'',
            'newpassword':''
        }
        $scope.loginfun = function () {
            console.log("scope users==>", $scope.user);
            $scope.submitted = true;
            if ($scope.login.$valid) {
                $http.post($rootScope.ApiUrl + 'adminlogin', $scope.user).then(function (data) {
                    console.log('login info ----------------------', data);
                    if (data.data.status) {
                        console.log("login");
                        $rootScope.isLogin = true;
                        $rootScope.userinfo.userName = data.data.userName;
                        $rootScope.userinfo.email = data.data.email;
                        $rootScope.userinfo.active = data.data.active;
                        localStorageService.remove('authorizationData');
                        localStorageService.set('authorizationData', {
                            'token': data.data.token,
                            'userName': $scope.user.userName
                        });

                        $state.go('dashbord.home');

                    } else {
                        console.log("not login");
                        toaster.pop('error', "Please check your UserName and Password", "");
                    }
                });
            } else {
                toaster.pop('error', 'Provide Correct Email Id & Password', "");
            }
        };
        $scope.resetPass = function (userForm) {
            var authData = localStorageService.get('authorizationData');
            var data={
                'username': authData.userName
            }
            data = _.extend(data, $scope.reset);
            if (userForm.$valid) {
                $http.post($rootScope.ApiUrl + 'resetPassword', data).then(function (data) {
                    if (data) {
                        localStorageService.remove('authorizationData');                    
                        toaster.pop('success', "Success", "Password updated successfully.");
                        $state.go('Login'); 
                    }
                });
            }
        }
    }
})();