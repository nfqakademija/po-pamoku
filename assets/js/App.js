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
      searchValue: null
    };
    this.onFilterChange = this.onFilterChange.bind(this);
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
    axios.get('/api/activity?page=' + page + '&limit=9&search=' + value.search + '&city=' + value.cityId + '&category=' + value.category +
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
  }

  onFilterChange(value) {
    this.setState({searchValue: value});
    this.searchActivities(1, value);
  }

  render() {
      return (<MapComponent/>);
    }
  }


export default App;