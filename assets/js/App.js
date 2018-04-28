import React from 'react';
import Filter from './components/Filter.js';
import { Pagination } from '@react-bootstrap/pagination';
const axios = require('axios');

class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      activities: [],
      currentPageNumber: 1
    };
  }
  
 getActivities(page) {
   axios.get('/api/activity?page=' + page + '&limit=9')
     .then(function (response) {
       this.setState({
         activities: Object.keys(response.data).map(i => response.data[i]),
         currentPageNumber: page
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
    this.getActivities(number);
  }


  onFilterChange(values)  {
    console.log(values);
  }

  render() {
    const { activities, currentPage, activitiesPerPage } = this.state;
    

    return (
      <div className="container">
      <Filter 
        onChange={this.onFilterChange}
      />
        <div className="row activities py-3">

           {activities.length !== 0 ? (activities.map((activity, index) =>  
            <div key={"currentAct" + index} className="col-md-4 col-sm-6 col-xs-6 py-3">
              <div className="activity-card">
                <img className="img-fluid" src="https://placeimg.com/640/480/any" alt="Card image cap" />
                <div className="activity-text">
                  <h5 className="activity-title">{activity.name}</h5>
                  <p>Kaina: {activity.priceFrom}-{activity.priceTo} eur</p>
                  <p>{activity.city}</p>
                  <a className="btn btn-more" href={"/activity/" + activity.id}> Plačiau </a>
                </div>
              </div>
            </div>)) : ('no data')}
            
        </div>

        <Pagination
          bsSize="medium"
          items={6}
          activepage={this.state.currentPageNumber}
          onSelect={this.handleSelect.bind(this)} />
        
      </div>
    );
    }
  }


export default App;