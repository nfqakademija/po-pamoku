import React from 'react';
const axios = require('axios');


class Filter extends React.Component {
    constructor() {
        super();

        this.state = {
            categories: [],
            cities: [],
            times: [],
            weekdays: [],
            category: '',
            cityId: '',
            weekday: '',
            time: '',
            search: '',
            age: '',
            price: '',
            subcategories: [],
            subcategory: ''
        };
    }

    componentDidMount() {
        this.getFilters();
    }

    getFilters() {
        axios.get('/api/filter/init')
            .then(function (response) {
                this.setState({
                    categories: response.data.categories,
                    cities: response.data.cities,
                    times: response.data.times,
                    weekdays: response.data.weekdays
                });
            }.bind(this))
            .catch(function (error) {
                console.error(error);
            });
    }
    // getSubcategories(category) {
    //     axios.get('/api/filter/subcategory/' + category)
    //         .then(function (response) {
    //             this.setState({
    //                 subcategories: response.data
    //             }, () => {
    //             });
    //         }.bind(this))
    //         .catch(function (error) {
    //             console.error(error);
    //         });
    // }
    render() {
        const { onChange } = this.props;
        const { categories, cities, times, weekdays, category, cityId, time, weekday, search, age, price, subcategories, subcategory } = this.state;
        return (
            <div className="row pt-3 pb-4 justify-content-center">
                <div className="col-12 col-sm-6 col-md py-2 text-center">
                    <input className="filter" name="search" type="text" placeholder="Pavadinimas" onChange={(event) => {
                        this.setState({ search: event.currentTarget.value }, 
                            () => onChange({ ...this.state })
                        );
                    }} />
                </div>
                <div className="col-12 col-sm-6 col-md py-2 text-center">
                        <input className="filter filter-small" name="price" type="number" placeholder="Kaina" onChange={(event) => {
                        this.setState({ price: event.target.value }, 
                            () => onChange({ ...this.state })
                        );
                    }} />
                </div>
                <div className="col-12 col-sm-6 col-md py-2">
                    <input className="filter filter-small" name="age" type="number" placeholder="Amžius" onChange={(event) => {
                        this.setState({ age: event.target.value },
                            () => onChange({ ...this.state })
                        );
                    }} />
                </div>
                <div className="col-12 col-sm-6 col-md py-2">
                    <select name="city" className="filter filter-select" onChange={(event) => {
                        const selectedCity = cities.find((item) => item.name == event.target.value);
                        this.setState({
                            cityId: selectedCity ? selectedCity.id : '',
                            }, () => onChange({ ...this.state }));
                        }}>
                        <option name="cityPlaceholder">Miestas</option>
                        {
                            cities.length !== 0 ? (cities.map((city, index) =>
                                <option key={"city" + index}>
                                    {city.name}
                                </option>)) : ('no data')
                        }
                    </select>
                </div>
                <div className="col-12 col-sm-6 col-md py-2">
                    <select name="category" className="filter filter-select" onChange={(event) => {
                        const selectedCategory = categories.find((item) => item.name == event.target.value);
                        this.setState({
                            category: selectedCategory ? selectedCategory.id : '',
                            subcategory: '',
                            }, () => onChange({ ...this.state }));
                        }}>
                        <option name="categoryPlaceholder">Kategorija</option>
                        {
                            categories.length !== 0 ? (categories.map((category, index) =>
                                <option key={"category" + index}>
                                    {category.name}
                                </option>)) : ('')
                        }
                    </select>
                </div>
                {/* <div>
                    {
                        category !== '' ? (

                            <div className="col-3 py-2">
                                <label>Tipas</label>
                                <select name="subcategory" className="filter" onChange={(event) => {
                                    function filterByName(item) {
                                        if (item.name == event.target.value) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    }
                                    let selectedSubcategory = subcategories.filter(filterByName);
                                    if (selectedSubcategory.length === 0) {
                                        this.setState({ subcategory: '' }, () => {
                                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                                            onChange(copy);
                                        });
                                    } else {
                                        this.setState({
                                            subcategory: selectedSubcategory[0].id,
                                        }, () => {
                                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                                            onChange(copy);
                                        });
                                    }
                                }}>
                                    <option name="subcategoryPlaceholder">Visi</option>
                                    {subcategories.map((subcategory, index) =>
                                        <option key={"subcategory" + index}>
                                            {subcategory.name}
                                        </option>)
                                    }
                                </select>
                            </div>
                        ) : ('')
                    }
                </div> */}

                {/* <div className="col-6 col-md-4 col-lg-3 py-2">
                    <select name="weekday" className="filter filter-select" onChange={(event) => {
                        this.setState({
                            weekday: event.target.value === 'Savaitės diena' ? '' : (weekdays.indexOf(event.target.value) + 1),
                        }, () => {
                            console.log(this.state.weekday);
                            onChange({ ...this.state })
                        });
                }}>
                    <option name="weekdayPlaceholder">Savaitės diena</option>
                    {
                        weekdays.length !== 0 ? (weekdays.map((weekday, index) =>
                            <option key={"weekday" + index}>
                                {weekday}
                            </option>)) : ('no data')
                    }
                </select>
            </div>
                <div className="col-6 col-md-4 col-lg-3 py-2">
                    <select name="time" className="filter filter-select" onChange={(event) => {
                        this.setState({
                            time: event.target.value === 'Laikas' ? '' : event.target.value,
                        }, () => {
                            console.log(this.state.time);
                            onChange({ ...this.state })});
                }}>
                    <option name="timePlaceholder">Laikas</option>
                    {
                        times.length !== 0 ? (times.map((time, index) =>
                            <option key={"time" + index}>
                                {time}
                            </option>)) : ('no data')
                    }
                </select>
                </div> */}
            </div>
        )
    }
}
export default Filter;