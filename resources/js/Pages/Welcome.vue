<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from "vue";
import axios from 'axios';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
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
let comment = ref([]);

watch([search, searchOnlyPublished], ([valueSearch, valueOnlyPublished]) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get(
            "/",
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

const handleOnChangeComment = (id) => {
}

const handleLikeClick = (id) => {
    //
    router.post(
        route('reaction.store', {id: id, action: 'like', comment: ''}),
    );
    // axios.post(route('like', id));
}
const handleDislikeClick = (id) => {
    router.post(
        route('reaction.store', {id: id, action: 'dislike', comment: ''}),
    );
}
const onClickComment = (id, commentIndex) => {
    router.post(
        route('reaction.store', {id: id, action: 'comment', comment: commentIndex}),
    );
    comment = ref([]);
}
</script>

<template>
    <Head title="Welcome" />

    <div
        class="relative sm:flex sm:justify-center sm:items-start min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Log in</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Register</Link
                >
            </template>
        </div>

        <div class="max-w-7xl w-full mx-auto mt-12 p-6 lg:p-8 flex flex-col">
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
            <div class="px-6 py-2 px-4 w-full" v-for=" (post, index) in $page.props.posts.data" :key="post.id">
                <div class="flex flex-col justify-between items-start bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-2 py-2 flex flex-col w-full">
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
                            <span v-html="post.content.length > 500 ? post.content.substring(0, 500)+'...' : post.content">
                            </span>
                        </div>
                        <div class="px-3 mt-6 flex flex-row">
                            <div class="basis-4/5 flex flex-row items-start justify-center">
                                <button type="button" @click.prevent="handleLikeClick(post.id)" class="flex flex-row text-blue-400 hover:text-blue-600 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2">
                                    <i class="fas fa-thumbs-up"></i>
                                    <p class="ml-2">{{post.like.length}}</p>
                                </button>
                                <button type="button" @click.prevent="handleDislikeClick(post.id)" class="flex flex-row text-red-400 hover:text-red-600 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2">
                                    <i class="fas fa-thumbs-down"></i>
                                    <p class="ml-2">{{post.dislike.length}}</p>
                                </button>
                                <input type="text" placeholder="comment..." v-model="comment[index]" @input="handleOnChangeComment" @focus="handleOnChangeComment" id="small-input" class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <button type="button" @click="onClickComment(post.id, comment[index])" class="text-white bg-blue-400 rounded-lg px-2 py-1 ml-4 hover:bg-blue-500">comment</button>
                            </div>
                        </div>
                        <div class="px-3 mt-6" v-if="post.comments.length > 0">
                            <div class="mb-4">Comments</div>
                            <div v-for="(comment, index) in post.comments" :key="comment.id" class="flex flex-row my-2 justify-start items-center">
                                <i class="fa-solid fa-user fa-2xl"></i>
                                <div class="flex flex-col ml-4">
                                    <p class="font-bold">{{ comment.author.name }}</p>
                                    <p>{{ comment.comment }}</p>
                                </div>
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
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
