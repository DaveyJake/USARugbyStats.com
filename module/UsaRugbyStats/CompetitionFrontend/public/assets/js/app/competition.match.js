angular.module('ursCompetitionMatch', ['rt.encodeuri', 'ngRange'])

    .factory('CompetitionMatchEventApi', ['$q', '$http', '$rootScope', 'CompetitionMatchApi', function($q, $http, $rootScope, CompetitionMatchApi) {
        var self = {
                get: function(params) {
                    var d = $q.defer();
                    $http.get('/api/competition/'+params.competition+'/match/'+params.match+'/event/'+params.id)
                         .success(function(data) {
                             d.resolve(data);
                         })
                         .error(function(err) {
                             d.reject(err);
                         });
                    return d.promise;
                },
                
                create: function(match, data) {
                    var d = $q.defer();
                                    
                    var req = {
                        method: 'POST', 
                        url: '/api/competition/'+match.competition+'/match/'+match.id+'/event',
                        data: $.param(data), 
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    };
                    
                    $http(req)
                         .success(function(data) {
                             CompetitionMatchApi.get(match).finally(function() {
                                 d.resolve(data);
                             })
                         })
                         .error(function(err) {
                             d.reject(err);
                         });
                    return d.promise;
                },
                
                remove: function(params) {
                    var d = $q.defer();
                                    
                    var req = {
                        method: 'DELETE', 
                        url: '/api/competition/'+params.competition+'/match/'+params.match+'/event/'+params.id
                    };
                    
                    $http(req)
                         .success(function(data) {
                             CompetitionMatchApi.get({id:params.match, competition:params.competition}).finally(function() {
                                 d.resolve(data);
                             })
                         })
                         .error(function(err) {
                             d.reject(err);
                         });
                    return d.promise;
                },
        };
        return self;
    }])

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
                         self.doPrepareForm(match).finally(function() { 
                             self._updateMatchDataInScope(data); d.resolve(data); 
                         });
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
                
                if ( typeof $rootScope.matchEvents == 'undefined' ) {
                    $rootScope.matchEvents = new Array();
                }
                angular.forEach(['A','H'], function(side) {
                    try {
                        angular.forEach($rootScope.match.teams[side].events, function(rec, index) {
                            var newrec = angular.copy(rec);
                            newrec.side = side;
                            newrec.minute = parseInt(newrec.minute); // Hack to get sorting right

                            if ( typeof newrec.id == 'undefined' || newrec.id == null ) {
                                $rootScope.matchEvents.unshift(newrec);
                                return;
                            }

                            var isAdded = false;
                            angular.forEach($rootScope.matchEvents, function(existingRec, existingIndex) {
                                if ( existingRec.id == newrec.id ) {
                                    $rootScope.matchEvents[existingIndex] = newrec;
                                    isAdded = true;
                                }
                            });
                            if ( ! isAdded ) {
                                $rootScope.matchEvents.unshift(newrec);
                            }
                            
                        });
                    } catch(e) {}
                });
            }
        };
        return self;
    }])

    .controller('main', ['$q', '$scope', '$rootScope', '$timeout', '$interval', 'CompetitionMatchApi', 'CompetitionMatchEventApi', function($q, $scope, $rootScope, $timeout, $interval, CompetitionMatchApi, CompetitionMatchEventApi) {
        $rootScope.match = {
            id: $rootScope.matchid,
            competition: $rootScope.compid
        };
        var urlParams = angular.copy($rootScope.match);
        
        $scope.refreshPage = function() {
            var todo = [ CompetitionMatchApi.get(urlParams) ];
            if ( $rootScope.isEditMode ) {
                todo.push(CompetitionMatchApi.doPrepareForm(urlParams));
            }
            return $q.all(todo);
        }

        $scope.refreshPage().then(
            function() {
                $scope.loading = false;
                $('.nghide').show().removeClass('nghide');
                
                // Periodic page refresh [@TODO should use websockets]
                $interval(function() {
                    if ( $rootScope.match.status == 'NS' || $rootScope.match.status == 'S' ) {
                        $scope.refreshPage();
                    }
                }, 30000);
                
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
                return;
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
                return;
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
        
        $scope.startEditingRoster = function() {
            $scope.isEditingRoster = true;
            $('#content').hide();
            $('#players').removeClass('col-sm-5').addClass('col-sm-12');
        }
        
        $scope.saveChangesToRoster = function() {
            if ( $rootScope.match.status != 'NS' ) {
                alert('Match is started so rosters cannot be changed.');
            }
            if ( $scope.matchRosterIsBeingSaved ) {
                return;
            }
            $scope.matchRosterIsBeingUpdated = true;

            var data = {};
            angular.forEach(['A','H'], function(side) {
                angular.forEach($rootScope.positionKeys, function(pkey, index) {
                    if ( ! $rootScope.permissions['match.teams.'+side+'.players'] ) {
                        return;
                    }
                    try {
                        if ( $rootScope.match.teams[side].players[pkey].player > 0 ) {
                            data['match[teams]['+side+'][players]['+pkey+'][id]'] 
                                = $rootScope.match.teams[side].players[pkey].id;
                            data['match[teams]['+side+'][players]['+pkey+'][number]'] 
                                = $rootScope.match.teams[side].players[pkey].number 
                                    ? $rootScope.match.teams[side].players[pkey].number
                                    : index+1;
                            data['match[teams]['+side+'][players]['+pkey+'][player]'] 
                                = $rootScope.match.teams[side].players[pkey].player;
                        }
                    } catch ( e ) {}
                });
            });
            
            $scope.matchRosterIsBeingSaved = true;
            $scope.error = {};
            
            CompetitionMatchApi.patch(urlParams, data).then(
                function(data) {
                    $scope.matchRosterIsBeingSaved = false;
                    $scope.isEditingRoster = false;
                    $scope.matchRosterSavedSuccessfully = true;
                    $timeout(function() { delete $scope.matchRosterSavedSuccessfully; }, 3000);
                    $('#players').removeClass('col-sm-12').addClass('col-sm-5'); 
                    $('#content').show();
                },
                function(err) {
                    if ( err.status == 422 ) {
                        angular.forEach(['A','H'], function(side) {
                            $scope.error[side] = {};
                            angular.forEach($rootScope.positionKeys, function(pkey, index) {
                                $scope.error[side][pkey] = {};
                                try { $scope.error[side][pkey]['player'] = err.validation_messages.match.teams[side].players[pkey].player; } catch (e) {}
                                try { $scope.error[side][pkey]['number'] = err.validation_messages.match.teams[side].players[pkey].number; } catch (e) {}
                            });
                        });
                        $scope.matchRosterIsBeingSaved = false;
                    } else {
                        alert(err.title);
                        $scope.matchRosterIsBeingSaved = false;
                    }
                }
            );
        }
        
        $scope.cancelEditingRoster = function() {
          $scope.isEditingRoster = false;
          $('#players').removeClass('col-sm-12').addClass('col-sm-5'); 
          $('#content').show();           
        }
        
        $scope.canAddEvents = function() {
            if ( typeof $rootScope.permissions == 'undefined' ) {
                return false;
            }
            return $rootScope.permissions['match.teams.H.events'] 
                || $rootScope.permissions['match.teams.A.events'];
        }
        
        $scope.addEventOfType = function(type) {
            delete $scope.error;
            
            $rootScope.newEvent = { event: type };
            $('#newEvent.minute').focus();
            $('#MatchEventCreateDialog').modal('show');
        }
        
        $scope.createNewEvent = function(newEvent) {
            $scope.modalLoading = true;
            
            var formdata = {};
            angular.forEach(newEvent, function(v,k) {
                if ( k == 'side' ) {
                    formdata["event[team]"] = $rootScope.match.teams[v].id;
                    return;
                }
                formdata["event["+k+"]"] = v;
            });
            
            CompetitionMatchEventApi.create(urlParams, formdata)
                .then(
                    function()
                    {
                        $scope.matchEventAddedSuccessfully = true;
                        $timeout(function() { delete $scope.matchEventAddedSuccessfully; }, 3000);
                        
                        $('#MatchEventCreateDialog').modal('hide');
                    },
                    function(err) 
                    {
                        if ( err.status == 422 ) {
                            $scope.error = err.validation_messages;
                            return;
                        }
                        alert(err.title);
                    }
                )
                .finally(function() {
                    $scope.modalLoading = false;
                })
        }
        
        $scope.trashEvent = function(event) {
            $('#MatchEventRemoveDialog').modal('show');
            $('#MatchEventRemoveDialog .yesbutton').unbind('click').click(function() {
                $scope.modalLoading = true;
                $scope.trashEventForRealz(event).finally(function() {
                    $scope.modalLoading = false;
                    $('#MatchEventRemoveDialog').modal('hide');
                });
            });
        }
        
        $scope.trashEventForRealz = function(event) {
            var p = $q.defer();
            if ( event.id != null ) {
                CompetitionMatchEventApi.remove({
                    competition: $rootScope.match.competition,
                    match: $rootScope.match.id,
                    id: event.id
                }).then(
                    function() {
                        $rootScope.matchEvents.splice($rootScope.matchEvents.indexOf(event), 1);
                        p.resolve();
                    },
                    function(err) {
                        alert(err.title);
                        p.reject();
                    }
                );
            } else {
                $rootScope.matchEvents.splice($rootScope.matchEvents.indexOf(event), 1);
                p.resolve();
            }            
            return p.promise;
        }
        
        $scope.markCompleted = function() {
            if ( $rootScope.match.status == 'NS' ) {
                alert('Match must be in progress!');
                return;
            }
            $scope.matchStatusIsChangingToCompleted = true;
            CompetitionMatchApi.patch(urlParams, {'match[status]': 'F'}).then(
                function(data) {
                    $scope.matchStatusIsChangingToCompleted = false;
                },
                function(err) {
                    alert(err.title);
                }
            );
        }
        
        $scope.markForfeit = function(status) {
            $scope.matchStatusIsChangingToForfeit = true;
            CompetitionMatchApi.patch(urlParams, {'match[status]': status}).then(
                function(data) {
                    $scope.matchStatusIsChangingToForfeit = false;
                },
                function(err) {
                    alert(err.title);
                }
            );
        }

        
        $scope.formatDate = function(date, format) {
            return moment(date).format(format);
        }

    }])
  
;