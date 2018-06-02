import React from 'react';
import axios from 'axios';


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
            </div>
        )
    }
}
export default Filter;