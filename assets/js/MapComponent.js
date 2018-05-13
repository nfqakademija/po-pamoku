import React from 'react';
import MapWithAMarkerClusterer from './MapWithAMarkerClusterer';
const axios = require('axios');

class MapComponent extends React.PureComponent {
    componentWillMount() {
        this.setState({ 
            markers: [],
        });
    }

    componentDidMount() {
        axios.get(this.props.query)
            .then(function (response) {
                this.setState({
                    markers: Object.keys(response.data).map(i => response.data[i])[1],
                });
            }.bind(this))
            .catch(function (error) {
                console.error(error);
            });
    }
    render() {

        return (
            <MapWithAMarkerClusterer lat={this.props.lat} zoom={this.props.zoom} lng={this.props.lng} markers={this.state.markers} />
        )
    }
}
export default MapComponent;
