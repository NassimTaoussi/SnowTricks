import './sass/app.scss';
import 'bootstrap';
import './js/pages/home.js';
import './js/pages/comment.js';
import './js/pages/login.js';
import './js/pages/trick.js';

const inputFile = document.getElementById('avatar_form_avatar');
console.log('0');
if (inputFile != null) {
    console.log('1');
    inputFile.addEventListener('change', (e) => {
        const images = e.target.files;
        if(images.length > 0) {
            console.log('2');
            const reader = new FileReader();
            const image = document.getElementById('avatar');
            reader.onload = () => {
                console.log(images);
                image.src = reader.result;
            } 
            reader.readAsDataURL(images[0]);
        }
    });
}