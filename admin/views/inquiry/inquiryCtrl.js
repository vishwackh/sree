(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('inquiryCtrl', inquiryCtrl);

        inquiryCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state','$filter'];

    /* @ngInject */
    function inquiryCtrl($scope, $http, $rootScope, localStorageService, toaster, $state,$filter) {

        function clearForm(){
            $scope.booking={
                'customername':'',
                'bridename':'',
                'brideDOB':'',
                'groomname':'',
                'groomDOB':'',
                'eventdate':'',
                'numberOfDays':'',
                'eventenddate':'',
                'phonenumber':'',
                'emailid':'',
                'eventname':'',
                'panadharno':'',
                'address':'',
                'totalamount':'',
                'addOnServices':'',
                'bookingType':'1'	   
            };
        }
        $scope.clear=function(){
            clearForm();
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
          $scope.addEnquiry =function(userForm){
            $scope.submitted = true;
            if (userForm.$valid) {
                $scope.booking.brideDOB=angular.copy($filter('date')($scope.booking.brideDOB, "yyyy-MM-dd"));   
                $scope.booking.groomDOB=angular.copy($filter('date')($scope.booking.groomDOB, "yyyy-MM-dd"));
                $scope.booking.eventdate=angular.copy($filter('date')($scope.booking.eventdate, "yyyy-MM-dd"));           
                $scope.booking.eventenddate=angular.copy($filter('date')($scope.booking.eventenddate, "yyyy-MM-dd")); 
                $http.post($rootScope.ApiUrl + 'booking', $scope.booking).then(function (data) {       
                    if (data) {
                        toaster.pop('success', "Success", "Inquiry addded successfully.");
                        clearForm();
                        userForm.$setPristine();
                        $scope.enquiryData(); 
                    }
                });
            }
          };   
          $scope.updatemodal=function(x){
            $scope.booking = x;
            $scope.booking.brideDOB=angular.copy(new Date(x.brideDOB));   
            $scope.booking.groomDOB=angular.copy(new Date(x.groomDOB));
            $scope.booking.eventdate=angular.copy(new Date(x.eventdate));           
            $scope.booking.eventenddate=angular.copy(new Date(x.eventenddate));             
            $('#editmodel').modal('show');             
          }
          $scope.delInquiry=undefined;
          $scope.deletemodal = function(x){
            $scope.delInquiry=x;
            $('#deletemodel').modal('show'); 
          }
          $scope.updateInquiry = function(userForm){
            $scope.submitted = true;
            if (userForm.$valid) {
                $scope.booking.brideDOB=angular.copy($filter('date')($scope.booking.brideDOB, "yyyy-MM-dd"));   
                $scope.booking.groomDOB=angular.copy($filter('date')($scope.booking.groomDOB, "yyyy-MM-dd"));
                $scope.booking.eventdate=angular.copy($filter('date')($scope.booking.eventdate, "yyyy-MM-dd"));           
                $scope.booking.eventenddate=angular.copy($filter('date')($scope.booking.eventenddate, "yyyy-MM-dd")); 
                $http.post($rootScope.ApiUrl + 'updateBooking', $scope.booking).then(function (data) {       
                    if (data) {
                        toaster.pop('success', "Success", "Inquiry Updated successfully.");
                        clearForm();
                        userForm.$setPristine();
                        $scope.enquiryData(); 
                    }
                    $('#editmodel').modal('hide'); 
                });
            }
          };
          $scope.deleteInquiry=function(){
           var data={'booking_Id':$scope.delInquiry.booking_Id};
           $http.post($rootScope.ApiUrl + 'deleteEnquiry', data).then(function (data) {       
            if (data) {
                toaster.pop('success', "Success", "Inquiry deleted successfully.");
                $scope.enquiryData(); 
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

        $scope.enquiryData = function () {
            //FETCH BOOKING DETAILS
            $http.get($rootScope.ApiUrl + 'getenquiryDetails').then(function (data) {
                console.log('booking info ----------------------', data);
                if (data.data) {
                    $scope.Inquiry=angular.copy(data.data);
                    if($scope.Inquiry)
                    { setValue()}                   
                }
            });
        };
        clearForm();
        $scope.enquiryData();                
    }
})();