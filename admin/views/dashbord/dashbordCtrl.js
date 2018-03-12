(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('dashbordCtrl', dashbordCtrl);

    dashbordCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state','$filter'];

    /* @ngInject */
    function dashbordCtrl($scope, $http, $rootScope, localStorageService, toaster, $state,$filter) {
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
        $scope.index=0;
        $scope.oneDaySelectionOnly = function (event, date) { 
            $scope.index=0;           
            $scope.selectedDays3.length = 0;
            $scope.selDate=angular.copy($filter('date')(new Date(date.date), "yyyy-MM-dd"));
            $scope.sortedData =angular.copy(_.where($scope.Inquiry, {'eventdate': $scope.selDate+' 00:00:00'}));   
            $scope.selectedData=angular.copy($scope.sortedData[0]);        
        }
        $scope.clickNext=function(){
            $scope.index++;
            $scope.selectedData=angular.copy($scope.sortedData[$scope.index]); 
        }
        $scope.clickPrev=function(){
            $scope.index--;
            $scope.selectedData=angular.copy($scope.sortedData[$scope.index]);
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