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
    // /api/filter/subcategory/{category}
    getSubcategories(category) {
        axios.get('/api/filter/subcategory/' + category)
            .then(function (response) {
                this.setState({
                    subcategories: response.data
                }, () => {
                    // console.log("getSubcategories callback: " + this.state.subcategories)
                });
            }.bind(this))
            .catch(function (error) {
                console.error(error);
            });
    }

    render() {
        const { onChange } = this.props;
        const { categories, cities, times, weekdays, category, cityId, time, weekday, search, age, price, subcategories, subcategory } = this.state;
        return (

            <div>

                <label>Paieška</label>
                <input name="search" type="text" onChange={(event) => {
                    this.setState({ search: event.currentTarget.value }, () => {
                        let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                        onChange(copy);
                    });

                }} />

                <label>Kategorija</label><br />
                <select name="category" onChange={(event) => {

                    function filterByName(item) {
                        if (item.name == event.target.value) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                    let selectedCategory = categories.filter(filterByName);
                    if (selectedCategory.length === 0) {
                        this.setState({ category: '' }, () => {
                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                            onChange(copy);
                        });
                    } else {

                        this.setState({ category: selectedCategory[0].id }, () => {
                            this.getSubcategories(this.state.category);
                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                            onChange(copy);
                        }
                        );
                    }

                }}>
                    <option name="categoryPlaceholder">Kategorija</option>
                    {
                        categories.length !== 0 ? (categories.map((category, index) =>
                            <option key={"category" + index}>
                                {category.name}
                            </option>)) : ('no data')
                    }
                </select><br />

                <div>
                    {
                        category !== '' ? (

                            <div>
                                <label>Subkategorija</label>
                                <select name="subcategory" onChange={(event) => {
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
                                    <option name="subcategoryPlaceholder">Nesvarbu</option>
                                    {subcategories.map((subcategory, index) =>
                                        <option key={"subcategory" + index}>
                                            {subcategory.name}
                                        </option>)
                                    }
                                </select>
                            </div>
                        ) : ('')
                    }
                </div>

                <label>Miestas</label><br />

                <select name="city" onChange={(event) => {

                    function filterByName(item) {
                        if (item.name == event.target.value) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                    let selectedCity = cities.filter(filterByName);
                    if (selectedCity.length === 0) {
                        this.setState({ cityId: '' }, () => {
                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                            onChange(copy);
                        });
                    } else {
                        this.setState({ cityId: selectedCity[0].id }, () => {
                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                            onChange(copy);
                        });
                    }

                }}>
                    <option name="cityPlaceholder">Miestas</option>

                    {
                        cities.length !== 0 ? (cities.map((city, index) =>
                            <option key={"city" + index}>
                                {city.name}
                            </option>)) : ('no data')
                    }
                </select><br />

                <label>Savaitės dienos</label><br />
                <select name="weekday" onChange={(event) => {
                    if (event.target.value === 'Savaitės diena') {
                        this.setState({ weekday: '' }, () => {
                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                            onChange(copy);
                        }
                        );
                    } else {
                        this.setState({ weekday: weekdays.indexOf(event.target.value) + 1 }, () => {
                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                            onChange(copy);
                        }
                        );
                    }

                }}>
                    <option name="weekdayPlaceholder">Savaitės diena</option>
                    {
                        weekdays.length !== 0 ? (weekdays.map((weekday, index) =>
                            <option key={"weekday" + index}>
                                {weekday}
                            </option>)) : ('no data')
                    }
                </select><br />

                <label>Būrelio pradžia</label><br />
                <select name="time" onChange={(event) => {
                    if (event.target.value === 'Laikas') {
                        this.setState({ time: '' }, () => {
                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                            onChange(copy);
                        }
                        );
                    } else {
                        this.setState({ time: event.target.value }, () => {
                            let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                            onChange(copy);
                        }
                        );
                    }

                }}>
                    <option name="timePlaceholder">Laikas</option>
                    {
                        times.length !== 0 ? (times.map((time, index) =>
                            <option key={"time" + index}>
                                {time}
                            </option>)) : ('no data')
                    }
                </select><br />

                <label>Amžius</label><br />
                <input name="age" type="number" onChange={(event) => {
                    this.setState({ age: event.target.value }, () => {
                        let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                        onChange(copy);
                    }
                    );

                }} />

                <label>Kaina</label><br />
                <input name="price" type="number" onChange={(event) => {
                    this.setState({ price: event.target.value }, () => {
                        let copy = Object.assign({ category, cityId, time, weekday, search, age, price, subcategory }, this.state);
                        onChange(copy);
                    }
                    );

                }} />
            </div>
        )
    }
}
export default Filter;