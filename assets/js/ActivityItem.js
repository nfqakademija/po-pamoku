import React from 'react';
import axios from 'axios';


class ActivityItem extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
<<<<<<< HEAD
            isFav: false
=======
            favoriteList: [],
            disabled: false
>>>>>>> e031a3384c39b6115b728525b5a3f0fe91412593
        }
    }


render() {
    const { item: activity } = this.props;
<<<<<<< HEAD
=======
    const { favoriteList } = this.state;
    let list = [];
    const disabled = this.state.disabled ? 'disabled' : ''
>>>>>>> e031a3384c39b6115b728525b5a3f0fe91412593
   return (
   <div className="col-xs-6 col-sm-6 col-lg-4 py-3">
        <div className="activity-card">
            <div className="card-image">

                <a className="card-btn overlay" href={"/activity/" + activity.id}><i className="fas fa-search-plus"></i></a>
                <img className="img-fluid" 
                src={activity.pathToLogo ? activity.pathToLogo : '/uploads/33e75ff09dd601bbe69f351039152189.jpg'} 
                alt="Card image cap" />
                <button className="like-btn"
                    disabled={disabled}
                    onClick={() => {
<<<<<<< HEAD
                            if (localStorage.getItem('favorite' + activity.id) === null){
                                localStorage.setItem('favorite' + activity.id, JSON.stringify(activity));
                                this.setState({ isFav: true });
                            }
                            else {
                                localStorage.removeItem('favorite' + activity.id);
                                this.setState({ isFav: false });
                            }
=======
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
>>>>>>> e031a3384c39b6115b728525b5a3f0fe91412593
                        }}>

                        <i className={this.state.isFav || localStorage.getItem('favorite' + activity.id) ? 'fas fa-heart' : 'far fa-heart'} ></i>
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