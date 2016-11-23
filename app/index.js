// Libraries
import angular from 'angular'
import ngRoute from 'angular-route'
import ngResource from 'angular-resource'

// Controllers
import LoginController from './controllers/login.controller'

// Configuration
import config from './config'

// Application
angular
  .module('ai-edu', [ngRoute, ngResource])
  .config(config)
  .controller('LoginController', LoginController)
  .run(['$rootScope', '$location', ($rootScope, $location) => {
    // Restrict access for guess and user and role
    $rootScope.$on('$routeChangeStart', () => {
      const path = $location.path()
      const user = localStorage.getItem('user')

      switch (path) {
        case '/':
          user && $location.path('/dashboard')
          break
        case '/dashboard':
          !user && $location.path('/')
          break
      }
    })
  }])
