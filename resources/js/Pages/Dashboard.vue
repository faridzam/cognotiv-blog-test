<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from "vue";
import { router } from '@inertiajs/vue3'

const props = defineProps({
    post: {
        type: Object,
        default: () => ({}),
    },
    filter: {
        type: Object,
        default: () => ({
            search: '',
            searchOnlyPublished: false,
        }),
    },
});

let search = ref(props.filter.search);
let searchOnlyPublished = ref(props.filter.searchOnlyPublished);
let timer = ref(500);

watch(searchOnlyPublished, (value) => {
    console.log(value);
});

watch([search, searchOnlyPublished], ([valueSearch, valueOnlyPublished]) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get(
            "/dashboard",
            {
                search: valueSearch,
                searchOnlyPublished: valueOnlyPublished,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }, 500)
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <!-- button create post -->
        <div class="pt-6">
            <div class="max-w-10x1 mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-row justify-between px-6">
                    <div class="basis-3/5 flex flex-row items-center justify-between">
                        <form class="basis-3/5">
                            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="search" v-model="search" id="default-search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="search title, author, content..." required>
                                <!-- <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button> -->
                            </div>
                        </form>
                        <div class="basis-2/5 pl-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="searchOnlyPublished" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Only Published</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <Link
                        :href="route('post.create')"
                        class=""
                        >
                            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Create Post</button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <!-- posts list -->
        <div class="py-6">
            <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="flex flex-row justify-start align-center flex-wrap">
                        <div class="basis-1/4 px-6 py-2 px-4" v-for=" post in $page.props.posts.data" :key="post.id">
                            <div class="flex flex-col justify-between items-start bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="px-2 py-2 flex flex-col">
                                    <div class="p-3 flex justify-center items-center">
                                        <img
                                        class="object-center object-cover h-48 w-96"
                                        :src="encodeURI(post.image)"/>
                                    </div>
                                    <div class="px-3 mt-2">
                                        <p class="text-lg font-bold">
                                            {{ post.title }}
                                        </p>
                                    </div>
                                    <div class="px-3">
                                        <p class="text-sm font-thin">
                                            {{ post.user }}
                                        </p>
                                    </div>
                                    <div class="px-3 mt-4">
                                        <span v-html="post.content.length > 160 ? post.content.substring(0, 160)+'...' : post.content">
                                        </span>
                                    </div>
                                    <div class="px-3 mt-6 flex flex-row">
                                        <div class="basis-1/2 flex-flex-row">
                                            <Link
                                            :href="route('post.edit', post.id)"
                                            class=""
                                            >
                                                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </Link>
                                            <Link
                                            method="delete"
                                            as="button"
                                            :href="route('post.destroy', post.id)"
                                            class=""
                                            >
                                                <button type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="text-gray-900">
                                content
                            </div>
                            <div class="text-gray-900">
                                <Link
                                :href="route('post.edit', post.id)"
                                class=""
                                >
                                    <button type="button" class="text-white bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-orange-600 dark:hover:bg-orange-700 focus:outline-none dark:focus:ring-orange-800">edit</button>
                                </Link>
                            </div> -->
                        </div>
                    </div>
                    <div v-if="$page.props.posts.links.length > 3" class="flex justify-center mt-4 space-x-4">
                        <Link
                            v-for="(link, k) in $page.props.posts.links"
                            :key="link.name"
                            class="px-4 py-3 text-sm leading-4 bg-white rounded hover:bg-white focus:text-indigo-500 hover:shadow"
                            :class="{'bg-blue-400 text-white': link.active}"
                            :href="link.url"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
