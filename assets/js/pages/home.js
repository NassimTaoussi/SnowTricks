import 'jquery/dist/jquery.min.js';
console.log('hello 0');
$(document).ready(function() {
    $("#loadMoreTricks").on("click", function(event){ 
        console.log('hello 1');
        var totalDisplayTricks = Number($('#totalDisplayTricks').val());
        var totalAllTricks = Number($('#totalAllTricks').val());
        var tricksPerLoading = Number($('#tricksPerLoading').val());

        if(totalDisplayTricks <= totalAllTricks){
            $("#totalDisplayTricks").val(totalDisplayTricks + tricksPerLoading);
        console.log('hello 2');

        $.ajax({  
            url: '/getData',  
            type: 'POST',
            data: {totalDisplayTricks:totalDisplayTricks},
            beforeSend: function(response){
                $("#loadMoreTricks").text("Chargement...");
            },
            success: function(response) {
                 // Setting little delay while displaying new content
                 setTimeout(function() {
                    // Appending tricks after last trick with class="trick"
                    $(".cardTrick:last").after(response).show().fadeIn("slow");

                    var totalDisplayTricks = Number($('#totalDisplayTricks').val());
                    // checking tricksNumber value is greater than totalTricks or not

                    if(totalDisplayTricks >= totalAllTricks){
                        // Change the text and background
                        $('#loadMoreTricks').addClass('d-none');
                    }else{
                        $("#loadMoreTricks").text("Afficher plus de figures");
                        if(totalDisplayTricks > 60) {
                            $("#arrow-top").show();
                        }
                    }
                }, 400);
            },
            error: function(error) {
                alert("Une erreur s'est produite. Réessayez un peu plus tard.");
            },
        })
        }
    })
})
