<template>
    <div class="conversation">
        <h1>{{ contact ? contact.name : 'Select a contact' }}</h1>
        <FeedComponent :contact="contact" :messages="message"/>
        <MessageComponent @send="sendMessage" />
    </div>
</template>

<script>
import FeedComponent from "./FeedComponent"
import MessageComponent from "./MessageComponent"

export default {
    props:{
        contact:{
            type: Object,
            default: null
        },  
        message:{
            type: Array,
            default: null
        }
    },
    methods:{
        sendMessage(text){
            if (! this.contact) {
                return 
            }
            
            axios.post('/conversation/send', {
                text: text,
                contact_id: this.contact.id
            })
            .then( (response) => {
                this.$emit('new', response.data)
            })
        }
    },
    components:{
        FeedComponent,
        MessageComponent
    }
}
</script>

<style lang="scss" scoped>
.conversation {
    flex: 5;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    h1 {
        font-size: 20px;
        padding: 10px;
        margin: 0;
        border-bottom: 1px dashed lightgray;
    }
}
</style>