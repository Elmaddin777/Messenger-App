<template>
    <div class="chat-app">
        <ConversationComponent :contact="selectedContact" :message="messages" @new="saveNewMessage"/>
        <ContactListComponent :contacts="contacts" @selected="startConversationWith"/>
    </div>
</template>

<script>
    import ContactListComponent from './ContactListComponent'
    import ConversationComponent from './ConversationComponent'

    export default {
        props:{
            user:{
                type: Object,
                required: true
            }
        },
        data(){
            return {
                selectedContact: null,
                messages: [],
                contacts: []
            }
        },
        mounted(){      
            
            Echo.private(`messages.${this.user.id}`)
                .listen('NewMessage', (e) => {
                    this.handleIncomingMessage(e.message, e.sender)
                })

            axios.get('/contacts')
                .then(response => {
                    this.contacts = response.data
                    //console.log(response.data);
                })
        },
        methods:{
            startConversationWith(contact){
                this.updateUnreadCount(contact, true)

                axios.get(`/conversation/${contact.id}`)
                    .then(response => {
                        this.messages = response.data
                        this.selectedContact = contact
                    })
            },
          
            saveNewMessage(text){
                this.messages.push(text)
            },
           
            handleIncomingMessage(message, sender){
                if (this.selectedContact && message.from == this.selectedContact.id) {
                    this.saveNewMessage(message)                  
                    return
                }

                var id = sender.id
                var contact = {}

                this.contacts.forEach(element => {
                    if (id == element.id) {
                        contact = element
                    }
                });
               
               this.updateUnreadCount(contact, false)
                
            },
            
            updateUnreadCount(contact, reset){
                if (reset) {
                    contact.unread = 0
                } else {
                    contact.unread += 1
                }
            }
        },
        components:{
            ContactListComponent, 
            ConversationComponent
        }
    }
</script>

<style lang="scss" scoped>
.chat-app {
    display: flex;
}
</style>