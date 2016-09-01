angular.module('app.routes', [])

.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider
    
  

      .state('page', {
    url: '/page2',
    templateUrl: 'templates/page.html',
    controller: 'pageCtrl'
  })

  .state('rutas', {
    url: '/page4',
    templateUrl: 'templates/rutas.html',
    controller: 'rutasCtrl'
  })

  .state('rutaA', {
    url: '/page5',
    templateUrl: 'templates/rutaA.html',
    controller: 'rutaACtrl'
  })

  .state('page2', {
    url: '/page6',
    templateUrl: 'templates/page2.html',
    controller: 'page2Ctrl'
  })

$urlRouterProvider.otherwise('/page2')

  

});