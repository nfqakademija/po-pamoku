import React from 'react';
import axios from 'axios';


class ActivityItem extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            favoriteList: [],
            disabled: false
        }
    }


render() {
    const { item: activity } = this.props;
    const { favoriteList } = this.state;
    let list = [];
    const disabled = this.state.disabled ? 'disabled' : ''
   return (
   <div className="col-xs-6 col-sm-6 col-lg-4 py-3">
        <div className="activity-card">
            <div className="card-image">

                <a className="card-btn overlay" href={"/activity/" + activity.id}><i className="fas fa-search-plus"></i></a>

                   <img className="img-fluid" src={activity.pathToLogo} alt="Card image cap" />

                <button className="like-btn"
                    disabled={disabled}
                    onClick={() => {
                        let storageList  = JSON.parse(localStorage.getItem('favoriteList'));
                        if (storageList == null) {
                            list.push(activity);
                            localStorage.setItem('favoriteList', JSON.stringify(list));
                            this.setState({disbaled: true});
                        } else {
                            storageList.forEach((item) => {
                                if (item.id === activity.id) {
                                    return;
                                }
                            });
                            storageList.push(activity);
                            localStorage.setItem('favoriteList', JSON.stringify(storageList));
                            this.setState({ favoriteList: storageList,
                            disabled: true });
                        }
                        }}>
                    <i className="far fa-heart"></i>
                </button>

                <div className="price">
                    {activity.priceFrom}-{activity.priceTo} €
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