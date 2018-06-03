import React from 'react';
import { compose, withProps, withHandlers, withStateHandlers } from "recompose";
import { GoogleMap, Marker, withGoogleMap, withScriptjs, InfoWindow } from 'react-google-maps';
import { MarkerClusterer } from "react-google-maps/lib/components/addons/MarkerClusterer";

const MapWithAMarkerClusterer = compose(
    withProps({
        googleMapURL: "https://maps.googleapis.com/maps/api/js?key=AIzaSyDxFs6BvSj-oMOLNcgaNqpCFJeml4LXEX4&v=3.exp&libraries=geometry,drawing,places",
        loadingElement: <div style={{ height: `90%` }} />,
        containerElement: <div style={{ height: `600px` }} />,
        mapElement: <div style={{ height: `100%` }} />,
    }),
    withStateHandlers(() => ({
        id: null,
    }), {
            onToggleOpen: () => (id) => {

                return ({
                    id,
                })
            }
        }),
    withScriptjs,
    withGoogleMap
)(props =>
    <GoogleMap
        zoom={props.zoom}
        center={{ lat: props.lat, lng: props.lng }}
    >

        <MarkerClusterer
            enableRetinaIcons
            gridSize={60}
        >
            {props.markers.map(marker => {
                let list = [];
                return (
                    <Marker
                        key={marker.id}
                        position={{ lat: marker.lat, lng: marker.lng }}
                        onClick={() => props.onToggleOpen(marker.id)}
                    >
                        {!!props.id && props.id === marker.id &&
                    <InfoWindow>
                        <div className="activity-card mt-4 ml-4 mb-2 info-window">
                            <div className="card-image">

                                <a className="card-btn overlay" href={"/activity/" + marker.id}><i className="fas fa-search-plus"></i></a>

                                <img className="img-fluid" 
                                    src={marker.pathToLogo ? marker.pathToLogo : '/uploads/33e75ff09dd601bbe69f351039152189.jpg'}
                                    alt="Card image cap" />

                                <button className="like-btn"
                                        onClick={() => {
                                            if (localStorage.getItem('favorite' + activity.id) === null){
                                                localStorage.setItem('favorite' + activity.id, JSON.stringify(activity));
                                                this.setState({ isFav: true });
                                            }
                                            else {
                                                localStorage.removeItem('favorite' + activity.id);
                                                this.setState({ isFav: false });
                                            }
                                        }}>

                                    <i className={this.state.isFav || localStorage.getItem('favorite' + activity.id) ? 'fas fa-heart' : 'far fa-heart'} ></i>
                                </button>

                                <div className="price">
                                        {marker.priceFrom === marker.priceTo ? (marker.priceFrom) : (marker.priceFrom + "-" + marker.priceTo)} €
                                </div>
                            </div>

                            <div className="activity-text">
                                <h5 className="activity-title">
                                    {marker.name}
                                </h5>
                                <p className="grey-text">
                                    {marker.city}, {marker.street} {marker.house}
                                </p>
                                <p className="d-flex justify-content-between align-items-baseline">
                                    <span className="grey-text">{marker.subcategory}</span>
                                </p>

                            </div>
                        </div>
                    </InfoWindow>}
                </Marker>
            )})}
        </MarkerClusterer>
    </GoogleMap>
);

export default MapWithAMarkerClusterer;
