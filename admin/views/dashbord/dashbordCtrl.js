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

        function setValue() {
            $scope.viewby = 5;
            $scope.totalItems = $scope.Inquiry.length;
            $scope.currentPage = 1;
            $scope.itemsPerPage = $scope.viewby;
            $scope.maxSize = 5; //Number of pager buttons to show                    
        }
        $scope.setPage = function (pageNo) {
            $scope.currentPage = pageNo;
        };
        $scope.setItemsPerPage = function (num) {
            $scope.itemsPerPage = num;
            $scope.currentPage = 1; //reset to first page
        }
        $scope.highlightDays = [];
        $scope.selectedData=undefined;
        $scope.oneDaySelectionOnly = function (event, date) {
            $scope.selectedDays3.length = 0;
            $scope.selectedData =angular.copy(_.where($scope.Inquiry, {booking_Id: date.title}));           
        }
        $scope.bookingData = function () {
            $http.get($rootScope.ApiUrl + 'getDashboardData').then(function (data) {
                if (data.data) {
                    $scope.Inquiry = angular.copy(data.data);
                    if ($scope.Inquiry) {
                        setValue()
                        angular.forEach($scope.Inquiry, function (value, key) {
                            $scope.highlightDays.push({
                                'date': moment(new Date(value.eventdate)),
                                'css': value.bookingType == 2 ? 'booking' : 'inquiry',
                                'selectable': true,
                                'title': value.booking_Id
                            })
                        });
                    }
                }
            });
        };
        $scope.bookingData()
    }
})();