export default config

function config ($routeProvider) {
  $routeProvider.when('/', {
    controller: 'LoginController as login',
    templateUrl: './app/views/login.html'
  })

  $routeProvider.when('/dashboard', {
    controller: 'DashboardController as dashboard',
    templateUrl: './app/views/dashboard.html'
  })

  $routeProvider.otherwise('/')
}

config.$inject = ['$routeProvider']
