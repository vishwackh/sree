(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('inquiryCtrl', inquiryCtrl);

        inquiryCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state'];

    /* @ngInject */
    function inquiryCtrl($scope, $http, $rootScope, localStorageService, toaster, $state) {
        $scope.ldata = {};
        $scope.addlocationdata = function (location,userForm) {
            $scope.submitted = true;
            if (userForm.$valid) {
                $http.post($rootScope.ApiUrl + 'addCategory', location).then(function (data) {
                    
                    if (data.data.status) {
                        toaster.pop('success', "Success", "Category addded successfully.");
                        $scope.ldata.package='';
                        $scope.ldata.title='';
                        $scope.ldata.field1='';
                        $scope.ldata.field2='';
                        $scope.ldata.field3='';
                        $scope.ldata.field4='';
                        $scope.ldata.field5='';
                       userForm.$setPristine();
                        $scope.getCategory()
                    } else {
                        toaster.pop('error', "Error", "Error While adding Category.");
                    }
                    
                    
                });
            }
        };
        function setValue() {
            $scope.viewby = 5;
            $scope.totalItems = $scope.categories.length;
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
        $scope.deletelocation = function(x){
            $scope.delobj = x;
            console.log("delete record",x);
            $('#deletemodel').modal('show');
            };

        $scope.getCategory = function () {
            $http.get($rootScope.ApiUrl + 'getCategory').then(function (data) {
                if(data){
                    $scope.categories = angular.copy(data.data.data);
                    if($scope.categories)
                    { setValue()}
                }
            });
        }
        $scope.view=function(data){            
            $('#viewmodel').modal('show');
            $scope.viewData=angular.copy(data);
        }
        $scope.deletelocationrecord = function(customer){
            $http.post($rootScope.ApiUrl + 'deleteCategory', customer).then(function (data) {
                
                if (data.data.status) {
                    toaster.pop('success', "Success", "Category Deleted successfully.");                
                    $scope.getCategory()
                } else {
                    toaster.pop('error', "Error", "Error While deleting Category.");
                }
                $('#deletemodel').modal('hide');
            });           
        };        
        $scope.getCategory()

    }
})();