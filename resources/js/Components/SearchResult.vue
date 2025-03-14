<script setup>

defineProps({
    data:{
        type: Object,
        required: false,
        default: [],
    },
    label: {
        type: String,
        required: false,
    },
    text: {
        type: String,
        required: false,
    }, 
    SelectOption: {
        type: Function,
        required: false,
    }
    
})



</script>
<template>

    <ul v-if="data.length" class="px-2 py-1 list-none bg-white border rounded-sm shadow-sm">
        <li class="cursor-pointer" v-for="item in data" @click="SelectOption(item[label])">{{ item[text] }}</li>
    </ul>
    
</template>