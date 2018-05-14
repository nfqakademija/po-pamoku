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
                return (
                    <Marker
                        key={marker.id}
                        position={{ lat: marker.lat, lng: marker.lng }}
                        onClick={() => props.onToggleOpen(marker.id)}
                    >
                        {!!props.id && props.id === marker.id &&
                    <InfoWindow>
                            <div className="view-box">
                                <h5>{marker.name}</h5>
                                <div className="image-box">
                                    {/* <img src={marker.pathToLogo}/> */}
                                    <img className="img-fluid" src="https://placeimg.com/640/480/any" alt="Card image cap" />
                                </div>
                                <p className="mt-3">
                                    <i className="fas fa-map-marker"></i>
                                    <span className="pl-2">{marker.street} {marker.house}, {marker.city}</span>
                                    
                                </p>
                                <p>
                                    <i className="fas fa-user"></i>
                                    <span className="pl-2">Amžius: {marker.ageFrom} - {marker.ageTo}</span>
                                </p>
                                <p>
                                    <i className="fas fa-euro-sign"></i>
                                    <span className="pl-2">{marker.priceFrom} - {marker.priceTo}</span>
                                    <a className="float-right" href={"/activity/" + marker.id}> Plačiau </a>
                                </p>
                            </div>
                    </InfoWindow>}
                </Marker>
            )})}
        </MarkerClusterer>
    </GoogleMap>
);

export default MapWithAMarkerClusterer;
