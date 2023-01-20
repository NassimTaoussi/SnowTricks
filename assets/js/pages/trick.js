//Photos

const photosCollectionHolder = document.querySelector("#trick_photos");

let indexPhotos = photosCollectionHolder.querySelectorAll('div').length;

const addPhoto = () => {
    photosCollectionHolder.innerHTML += photosCollectionHolder.dataset.prototype.replace(/__name__/g, indexPhotos)
    console.log(photosCollectionHolder.dataset.prototype);
    indexPhotos++;
};

const btnPhoto = document.querySelector("#new-photo");

if(btnPhoto) {
    addEventListener('click', addPhoto);
}

// Videos

const videosCollectionHolder = document.querySelector("#trick_videos");

let indexVideos = videosCollectionHolder.querySelectorAll('div').length;

const addVideo = () => {
    videosCollectionHolder.innerHTML += videosCollectionHolder.dataset.prototype.replace(/__name__/g, indexVideos)
    console.log(videosCollectionHolder.dataset.prototype);
    indexVideos++;
};

const btnVideo = document.querySelector("#new-video");

if(btnVideo) {
    addEventListener('click', addVideo);
}