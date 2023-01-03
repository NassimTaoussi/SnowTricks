import 'jquery/dist/jquery.min.js';

$(document).ready(function() {
    $("#loadMoreTricks").on("click", function(event){  
        var page = 1;
        var totalElement =  Number($('#$allCountTricks').val());
        console.log(totalElement);
        var nbrElementsByPage = 15;

        $.ajax({  
            url: Routing.generate('index'),  
            type: 'POST',   
            dataType: 'json',  
            async: true,
            success: function(response) {
                if(response.length === 0){
                    $("#loadMoreTricks").hide();
                    var $element = $("<div></div>", { 'class': "noNode", 'text': 'Vous avez consulté tous les tricks'});
                    $("#moreTricks").append($element);
                }
                else {
                    response.forEach(function() {
   
                    })
                }
            }
        })
    })
})
