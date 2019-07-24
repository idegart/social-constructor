import BaseBlock from "@model/Blocks/BaseBlock";
import BlockParam from "@model/Blocks/BlockParam";

export default class ExternalApi extends BaseBlock {
    constructor(props, block) {
        super(props, block);

        this.color = 'bg-info';
        this.title = `External API`;
        this.icon = 'fas fa-satellite-dish';

        this.paramsIn.push(
            new BlockParam({
                block: block,
                label: 'IN',
                connector_id: block.get('id'),
                type: BlockParam.TYPE_IN
            })
        );

        let options = block.get('data.options')

        options.forEach(option => {
            this.paramsOut.push(
                new BlockParam({
                    block: block,
                    label: option.key,
                    connector_id: option.next_block_id,
                    cb: this.connectOutParam(option),
                    cbClick: this.removeNextBlock(option),
                    cbRemove: this.removeButton(option)
                })
            );
        });
    }

    connectOutParam (button) {
        return ({end_param}) => {
            this.block.set({data: {option_update: {
                        id: button.id, next_block_id: end_param.block.get('id')}
                }});
            this.block.save()
        }
    }

    removeNextBlock (button) {
        return () => {
            this.block.set({data: {button_update: {
                        id: button.id, next_block_id: null}
                }});
            this.block.save()
        }
    }

    removeButton (option) {
        return () => {
            this.block.set({data: {option_remove: option.id}});
            this.block.save()
        }
    }
}
