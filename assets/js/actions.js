import fetch from 'isomorphic-fetch';

export function fetchBlogPosts() {
    return fetch('api/activities', {
        method: 'GET'
    }).then(res => res.json())
        .catch(err => err);
}