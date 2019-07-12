import Konva from "konva";

export default {
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
}
