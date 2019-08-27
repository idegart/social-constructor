import BaseBlock from "@model/Blocks/BaseBlock";
import BlockParam from "@model/Blocks/BlockParam";

export default class Dialogflow extends BaseBlock {
    constructor(props, block) {
        super(props, block);

        this.color = 'bg-info';
        this.title = 'Dialogflow';
        this.icon = 'fas fa-robot';

        let actions = block.get('data.actions')

        actions.forEach(action => {
            this.paramsOut.push(
                new BlockParam({
                    block: block,
                    label: action.action,
                    connector_id: action.next_block_id,
                    cb: this.connectOutParam(action),
                    cbClick: this.removeNextBlock(action),
                    cbRemove: this.removeButton(action)
                })
            );
        });

        if (!block.get('data.is_initial')) {
            this.paramsIn.push(
                new BlockParam({
                    block: block,
                    label: 'IN',
                    connector_id: block.get('id'),
                    type: BlockParam.TYPE_IN
                })
            );
        }
    }

    connectOutParam (action) {
        return ({end_param}) => {
            this.block.set({data: {action_update: {
                        id: action.id, next_block_id: end_param.block.get('id')}
                }});
            this.block.save()
        }
    }

    removeNextBlock (action) {
        return () => {
            this.block.set({data: {action_update: {
                        id: action.id, next_block_id: null}
                }});
            this.block.save()
        }
    }

    removeButton (action) {
        return () => {
            this.block.set({data: {action_remove: action.id}});
            this.block.save()
        }
    }
}
