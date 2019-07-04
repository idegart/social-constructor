import { toInteger, max, get } from 'lodash'

export default {
    script: state => state.script,
    schemas: state => state.schemas,
    blocks: state => state.blocks,
    fieldContainer: state => state.fieldContainer,
    fieldComponent: state => state.fieldComponent,

    getRealPosition: state => (el, center = true) => {
        let rect = el.getBoundingClientRect();

        return {
            left: toInteger(rect.left + state.fieldContainer.scrollLeft + (center ? rect.width / 2 : 0)),
            top: toInteger(rect.top + state.fieldContainer.scrollTop + (center ? rect.height / 2 : 0)),
        }
    },

    isConnectorDragging: state => !! state.connector,
    connector: state => state.connector,
}
