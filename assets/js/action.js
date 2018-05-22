
export const onRequest = () => {
    console.log("onRequest");
    return (dispatch, getState) => {
        console.log("veikia2");
        fetch('/api/activity?page=1&limit=12')
            .then(response => { Object.keys(response.data).map(i => response.data[i])[1]} )
            .then(data => dispatch({ type: "GET_ACTIVITIES", payload: data }));
    }
}
