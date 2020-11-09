<template>
    <div class="spinner" v-show="show">
        <b-spinner class="centered"></b-spinner>
    </div>
</template>

<script>
    import EventBus from '../utils/event-bus';

    export default {
        data: function() {
            return {
                show: false,
            }
        },
        mounted() {
            let self = this;
            EventBus.$on('SHOW_LOADER', function (payLoad) {
                self.show = true;

            });
            EventBus.$on('HIDE_LOADER', function (payLoad) {
                self.show = false;
            });
        }
    }
</script>
