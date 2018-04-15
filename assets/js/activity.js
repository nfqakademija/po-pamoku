import React from 'react';
const axios = require('axios');

class Activity extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            activity: []
        };
    }
    componentDidMount() {
        axios.get('/api/activities/' + id)
            .then(function (response) {
                this.setState({
                    activities: response.data
                });
                console.log(this.state.activities);
            }.bind(this))
            .catch(function (error) {
                console.error(error);
            });

        axios.get('/api/activities')
            .then(function (response) {
                this.setState({
                    activities: response.data
                });
                console.log(this.state.activities);
                // versionElement.innerText = response.data["build/js/app.js"];
            }.bind(this))
            .catch(function (error) {
                console.error(error);
                // versionElement.innerText = 'Error: '.error;
            });

    }
    render() {
        const isData = this.state.activities;

        return (
            <div className="container">
                <div className="row">
                    <p>Labas</p>
                </div>
            </div>
        );
    }
}

export default Activity;