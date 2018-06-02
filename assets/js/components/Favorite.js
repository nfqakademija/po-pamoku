import React from 'react';
import axios from 'axios';

class Favorite extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            favoriteActivities: [],
        };
    }
    render() {
        return (
                <button className="like-btn">
                    <i className="far fa-heart"></i>
                </button>
        )
    }
}

export default Favorite;
