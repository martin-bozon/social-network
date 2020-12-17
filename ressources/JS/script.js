$(function ()
    {                    
        // Dropdown header
        $('.dropdown-trigger').dropdown();
        // Search bar header                      
        $.ajax(
          {
              url : 'App/Controller/IndexController',
              type : 'post',
              data : {action : 'search'},              
              success: (response) =>
                {                                    
                  var user = JSON.parse(response);                       
                  var dataUser = {};
                  var dataUserId = {};
                  for (var i = 0; i < user.length; i++) 
                    {
                      dataUser[user[i].first_name + ' ' + user[i].last_name] = user[i].picture_profil;
                      dataUserId[user[i].first_name + ' ' + user[i].last_name] = user[i];
                    }                                            
                  $('input.autocomplete').autocomplete(
                    {
                      data: dataUser,                      
                      onAutocomplete : function(e)
                        {
                          $('input.autocomplete').val('');
                          window.location = "profil?id="+dataUserId[e].id;                          
                        },
                    });      
                },            
          });
          // Bouton déco
          $('.fa-power-off').click(function()
            {
              $.ajax(
                  {
                    url : 'App/Controller/IndexController',
                    type : 'post',
                    data : {action : 'deco'},
                    success : (data) =>
                      {                                                                      
                        localStorage.clear();                             
                        pageConnexion();       
                        window.location = 'index.php';
                      },
                  });
            });     
          //  Bouton créer conversation
          $('#bouton_conv').click(function(e)
            {    
              $('body').append("<section id='pop-up-background' class='z-index-3 absolute flex flex-column justify-center align-center'>" +
              "<div id='pop-up-content' class='m-1 background-white p-05'>" +              
              "<div class='row m-0' id='recherche_personne'>"+
              "<div class='col s12'>"+
              "<div class='row m-0'>"+
              "<div class='input-field col s12 m-0'>"+
              "<input type='text' id='autocomplete-conv' class='autocomplete' placeholder='Rechercher une personne...'/>"+              
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "<ul id='liste_personne'></ul>"+
              "<button id='creer_conv' class='bouton btn waves-effect waves-light col s6 offset-s3'>Créer<i class='material-icons right'>add_circle_outline</i></button>"+
              "</div>" +
              "</section>");             
              $.ajax(
                {
                    url : 'App/Controller/IndexController',
                    type : 'post',
                    data : {action : 'search'},              
                    success: (response) =>
                      {                                                              
                        var user = JSON.parse(response);                       
                        var dataUser = {};
                        var dataUserInfo = {};
                        var creatorId = localStorage.id;       
                        var groupeId = [creatorId];                        
                        for (var i = 0; i < user.length; i++) 
                          {
                            dataUser[user[i].first_name + ' ' + user[i].last_name] = user[i].picture_profil;
                            dataUserInfo[user[i].first_name + ' ' + user[i].last_name] = user[i];
                          }          
                          
                        $('#autocomplete-conv').autocomplete(
                          {
                            data: dataUser,                      
                            onAutocomplete : function(e)
                              {                                                                             
                                if(jQuery.inArray(dataUserInfo[e].id, groupeId) !== -1)                                 
                                  {
                                    $('#autocomplete-conv').val('');                                       
                                  }
                                else  
                                  {
                                    groupeId.push(dataUserInfo[e].id);                                       
                                    $('#autocomplete-conv').val('');    
                                    $('#liste_personne').append('<li id='+dataUserInfo[e].id+'>'+dataUserInfo[e].first_name+' '+dataUserInfo[e].last_name+' <i class="fas fa-times"></i></li>');                                    
                                    $('.fa-times').click(function()
                                      {                                        
                                        $(this).parent().remove();
                                        groupeId.splice($.inArray(dataUserInfo[e].id, groupeId), 1);                                        
                                      });
                                  }                              
                              },
                          });      
                      },            
                });
              //gestion disparition du bouton
              var modal = document.getElementById("pop-up-background");
              window.onclick = function (event) {
                  if (event.target == modal) {
                      modal.remove();
                  }
              };
            });
    });
