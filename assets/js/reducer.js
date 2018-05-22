const INITIAL_STATE = {
    activities: [],
};

const reducer = (state = INITIAL_STATE, action) => {
    switch (action.type) {
        case "GET_ACTIVITIES":
            return { ...state, activities: action.payload };
        default:
            return state;
    }
};

export default reducer;