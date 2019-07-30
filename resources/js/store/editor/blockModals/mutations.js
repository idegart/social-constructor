export default {
    openBlockModal: (state, {modalName, block}) => {
        state.blockModal.id = modalName;
        state.blockModal.block = block;
    },

    hideBlockModal: state => {
        state.blockModal.id = null;
        state.blockModal.block = null;
    }
}
