import Vue from 'vue'
import Vuex from 'vuex';
import {toInteger} from "lodash";

Vue.use(Vuex);

const store = new Vuex.Store();

export default store

export function getRealPosition (el, center = true) {
    let rect = el.getBoundingClientRect(),
        fieldContainer = document.getElementById('editorContainer');

    return {
        left: toInteger(rect.left + fieldContainer.scrollLeft + (center ? rect.width / 2 : 0)),
        top: toInteger(rect.top + fieldContainer.scrollTop + (center ? rect.height / 2 : 0)),
    }
}
