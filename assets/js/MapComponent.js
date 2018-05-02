import React from 'react';
import MapWithAMarkerClusterer from './MapWithAMarkerClusterer';

​class MapComponent extends React.Component {
    componentWillMount() {
        this.setState({ markers: [] })
    }
​
    componentDidMount() {
        const url = [
            // Length issue
            `https://gist.githubusercontent.com`,
            `/farrrr/dfda7dd7fccfec5474d3`,
            `/raw/758852bbc1979f6c4522ab4e92d1c92cba8fb0dc/data.json`
        ].join("")
​
        fetch(url)
            .then(res => res.json())
            .then(data => {
                this.setState({ markers: data.photos });
            });
    }
​
    render() {
        return (
            <MapWithAMarkerClusterer
                markers={this.state.markers}
            />
        );
    }
}

export default MapComponent;
