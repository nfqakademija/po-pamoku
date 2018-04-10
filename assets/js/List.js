import React, { Component } from 'react';
import {fetchBlogPosts} from './actions';
import Table from './Table';

export default class List extends Component {

    constructor(props) {
        super(props);

        this.state = {
            activities: []
        };
    };

    componentDidMount() {
        fetchBlogPosts()
            .then((data) => {
                this.setState(state => {
                    state.activities = data;
                    return state;
                })
            })
            .catch((err) => {
                console.error('err', err);
            });
    };

    render() {
        return (
            <div>
                <Table activities={this.state.activities}/>
            </div>
        );
    }
}