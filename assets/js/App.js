import React from 'react';
import Filter from './components/Filter.js';
import Sort from './components/Sort.js';
import { Pagination } from '@react-bootstrap/pagination';
import MapComponent from "./MapComponent";

const axios = require('axios');

class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
        activities: [],
        currentPageNumber: 1,
        totalActivities: 1,
        addFilter: false,
        searchValue: null,
        isMap: false,
        lat: 55.0,
        lng: 24.0,
        zoom: 7
    };
    this.onFilterChange = this.onFilterChange.bind(this);
    this.my = this.my.bind(this);
  }
 getActivities(page) {
   axios.get('/api/activity?page=' + page + '&limit=12')
     .then(function (response) {
       this.setState({
         activities: Object.keys(response.data).map(i => response.data[i])[1],
         currentPageNumber: page,
         totalActivities: Object.keys(response.data).map(i => response.data[i])[0]
       });
     }.bind(this))
     .catch(function (error) {
       console.error(error);
     });
}
  searchActivities(page, value) {
    console.log(value);
    axios.get('/api/activity?page=' + page + '&limit=12&search=' + value.search + '&city=' + value.cityId + '&category=' + value.category +
      '&weekday=' + value.weekday + '&time=' + value.time + '&age=' + value.age + '&price=' + value.price + '&subcategory=' + value.subcategory)
      .then(function (response) {
        this.setState({
          activities: Object.keys(response.data).map(i => response.data[i])[1],
          totalActivities: Object.keys(response.data).map(i => response.data[i])[0],
          addFilter: true
        });
      }.bind(this))
      .catch(function (error) {
        console.error(error);
      });
  }

  componentDidMount() {
    this.getActivities(1);
  }

  handleSelect(number) {
    console.log('handle select', number);
    this.setState({ currentPageNumber: number });
    if (this.state.addFilter === true) {
      this.searchActivities(number, this.state.searchValue);
    } else {
      this.getActivities(number);
    }
    let top = document.getElementById('toTop').offsetTop;
    window.scrollTo({
      top: top
    });
  }

  onFilterChange(value) {
    this.setState({searchValue: value});
    this.searchActivities(1, value);
  }

  my() {
    if (!navigator.geolocation) {
      alert('Geolocation is not supported by your browser');
      
      return;
    }
    navigator.geolocation.getCurrentPosition(
      (position) => {

        const lat = position.coords.lat;
        const lng = position.coords.lng;

        alert(lng);
        if (lat === undefined || lng === undefined) {
            this.setState({ lat: 54.6963489, lng: 25.2766971, zoom: 15 });
        }
        else {
          this.setState({ lat: lat, lng: lng , zoom: 15});
        }
      }, 
    () => {
        this.setState({ lat: 54.6963489, lng: 25.2766971, zoom: 15 });
      //alert("Unable to retrieve your location");
    });
  };

  render() {
    const { activities, 
      currentPage, 
      activitiesPerPage,
      lat,
      lng,
    } = this.state;
    let totalPages = Math.ceil(this.state.totalActivities / 12);

const btnSwitch = (
    <div>
        <button
            onClick={() => this.setState({ isMap: false })}
        >Sąrašas</button>
          <button
              onClick={() => this.setState({ isMap: true })}
          >Žemėlapis</button>

    </div>
      );

      const geo = (
          <button
          onClick={this.my}
          >Rasti mano vietą</button>
      );

      if (this.state.isMap) {
          return (
              <div className="container">
                  <div className="container py-5">
                      <div id="toTop"></div>
                      <Filter
                          onChange={this.onFilterChange}/>
                      {btnSwitch}
                      {geo}
                  </div>

                  <MapComponent zoom={this.state.zoom} lat={this.state.lat} lng={this.state.lng} />


              </div>
          );
      }    return (
      <div>
          <div className="container py-5">
              <div id="toTop"></div>
              <Filter
                  onChange={this.onFilterChange}/>
              {btnSwitch}

          </div>
      <div className="container">

        {/*<Sort />*/}

        <div className="row activities py-3">

           {activities.length !== 0 ? (activities.map((activity, index) =>  
            <div key={"currentAct" + index} className="col-md-3 col-sm-6 col-xs-6 py-3">
              <div className="activity-card">
              <div className="card-image">
                  <img className="img-fluid" src="https://placeimg.com/640/480/any" alt="Card image cap" />
                  <div className="like-btn">
                    <i className="far fa-heart"></i>
                  </div>
                  {/* <img className="" src="build/images/football.png" alt="Card image cap" /> */}
              </div>
               
                <div className="activity-text">
                  <h5 className="activity-title">{activity.name}</h5>
                    <p className="location"><i className="fas fa-map-marker"></i>{activity.city}, {activity.street} g. {activity.house}</p>
                    <p>Kaina: {activity.priceFrom}-{activity.priceTo} €</p>
                  
                    <p>{activity.category} / <span className="text-secondary">{activity.subcategory}</span></p>
                  {/* <p>{activity.ageFrom} - {activity.ageTo}</p> */}
                  {/* <p>{activity.weekday}</p> */}
                  {/* <p>{activity.time} - {activity.timeTo}</p> */}
                  <hr />
                  <div className="stars">
                    <i className="fas fa-star"></i>
                    <i className="fas fa-star"></i>
                    <i className="fas fa-star"></i>
                    <i className="fas fa-star"></i>
                    <i className="fas fa-star"></i>
                    {/* <img className="" src="build/images/star.png" alt="Card image cap" /> 
                    <img className="" src="build/images/star.png" alt="Card image cap" /> 
                    <img className="" src="build/images/star.png" alt="Card image cap" />
                    <img className="" src="build/images/star.png" alt="Card image cap" />
                    <img className="" src="build/images/star.png" alt="Card image cap" />  */}
                    <a className="btn card-btn" href={"/activity/" + activity.id}> Plačiau </a>
                  </div>
                </div>
              </div>
            </div>)) : ('Deja, būrelių nėra')}
            
        </div>

        <Pagination
          bsSize="medium"
          items={totalPages}
          activepage={this.state.currentPageNumber}
          onSelect={this.handleSelect.bind(this)} />
        
      </div>
      </div>
    );
    }
  }


export default App;