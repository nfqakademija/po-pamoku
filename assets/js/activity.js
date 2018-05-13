import React from 'react';
import ReactDOM from 'react-dom';
import '../../node_modules/bootstrap/dist/css/bootstrap.min.css';
import Comments from './Comments';

const id = window.location.pathname.split('/').slice(-1)[0];
const form = document.getElementById('commentForm');

ReactDOM.render(<Comments id = {id} form = {form}/>, document.getElementById('comments'));
