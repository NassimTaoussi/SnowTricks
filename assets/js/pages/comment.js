console.log("Ici comment.js");

const loadMoreButton = document.getElementById("loadMoreComments");

console.log(loadMoreButton);

/*let page = 1;

document.querySelector('#loadMoreComments').addEventListener('click', (e) => {
    page++;
    const id = document.getElementById('idTrick').value

    fetch('/tricks/' + id + '/comments/load-more?page=' + page)
})*/

if (loadMoreButton)
{
    document.getElementById("loadMoreComments").addEventListener("click", async (event) => {
        let totalDisplayComments = Number(document.getElementById('totalDisplayComments').value);
        let totalAllComments = Number(document.getElementById('totalAllComments').value);
        let commentsPerLoading = Number(document.getElementById('commentsPerLoading').value);
        let idTrick = document.getElementById('idTrick').value;
    
        console.log(totalDisplayComments);
        console.log(totalAllComments);
        console.log(commentsPerLoading);
    
        if(totalDisplayComments <= totalAllComments){
            document.getElementById("totalDisplayComments").value = totalDisplayComments + commentsPerLoading;
    
            loadMoreButton.textContent = "Chargement...";
    
            const url = '/getMoreComments/'+ idTrick + "?totalDisplayComments=" + totalDisplayComments;
    
            fetch(url)
            .then(response => response.text())
            .then(response => {
                
                setTimeout(function() {
                    // Appending tricks after last trick with class="trick"
                    const lastComment = document.querySelector(".cardComment:last-child");
                    const div = document.createElement("div");
                    div.innerHTML = response;
                    lastComment.after(div);
    
                    totalDisplayComments = Number(document.getElementById('totalDisplayComments').value);
                    // checking tricksNumber value is greater than totalTricks or not
    
                    if (totalDisplayComments >= totalAllComments) {
                        // Change the text and background
                        loadMoreButton.classList.add("d-none");
                        document.querySelector("#moreComments").innerHTML =
                        "Plus aucun commentaire de disponible";
                    } else {
                        loadMoreButton.textContent = "Afficher plus de commentaires";
                    }
                }, 400);
            })
        }
    });
}