import React from 'react';

class Filter extends React.Component {
    constructor() {
        super();

        this.state = {
            name: null,
            pavarde: null,
        };
    }

    render() {
        const { onChange } = this.props;
        // const values = { ...this.state };

        return (
            <div>
                <label>Name</label>
                <input name="name" onChange={(value) => {
                    this.setState({ name: value });

                    onchange(values);
                }} />
                <input name="name" onChange={(value) => {
                    this.setState({ pavarde: value })

                    onchange(values);
                }} />
            </div>
        )
    }
}
export default Filter;