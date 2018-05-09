const axios = require('axios');

let form = document.getElementById('commentForm');
form.onsubmit = handleCommentPosting;

function handleCommentPosting(e) {
    e.preventDefault();
    let commentForm = e.currentTarget;
    console.log(commentForm);
    let data = new FormData(commentForm);
    axios.post(commentForm.action, data)
        .then(console.log('success'));
}



