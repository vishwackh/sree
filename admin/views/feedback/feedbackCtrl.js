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
            $scope.list=[];
            $scope.cat={
                r:'',
                c:'' 
            }
        }
        $scope.rating=[1,2,3,4,5];
        $scope.categories=['one','two','three','four'];
        $scope.clear = function () {
            clearForm();
        }
        $scope.feedList = function () {
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

        $scope.revert=function(index,data){
            $scope.categories.push(data);
            $scope.list.splice(index,1)
        };
$scope.addrating=function(userForm1){
    $scope.submitted = true;
    if (userForm1.$valid) {  
        $scope.list.push({'rating':$scope.cat.r,'category': $scope.cat.c});       
        $scope.categories.splice($scope.categories.indexOf($scope.cat.c),1);
        $scope.cat.r='';
        $scope.cat.c='';
        userForm1.$setPristine();

    }
}
        $scope.addEvent = function (userForm) {
            $scope.submitted = true;
            var data={};
         data=_.extend(data,$scope.booking);
         data=_.extend(data,{'rating':$scope.list});
         console.log("request data===>",data);
            if (userForm.$valid) {               
                $http.post($rootScope.ApiUrl + 'custFeedback', data).then(function (data) {
                    if (data) {
                        toaster.pop('success', "Success", "Event addded successfully.");
                        clearForm();
                        userForm.$setPristine();
                        $scope.feedList();
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
                        $scope.feedList();
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
                    $scope.feedList();
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
            var rstData = {
                'feedback_Id': x.feedback_Id
            };
            $http.post($rootScope.ApiUrl + 'getCustRating', rstData).then(function (data) {
                console.log("response data==>",data);
                if (data) {
                    $scope.viewData1=angular.copy(data.data);
                }
            });            
            $('#viewmodel').modal('show');
        }


        clearForm();
        $scope.feedList();              
    }
})();