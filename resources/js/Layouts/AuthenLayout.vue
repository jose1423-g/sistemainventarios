<script setup>
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
    // import 'primeicons/primeicons.css'
    import { ref } from 'vue';
    
    const addclass = ref('-translate-x-full');

    const toggleAside = () => {
        addclass.value  = addclass.value === 'transform-none' ? '-translate-x-full' : 'transform-none';
    }

</script>

<template>
    <nav class="fixed top-0 left-0 z-50 w-full bg-white border-b border-b-gray-200">
        <div class="flex items-center justify-between px-5 py-3 h-[5rem]">
            <div class="flex">
                <button type="button" class="block p-0 mr-6 bg-white sm:hidden" @click="toggleAside">
                    <i class="text-black pi pi-bars !text-2xl"></i>
                </button>
                <ApplicationLogo class="block w-auto text-gray-800 fill-current h-9"/>
            </div>
            <div>
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button
                                type="button"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                            >
                                {{ $page.props.auth.user.name }}

                                <svg
                                    class="-me-0.5 ms-2 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <DropdownLink
                            :href="route('profile.edit')"
                        >
                            Profile
                        </DropdownLink>
                        <DropdownLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Log Out
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </div>
    </nav>    
    <aside :class="addclass" class="fixed top-0 sm:translate-x-0 left-0 z-40 w-64 h-screen border-r bg-white border-r-gray-200 pt-[5rem] py-4 px-3 transform transition-transform duration-300">
        <div class="h-full py-3 overflow-y-auto">
            <ul class="flex flex-col gap-4">
                <li>
                    <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                    </ResponsiveNavLink>
                </li>
                <li>
                    <!-- <ResponsiveNavLink
                            :href="route('CheckInvoices')"
                            :active="route().current('CheckInvoices')"
                        >
                            Consulta tus facturas
                    </ResponsiveNavLink> -->
                </li>
                <li>
                    <!-- <ResponsiveNavLink
                            :href="route('UploadKeyAndCer')"
                            :active="route().current('UploadKeyAndCer')"
                        >
                            Carga tu certificado y key
                    </ResponsiveNavLink> -->
                </li>
            </ul>
        </div>                
    </aside>
    <div class="ml-0 sm:ml-64 mt-[5rem]">
        <div class="h-full px-4 py-4">
            <slot />
        </div>
    </div> 
</template>