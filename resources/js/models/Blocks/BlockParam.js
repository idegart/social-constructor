import { get, uniqueId, truncate } from 'lodash'
import store from '@js/store'

export default class BlockParam {

    static get CONNECT_BLOCK () { return  'block'};
    static get TYPE_IN () {return 'IN'};
    static get TYPE_OUT () {return 'OUT'};

    constructor(props) {
        this._id = uniqueId();
        this.block = get(props, 'block');
        this.label = truncate(get(props, 'label'), {'length': 12});
        this.type = get(props, 'type', BlockParam.TYPE_OUT);
        this.connector = {
            type: get(props, 'connector_type', BlockParam.CONNECT_BLOCK),
            id: get(props, 'connector_id'),
            icon: {
                active: 'fas fa-circle',
                inactive: 'far fa-circle',
            },
        };

        this.extraClass = get(props, 'extraClass');

        this.payload = get(props, 'payload');
        this.cb = get(props, 'cb');
        this.cbClick = get(props, 'cbClick');
        this.cbRemove = get(props, 'cbRemove');
    }

    getIcon () {
        return  this.type === BlockParam.TYPE_OUT
            ? this.connector.id
                ? this.connector.icon.active
                : this.connector.icon.inactive
            : this.connector.icon.inactive
    }

    handleEndPoint () {
        let connector = store.getters['editor/connector'];

        if (connector && typeof connector.start_param.cb === 'function') {
            connector.start_param.cb({
                end_param: this,
                start_param: connector.start_param
            });
        }
    }

    handleStartPoint (event) {
        store.commit('editor/setStartConnector', {
            el: event.target,
            param: this,
        })
    }

    handleOutClickEvent (event) {
        this.cbClick(event)
    }
}
