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
    axios.get('/api/activity/1/10')
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
            <div className="col-lg-3 col-md-4 col-sm-6 col-xs-6">
              <div className="card">
                <img className="card-img-top" src="https://placeimg.com/640/480/any" alt="Card image cap" />
                <div className="card-body">
                  <h5 className="card-title">{activity.name}</h5>
                </div>
                <ul className="list-group list-group-flush">
                  <li className="list-group-item">Kaina: {activity.priceFrom} - {activity.priceTo}</li>
                  <li className="list-group-item">Amžius: {activity.ageFrom} - {activity.ageTo}</li>
                </ul>
                <div className="card-body">
                  <a className="btn btn-primary" href={"/activity/" + activity.id}> Plačiau </a>
                </div>
                <button onClick={this.toggleHidden.bind(this)} >
                  Click to show modal
        </button>
                {!this.state.isHidden && <Show name="Elena" />}
              </div>
            </div>)) : ('no data')}
        </div>
      </div>
    );
    }
  }

function Show(props) {
  return <p>Hello, {props.name}</p>;
}


export default App;