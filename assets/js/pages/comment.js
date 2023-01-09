console.log("Ici comment.js");

const bouton = document.querySelector("#moreComments");

bouton.addEventListener("click", function () {
    console.log("ici Event click");
    const element1 = document.querySelector('#totalDisplayComments');
    const element2 = document.querySelector('#totalAllComments');
    const element3 = document.querySelector('#commentsPerLoading');

    let totalDisplayComments = element1 ? Number(element1.value) : null;
    let totalAllComments = element2 ? Number(element2.value) : null;
    let commentsPerLoading = element3 ? Number(element3.value) : null;

    if(totalDisplayComments <= totalAllComments){
        
        console.log("ici Condition");

        fetch('/getMoreComments', {
            method: 'POST',
            body: {totalDisplayComments: totalDisplayComments}
        })
        .then(response => console.log(response.json))
        .then(data => {
            setTimeout(function() {

            }, 400)
        })
    }
});