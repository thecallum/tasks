const VueX = require("vuex");

const initializeListData = data => {
    const lists = {};

    /*
        Structure
        =========

        lists = {
            id: 26,
            created_at: "2020-01-18 17:11:48",
            updated_at: "2020-01-19 11:44:52",
            board_id: "1",
            name: "Callums newest list",
            order: "0",
            cards: []
        };

    */

    data.lists.forEach(list => {
        lists[list.id] = {
            ...list,
            cards: data.cards
                .filter(card => card.task_id.toString() === list.id.toString())
                .sort((a, b) => parseInt(a.order) - parseInt(b.order))
        };
    });

    return lists;
};

const Store = new VueX.Store({
    state: {
        lists: initializeListData(data),
        comments: data.comments,
        modalActive: false,
        modalCard: {}
    },
    mutations: {
        updateLists(state, payload) {
            state.lists = payload.lists;
        },
        addCard(state, payload) {
            state.lists[payload.listId].cards = [
                ...state.lists[payload.listId].cards,
                payload.newCard
            ];
        },
        deleteCard(state, payload) {
            state.lists[payload.selectedCard.task_id].cards = state.lists[
                payload.selectedCard.task_id
            ].cards.filter(
                card =>
                    card.id.toString() !== payload.selectedCard.id.toString()
            );
        },
        updateCard(state, payload) {
            state.lists[payload.updatedCard.task_id].cards = state.lists[
                payloadupdatedCard.task_id
            ].cards.map(card => {
                return card.id.toString() === payload.updatedCard.id.toString()
                    ? payload.updatedCard
                    : card;
            });
        },
        deleteList(state, payload) {
            const newLists = JSON.parse(JSON.stringify(state.lists));
            delete newLists[payload.list.id];
            state.lists = newLists;
        },
        createList(state, payload) {
            state.lists = {
                ...state.lists,
                [payload.list.id]: { ...payload.list, cards: [] }
            };
        },
        addComment(state, payload) {
            state.comments = [payload.comment, ...state.comments];
        },
        deleteComment(state, payload) {
            state.comments = state.comments.filter(comment => {
                return comment.id.toString() !== payload.comment.id.toString();
            });
        },
        cardDragged(state, payload) {
            // Update Card Index
            state.lists[payload.listId].cards = payload.newArray.map(
                (card, index) => ({
                    ...card,
                    order: index
                })
            );
        },
        toggleModal(state, payload) {
            if (payload.showModal) {
                state.modalCard = payload.card;
                state.modalActive = true;
            } else {
                state.modalActive = false;
                state.modalCard = {};
            }
        }
    },
    getters: {
        cardComments: state => card_id => {
            return state.comments.filter(comment => {
                return comment.card_id.toString() === card_id.toString();
            });
        }
    }
});

export default Store;
