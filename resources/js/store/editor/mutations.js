import Konva from "konva";
import {getRealPosition} from '../index'

export default {
    setScript: (state, script) => state.script = script,
    clearScript: state => state.script = null,

    addSchema: (state, schema) => state.schemas.push(schema),
    clearSchemas: state => state.schemas = [],

    setSchema: (state, schema) => state.schema = schema,
    clearShema: state => state.schema = null,

    addBlock: (state, block) => state.blocks.push(block),
    clearBlocks: state => state.blocks = [],

    setSchemaField: state => {
        state.fieldContainer = document.getElementById('editorContainer');
        state.fieldComponent = document.getElementById('editorField');

        state.field = new Konva.Stage({
            container: state.fieldComponent,
            width: 5000,
            height: 1000
        });

        state.connectionsLayer = new Konva.Layer();

        state.field.add(state.connectionsLayer);
    },

    setStartConnector: (state, {el, param}) => {
        let line = new Konva.Line({
            stroke: 'white',
            strokeWidth: 3,
            lineCap: 'round',
            lineJoin: 'round',
            dash: [10, 5, 0.1, 5],
        });

        state.connectionsLayer.add(line);

        state.connector = {
            start: getRealPosition(el),
            start_param: param,
            end: null,
            line,
        };
    },

    setEndConnector: (state, el) => {
        let editorElement = document.getElementById('editorContainer');

        state.connector.end = {
            left: el.x + editorElement.scrollLeft,
            top: el.y + editorElement.scrollTop,
        };

        state.connector.line.points(
            [
                state.connector.start.left, state.connector.start.top,
                state.connector.end.left, state.connector.end.top
            ]
        );

        state.connectionsLayer.draw()
    },

    removeConnector: state => {
        state.connector.line.destroy();
        state.connector = null;
        state.connectionsLayer.draw()
    }
}
