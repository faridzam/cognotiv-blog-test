<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm, usePage} from '@inertiajs/vue3';
import { computed, ref, watch, reactive } from 'vue';
import { QuillEditor, Quill } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

// const post = computed(() => usePage().props.value.post);
const props = defineProps({post: Object});

const form = useForm({
    forceFormData: true,
    _method: 'put',
    id: props.post.id,
    image: null,
    title: props.post.title,
    meta_title: props.post.meta_title,
    slug: props.post.slug,
    content: props.post.content,
    isPublished: props.post.isPublished === 1 ? true : false,
    _token: String
});

// watch( form, function(val) {
//     form.slug = val.title.replace(/\s+/g, '-').toLowerCase();
// })

const onTitleChange = (e) => {
    form.slug = e.replace(/\s+/g, '-').toLowerCase();
    form.meta_title = e;
}

const onContentChange = (e) => {
    console.log(e);
}

const submit = () => {
    form.post(route('post.update', props.post.id), {
        onFinish: () => form.reset('image', 'title', 'meta_title', 'slug', 'content', 'isPublished'),
    });
};

const onSelectImage = (file) => {
    form.image = file.target.files[0];
}

</script>

<template>
    <Head title="Create Post" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Post</h2>
        </template>

        <div class="py-6">
            <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div class="p-6 flex flex-column flex-wrap">
                            <div class="basis-full my-2">
                                <InputLabel for="title" value="Title"/>

                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    @update:model-value="onTitleChange($event)"
                                    required
                                    autofocus
                                    autocomplete="title"
                                />

                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>
                            <div class="basis-full my-2">
                                <InputLabel for="meta_title" value="Meta Title" />

                                <TextInput
                                    id="meta_title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.meta_title"
                                    required
                                    autocomplete="meta_title"
                                />

                                <InputError class="mt-2" :message="form.errors.meta_title" />
                            </div>
                            <!-- <div class="basis-1/5 flex justify-end items-end mb-4 pr-3">
                                https://site.xxx/post/
                            </div> -->
                            <div class="basis-1/5 my-2 pr-2">

                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload Image</label>
                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="image" v-on:change="onSelectImage" type="file">

                            </div>
                            <div class="basis-4/5 my-2 pl-2">

                                <InputLabel for="slug" value="Slug" />

                                <TextInput
                                    id="slug"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.slug"
                                    required
                                    autocomplete="slug"
                                    disabled
                                />

                                <InputError class="mt-2" :message="form.errors.slug" />
                            </div>
                            <div class="basis-full my-2 h-72">
                                <div class="h-64">
                                    <QuillEditor theme="snow" toolbar="minimal" v-model:content="form.content" content-type="html"/>
                                </div>
                            </div>

                            <div class="basis-full my-2 mt-5">
                                <div class="flex flex-row items-center justify-between">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form.isPublished" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Publish Post</span>
                                    </label>
                                    <PrimaryButton class="" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Submit
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script>
    // import { QuillEditor, Quill } from '@vueup/vue-quill'
    // import '@vueup/vue-quill/dist/vue-quill.snow.css';

    // export default {
    //     components: {
    //         QuillEditor
    //     },
    // }
</script>

<style scoped>
</style>
