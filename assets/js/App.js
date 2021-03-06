import React from 'react';
import Filter from './components/Filter.js';
import Favorite from './components/Favorite';
import { Pagination } from '@react-bootstrap/pagination';
import MapComponent from "./MapComponent";
import axios from 'axios';
import ActivityItem from './ActivityItem.js';

class App extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
        activities: [],
        allActivities: [],
        // activities: data.Activities,
        currentPageNumber: 1,
        totalActivities: 1,
        addFilter: false,
        searchValue: null,
        isMap: false,
        lat: 55.0,
        lng: 24.0,
        zoom: 7,
        query: '/api/activity?page=1&limit=99999',
        favoritesLength: 0,

    };
    this.onFilterChange = this.onFilterChange.bind(this);
    this.my = this.my.bind(this);
  }

  componentDidMount() {
    this.getActivities(1);
    this.getLocalStorage();

    this.onFavorited();


      axios.get(this.state.query)
          .then(function (response) {
              this.setState({
                  allActivities: Object.keys(response.data).map(i => response.data[i])[1],
              });
          }.bind(this))
          .catch(function (error) {
              console.error(error);
          });
  }

getActivities(page) {
    axios.get('/api/activity?page=' + page + '&limit=12')
        .then(function (response) {
            this.setState({
                activities: response.data.Activities,
                currentPageNumber: page,
                totalActivities: response.data.count
            });
        }.bind(this))
        .catch(function (error) {
            console.error(error);
        });
}
  searchActivities(page, value) {
    axios.get('/api/activity?page=' + page + '&limit=12&search=' + value.search + '&city=' + value.cityId + '&category=' + value.category +
      '&weekday=' + value.weekday + '&time=' + value.time + '&age=' + value.age + '&price=' + value.price + '&subcategory=' + value.subcategory)
      .then(function (response) {
        this.setState({
            activities: response.data.Activities,
            totalActivities: response.data.count,
            addFilter: true,
            isMap: false,
            query: '/api/activity?page=1&limit=99999&search=' + value.search + '&city=' + value.cityId + '&category=' +
            value.category + '&weekday=' + value.weekday + '&time=' + value.time + '&age=' + value.age +
            '&price=' + value.price + '&subcategory=' + value.subcategory,
        });
      }.bind(this))
      .catch(function (error) {
        console.error(error);
      });
  }

  handleSelect(number) {
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
getLocalStorage() {
  const favoriteList = JSON.parse(localStorage.getItem('favoriteList'));
  this.setState({ favorites: favoriteList });
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
    onGetFavoritesLength = () => {
        const favoritesValue = localStorage.getItem('favorites');
        const favorites =  (favoritesValue && JSON.parse(favoritesValue)) || [];

        return favorites.length;
    };

    onFavorited = () => {
        this.setState({
            favoritesLength: this.onGetFavoritesLength()
        });
    };



  render() {
    const {
        activities,
        allActivities,
      currentPage,
      activitiesPerPage,
      lat,
      lng,
    } = this.state;
    let totalPages = Math.ceil(this.state.totalActivities / 12);

const btnSwitch = (
    <div>
        <button
            className="btn map-btn mt-3"
            onClick={() => {
                this.setState({ isMap: !this.state.isMap });


            }}>
              <i className="fas fa-map-marker"></i>
      <span className="location-btn pl-2">Žemėlapis</span>
        </button>
      </div>
      );

      const geo = (
          <button
          className="btn myPlace-btn"
          onClick={this.my}
          >Rasti mano vietą</button>
      );
    const favs = [];
      const favoritesValue = localStorage.getItem('favorites');
      const favorites =  (!!favoritesValue && JSON.parse(favoritesValue)) || [];

    for (let i = 0; i < allActivities.length; i++) {
        const isFavorite = favorites.find((item) => parseInt(item) === parseInt(allActivities[i].id));

        if(!!isFavorite){
            favs.push(<ActivityItem
                key={'favorited-item-' + allActivities[i].id}
                item={allActivities[i]}
                onFavorited={this.onFavorited}
            />);

        }
    }

      return (
      <div>
        <div className="col-12 search-panel" id="filterTop">            
                <div id="toTop"></div>
            <ul className="nav nav-tabs justify-content-center" id="searchTab" role="tablist">
              <li className="nav-item">
                <a className="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Paieška</a>
                  </li>
              <li className="nav-item">
                <a className="nav-link" id="map-tab" data-toggle="tab" href="#mapTab" role="tab" aria-controls="profile" aria-selected="false"
                      onClick={() => {
                        this.setState({ isMap: true });
                      }}>
                      Žemėlapis
                    </a>
                  </li>
              <li className="nav-item">
                <a className="nav-link" id="favorite-tab" data-toggle="tab" href="#favorite" role="tab" aria-controls="favorite" aria-selected="true"
                   onClick={() => {
                       this.onFavorited();
                   }}
                >
                Mėgstamiausi</a>
              </li>
                </ul>
            <div className="tab-content" id="searchTabContent">
              <div className="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <div className="container">
                  <Filter
                    onChange={this.onFilterChange} />
                  </div>
              </div>
              <div className="tab-pane fade" id="mapTab" role="tabpanel" aria-labelledby="map-tab">
                    {this.state.isMap &&
                      <div className="" id="map">
                        <div className="row justify-content-between position-relative">
                          <div className="py-3 col-3 geo-btn">
                            {geo}
                          </div>
                          <div className="col-12">
                            <MapComponent query={this.state.query} zoom={this.state.zoom} lat={this.state.lat} lng={this.state.lng} markers={allActivities}/>
                          </div>
                        </div>
                      </div>
                    }
                  </div>
              <div className="tab-pane fade" id="favorite" role="tabpanel" aria-labelledby="favorite-tab">
                <div className="container">
                  <h2 className="my-5 text-center">Mėgstamiausi būreliai</h2>
                  <div className="row">{favs}</div>
                  < hr />
                  <h2 className="my-5">Visi būreliai</h2>
                  
                </div>
              </div>
                </div>
        </div>
          <div className="container">
            <div className="row">
        <div className="col-12">
                {/*<Sort />*/}
                <div className="row activities pb-5">
                  {activities.length !== 0 ? (activities.map((activity, index) =>
                  <ActivityItem
                  key={"currentAct" + index}
                  item={activity}
                  onFavorited={this.onFavorited}
                  />
                  )) : ('')}
                    <div className="col-12 text-center pt-4">
                      <Pagination
                    bsSize="medium"
                    items={totalPages}
                    activepage={this.state.currentPageNumber}
                    onSelect={this.handleSelect.bind(this)} />
                    </div>
                  
                </div>
        </div>
        </div>
        </div>
      </div>
    );
    }
  }


export default App;