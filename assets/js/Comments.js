import React from 'react';

const axios = require('axios');

class Comments extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            comments: [],
            id: window.location.pathname.split('/').slice(-1)[0]
        }
    }

    componentDidMount() {
        axios.get('/api/comments/'+this.state.id)
            .then(response => this.setState({
                comments: Object.keys(response.data).map(i => response.data[i])
            }));
    }

    render() {
        console.log(this.state.comments);
        let comments = this.state.comments;
        return (
            comments.map((comment, id) =>
                <div key={id}>
                    <div>{comment.commentText}</div>
                    <div>{comment.createdAt}</div>
                    <div>{comment.user.name} {comment.user.surname}</div>
                </div>
            )
        );
    }

}

export default Comments;