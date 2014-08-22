angular.module('ursCompetitionMatch', ['rt.encodeuri'])

    .factory('CompetitionMatchApi', ['$q', '$http', '$rootScope', function($q, $http, $rootScope) {
        var self = {
            doPrepareForm: function(match) {
                var d = $q.defer();
                $http.get('/api/competition/'+match.competition+'/match/'+match.id+'/prepare-form')
                     .success(function(data) {
                         $rootScope.permissions = data.feature_flags;
                         $rootScope.formdata = data.datasets;
                         d.resolve(data);
                     })
                     .error(function(err) {
                         d.reject(err);
                     });
                return d.promise;
            },
            
            get: function(match) {
                var d = $q.defer();
                $http.get('/api/competition/'+match.competition+'/match/'+match.id)
                     .success(function(data) {
                         self._updateMatchDataInScope(data);
                         d.resolve(data);
                     })
                     .error(function(err) {
                         d.reject(err);
                     });
                return d.promise;
            },
            
            patch: function(match, data) {
                var d = $q.defer();
                                
                var req = {
                    method: 'PATCH', 
                    url: '/api/competition/'+match.competition+'/match/'+match.id,
                    data: $.param(data), 
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                };
                
                $http(req)
                     .success(function(data) {
                         self._updateMatchDataInScope(data);
                         d.resolve(data);
                     })
                     .error(function(err) {
                         d.reject(err);
                     });
                return d.promise;
            },
            
            _updateMatchDataInScope: function(data) {
                $rootScope.extdata = data._embedded;
                
                $rootScope.match = data.match;
                $rootScope.competition = data._embedded.competition;
                $rootScope.location = data._embedded.location;
                $rootScope.homeTeam = data._embedded.team[data.match.teams.H.team];
                $rootScope.awayTeam = data._embedded.team[data.match.teams.A.team];
            }
        };
        return self;
    }])

    .controller('main', ['$q', '$scope', '$rootScope', 'CompetitionMatchApi', function($q, $scope, $rootScope, CompetitionMatchApi) {
        $rootScope.match = {
            id: $rootScope.matchid,
            competition: $rootScope.compid
        };
        var urlParams = angular.copy($rootScope.match);
                
        $q.all([
            CompetitionMatchApi.get(urlParams),
            CompetitionMatchApi.doPrepareForm(urlParams)
        ]).then(
            function() {
                $scope.loading = false;
                $('.nghide').show().removeClass('nghide');
            },
            function(err) {
                alert(err.title); 
            }
        );
        
        $scope.displayTeamName = function(tid) {
            if ( typeof $rootScope.match._embedded.team[tid] == 'undefined' ) {
                return 'N/A';
            }
            return $rootScope.match._embedded.team[tid].name;
        }
        
        $scope.editDetails = function() {
            $scope.modalLoading = true;
            delete $scope.error;
            delete $scope.success;
            
            $('#MatchDetailsEditDialog').modal('show');
            $q.all([
                CompetitionMatchApi.get(urlParams),
                CompetitionMatchApi.doPrepareForm(urlParams)
            ]).then(
                function(data) {
                    $scope.modalLoading = false;
                },
                function(err) {
                    $scope.modalLoading = false;
                    alert(err.title); 
                }
            );
        }
        
        $scope.saveMatchDetails = function(data) {
            $scope.modalLoading = true;
            
            var changes = {};                
            if ( $rootScope.permissions['match.date'] ) {
                changes['match[date]'] = data.date;
            }
            if ( $rootScope.permissions['match.location'] ) {
                changes['match[location]'] = data.location;
            }
            if ( $rootScope.permissions['match.locationDetails'] ) {
                changes['match[locationDetails]'] = data.locationDetails;
            }
            if ( $rootScope.permissions['match.teams.H.team'] ) {
                changes['match[teams][H][team]'] = data.teams.H.team;
            }
            if ( $rootScope.permissions['match.teams.A.team'] ) {
                changes['match[teams][A][team]'] = data.teams.A.team;
            }
            
            CompetitionMatchApi.patch(urlParams, changes).then(
                function(data) {
                    $scope.modalLoading = false;
                    $scope.success = true;
                },
                function(err) {
                    if ( err.status == 422 ) {
                        $scope.error = {};
                        if ( typeof err.validation_messages.match.date != 'undefined' ) {
                            $scope.error['match.date'] = err.validation_messages.match.date;
                        }
                        if ( typeof err.validation_messages.match.location != 'undefined' ) {
                            $scope.error['match.location'] = err.validation_messages.match.location;
                        }
                        if ( typeof err.validation_messages.match.locationDetails != 'undefined' ) {
                            $scope.error['match.locationDetails'] = err.validation_messages.match.locationDetails;
                        }
                        try {
                            if ( typeof err.validation_messages.match.teams.H.team != 'undefined' ) {
                                $scope.error['match.teams.H.team'] = err.validation_messages.match.teams.H.team;
                            }
                        } catch ( e ) {}
                        try {
                            if ( typeof err.validation_messages.match.teams.A.team != 'undefined' ) {
                                $scope.error['match.teams.A.team'] = err.validation_messages.match.teams.A.team;
                            }
                        } catch ( e ) {}
                    } else {
                        alert(err.title);
                    }
                    $scope.modalLoading = false;
                }
            );
        }
        
        $scope.startMatch = function() {
            if ( $rootScope.match.status != 'NS' ) {
                alert('Match already started!');
            }
            $scope.matchStatusIsChanging = true;
            CompetitionMatchApi.patch(urlParams, {'match[status]': 'S'}).then(
                function(data) {
                    $scope.matchStatusIsChanging = false;
                },
                function(err) {
                    alert(err.title);
                }
            );
        }
        
        $scope.resetMatchToNotStarted = function() {
            if ( $rootScope.match.status == 'NS' ) {
                alert('Match already not started!');
            }
            $scope.matchStatusIsChanging = true;
            CompetitionMatchApi.patch(urlParams, {'match[status]': 'NS'}).then(
                function(data) {
                    $scope.matchStatusIsChanging = false;
                },
                function(err) {
                    alert(err.title);
                }
            );
        }
        
        $scope.saveChangesToRoster = function() {
            if ( $rootScope.match.status != 'NS' ) {
                alert('Match is started, so rosters cannot be changed!');
            }
            $scope.matchRosterIsBeingUpdated = true;
            
            console.log($rootScope.match.teams.H.players);
            console.log($rootScope.match.teams.A.players);
            
            data = {}
            angular.forEach($rootScope.match.teams.H.players, function(v,k) {
                console.log(k);
                console.log(v);
            });
            
        }
        
        $scope.formatDate = function(date, format) {
            return moment(date).format(format);
        }

    }])
  
;