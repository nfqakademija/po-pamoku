import React from 'react';
import ReactDOM from 'react-dom';
import '../../node_modules/bootstrap/dist/css/bootstrap.min.css';
import Comments from './Comments';
import Rating from './Rating';

const id = window.location.pathname.split('/').slice(-1)[0];
const commentFormId = 'commentForm';
const ratingFormId = 'ratingForm';

ReactDOM.render(<Comments id = {id} form = {commentFormId}/>, document.getElementById('comments'));
ReactDOM.render(<Rating id = {id} form = {ratingFormId}/>, document.getElementById('rating'));
