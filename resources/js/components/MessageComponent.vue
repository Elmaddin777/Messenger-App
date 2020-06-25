<template>
    <div class="composer">
        <textarea v-model="message" @keydown.enter="send" placeholder="Message ..."></textarea>
    </div>
</template>

<script>
export default {
    data(){
        return {
            message: ''
        }
    },
    methods:{
        send(e){
            if (this.message === '') { return }
            
            e.preventDefault()
            this.$emit('send', this.message)
            this.message = ''
        }
    }
}
</script>

<style lang="scss" scoped>
.composer textarea {
    width: 96%;
    margin: 10px;
    resize: none;
    border-radius: 3px;
    border: 1px solid lightgray;
    padding: 6px;
}
</style>