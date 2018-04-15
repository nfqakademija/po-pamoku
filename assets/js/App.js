import React from 'react';
import Activity from './activity';
const axios = require('axios');



class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = { 
      activities: [] 
    };
  }

 componentDidMount() {
    // let versionElement = document.getElementById('version');
   axios.get('/api/activities')
      .then(function (response) {
        this.setState({
          activities: response.data
        });
        console.log(this.state.activities);
        // versionElement.innerText = response.data["build/js/app.js"];
     }.bind(this))
      .catch(function (error) {
        console.error(error);
        // versionElement.innerText = 'Error: '.error;
      });
  //  fetch('/api/activities')
  //    .then( response => {
  //      response.json()
  //    .then(myJson =>{
  //      console.log(myJson);
  //      console.log(myJson[0]);
  //      this.setState({
  //        activities: myJson
  //      });
  //     });
  //   });
  //  fetch('/api/activities')
  //  .then(response => {
  //    this.setState({
  //      activities: response.json()
  //    });
  //    console.log(activities);
  //   });
   axios.get('/api/activities')
     .then(function (response) {
       this.setState({
         activities: response.data
       });
       console.log(this.state.activities);
       // versionElement.innerText = response.data["build/js/app.js"];
     }.bind(this))
     .catch(function (error) {
       console.error(error);
       // versionElement.innerText = 'Error: '.error;
     });
   
 }
  
  
    render() {
      const isData = this.state.activities;

      return  (
        <div className="container">
        <div className="row activities">            
            {isData.length !== 0 ? (isData.map((activity) =>
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
                </div>
              </div>)) : ('no data')} 
            </div>
        </div>
      );
    }
}

export default App;