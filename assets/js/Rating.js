import React from 'react';
import axios from 'axios';


class Rating extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      id: props.id,
      ratingFormId: props.form,
    };
  }

  componentWillMount() {
    this.setOnSubmit();
  }

  componentDidMount() {
    this.setOnSubmit();
  }

    setOnSubmit = () => {
      let ratingForm = document.getElementById(this.state.ratingFormId);
      if (typeof(ratingForm) != 'undefined')
      {
        ratingForm.onsubmit = this.handlePosting;
      }
    };

    handlePosting = (e) => {
      e.preventDefault();
      let ratingForm = e.currentTarget;
      let data = new FormData(ratingForm);
      axios.post(ratingForm.action, data)
        .then(response => {
          ratingForm.closest('div').innerHTML = response.data;
          this.setOnSubmit();
        })
        .catch(error => {
          ratingForm.closest('div').innerHTML = error.response.data;
          this.setOnSubmit();
        });
    };

    render() {
      return null;
    }

}

export default Rating;