<script setup> 
    import LoadSpinner from './LoadSpinner.vue';
    
    defineProps({        
        Show: {
            type: Boolean,
            default: true,
            required: false,
        },
        
        Disabled: {
            type: Boolean,
            default: false,
            required: false,
        }
    })

</script>

<template>
    <button
        class="items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-900 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"
        :disabled="Disabled"
    >        
        <template v-if="Show">                            
            <div class="flex items-center justify-center space-x-2 text-center">
                <slot />
            </div>
        </template>
        <template v-else>
            <div class="flex items-center justify-center">
                <LoadSpinner />
            </div>
        </template>
    </button>
</template>
