import axios from 'axios';

export function fetchBlogPosts() {
    return axios.get('api/activities'
    ).then(res => res.data)
        .catch(err => err);
}