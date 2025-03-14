<script setup>

defineProps({
    id: {
        type: String,
        required: true,
    },
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

    <ul :id="id" v-if="data.length" class="absolute z-30 w-full py-1 list-none bg-white border rounded-sm shadow-sm max-h-[15rem] overflow-y-auto">
        <li class="px-2 py-1 cursor-pointer hover:bg-gray-300" v-for="item in data" @click="SelectOption(item[label])">{{ item[text] }}</li>
    </ul>
    
</template>