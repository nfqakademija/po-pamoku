import React from 'react';
import ReactDOM from 'react-dom';
import '../../node_modules/bootstrap/dist/css/bootstrap.min.css';
import Comments from './Comments';

const id = window.location.pathname.split('/').slice(-1)[0];

ReactDOM.render(<Comments id = {id}/>, document.getElementById('comments'));
