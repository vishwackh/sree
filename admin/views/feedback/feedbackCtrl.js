(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('feedbackCtrl', feedbackCtrl);

        feedbackCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state','$filter'];

    /* @ngInject */
    function feedbackCtrl($scope, $http, $rootScope, localStorageService, toaster, $state,$filter) {
        function clearForm() {
            $scope.booking = {
                'customername': '',
                'feedback': ''
            };
        }
        $scope.clear = function () {
            clearForm();
        }
 

        $scope.addEvent = function (userForm) {
            $scope.submitted = true;
            if (userForm.$valid) {               
                $http.post($rootScope.ApiUrl + 'custFeedback', $scope.booking).then(function (data) {
                    if (data) {
                        toaster.pop('success', "Success", "Event addded successfully.");
                        clearForm();
                        userForm.$setPristine();
                        $scope.feedbackList();
                    }
                });
            }
        };
        $scope.updatemodal = function (x) {
            $scope.booking = x;
            $('#editmodel').modal('show');
        }
        $scope.delInquiry = undefined;
        $scope.deletemodal = function (x) {
            $scope.delInquiry = x;
            $('#deletemodel').modal('show');
        }
        $scope.updateEvent = function (userForm) {
            $scope.submitted = true;
            if (userForm.$valid) {        
                $http.post($rootScope.ApiUrl + 'updateEvent', $scope.booking).then(function (data) {
                    if (data) {
                        toaster.pop('success', "Success", "Event Updated successfully.");
                        clearForm();
                        userForm.$setPristine();
                        $scope.feedbackList();
                    }
                    $('#editmodel').modal('hide');
                });
            }
        };
        $scope.deleteEvent = function () {
            var data = {
                'event_Id': $scope.delInquiry.event_Id
            };
            $http.post($rootScope.ApiUrl + 'deleteEvent', data).then(function (data) {
                if (data) {
                    toaster.pop('success', "Success", "Event deleted successfully.");
                    $scope.feedbackList();
                }
                $('#deletemodel').modal('hide');
            });
        }

        function setValue() {
            $scope.viewby = 5;
            $scope.totalItems = $scope.feedbackList.length;
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
        $scope.view = function (x) {
            $scope.viewData = x;
            $('#viewmodel').modal('show');
        }

        $scope.feedbackList = function () {
            //FETCH BOOKING DETAILS
            $http.get($rootScope.ApiUrl + 'getCustFeedback').then(function (data) {
                console.log('booking info ----------------------', data);
                if (data.data) {
                    $scope.feedbackList = angular.copy(data.data);
                    if ($scope.feedbackList) {
                        setValue()
                    }
                }
            });
        };
        clearForm();
        $scope.feedbackList();              
    }
})();