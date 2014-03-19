// Helper Function for bad images.
 function imgError(image) {
    image.onerror = "";
    if ($(image).hasClass('group-logo')) {
      image.src = "assets/group-icon.png";
    }
    else {
      image.src = "/assets/default_profile.png";
    }

    return true;
  }

 function closePopover(popoverButton) {
	 var id = $(popoverButton).attr('id');
	 $('.player-popover-link a#' + id + '.popover-active').popover('hide').removeClass('popover-active');
 }

 function iframeLoaded(iframe) {
	 setTimeout(function() {
      var id = $(iframe).attr('id');
      var iframeHeight = $(iframe).contents().height();
      var iframeWidth = $(iframe).contents().width();
      if ($(iframe).hasClass('player-popover-iframe')) {
        $('.popover#' + id).height(iframeHeight + 50).width(iframeWidth + 50);
        $('.popover#' + id + ' iframe').height(iframeHeight).width(iframeWidth);
      }
      if ($(iframe).hasClass('player-modal-iframe')) {
        var iframeHeight = $(iframe).contents().find('#wrapper').height();
        $(iframe).height(iframeHeight).width(iframeWidth);
      }
	 }, 50);
 }

 function playerSyncPoll(t, roster_id, game_id, team_id) {
     roster_id = roster_id || '';
     game_id = game_id || '';
     team_id = team_id || '';
	 $.ajax({
		 type: "POST",
		 url: "player_sync_poll.php",
		 data: { token: t },
		 success: function(response) {
			 if (response == '1') {
				 setTimeout(playerSyncPoll, 2000, t, roster_id, game_id, team_id);
			 }
			 if (response == '2') {
				 $('.progress > .bar').css('width', '60%');
				 setTimeout(playerSyncPoll, 2000, t, roster_id, game_id, team_id);
			 }
			 if (response == '4') {
				 $('.progress > .bar').css('width', '100%');
				 $('#synching').html('Players synched.');
                 if (roster_id == '') {
                    window.location = '/game_roster.php?no_sync=1&gid=' + game_id + '&tid=' + team_id;
                 }else {
                    window.location = '/event_roster.php?no_sync=1&roster_id=' + roster_id;
                 }
			 }
		 }});
}

function playerRoleSyncPoll(t, roster_id) {
	tokens = JSON.stringify(t);
	$.ajax({
		 type: "POST",
		 url: "player_role_sync_poll.php",
		 data: { tokens: tokens },
		 success: function(response) {
			 width = Math.round(response).toString() + '%';
			 $('.progress > .bar').css('width', width);
			 if (response < 100) {
				setTimeout(playerRoleSyncPoll, 5000, t, roster_id);
			 } else {
				$('#synching').html('Players and roles synched.');
				window.location = '/event_roster.php?no_sync=1&roster_id=' + roster_id;
			 }
		 }});
}

