import React from 'react';
const axios = require('axios');

class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      activities: [],
      currentPage: 1,
      activitiesPerPage: 4
    };
    this.handleClick = this.handleClick.bind(this);
  }
  
  handleClick(event) {
    this.setState({
      currentPage: Number(event.target.id)
    });
  }

  componentDidMount() {
    axios.get('/api/activity?page=1&limit=9')
      .then(function (response) {
        this.setState({
          activities: Object.keys(response.data).map(i => response.data[i])
        });
      }.bind(this))
      .catch(function (error) {
        console.error(error);
      });
  }

  render() {
    const { activities, currentPage, activitiesPerPage } = this.state;
    // pagination logic
    const indexOfLastActivity = currentPage * activitiesPerPage;
    const indexOfFirstActivity = indexOfLastActivity - activitiesPerPage;
    const currentActivities = activities.slice(indexOfFirstActivity, indexOfLastActivity);
    // ---------------
     const renderActivities = currentActivities.map((activity, index) => {
      return <div className="col-md-4 col-sm-6 col-xs-6 py-3">
      <div className="activity-card">
        <img className="img-fluid" src="https://placeimg.com/640/480/any" alt="Card image cap" />
        <div className="activity-text">
          <h5 key={index} className="activity-title">{activity.name}</h5>
          <p>Kaina: {activity.priceFrom}-{activity.priceTo} eur</p>
          <p>{activity.city}</p>
          <a className="btn btn-more" href={"/activity/" + activity.id}> Plačiau </a>
        </div>
      </div>
  </div>
    });

    const pageNumbers = [];
    for (let i = 1; i <= Math.ceil(activities.length / activitiesPerPage); i++) {
      pageNumbers.push(i);
    }

    const renderPageNumbers = pageNumbers.map(number => {
      return (
          <button
          type="button"
            key={number}
            id={number}
            onClick={this.handleClick}
          >
            {number}
          </button>
      );
    });

    return (
      <div className="container">
        <div className="row activities">
           {/* {activities.length !== 0 ? (activities.map((activity) =>  )) : ('no data')} */}
          {renderActivities}
        </div>
        <div id="page-numbers">
            {renderPageNumbers}
        </div>
      </div>
    );
    }
  }


export default App;