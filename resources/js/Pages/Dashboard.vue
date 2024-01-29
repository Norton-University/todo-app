<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Checkbox from "@/Components/Checkbox.vue";

interface todoType {
    id: number,
    title: string,
    completed: boolean,
}

const props = defineProps<{
    todos: todoType[],
    errors: any,
}>();


const form = useForm({
    title: '',
    completed: false,
});

const save = () => {
    form.post(route('todos.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}


const toggleTodos = (todo: todoType) => {
    router.put(route('todos.toggle', todo.id), {}, {
        preserveScroll: true,

    });
}

const onDelete = (id: number) => {
    form.delete(route('todos.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}

</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-4xl">
                    <!--Todos form-->
                    <div>

                        <form @submit.prevent="save" class="flex items-center gap-4">
                            <TextInput
                                type="text"
                                class="mt-1  w-full "
                                v-model="form.title"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <PrimaryButton class="" type="submit">Add</PrimaryButton>
                        </form>
                        <InputError class="mt-2" :message="form.errors.title"/>
                    </div>
                    <div>
                        <ul v-if="todos.length" class="p-4 rounded-xl bg-yellow-50 mt-4 divide-y ">
                            <li v-for="todo in todos" :key="todo.id"
                                class="flex justify-between items-center gap-2 py-2">
                                <div class="flex items-center">
                                    <Checkbox @change="toggleTodos(todo)" v-model="todo.completed"
                                              class="form-checkbox h-5 w-5 text-gray-600 mr-3"
                                              :checked="todo.completed"/>
                                    <span class="text-xl" :class="{'line-through': todo.completed}">{{
                                            todo.title
                                        }}</span>
                                </div>
                                <DangerButton class="text-xl" @click="onDelete(todo.id)">Delete</DangerButton>

                            </li>
                        </ul>
                        <div v-else class="p-4 rounded-xl bg-yellow-50 mt-4">
                            <p class="text-xl">No todos yet</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
