/**
 * Created by thanadej on 8/16/2016 AD.
 */
app.controller('TodoController',  function($state,$http,$rootScope, $scope,$auth,$localStorage) {

    $scope.todos=[];
    $scope.newTodo={};
    $scope.user = $localStorage.user;
    console.log($scope.user);
    $scope.init = function (){

        $http.get('/api/todo').success(function(data){
            $scope.todos=data;
        })
    };

    $scope.save = function(){
        $http.post('/api/todo',$scope.newTodo).success(function (data) {
            $scope.todos.push(data);
            $scope.newTodo={};
        });
    };

    $scope.update = function(index){
        $http.put('/api/todo/'+ $scope.todos[index].id,$scope.todos[index]);
    };

    $scope.delete = function(index){
        $http.delete('/api/todo/'+ $scope.todos[index].id).success(function(){
            $scope.todos.splice(index,1);
        });
    };

    $scope.logout = function() {
        $auth.logout().then(function() {
            $localStorage.user = null;
            $rootScope.currentUser = null;
        });
    }

    $scope.init();

});