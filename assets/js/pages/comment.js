console.log("Ici comment.js");

const bouton = document.querySelector("#loadMoreComments");

var responseClone;

bouton.addEventListener("click", function () {
    console.log("ici Event click");
    const element1 = document.querySelector('#totalDisplayComments');
    const element2 = document.querySelector('#totalAllComments');
    const element3 = document.querySelector('#commentsPerLoading');
    const element4 = document.querySelector('#idTrick');

    let totalDisplayComments = element1 ? Number(element1.value) : null;
    let totalAllComments = element2 ? Number(element2.value) : null;
    let commentsPerLoading = element3 ? Number(element3.value) : null;
    let idTrick = element4 ? Number(element4.value) : null;

    if(totalDisplayComments <= totalAllComments){
        
        console.log("ici Condition");

        fetch('/getMoreComments/' + idTrick, {
            method: 'POST',
            body: {totalDisplayComments: totalDisplayComments}
        })
        .then(response => response.json())
        .then(data => console.table(data)/*{
            console.log("ici 1")
            setTimeout(function() {
                const element1 = document.querySelector('#totalDisplayComments');
                let totalDisplayComments = element1 ? Number(element1.value) : null;

                if(totalDisplayComments >= totalAllComments){

                }

            }, 400)
        }*/)
    }
});