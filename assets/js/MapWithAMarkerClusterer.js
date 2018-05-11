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
                console.log('id', id)
                
                return ({
                    id,
            })}
        }),
        withHandlers({
            onA: (asd) => {
                console.log('asd', asd)

                return this.props;
            },
        }),
    withScriptjs,
    withGoogleMap
)(props =>
    <GoogleMap
        defaultZoom={7}
        defaultCenter={{ lat: props.lat, lng: props.lng }}
    >

        <MarkerClusterer
            averageCenter
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
                    <InfoWindow
                    
                            onCloseClick={() => {props.onToggleOpen(marker);
                            console.log(marker);}}>
                            <div style={{ width: `250px`}}>
                                <h5>{marker.name}</h5>
                                <div style={{ width: '250px',
                                    height: '150px',
                                    lineHeight: '100px',
                                    margin: '2px 0',
                                    textAlign: 'center',
                                    overflow: 'hidden'}}>
                                    <img style={{ width: `250px`}} src={marker.pathToLogo}/>
                                </div>
                                <p>Adresas: {marker.street} {marker.house}, {marker.city}</p>
                                <p>Kaina: {marker.priceFrom} - {marker.priceTo}, Amžius: {marker.ageFrom} - {marker.ageTo}</p>
                                <a href={"/activity/" + marker.id}> Plačiau </a>
                            </div>
                    </InfoWindow>}
                </Marker>
            )})}
        </MarkerClusterer>
    </GoogleMap>
);

export default MapWithAMarkerClusterer;
