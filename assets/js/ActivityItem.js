import React from 'react';
import axios from 'axios';


class ActivityItem extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isFav: false
        }
    }

    getfavorites = () => {
        const favoritesValue = localStorage.getItem('favorites');
        return (!!favoritesValue && JSON.parse(favoritesValue)) || [];
    }

    isFavorite = (id) => {
        return this.getfavorites().find((item) => item === id);
    };

    onFavorite = (id) => {
        let favorites = this.getfavorites();
        const { onFavorited } = this.props;
        const isFavorite = this.isFavorite(id);

        if(!!isFavorite){
            for(let i = favorites.length - 1; i >= 0; --i) {
                if(favorites[i] === id) {
                    favorites.splice(i, 1);
                }
            }
        }
        else{
            favorites.push(id);
        }

        localStorage.setItem('favorites', JSON.stringify(favorites));

        this.setState({ isFav: !isFavorite });
        onFavorited();
    };


render() {
    const { item: activity, isInInfoWindow } = this.props;
    const isFavorite = this.isFavorite(activity.id);

    return (
   <div className={!isInInfoWindow ? 'col-xs-6 col-sm-6 col-lg-4 py-3' : ''}>
        <div className="activity-card">
            <div className="card-image">

                <a className="card-btn overlay" href={"/activity/" + activity.id}><i className="fas fa-search-plus"></i></a>
                <img className="img-fluid" 
                src={activity.pathToLogo ? activity.pathToLogo : '/uploads/33e75ff09dd601bbe69f351039152189.jpg'} 
                alt="Card image cap" />
                <button className="like-btn"
                    onClick={() => this.onFavorite(activity.id)}
                >

                        <i className={isFavorite ? 'fas fa-heart' : 'far fa-heart'} ></i>
                </button>

                <div className="price">
                    {activity.priceFrom === activity.priceTo ? (activity.priceFrom) : (activity.priceFrom + "-" + activity.priceTo)} €
                </div>
            </div>

            <div className="activity-text">
                <h5 className="activity-title">
                    {activity.name}
                </h5>
                <p className="grey-text">
                    {activity.city}, {activity.street} {activity.house}
                </p>
                <p className="d-flex justify-content-between align-items-baseline">
                    <span className="grey-text">{activity.subcategory}</span>
                </p>

            </div>
        </div>
    </div>
    )
}


}
export default ActivityItem;