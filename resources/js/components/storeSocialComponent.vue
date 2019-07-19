<template>
    <div>
        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#storeScriptModal">
            Add new
            <i class="fas fa-plus"></i>
        </button>

        <div class="modal fade" id="storeScriptModal" tabindex="-1" role="dialog" aria-labelledby="storeScriptModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeScriptModalLabel">Add new social channel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitScript" autocomplete="off">
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input v-model="form.type"
                                           value="vkontakte"
                                           type="radio"
                                           id="socialVk"
                                           name="customRadioInline1"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="socialVk">Vkontakte</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input v-model="form.type"
                                           value="telegram"
                                           type="radio"
                                           id="socialTg"
                                           name="customRadioInline1"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="socialTg">Telegram</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input v-model="form.type"
                                           value="chat2desk"
                                           type="radio"
                                           id="socialC2D"
                                           name="customRadioInline1"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="socialC2D">Chat2Desk</label>
                                </div>
                            </div>
                            <div v-if="form.type === 'vkontakte'">
                                <div class="form-group">
                                    <label for="vk_group_id">Group ID</label>
                                    <input v-model="form.vk_group_id"
                                           class="form-control"
                                           :class="getValidationClass('form.vk_group_id')"
                                           id="vk_group_id"
                                           placeholder="Enter group ID">
                                    <validation-errors :$v="$v" attribute="form.vk_group_id" />
                                </div>
                            </div>
                            <div v-if="form.type === 'telegram'">
                                <div class="form-group">
                                    <label for="telegram_token">Token</label>
                                    <input v-model="form.telegram_token"
                                           class="form-control"
                                           :class="getValidationClass('form.telegram_token')"
                                           id="telegram_token"
                                           placeholder="Enter access token">
                                    <validation-errors :$v="$v" attribute="form.telegram_token" />
                                </div>
                            </div>
                            <div v-if="form.type === 'chat2desk'">
                                <div class="form-group">
                                    <label for="chat2desk_token">Token</label>
                                    <input v-model="form.chat2desk_token"
                                           class="form-control"
                                           :class="getValidationClass('form.chat2desk_token')"
                                           id="chat2desk_token"
                                           placeholder="Enter access token">
                                    <validation-errors :$v="$v" attribute="form.chat2desk_token" />
                                </div>
                                <div class="form-group">
                                    <label for="chat2desk_operator_id">Operator ID</label>
                                    <input v-model="form.chat2desk_operator_id"
                                           class="form-control"
                                           :class="getValidationClass('form.chat2desk_operator_id')"
                                           id="chat2desk_operator_id"
                                           placeholder="Enter operator id">
                                    <validation-errors :$v="$v" attribute="form.chat2desk_operator_id" />
                                </div>
                            </div>
                            <div class="form-group form-check">
                                <input v-model="form.accept"
                                       type="checkbox"
                                       class="form-check-input"
                                       :class="getValidationClass('form.accept')"
                                       id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                <validation-errors :$v="$v" attribute="form.accept" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button @click="submitScript" type="submit" class="btn btn-primary" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm"
                                  style="vertical-align: sub"
                                  role="status" aria-hidden="true"></span>
                            Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { required, numeric, requiredIf } from 'vuelidate/lib/validators'
    import { accepted } from '@plugin/validationRules'
    import validationMixin from "@js/mixins/validationMixin";

    import { apiAxios } from '@plugin/axios'

    export default {
        name: "storeSocialComponent",
        mixins: [validationMixin],

        validations: {
            form: {
                type: {
                    required,
                },
                vk_group_id: {
                    requiredIf: requiredIf(function (form) {
                        return form.type === 'vkontakte'
                    }),
                    numeric,
                },
                telegram_token: {
                    requiredIf: requiredIf(function (form) {
                        return form.type === 'telegram'
                    }),
                },
                chat2desk_token: {
                    requiredIf: requiredIf(function (form) {
                        return form.type === 'chat2desk'
                    }),
                },
                chat2desk_operator_id: {
                    requiredIf: requiredIf(function (form) {
                        return form.type === 'chat2desk'
                    }),
                },
                accept: {
                    required,
                    accepted,
                }
            }
        },

        data: () => ({
            loading: false,

            form: {
                type: 'vkontakte',
                vk_group_id: '',
                telegram_token: '',
                chat2desk_token: '',
                chat2desk_operator_id: '',
                accept: false
            }
        }),

        methods: {
            submitScript () {
                this.$v.$touch()

                if (this.$v.$invalid) {
                    return
                }

                this.loading = true;

                apiAxios.post('/socialChannels/', this.form)
                    .then(() => {
                        window.location.reload();
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }
        }
    }
</script>

<style scoped>

</style>
