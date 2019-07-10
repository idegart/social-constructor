import BaseBlock from "@model/Blocks/BaseBlock";
import BlockParam from "@model/Blocks/BlockParam";

export default class ReceiveMessage extends BaseBlock {
    constructor(props, block) {
        super(props, block);

        this.color = 'bg-success';
        this.title = 'Message w/keyboard';
        this.icon = 'fas fa-keyboard';

        this.paramsIn.push(
            new BlockParam({
                block: block,
                label: 'IN',
                connector_id: block.get('id'),
                type: BlockParam.TYPE_IN
            })
        );

        let buttons = block.get('data.buttons')

        buttons.forEach(button => {
            this.paramsOut.push(
                new BlockParam({
                    block: block,
                    label: button.label,
                    connector_id: button.next_block,
                    cb: this.connectOutParam(button),
                    cbClick: this.removeNextBlock(button),
                    cbRemove: this.removeButton(button)
                })
            );
        });

        this.paramsOut.push(
            new BlockParam({
                block: block,
                label: 'ON ERROR',
                connector_id: block.get('data.error_next_block'),
                cb: this.connectOutErrorParam(),
                cbClick: this.removeOutErrorParam(),
                extraClass: 'text-danger'
            })
        );
    }

    connectOutErrorParam () {
        return ({end_param}) => {
            this.block.set({data: {error_next_block: end_param.block.get('id')}});
            this.block.save()
        }
    }

    removeOutErrorParam () {
        return () => {
            this.block.set({data: {error_next_block: null}});
            this.block.save()
        }
    }

    connectOutParam (button) {
        return ({end_param}) => {
            this.block.set({data: {button_update: {
                id: button.id, next_block: end_param.block.get('id')}
            }});
            this.block.save()
        }
    }

    removeNextBlock (button) {
        return () => {
            this.block.set({data: {button_update: {
                id: button.id, next_block: null}
            }});
            this.block.save()
        }
    }

    removeButton (button) {
        return () => {
            this.block.set({data: {button_remove: button.id}});
            this.block.save()
        }
    }
}