$(document).ready(function() {
  $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
  $('input.text-input').css({backgroundColor:"#FFFFFF"});
  $('input.text-input').focus(function(){
    $(this).css({backgroundColor:"#FFDDAA"});
  });
  $('input.text-input').blur(function(){
    $(this).css({backgroundColor:"#FFFFFF"});
  });
  if (!$('#signoff').hasClass('Finished')) {
    $('#signoff').hide();
  }

  var initDateTime = function() {
    $('.date_select').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

    $('.time-entry').timeEntry({
      spinnerImage: '',
      spinnerBigImage: ''
    });

    $(".chzn-select").chosen({allow_single_deselect: true, width: "100%", no_results_test: "No results matched"});
    $('#available-fields').live('click', function(e) {
      e.preventDefault();
      resourceSync.initSync();
      $(this).html('<label for="field" id="field_label" class="control-label">Playing Field</label>');
    });
    $(".chzn-select-team").chosen({
      template: function (text, templateData) {
        return [
          "<span style='float: right'>" + templateData.type.charAt(0).toUpperCase() + templateData.type.slice(1) + "</span>",
          "<div>" + text + "</div>",
          "<div><i>" + templateData.description + "</i></div>"
        ].join("");
      }
    });
  };

  var playerPopoverInit = function() {
    $('.player-popover-link a').popover({
	  trigger: 'manual',
	  html: true,
	  content: '',
      placement: function(context, source) {
        var position = $(source).position();
        if (position.left > 515) {
          return "left";
        }
        else {
          return "right";
        }
      }
    }).click(function(e) {
	e.preventDefault();
	e.stopPropagation();
	  var el = $(this);
	if (!$(this).hasClass('popover-active')) {
		var playerId = $(this).attr('id');
		$(this).addClass('popover-active').popover('show');
	}
	$('.player-popover-link a.popover-active').each(function(index){
		if (!$(this).is(el)) {
			$(this).removeClass('popover-active').popover('hide');
		}
	});
	    return false;
    });
  };

  var playerModalInit = function() {
    $('.player-modal-link a').click(function(e){
      var modalTemplate = $(this).data('modal-template');
      $('body').prepend(modalTemplate);
      var modalId = $(this).attr('id');
      $('#' + modalId + '-modal').modal().bind('hidden', function(){
        $('body .player-modal').remove();
      });
      return false;
    });
  }

  var playerNameInit = function() {
    playerPopoverInit();
    playerModalInit();
  }


  initDateTime();
  playerNameInit();



  var getFormData = function(formElementName, errorElementName) {
    var errorElementName = errorElementName || '#form-validation-error';
    errorElementName = formElementName + ' ' + errorElementName;
    var formData = {validated : true};
    $(formElementName + ' input[type!="submit"], ' + formElementName + ' select').each(function(index){
      var elementValidated = true;
      var validationMessage = '';
      // Get value
      var val = "";
      if ($(this).hasClass('time-entry')) {
        val = $(this).timeEntry('getTime');
      }
      else {
        val = $(this).val();
      }

      // Validate
      if ($(this).hasClass('required') && (val == "" || val == null)) {
        elementValidated = false;
        var missing = $(this).prop('placeholder') || $(this).data('placeholder') || "Missing Fields";
        validationMessage = 'Please Enter ' + missing;
      }
      if ($(this).data('minute-max-value') && val > $(this).data('minute-max-value')) {
        elementValidated = false;
        validationMessage = 'Maximum allowed is ' + $(this).data('minute-max-value');
      }

      if (elementValidated == false) {
         $(errorElementName + ' .error-message').text(validationMessage);
         $(errorElementName).show();
         $(this).parents('.control-group').addClass('error');
         formData.validated = false;
         return false;
      }
      else {
        $(this).parents('.control-group').removeClass('error');
        formData[$(this).prop('id')] = val;
      }
    });
    return formData;
  }
  var storedHeight = $('.loader-height').height();
  var reloadData = function(elementName, loaderTarget, extraOpsPostLoad) {
    $(elementName).fadeOut('slow', function(){
          $(elementName).html('<div style="width: 100%; position:relative;"><img src="/assets/img/ajax-loader.gif"></div>').find('div').height(storedHeight);
          $(elementName).fadeIn('fast');
          $(elementName).load(loaderTarget, function(){
            $(elementName).fadeIn('fast');
            initDateTime();
            if (extraOpsPostLoad) {
              extraOpsPostLoad();
            }
          });
        });
  }

  //Team Games.
  $('.chzn-game-switcher').chosen().change(function(){
    var game_id = $(this).val();
    var url = 'game.php?id=' + game_id + '&iframe=true' + $('.game-loadspace').data('requested-ops');
    $('.game-loadspace').load(url + ' #wrapper', function(response, status) {
      playerNameInit();
      if (status == 'success' && windowProxy) {
        windowProxy.post({
          'height' : $('html').height(),
          'id' : window.location.hash
        });
      }
    });
  });

  $('.game-switcher-row .chzn-container').mousedown(function(){
    if (windowProxy) {
      var height = $(this).height() + $(this).find('.chzn-drop').height();
      windowProxy.post({
          'height' : height,
          'id' : window.location.hash
      });
    }
  });


  //adding a score event for a game
  $("#addscore").live('submit', function() {
    // validate and process form
    // first hide any error messages
    $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
    var formData = getFormData('#addscore', '#form-validation-error');
    if (!formData || formData.validated == false) {
      return false;
    }

    var refresh = $("#refresh").val();
    var game_id = $("#game_id").val();
    var game_score = '/game_score.php?id=' + game_id;

    $.post('/add_score_process.php', {
      minute: formData.minute,
      type: formData.type,
      player: formData.player,
      game_id: game_id
    }, function(){
        reloadData('#events_wrapper', refresh);
        reloadData('#score', game_score);
      });
    return false;
  });


  //adding a sub event to a game
  $("#addsub").live('submit', function() {
    $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
    var formData = getFormData('#addsub', '#form-validation-error');
    if (!formData || formData.validated == false) {
      return false;
    }
    formData.subrefresh = $("#subrefresh").val();
    formData.game_id = $("#game_id").val();
    $.post('/add_sub_process.php', {
      submin: formData.submin,
      subtype: formData.subtype,
      player_on: formData.player_on,
      player_off: formData.player_off,
      game_id: formData.game_id
    }, function(){
      reloadData('#subs, #events_wrapper', formData.subrefresh);
    });

    return false;
  });



  //adding a card event to a game
  $("#addcard").live('submit', function() {
    $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
    var formData = getFormData('#addcard', '#form-validation-error');
    if (!formData || formData.validated == false) {
      return false;
    }

    formData.cardrefresh = $("#cardrefresh").val();
    formData.card_game_id = $("#card_game_id").val();

    $.post('/add_card_process.php', {
      cardmin: formData.cardmin,
      cardtype: formData.cardtype,
      cardplayer: formData.cardplayer,
      card_game_id: formData.card_game_id
    }, function(){
      reloadData('#cards, #events_wrapper', formData.cardrefresh);
    });
    return false;
  });

  //adding a status to a game
  $("#addstatus").live('submit', function() {
    $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
    var formData = getFormData('#addstatus', '#form-validation-error');
    if (!formData || formData.validated == false) {
      return false;
    }

    formData.status = $("#gamestatus").val();
    formData.status_game_id = $("#status_game_id").val();

    $.post('/add_status_process.php', {
      status: formData.status,
      game_id: formData.status_game_id
    }, function(){
      if (formData.status == 28) {
          $('#signoff').show();
      } else {
          $('#signoff').hide();
      }
    });
    return false;
  });



    //deleting a scoring event from a game
    $(".dScore").live('click', function() {

        var refresh = $("#refresh").val();
            var dId = $(this).data('del-score-id');

            var game_id = $("#game_id").val();
            var game_score = '/game_score.php?id=' + game_id;

        $.post('/delete_score_process.php',
        {id: dId, game_id: game_id},
        function(){
          reloadData('#events_wrapper', refresh);
          reloadData('#score', game_score);

        });

    return false;
    });


    //delete a sub event from a game
    $(".dSub").live('click', function() {

        var subDrefresh = $("#subDrefresh").val();
        var dId = $(this).data('del-sub-id');
            var game_id = $("#game_id").val();

        $.post('/delete_sub_process.php',
        {id: dId, game_id: game_id},
        function(){
          reloadData('#events_wrapper', subDrefresh);
        });

    return false;
    });


    //delete a card event from a game
    $(".dCard").live('click', function() {

        var cardDrefresh = $("#cardrefresh").val();
        var dId = $(this).data('del-card-id');
            var game_id = $("#game_id").val();

        $.post('/delete_card_process.php',
        {id: dId, game_id: game_id},
        function(){
          reloadData('#events_wrapper', cardDrefresh);
        });

    return false;
    });


    //add a competition
    $("#addcomp").live('submit', function() {
      $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
      var formData = getFormData('#addcomp', '#form-validation-error');
      if (!formData || formData.validated == false) {
        return false;
      }
      else {
        return true;
      }
    });
      
    //Select All checkboxes
    $(document).ready(function(){
    $('input#active, input#sync_all').bind('click', function(){
        var status = $(this).is(':checked');
        $('input[type="checkbox"]', $(this).parent('')).attr('checked', status);
        });
        
    });

    var resourceSync = {
      getTeams: function() {
        var home_team_id = $('.addgame-home').val();
        var away_team_id = $('.addgame-away').val();
        var teams = {};
        if (home_team_id != '') {
          teams.home = home_team_id;
        }
        if (away_team_id != '') {
          teams.away = away_team_id;
        }
        return teams;
      },

      buildResourcesByTeam: function(resources, team_id) {
        var resourcesByTeam = $('#addgamediv').data('resourcesByTeam') || {};
        if (!$.isArray(resources)) {
          var resourcesToStore = new Array(resources);
        }
        else {
          var resourcesToStore = resources;
        }
        resourcesByTeam[team_id] = resourcesToStore;
        $('#addgamediv').data('resourcesByTeam', resourcesByTeam);

        return resourcesByTeam;
      },

      buildTeamsByResource: function(resources, team_id) {
        var teamsByResource = $('#addgamediv').data('teamsByResource') || {};
        for (r in resources) {
          var resource = resources[r];
          if ($.isEmptyObject(teamsByResource[resource.uuid])) {
            teamsByResource[resource.uuid] = {
              uuid: resource.uuid,
              title: resource.title,
            }
          }
        }
        $('#addgamediv').data('teamsByResource', teamsByResource);
        return teamsByResource;
      },

      buildAvailableResources: function(teams) {
        var availableResources = {};
        for (t in teams) {
          var search_team = teams[t];
          var resourcesByTeam = $('#addgamediv').data('resourcesByTeam') || {};
          if (!$.isEmptyObject(resourcesByTeam[search_team])) {
            for (av_resource in resourcesByTeam[search_team]) {
              resourceForATeam = resourcesByTeam[search_team][av_resource];
              availableResources[resourceForATeam.uuid] = {};
              availableResources[resourceForATeam.uuid].uuid = resourceForATeam.uuid;
              availableResources[resourceForATeam.uuid].title = resourceForATeam.title;
              availableResources[resourceForATeam.uuid].teamOwner = search_team;
            }
          }

        }
        return availableResources;
      },

      showAvailableResources: function(availableResources) {
        $('#addgamediv #field').children('option[value!=""]').remove().end();
          for (av_resource in availableResources) {
            var resourceData = availableResources[av_resource];
            var newOption = $('<option value="' + resourceData.uuid + '">' + resourceData.title + '</option>').data('teamOwner', resourceData.teamOwner);
            $('#addgamediv #field').append(newOption);
          }
          $('#addgamediv #field').removeAttr('disabled').trigger("liszt:updated");
      },

      initSync: function() {
        var syncClass = this;
        var base_url = $('#addgamediv #active-domain').val() + '/api/v1/rest/groups/';
        $('#addgamediv #field').attr('disabled', 'disabled').trigger("liszt:updated");
        // Get home first.
        var teams = this.getTeams();
        var url = base_url + teams.home + '/resources.jsonp';
        $.getJSON(url + "?callback=?", null, function(resources) {
          // Just sync.
          syncClass.buildResourcesByTeam(resources, teams.home);
          syncClass.buildTeamsByResource(resources, teams.home);

          var url = base_url + teams.away + '/resources.jsonp';
          $.getJSON(url + "?callback=?", null, function(resources) {
            syncClass.buildResourcesByTeam(resources, teams.away);
            syncClass.buildTeamsByResource(resources, teams.away);

            //Finally build available resources after the last call completes.
            var latestTeams = syncClass.getTeams();
            if (latestTeams.home == teams.home && latestTeams.away == teams.away) {
              // If the teams have changed during our requests, let the next set take care of it.
              var availableResources = syncClass.buildAvailableResources(latestTeams);
              syncClass.showAvailableResources(availableResources);
            }
          });
        });
      }
    }

    //adding a game
    $("#addgame").live('submit', function() {
        $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
        var formData = getFormData('#addgame', '#form-validation-error');

        if (!formData || formData.validated == false) {
          return false;
        }

        formData.koh = formData.ko_time.getHours();
        formData.kom = formData.ko_time.getMinutes();
        formData.grefresh = $("#grefresh").val();
        formData.comp_id = $("#comp_id").val();

        var resourcesByTeam = $('#addgamediv').data('resourcesByTeam');
        var teamsByResource = $('#addgamediv').data('teamsByResource');
        if ($('#addgamediv #field').val() != '' && $('#addgamediv #field').val() != null) {
          var selectedResource = {
            uuid: $('#addgamediv #field').val(),
            teamOwner: $('#addgamediv #field option:selected').data('teamOwner')
          };
        }
        else {
          var selected_resource = {};
        }
        $('#add_game').attr('disabled','disabled');
        $.post('/add_game_process.php', {
          gnum: formData.gnum,
          field_loc: formData.field_loc,
          field_addr: formData.field_addr,
          kdate: formData.kdate,
          koh: formData.koh,
          kom: formData.kom,
          home: formData.home,
          away: formData.away,
          comp_id: formData.comp_id,
          resources_by_team: resourcesByTeam,
          teams_by_resource: teamsByResource,
          selected_resource: selectedResource,
        }, function(){
            reloadData('#games', formData.grefresh);
            $('#add_game').removeAttr('disabled');
        });
        return false;
    });


    //adding a team to a comp
    $("#addteam").live('submit', function() {

        // validate and process form
        // first hide any error messages
      $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
      var formData = getFormData('#addteam', '#form-validation-error');
     if (!formData || formData.validated == false) {
        return false;
      }
      var trefresh = $("#trefresh").val();
      var lrefresh = $("#lrefresh").val();
      var comp_id = $("#comp_id").val();
      $.post('/add_team_process.php', {
        team: formData.team,
        comp_id: comp_id,
        division: formData.division
      }, function(){
          reloadData('#teams', trefresh);
          reloadData('#addteamdiv', lrefresh);
          reloadData('#addgamediv', '/add_game.php?id='+comp_id);
        });
        return false;
    });



    //deleting a game from the competition list
    $(".dGame").live('click', function() {
    if(!confirm('Are you sure you want to delete this game?')){return false;}

        var refresh = $("#grefresh").val();
            dId = $(this).data('del-game-id');

        $.post('/delete_game_process.php',
        {game_id: dId},
        function(){
          reloadData('#games', refresh);
        });

    return false;
    });


    //deleting a team from the competition list
    $(".dTeam").live('click', function() {
    if(!confirm('Are you sure you want to delete this team from this competition?')){return false;}

        var refresh = $("#trefresh").val();

            var comp_id = $("#comp_id").val();
            var team_id = $(this).data('del-team-id');

        $.post('/delete_team_process.php',
        {comp_id: comp_id, team_id: team_id},
        function(){
          reloadData('#teams', refresh);
          reloadData('#addteamdiv', '/add_team.php?id='+comp_id);
        });

    return false;
    });


    //game confirmation sign off
    $(".signbox").live('change', function() {
    if(!confirm('Change signoff status?')){return false;}

        var srefresh = $("#srefresh").val();

            if ($("#ref").is(':checked'))var ref=1; else var ref=0;
            if ($("#num4").is(':checked'))var num4=1; else var num4=0;
            if ($("#homec").is(':checked'))var homec=1; else var homec=0;
            if ($("#awayc").is(':checked'))var awayc=1; else var awayc=0;

            var game_id = $("#game_id").val();

        $.post('/signatures_process.php',
        {ref: ref, num4: num4, homec: homec, awayc: awayc, game_id: game_id},
        function(){
/*           reloadData('#signoff', srefresh);  do we need to refresh since they're checkboxes? Change language to signature: vs. "signed/not signed"*/
        });

    return false;
    });



    //show edit info
            $("#eShow").live('click', function() {
            var game_id = $("#game_id").val();
            $('#info').html('<div style="height:93px; width: 100%; background: #FFF;"<img src="/assets/img/ajax-loader.gif">');
        $('#info').fadeIn('fast');
               $('#info').load('/edit_game_info.php?id='+game_id, function(){
             $('#info').fadeIn('fast');
             initDateTime();
             });
             

    return false;
    });


    //submit game edit info
    $("#eGame").live('click', function() {
    if(!confirm('Update game info as shown?')){return false;}
       $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();
        var formData = getFormData('#editgame', '#form-validation-error');

        if (!formData || formData.validated == false) {
          return false;
        }
        formData.koh = formData.ko_time.getHours();
        formData.kom = formData.ko_time.getMinutes();
        formData.comp_id = $("#comp_id").val();
        var refresh = '/game_info.php?id='+formData.game_id;
        $.post('/edit_game_info_process.php', {
          field: formData.field,
          gnum: formData.gnum,
          field_addr: formData.field_addr,
          field_loc: formData.field_loc,
          kdate: formData.kdate,
          koh: formData.koh,
          kom: formData.kom,
          game_id: formData.game_id
        }, function(){
            reloadData('#info', refresh);
          });
          return false;
        });


    //submit event roster edit info
    $("#ersubmit").live('click', function() {
    if(!confirm('Update roster?')){return false;}

        $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();

          var max = $("input#max").val();

          var players = '-';
          for (var i=1;i<=max;i++){
          var temp = 'p'+i;
          var val = $("#"+temp).val()
          if(val != '' && val != undefined){
          players = players+val+'-';
          }
          }

            var roster_id = $("#roster_id").val();

            var refresh = '/edit_event_roster.php?id='+roster_id;
        $.post('/edit_event_roster_process.php',
        {players: players, roster_id: roster_id},
        function(){
          reloadData('#eroster', refresh);
        });

    return false;
    });


    //submit game roster info
    $("#grsubmit").live('click', function() {
      if( !confirm('Update roster?') ){
        return false;
      }

      $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();

      var max = $("input#max").val();

      var players = '-';
      for (var i = 1; i <= max; i++) {
        var temp = 'p' + i;
        var val = $("#" + temp).val()
        if(val != '' && val != undefined){
          players = players + val + '-';
        }
        else {
          players = players + 'NIL' + '-';
        }
      }

      var positions = '-';
      for (var i = 1; i <= max; i++) {
        var temp = 'pos' + i;
        var val = $("#" + temp).val();
        if(val != '' && val != undefined){
          positions = positions + val + '-';
        }
        else {
          positions = positions + 'NIL' + '-';
        }
      }

      var numbers = '-';
      for (var i = 1; i <= max; i++) {
        var temp = 'n' + i;
        var val = $("#" + temp).val();

        if(val != undefined) {
          if(val =='' ) {
            numbers = numbers+'B-';
          }
          else {
            numbers = numbers+val+'-';
          }
        }
      }

      var frontrows = '-';
      for (var i = 1; i <= max; i++) {
        var temp = 'fr' + i;
        var val = $("#" + temp + ":checked").val();
        if(val != undefined) {
          frontrows = frontrows + '1-';
        }
        else {
          frontrows = frontrows + '0-';
        }
      }


      var game_id = $("#game_id").val();
      var team_id = $("#team_id").val();
      var roster_id = $("#roster_id").val();

      var refresh = '/edit_game_roster.php?gid=' + game_id + '&tid=' + team_id;

      $.post('/edit_game_roster_process.php', {
        players: players,
        positions: positions,
        numbers: numbers,
        frontrows: frontrows,
        roster_id: roster_id,
        team_id: team_id
      },
      function(){
        reloadData('#groster', refresh);
      });

    return false;
    });

    //submit game roster using previous game data
    $("#presubmit").live('click', function() {
    if(!confirm('Update roster?')){return false;}

        $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();

            var comp_id = $("#comp_id").val();
            var game_id = $("#game_id").val();
            var team_id = $("#team_id").val();
            var roster_id = $("#roster_id").val();
            var refresh = '/edit_game_roster.php?gid='+game_id+'&tid='+team_id;

        $.post('/edit_game_roster_previous_process.php',
        {roster_id: roster_id, team_id: team_id, game_id: game_id, comp_id: comp_id},
        function(){
          reloadData('#groster', refresh);
        });

    return false;
    });



    //team to DB
    $("#teamadd").live('click', function() {
    if(!confirm('Add club to database?')){return false;}

        $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();

          var name = $("#name").val();
        if (name == "") {
              $("#name_error").show();
              $("#name").focus();
              return false;
            }

          var num = $("#num").val();
        if (num == "") {
              $("#num_error").show();
              $("#num").focus();
              return false;
            }

            var short = $("#short").val();

            //check if team exists with that name or num
            $.post('/db_update_team_check.php',
        {name: name, num: num},
        function(data){
                 if(data!=''){
                 alert(data);
                 return false;
                 }
                 else
                 {



            var refresh = '/db_update_team_list.php';

        $.post('/db_update_team_process.php',
        {name: name, num: num, short: short},
        function(){
          reloadData('#clublist', refresh);
        });
            }//end of check else
            });

    return false;
    });



    //deleting a user
    $(".dUser").live('click', function() {
    if(!confirm('Are you sure you want to delete this user?')){return false;}

            var dId = $(":input").eq($(":input").index(this) + 1).val();

        $.post('/delete_user_process.php',
        {user_id: dId},
        function(){
          reloadData('#users', 'users_list.php');
        });

    return false;
    });




    //show edit info for users
            $(".eUser").live('click', function() {

            var eId = $(":input").eq($(":input").index(this) + 1).val();
           reloadData('#users', '/edit_user.php?id='+eId, function() {
             $('#useradd').hide();
             $('#accessadd').hide();
           });

    return false;
    });


    //submit user edit info
    $("#eUserSubmit").live('click', function() {
    if(!confirm('Update user info as shown?')){return false;}

        $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();

          var login = $("input#login").val();
        if (login== "") {
              $("label#login_error").show();
              $("input#login").focus();
              return false;
            }

        var team = $('#team').val();

        var access = $('#access').val();
        if (access == "") {
              $("label#access_error").show();
              $("input#access").focus();
              return false;
        }
        if (access == 1 && (login.indexOf("@") === -1)) {
              alert('Access code users should not be administrators.');
              return false;
        }

            var user_id = $("#user_id").val();

        $.post('/edit_user_process.php',
        {login: login, team: team, access: access, user_id: user_id},
        function(){
          reloadData('#users', 'users_list.php', function(){
            $('#useradd').show();
          });
        });

    return false;
    });


    //add new user
    $("#addUser").live('click', function() {
        $('.error').not(function(index){return $(this).hasClass('control-group');}).hide();

          var login = $("input#login").val();
          var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        if (login== "" || !pattern.test(login)) {
              $("label#login_error").show();
              $("input#login").focus();
              return false;
            }

        var team = $('#team').val();

        var access = $('#access').val();
        if (access == "") {
              $("label#access_error").show();
              $("input#access").focus();
              return false;
            }

        $.post('/add_user_process.php',
        {login: login, team: team, access: access},
        function(data){
        if(data) alert(data);
          reloadData('#users', 'users_list.php');
        });

    return false;
    });

    // Add new access code.
    $("#addAccess").live('click', function() {
        $('.error').not(function(index){
            return $(this).hasClass('control-group');
        }).hide();

        var login = $("input#login-access").val();
        if (login== "") {
            $("label#login_error_access").show();
            $("input#login-access").focus();
            return false;
        }

        var access = $('#access-team').val();
        if (access == "") {
            $("label#access_error").show();
            $("input#access-team").focus();
            return false;
        }

        var team = $('#team-access').val();
        if ((team == "") && (access != 2)) {
            $("label#team_access_error").show();
            $("input#team-access").focus();
            return false;
        }

        var proxy_user = $('#proxy_user').val();
        $.post('/add_user_process.php',
        {
            login: login,
            team: team,
            access: access,
            proxy_user: proxy_user
        },
        function(data){
            if(data) alert(data);
            reloadData('#users', 'users_list.php');
        });

        return false;
    });
    
   
    $(".scoreboard h1").css('font-size',function(){
        var $numWords = $(this).text().length; // get length of text for current p element
        if (($numWords >= 1) && ($numWords < 25)) {
            return "200%";
        }
        else if (($numWords >= 22) && ($numWords < 40)) {
            return "150%";
        }
        else if (($numWords >= 40) && ($numWords < 100)) {
            return "100%";
        }
        else if (($numWords >= 100) && ($numWords < 140)) {
            return "75%";
        }
        else {
            return "100%";
        }           
    });  
    
    $(".table.rosters th").css('font-size',function(){
        var $numWords = $(this).text().length; // get length of text for current p element
        if (($numWords >= 1) && ($numWords < 25)) {
            return "100%";
        }
        else if (($numWords >= 22) && ($numWords < 35)) {
            return "90%";
        }
        else if (($numWords >= 35) && ($numWords < 45)) {
            return "60%";
        }
        else if (($numWords >= 45) && ($numWords < 50)) {
            return "70%";
        }
        else {
            return "60%";
        } 
     });   
      
     $(".team-name h1").css('font-size',function(){
        var $numWords = $(this).text().length; // get length of text for current p element
        if (($numWords >= 1) && ($numWords < 30)) {
            return "300%";
        }
        else if (($numWords >= 30) && ($numWords < 45)) {
            return "250%";
        }
        else {
            return "200%";
        }  
    });
                                 

});
