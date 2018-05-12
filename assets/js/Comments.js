import React from 'react';

const axios = require('axios');


const ViewComments = (props) => {
    let comments = props.comments;
    return (
        (comments.map((comment, id) =>
            <div key={id}>
                <div>{comment.commentText}</div>
                <div>{comment.createdAt}</div>
                <div>{comment.user.name} {comment.user.surname}</div>
            </div>
        )
        )
    );
};

class Comments extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            comments: [],
            id: props.id,
        };
    }

    getData = () => {
        axios.get('/api/comments/'+this.state.id)
            .then(response => this.setState({
                comments: Object.keys(response.data).map(i => response.data[i])
            }));
    };

    handleCommentPosting = (e) => {
        e.preventDefault();
        let commentForm = e.currentTarget;
        let data = new FormData(commentForm);
        axios.post(commentForm.action, data)
            .then(response => {
                commentForm.closest('div').innerHTML = response.data;
            })
            .then(this.getData())
            .catch(error => {
                commentForm.closest('div').innerHTML = error.response.data;
            });
    };

    componentWillMount() {
        document.getElementById('commentForm').onsubmit = this.handleCommentPosting;

    }

    componentDidMount() {
        this.getData();
    }

    componentWillUpdate() {
        document.getElementById('commentForm').onsubmit = this.handleCommentPosting;
    }

    render() {
        return (
            (this.state.comments &&
            <div>
                <ViewComments comments={this.state.comments}/>
            </div>
            )
        );
    }

}

export default Comments;