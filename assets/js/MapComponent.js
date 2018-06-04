import React from 'react';
import MapWithAMarkerClusterer from './MapWithAMarkerClusterer';
import axios from 'axios';

class MapComponent extends React.PureComponent {
    render() {

        return (
            <MapWithAMarkerClusterer lat={this.props.lat} zoom={this.props.zoom} lng={this.props.lng} markers={this.props.markers} />
        )
    }
}
export default MapComponent;
