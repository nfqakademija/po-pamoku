import React from 'react';
import axios from 'axios';

const ViewComments = (props) => {
  let comments = props.comments;
  return (
    (comments.map((comment, id) =>
      <div className="col-12" key={id}>
        <div className="row my-3">
          <div className="col-12 comment-details">{comment.createdAt}/ {comment.user.name} {comment.user.surname}</div>
          <div className="col-12 pt-2 comment-text">{comment.commentText}</div>
        </div>
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
      commentFormId: props.form
    };
  }

  componentWillMount() {
    this.getData();
    this.setOnSubmit();
  }

  componentDidMount() {
    this.setOnSubmit();
    this.getData();
  }

  componentDidUpdate() {
    this.setOnSubmit();
  }

    getData = () => {
      axios.get('/api/comments/'+this.state.id)
        .then(response => this.setState({
          comments: Object.keys(response.data).map(i => response.data[i])
        }));
    };

    setOnSubmit = () => {
      let commentForm = document.getElementById(this.state.commentFormId);
      if (typeof(commentForm) != 'undefined' && commentForm != null)
      {
        commentForm.onsubmit = this.handleCommentPosting;
      }
    }

    handleCommentPosting = (e) => {
      e.preventDefault();
      let commentForm = e.currentTarget;
      let data = new FormData(commentForm);
      axios.post(commentForm.action, data)
        .then(response => {
          commentForm.closest('div').innerHTML = response.data;
          this.getData();
        })
        .catch(error => {
          commentForm.closest('div').innerHTML = error.response.data;
          this.setOnSubmit();
        });
    };



    render() {
      return (
        (this.state.comments &&
                <div className="container">
                  <div className="row no-gutters my-5">
                    <ViewComments comments={this.state.comments}/>
                  </div>
                </div>
        )
      );
    }

}

export default Comments;