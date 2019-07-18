import Konva from "konva";
import {getRealPosition} from "@store/index";

export default {
    setStartConnector: (state, {el, param}) => {
        let line = new Konva.Line({
            stroke: 'white',
            strokeWidth: 3,
            lineCap: 'round',
            lineJoin: 'round',
            dash: [10, 5, 0.1, 5],
        });

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
    },

    removeConnector: state => {
        state.connector = null;
    },
}
