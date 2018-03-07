(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('bookingCancellationCtrl', bookingCancellationCtrl);

    bookingCancellationCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state'];

    /* @ngInject */
    function bookingCancellationCtrl($scope, $http, $rootScope, localStorageService, toaster, $state) {

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
        $scope.cancelDetails = function () {

            //FETCH BOOKING DETAILS
            $http.get($rootScope.ApiUrl + 'getBookingCancelation').then(function (data) {
                if (data.data) {
                    $scope.cancelDetails = angular.copy(data.data);
                    if ($scope.cancelDetails) {
                        setValue()
                    }
                }
            });
        };



        $scope.cancelDetails();
    }
})();