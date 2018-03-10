(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('eventsCtrl', eventsCtrl);

    eventsCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state', '$filter'];

    /* @ngInject */
    function eventsCtrl($scope, $http, $rootScope, localStorageService, toaster, $state, $filter) {

        function clearForm() {
            $scope.booking = {
                'eventname': '',
                'eventdate': '',
                'description': ''
            };
        }
        $scope.clear = function () {
            clearForm();
        }

        $scope.popup1 = {
            opened: false
        };

        $scope.open1 = function () {
            $scope.popup1.opened = true;
        };

        // $scope.altInputFormats = ['M!/d!/yyyy'];
        $scope.dateOptions = {
            dateDisabled: false,
            formatYear: 'yyyy/MM/dd',
            maxDate: new Date(2020, 5, 22),
            minDate: new Date(),
            startingDay: 1
        };

        $scope.addEvent = function (userForm) {
            $scope.submitted = true;
            if (userForm.$valid) {
                $scope.booking.eventdate = angular.copy($filter('date')($scope.booking.eventdate, "yyyy-MM-dd"));
                $http.post($rootScope.ApiUrl + 'addEvent', $scope.booking).then(function (data) {
                    if (data) {
                        toaster.pop('success', "Success", "Event addded successfully.");
                        clearForm();
                        userForm.$setPristine();
                        $scope.eventData();
                    }
                });
            }
        };
        $scope.updatemodal = function (x) {
            $scope.booking = x;
            $scope.booking.eventdate = angular.copy(new Date(x.eventdate));
            $('#editmodel').modal('show');
        }
        $scope.delInquiry = undefined;
        $scope.deletemodal = function (x) {
            $scope.delInquiry = x;
            $('#deletemodel').modal('show');
        }
        $scope.updateEvent = function (userForm11) {
            $scope.submitted = true;
            if (userForm11.$valid) {
                $scope.booking.eventdate = angular.copy($filter('date')($scope.booking.eventdate, "yyyy-MM-dd"));               
                $http.post($rootScope.ApiUrl + 'updateEvent', $scope.booking).then(function (data) {
                    if (data) {
                        toaster.pop('success', "Success", "Event Updated successfully.");
                        clearForm();
                        userForm1.$setPristine();
                        $scope.eventData();
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
                    $scope.eventData();
                }
                $('#deletemodel').modal('hide');
            });
        }

        function setValue() {
            $scope.viewby = 5;
            $scope.totalItems = $scope.eventDataList.length;
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

        $scope.eventData = function () {
            //FETCH BOOKING DETAILS
            $http.get($rootScope.ApiUrl + 'getEventData').then(function (data) {
                console.log('booking info ----------------------', data);
                if (data.data) {
                    $scope.eventDataList = angular.copy(data.data);
                    if ($scope.eventDataList) {
                        setValue()
                    }
                }
            });
        };
        clearForm();
        $scope.eventData();
    }
})();