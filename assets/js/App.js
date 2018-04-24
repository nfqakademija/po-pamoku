import React from 'react';
const axios = require('axios');

class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      activities: [],
      isHidden: true
    };
  }
  toggleHidden() {
    this.setState({
      isHidden: !this.state.isHidden
    })
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
    const isDataArray = this.state.activities;
    console.log(isDataArray);
    return (
      <div className="container">
        <div className="row activities">
          {isDataArray.length !== 0 ? (isDataArray.map((activity) =>       
            <div className="col-md-4 col-sm-6 col-xs-6 py-3">
              <div className="activity-card">
                <img className="img-fluid" src="https://placeimg.com/640/480/any" alt="Card image cap" />
                <div className="activity-text">
                  <h5 className="activity-title">{activity.name}</h5>
                  <p>Kaina: {activity.priceFrom}-{activity.priceTo} eur</p>
                  <p>{activity.city}</p>
                  <a className="btn btn-more" href={"/activity/" + activity.id}> Plačiau </a>
                </div>
               
                {/* <button onClick={this.toggleHidden.bind(this)} >
                  Click to show modal
        </button>
                {!this.state.isHidden && <Show name="Elena" />} */}
              </div>
            </div>)) : ('no data')}
        </div>
      </div>
    );
    }
  }

// function Show(props) {
//   return <p>Hello, {props.name}</p>;
// }


export default App;