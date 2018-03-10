(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('PaymentCtrl', PaymentCtrl);

    PaymentCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state'];

    /* @ngInject */
    function PaymentCtrl($scope, $http, $rootScope, localStorageService, toaster, $state) {

        function clearForm() {
            $scope.payment = {
                'booking_Id': '',
                'paidamount': '',
                'paymentType': '',
                'chequeno': '',
                'chequeURL': '',
                'balanceamount': ''
            };
        }


        function setValue() {
            $scope.viewby = 5;
            $scope.totalItems = $scope.Inquiry.length;
            $scope.currentPage = 1;
            $scope.itemsPerPage = $scope.viewby;
            $scope.maxSize = 5; //Number of pager buttons to show                    
        }

        function setValue1() {
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
        $scope.totalPaid = 0;
        $scope.view = function (x) {
            $scope.totalAmount = x.totalamount;
            var data = {
                'booking_Id': x.booking_Id
            };
            $http.post($rootScope.ApiUrl + 'getPaymentDetails', data).then(function (data) {
                if (data) {
                    $scope.bookingDetail = angular.copy(data.data);
                    if ($scope.bookingDetail) {
                        angular.forEach($scope.bookingDetail, function (value) {
                            $scope.totalPaid = $scope.totalPaid + parseInt(value.paidamount);
                        });
                        setValue1()
                    }
                }

            });
            $('#viewmodel').modal('show');
        }
        $scope.paymodal = function (x) {
            var data = {
                'booking_Id': x.booking_Id
            };
            $http.post($rootScope.ApiUrl + 'getPaymentDetails', data).then(function (data) {
                if (data) {
                    $scope.bookingDetail = angular.copy(data.data);
                    if ($scope.bookingDetail) {
                        $scope.balance = $scope.bookingDetail[$scope.bookingDetail.length - 1].balanceamount
                        setValue1()
                    }
                }

            });
            $scope.payment.booking_Id = x.booking_Id;

            $('#paymodel').modal('show');
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
        $scope.addpay = function (userForm) {
            $scope.submitted = true;
            if (userForm.$valid) {
                $http.post($rootScope.ApiUrl + 'addPaymentDetails', $scope.payment).then(function (data) {
                    if (data) {
                        toaster.pop('success', "Success", "Payment Added successfully");
                        clearForm();
                        userForm.$setPristine();
                        $('#paymodel').modal('hide');
                    }
                });
            }
        };
        $scope.bookingData();
        clearForm()
    }
})();