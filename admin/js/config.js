(function () {
    'use strict';

    angular
        .module('emsAdmin')
        .config(function ($stateProvider, $urlRouterProvider) {

            $urlRouterProvider.otherwise('/Login');
            $stateProvider
                .state('Login', {
                    url: '/Login',
                    templateUrl: 'views/login/login.html',
                    controller: 'loginCtrl'
                })               
                .state('dashbord', {
                    url: '/dashbord',
                    templateUrl: 'views/dashbord/dashbord.html',
                    controller: 'dashbordCtrl'
                })
                .state('dashbord.categories', {
                    url: '/specialPackages',
                    templateUrl: 'views/categories/categories.html',
                    controller: 'categoriesCtrl'
                })                
                .state('dashbord.packages', {
                    url: '/packages',
                    templateUrl: 'views/video/video.html',
                    controller: 'videoCtrl'
                }) 
                .state('dashbord.images', {
                    url: '/images',
                    templateUrl: 'views/images/images.html',
                    controller: 'imagesCtrl'
                })                                 
                .state('dashbord.home', {
                    url: '/home',
                    templateUrl: 'views/dashbord/home.html',
                    controller: 'dashbordCtrl'
                });                
        })
        .run(function ($rootScope, $state, $stateParams,$window) {
            $rootScope.$state = $state;
            $rootScope.$stateParams = $stateParams;
            $rootScope.$on('$stateChangeStart',
                function (event, toState, toParams, fromState, fromParams) {
                    // do something
                    $window.scrollTo(0, 0);
                });
        })
        .factory('authInterceptorService', ['$q', '$location', 'localStorageService','$state', function ($q, $location, localStorageService,$state) {

            var authInterceptorServiceFactory = {};
            var _request = function (config) {
                config.headers = config.headers || {};
  
                var authData = localStorageService.get('authorizationData');
                
                if (authData) {
                    config.headers.authorization = authData.token;
                }else{
                    $state.go('Login');
                }
                return config;
            };

            var _responseError = function (rejection) {
                return $q.reject(rejection);
            };

            authInterceptorServiceFactory.request = _request;
            authInterceptorServiceFactory.responseError = _responseError;
            return authInterceptorServiceFactory;
        }])
        .config(['$httpProvider', function ($httpProvider) {
            $httpProvider.defaults.useXDomain = true;
            $httpProvider.interceptors.push('authInterceptorService');
        }]);




})();