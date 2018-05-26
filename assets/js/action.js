
export const onRequest = () => {
    console.log("onRequest");
    return (dispatch, getState) => {
        fetch('/api/activity?page=1&limit=12')
            .then(response => response.json())
            .then(data => {
                if(data && data.Activities) {

                }

                dispatch({ type: "GET_ACTIVITIES", payload: { data: data.Activities }});
            });

    }
}

// function* onRequest({ payload: url }) {
//     const { response, error } = yield put(onFetch(url));

//     if(response) {

//         return;
//     }


// }
