export default class LoginController {
  constructor ($http, $location) {
    this.email = ''
    this.password = ''
    this.authError = false
    this.$http = $http
    this.$location = $location
  }

  perform () {
    this.$http
      .post('/proyecto-final/api/authenticate.php', {
        email: this.email,
        password: this.password
      })
      .then((resp) => {
        console.log(resp)

        if (resp.data.authenticated) {
          localStorage.setItem('user', JSON.stringify(resp.data.user))
          this.authError = false
          this.$location.path('/dashboard')
        } else {
          this.authError = true
        }
      })
  }
}

LoginController.$inject = ['$http', '$location']
