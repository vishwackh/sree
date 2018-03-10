(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('bookingDetailsCtrl', bookingDetailsCtrl);

    bookingDetailsCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state', '$filter'];

    /* @ngInject */
    function bookingDetailsCtrl($scope, $http, $rootScope, localStorageService, toaster, $state, $filter) {
        function clearForm() {
            $scope.booking = {
                'customername': '',
                'bridename': '',
                'brideDOB': '',
                'groomname': '',
                'groomDOB': '',
                'eventdate': '',
                'numberOfDays': '',
                'eventenddate': '',
                'phonenumber': '',
                'emailid': '',
                'eventname': '',
                'panadharno': '',
                'address': '',
                'totalamount': '',
                'addOnServices': '',
                'bookingType': '2',
                'paidamount': '',
                'paymentType': '',
                'chequeno': '',
                'chequeURL': '',
                'balanceamount': ''
            };
            $scope.bookingData();
        }
        $scope.clear = function(){
            clearForm() 
        }
        $scope.popup1 = {
            opened: false
        };
        $scope.popup2 = {
            opened: false
        };
        $scope.popup3 = {
            opened: false
        };
        $scope.popup4 = {
            opened: false
        };
        $scope.open1 = function () {
            $scope.popup1.opened = true;
        };
        $scope.open2 = function () {
            $scope.popup2.opened = true;
        };
        $scope.open3 = function () {
            $scope.popup3.opened = true;
        };
        $scope.open4 = function () {
            $scope.popup4.opened = true;
        };
        // $scope.altInputFormats = ['M!/d!/yyyy'];
        $scope.dateOptions = {
            dateDisabled: false,
            formatYear: 'yyyy/MM/dd',
            maxDate: new Date(2020, 5, 22),
            minDate: new Date(),
            startingDay: 1
        };
        $scope.dateOptions1 = {
            dateDisabled: false,
            formatYear: 'yyyy/MM/dd',
            maxDate: new Date(),
            minDate: new Date(1940, 5, 22),
            startingDay: 1
        };
        $scope.updatemodal = function (x) {
            $scope.booking = x;
            $scope.booking.brideDOB = x.brideDOB?angular.copy(new Date(x.brideDOB)):'';
            $scope.booking.groomDOB = x.groomDOB?angular.copy(new Date(x.groomDOB)):'';
            $scope.booking.eventdate = angular.copy(new Date(x.eventdate));
            $scope.booking.eventenddate = angular.copy(new Date(x.eventenddate));
            $('#editmodel').modal('show');
        }
        $scope.delInquiry = undefined;
        $scope.cancelmodal = function (x) {
            $scope.delInquiry = x;
            $('#deletemodel').modal('show');
        }
        $scope.addbooking = function (userForm) {
            $scope.submitted = true;
            if (userForm.$valid) {
                $scope.booking.brideDOB =  $scope.booking.brideDOB?angular.copy($filter('date')($scope.booking.brideDOB, "yyyy-MM-dd")):null;
                $scope.booking.groomDOB = $scope.booking.groomDOB?angular.copy($filter('date')($scope.booking.groomDOB, "yyyy-MM-dd")):null;
                $scope.booking.eventdate = angular.copy($filter('date')($scope.booking.eventdate, "yyyy-MM-dd"));
                $scope.booking.eventenddate = angular.copy($filter('date')($scope.booking.eventenddate, "yyyy-MM-dd"));
                $http.post($rootScope.ApiUrl + 'booking', $scope.booking).then(function (data) {
                    if (data) {
                        toaster.pop('success', "Success", "Booking details Inserted successfully");
                        clearForm();
                        userForm.$setPristine();
                        $scope.bookingData();
                    }
                });
            }
        };
        $scope.updatebooking = function (userForm1) {
            $scope.submitted = true;
            if (userForm1.$valid) {
                $scope.booking.brideDOB = $scope.booking.brideDOB?angular.copy($filter('date')($scope.booking.brideDOB, "yyyy-MM-dd")):null;
                $scope.booking.groomDOB = $scope.booking.groomDOB?angular.copy($filter('date')($scope.booking.groomDOB, "yyyy-MM-dd")):null;
                $scope.booking.eventdate = angular.copy($filter('date')($scope.booking.eventdate, "yyyy-MM-dd"));
                $scope.booking.eventenddate = angular.copy($filter('date')($scope.booking.eventenddate, "yyyy-MM-dd"));
                $http.post($rootScope.ApiUrl + 'updateBooking', $scope.booking).then(function (data) {
                    if (data) {
                        toaster.pop('success', "Success", "Booking details updated successfully");
                        clearForm();
                        userForm1.$setPristine();
                        $scope.bookingData();
                    }
                    $('#editmodel').modal('hide');
                });
            }
        };

        $scope.cancelBooking = function () {
            var data = {
                'booking_Id': $scope.delInquiry.booking_Id,
                'eventdate': $scope.delInquiry.eventdate
            };
            $http.post($rootScope.ApiUrl + 'bookingCanel', data).then(function (data) {
                if (data) {
                    toaster.pop('success', "Success", "Booking canceled successfully.");
                    $scope.bookingData();
                }
                $('#deletemodel').modal('hide');
            });
        }

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
        $scope.view = function (x) {
            $scope.viewData = x;
            $('#viewmodel').modal('show');
        }

        $scope.bookingData = function () {

            //FETCH BOOKING DETAILS
            $http.get($rootScope.ApiUrl + 'getbookingDetails').then(function (data) {
                if (data.data) {
                    $scope.Inquiry = angular.copy(data.data);
                    if ($scope.Inquiry) {
                        setValue()
                    }
                }
            });
        };
        clearForm();
       
    }
})();