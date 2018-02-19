(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .controller('imagesCtrl', imagesCtrl);

    imagesCtrl.$inject = ['$scope', '$http', '$rootScope', 'localStorageService', 'toaster', '$state'];

    /* @ngInject */
    function imagesCtrl($scope, $http, $rootScope, localStorageService, toaster, $state) {
        $scope.ldata = {};
        $scope.path = '../api/upload/';
        $scope.myFile = [];
        $scope.getFileDetails = function (e) {
            $scope.myFile = [];
            $scope.$apply(function () {
                // STORE THE FILE OBJECT IN AN ARRAY.
                for (var i = 0; i < e.files.length; i++) {
                    $scope.myFile.push(e.files[i])
                }
            });
        };
        $scope.message = "Upload Image"
        $scope.addImage = function (ldata,userForm) {
            $scope.submitted = true;
           

            if (userForm.$valid) {
                $scope.message = "Uploading......";
                var file = $scope.myFile;
                var fd = new FormData();
                fd.append('file', file);
                fd.append('title', ldata.title);
                fd.append('description', ldata.description);

                $http.post($rootScope.ApiUrl + 'addImage', fd, {
                    transformRequest: angular.identity,
                    headers: { 'Content-Type': undefined }
                }).then(function (data) {
                    if (data.data.status) {
                        toaster.pop('success', "Success", "Image addded successfully.");
                        $scope.myFile = [];
                        $scope.ldata.title='';
                        $scope.ldata.description='';
                        userForm.$setPristine();
                        $scope.getImageList()
                        $scope.message = "Upload Image"
                    } else {
                        toaster.pop('error', "Error", "Rename image name & try again.");
                        $scope.message = "Upload Image"
                    }
                });

            }
        };
        $scope.deleteImage = function (x) {
            $scope.delobj = x;
            $('#deletemodel').modal('show');
        };

        $scope.viewImage = function (x) {
            $scope.image = '../api/upload/' + x.url;

            $('#viewmodel').modal('show');
        }

        $scope.getImageList = function () {
            $http.get($rootScope.ApiUrl + 'getImageList').then(function (data) {
                if (data) {
                    $scope.imageList = angular.copy(data.data.data);
                    if($scope.imageList)
                   { setValue()}
                }
            });
        };



        function setValue() {
            $scope.viewby = 5;
            $scope.totalItems = $scope.imageList.length;
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

        $scope.deleteImagerecord = function (customer) {
            $http.post($rootScope.ApiUrl + 'deleteImage', customer).then(function (data) {

                if (data.data.status) {
                    toaster.pop('success', "Success", "Image Deleted successfully.");
                    $scope.getImageList()
                } else {
                    toaster.pop('error', "Error", "Error While deleting Image.");
                }
                $('#deletemodel').modal('hide');
            });
        };
      
        $scope.getImageList()

    }
})();