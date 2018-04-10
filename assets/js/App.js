import React, { Component } from 'react';
import { Router, Route, IndexRoute, withRouter } from 'react-router'
import { BrowserRouter } from 'react-router-dom'
import List from './List';

export default class App extends Component {

    render() {
        return (
            <BrowserRouter>
                <Route path="/testi" component={List} />
            </BrowserRouter>
        );
    }
}