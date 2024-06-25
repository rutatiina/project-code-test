<template>
    <Button
        @click="showForm('task')"
        label="Add new task"
        icon="PlusIcon"
        color="bg-lime-300"
        size="xl" />
    <div class="space-y-4">
        <div
            v-for="item in navItems"
            :key="item"
            @click="openCloseSublists(item.label)"
            class="w-full h-auto flex-col flex -my-2">
            <div class="flex h-12 items-center hover:bg-yellow-100 justify-between p-1 hover:border hover:shadow-md rounded">
                <div class="flex items-center space-x-2 text-sm">
                    <component
                        class="h-6 w-6 stroke-2"
                        :is="Icons[item.icon]"></component>
                    <span class="font-semibold">{{ item.label }}</span>
                </div>
                <div>
                    <component
                        class="h-5 w-5 stroke-2"
                        :is="Icons['ChevronDownIcon']"
                        v-if="item.subList.length > 0 && !subListsOpen.includes(item.label)"></component>
                    <component
                        class="h-5 w-5 stroke-2"
                        :is="Icons['ChevronUpIcon']"
                        v-if="item.subList.length > 0 && subListsOpen.includes(item.label)"></component>
                </div>
            </div>
            <div
                v-if="item.subList.length > 0 && subListsOpen.includes(item.label)"
                class="pl-10 space-y-4">
                <div
                    class="flex items-center space-x-2 cursor-pointer text-sm mt-4"
                    @click="showForm(item.name)">
                    <component
                        class="h-5 w-5 stroke-2"
                        :is="Icons['PlusCircleIcon']"
                        v-if="item.subList.length > 0 && subListsOpen.includes(item.label)"></component>
                    <span>Add {{ item.name }}</span>
                </div>
                <div
                    v-for="item in item.subList"
                    :key="item">
                    <div
                        :class="{ 'bg-yellow-300': item.name === 'Statra Insurance' }"
                        class="flex items-center space-x-2 cursor-pointer p-1 rounded">
                        <span :class="`h-3 w-3 ${item.color} rounded-full`"></span>
                        <span class="text-sm">{{ item.name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <Modal>
            <template v-slot:heading>Add {{ selectedForm }}</template>
            <template v-slot:form>
                <form
                    class="grid grid-cols-2 gap-2 -space-y-0"
                    v-if="selectedForm === 'task'">
                    <!-- Project filed -->
                    <select
                        v-model="taskRecord.project_id"
                        placeholder="Select project"
                        class="col-span-2 w-full h-12 border p-2 rounded text-xs minimal"
                        :class="'col-span-2 w-full h-12 border p-2 rounded text-xs minimal ' + [taskRecord.project_id == '' ? 'text-gray-400' : '']">
                        <option
                            value=""
                            disabled>
                            Please select Project
                        </option>
                        <option
                            v-for="project in projects"
                            :value="project.id">
                            {{ project.name }}
                        </option>
                    </select>

                    <!-- Project field error -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.project_id"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.project_id">{{ error }}</div>
                    </div>

                    <!-- Name field -->
                    <Input
                        v-model="taskRecord.name"
                        placeholder="Title"
                        class="col-span-2" />

                    <!-- Name field error-->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.name"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.name">{{ error }}</div>
                    </div>

                    <span class="inline-block align-baseline text-gray-400 font-bold text-xs mb-0 pl-1">Start date</span>
                    <span class="inline-block align-baseline text-gray-400 font-bold text-xs mb-0 pl-1">End date</span>
                    <Input
                        v-model="taskRecord.start_date"
                        placeholder="Start Date"
                        class="mt-0"
                        type="date" />
                    <Input
                        v-model="taskRecord.end_date"
                        placeholder="End Date"
                        type="date" />

                    <!-- Name field error-->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.start_date"
                        class="col-span-1 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.start_date">{{ error }}</div>
                    </div>
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.end_date"
                        class="col-span-1 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.end_date">{{ error }}</div>
                    </div>

                    <!-- start: category -->
                    <select
                        v-model="taskRecord.category_id"
                        :class="'col-span-2 w-full h-12 border p-2 rounded text-xs minimal ' + [taskRecord.category_id == '' ? 'text-gray-400' : '']">
                        <option
                            value=""
                            disabled>
                            Please select category
                        </option>
                        <option
                            v-for="category in categories"
                            :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>

                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.category_id"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.category_id">{{ error }}</div>
                    </div>
                    <!-- end: category field -->

                    <!-- start: priorities -->
                    <select
                        v-model="taskRecord.priority_id"
                        :class="'col-span-2 w-full h-12 border p-2 rounded text-xs minimal ' + [taskRecord.priority_id == '' ? 'text-gray-400' : '']">
                        <option
                            value=""
                            disabled>
                            Please select priority
                        </option>
                        <option
                            v-for="priority in priorities"
                            :value="priority.id">
                            {{ priority.name }}
                        </option>
                    </select>

                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.priority_id"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.priority_id">{{ error }}</div>
                    </div>
                    <!-- end: priorities field -->

                    <!-- start: status -->
                    <select
                        v-model="taskRecord.status_id"
                        :class="'col-span-2 w-full h-12 border p-2 rounded text-xs minimal ' + [taskRecord.status_id == '' ? 'text-gray-400' : '']">
                        <option
                            value=""
                            disabled>
                            Please select status
                        </option>
                        <option
                            v-for="status in statuses"
                            :value="status.id">
                            {{ status.name }}
                        </option>
                    </select>

                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.status_id"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.status_id">{{ error }}</div>
                    </div>
                    <!-- end: status field -->

                    <!-- start: taskMembers -->
                    <div class="col-span-2 w-full">
                        <div class="font-bold text-sm my-2 ms-2 text-gray-500">Attached to:</div>
                        <ul class="list-decimal list-inside">
                            <li
                                class="text-sm mb-2 bg-gray-100 py-2 px-3 relative"
                                v-for="(member, key) in taskMembers"
                                :key="member.id">
                                {{ member.name }}

                                <button
                                    @click.prevent="removeMember(member.id)"
                                    type="button"
                                    class="h-full px-1 text-sm font-bold border-s-2 border-red-500 text-red-500 absolute top-0 right-0">
                                    <Icons.XMarkIcon class="h-5 stroke-2 cursor-pointer" />
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="col-span-2 w-full align-top flex">
                        <select
                            v-model="user"
                            placeholder="Select a color"
                            :class="'grow border h-full p-2 w-auto rounded text-xs border-2 border-gray-500 minimal-small ' + [Object.keys(user).length == 0 ? 'text-gray-400' : '']">
                            <option
                                :value="{}"
                                disabled>
                                Choose member to attach
                            </option>
                            <template
                                v-for="user in users"
                                :key="user.id">
                                <option
                                    v-if="!taskRecord.member_user_ids.includes(user.id)"
                                    :value="user">
                                    {{ user.name }}
                                </option>
                            </template>
                        </select>
                        <button
                            @click.prevent="attachMember()"
                            type="button"
                            class="flex-none px-2 h-full ml-1 rounded text-sm font-bold border-2 border-gray-500"
                            value="Assign">
                            <Icons.PlusIcon class="h-5 stroke-2 cursor-pointer" />
                        </button>
                    </div>

                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.member_user_ids"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.member_user_ids">{{ error }}</div>
                    </div>
                    <!-- end: taskMembers field -->

                    <!-- Description field -->
                    <TextArea
                        v-model="taskRecord.description"
                        rows="5"
                        placeholder="Description"
                        class="col-span-2" />

                    <!-- Description field Error -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.description"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.description">{{ error }}</div>
                    </div>

                    <Button
                        @click.prevent="taskStore"
                        label="Add new task"
                        icon="PlusIcon"
                        color="bg-lime-500 text-white col-span-2"
                        size="xl" />

                    <!-- Tag creation success message -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'success'"
                        class="col-span-2 text-s text-green-600 py-5">
                        Task create successfully
                    </div>
                </form>
                <form
                    class="grid grid-cols-2 gap-2 -space-y-0"
                    v-if="selectedForm === 'tag'">
                    <!-- Name field -->
                    <Input
                        v-model="tagRecord.name"
                        placeholder="Name"
                        class="col-span-2" />

                    <!-- Name field error-->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.name"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.name">{{ error }}</div>
                    </div>

                    <!-- Color filed -->
                    <select
                        v-model="tagRecord.color"
                        placeholder="Select a color"
                        class="col-span-2 w-full h-12 border p-2 rounded text-xs">
                        <option
                            value=""
                            disabled>
                            Please select one
                        </option>
                        <option value="bg-purple-600">Purple</option>
                        <option value="bg-green-600">Green</option>
                        <option value="bg-yellow-400">Yellow</option>
                    </select>

                    <!-- Color field error -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.color"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.color">{{ error }}</div>
                    </div>

                    <!-- Description field -->
                    <TextArea
                        v-model="tagRecord.description"
                        rows="5"
                        placeholder="Description"
                        class="col-span-2" />

                    <!-- Description field Error -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.description"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.description">{{ error }}</div>
                    </div>
                    <Button
                        @click.prevent="tagStore"
                        label="Add new task"
                        icon="PlusIcon"
                        color="bg-lime-500 text-white col-span-2"
                        size="xl" />

                    <!-- Tag creation success message -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'success'"
                        class="col-span-2 text-s text-green-600 py-5">
                        Tag create successfully
                    </div>
                </form>
                <form
                    class="grid grid-cols-2 gap-2 -space-y-0"
                    v-if="selectedForm === 'member'">
                    <Input
                        placeholder="Title"
                        class="col-span-2" />
                    <Input
                        placeholder="Start Date"
                        type="date" />
                    <Input
                        placeholder="End Date"
                        type="date" />
                    <Input
                        placeholder="Priority"
                        class="col-span-2" />
                    <TextArea
                        rows="5"
                        placeholder="Description"
                        class="col-span-2" />
                    <TextArea
                        rows="5"
                        placeholder="Members"
                        class="col-span-2" />
                    <Button
                        label="Add new task"
                        icon="PlusIcon"
                        color="bg-lime-500 text-white col-span-2"
                        size="xl" />
                </form>
                <form
                    class="grid grid-cols-2 gap-2 -space-y-0"
                    v-if="selectedForm === 'status'">
                    <Input
                        placeholder="Title"
                        class="col-span-2" />
                    <Input
                        placeholder="Start Date"
                        type="date" />
                    <Input
                        placeholder="End Date"
                        type="date" />
                    <Input
                        placeholder="Priority"
                        class="col-span-2" />
                    <TextArea
                        rows="5"
                        placeholder="Description"
                        class="col-span-2" />
                    <TextArea
                        rows="5"
                        placeholder="Members"
                        class="col-span-2" />
                    <Button
                        label="Add new task"
                        icon="PlusIcon"
                        color="bg-lime-500 text-white col-span-2"
                        size="xl" />
                </form>
                <form
                    class="grid grid-cols-2 gap-2 -space-y-0"
                    v-if="selectedForm === 'project'">
                    <!-- Name field -->
                    <Input
                        v-model="projectRecord.name"
                        placeholder="Name"
                        class="col-span-2" />

                    <!-- Name field error-->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.name"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.name">{{ error }}</div>
                    </div>

                    <!-- Color filed -->
                    <select
                        v-model="projectRecord.color"
                        placeholder="Select a color"
                        class="col-span-2 w-full h-12 border p-2 rounded text-xs">
                        <option
                            value=""
                            disabled>
                            Please select one
                        </option>
                        <option value="bg-purple-600">Purple</option>
                        <option value="bg-green-600">Green</option>
                        <option value="bg-yellow-400">Yellow</option>
                    </select>

                    <!-- Color field error -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.color"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.color">{{ error }}</div>
                    </div>

                    <!-- Description field -->
                    <TextArea
                        v-model="projectRecord.description"
                        rows="5"
                        placeholder="Description"
                        class="col-span-2" />

                    <!-- Description field Error -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.description"
                        class="col-span-2 text-xs text-red-500">
                        <div v-for="error in apiResponse.data.description">{{ error }}</div>
                    </div>
                    <Button
                        @click.prevent="projectStore"
                        label="Add new task"
                        icon="PlusIcon"
                        color="bg-lime-500 text-white col-span-2"
                        size="xl" />

                    <!-- Tag creation success message -->
                    <div
                        v-if="apiResponse.data && apiResponse.status == 'success'"
                        class="col-span-2 text-s text-green-600 py-5">
                        Project create successfully
                    </div>
                </form>
                <form
                    class="grid grid-cols-2 gap-2 -space-y-0"
                    v-if="selectedForm === 'category'">
                    <Input
                        placeholder="Title category"
                        class="col-span-2" />
                    <Input
                        placeholder="Start Date"
                        type="date" />
                    <Input
                        placeholder="End Date"
                        type="date" />
                    <Input
                        placeholder="Priority"
                        class="col-span-2" />
                    <TextArea
                        rows="5"
                        placeholder="Description"
                        class="col-span-2" />
                    <TextArea
                        rows="5"
                        placeholder="Members"
                        class="col-span-2" />
                    <Button
                        label="Add new task"
                        icon="PlusIcon"
                        color="bg-lime-500 text-white col-span-2"
                        size="xl" />
                </form>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref, provide } from "vue"
import { storeToRefs } from "pinia"

import { useTaskManagerStore } from "@/stores/app"
import * as Icons from "@heroicons/vue/24/outline"
import * as TagServices from "@/services/TagServices"
import * as ProjectServices from "@/services/ProjectServices"
import * as CategoryServices from "@/services/CategoryServices"
import * as PriorityServices from "@/services/PriorityServices"
import * as StatusServices from "@/services/StatusServices"
import * as TaskServices from "@/services/TaskServices"
import * as UserServices from "@/services/UserServices"
import Modal from "./Modal.vue"
import Input from "./Input.vue"
import Button from "./Button.vue"
import TextArea from "./TextArea.vue"

let store = useTaskManagerStore()

const props = defineProps({
    name: [String, Number],
    label: [String, Number],
    icon: [String, Number],
    subList: [Array, Object]
    // items: [Array, Object] //line to be deleted
})

const subListsOpen = ref([])

const selectedForm = ref("tag")
const isOpen = ref(false)

const { tasks, tags, projects, categories, priorities, statuses, users, user, taskRecord, taskMembers, apiResponse } = storeToRefs(store)

const navItems = ref([
    { label: "Plan", icon: "CalendarIcon", subList: [] },
    {
        label: "Task List",
        name: "task",
        icon: "ClipboardDocumentListIcon",
        subList: projects
    },
    {
        label: "Projects",
        name: "project",
        icon: "FolderIcon",
        subList: projects
    },
    {
        label: "Tags",
        name: "tag",
        icon: "TagIcon",
        subList: tags
    },
    {
        label: "Members",
        name: "member",
        icon: "UserGroupIcon",
        subList: users
    }
])

provide("isOpenSideModal", isOpen)

const openCloseSublists = (subList) => {
    if (!subListsOpen.value.includes(subList)) subListsOpen.value.push(subList)
    else {
        let indexOfList = subListsOpen.value.indexOf(subList)
        if (indexOfList !== -1) subListsOpen.value.splice(indexOfList, 1)
    }
}

const showForm = (form) => {
    isOpen.value = true
    selectedForm.value = form
    // console.log(form, isOpen.value)

    //clear any api response data
    apiResponse.value = {}
}

///////////////////////
const tagRecord = ref({
    name: "",
    color: "bg-purple-600",
    description: ""
})
const projectRecord = ref({
    name: "",
    color: "bg-purple-600",
    description: ""
})

function attachMember() {
    taskMembers.value.push(user.value)
    taskRecord.value.member_user_ids = taskMembers.value.map((m) => m.id)
    user.value = {}
}

function removeMember(id) {
    taskMembers.value = taskMembers.value.filter((m) => m.id !== id)
    taskRecord.value.member_user_ids = taskMembers.value.map((m) => m.id)
    // delete taskMembers.value[key]
}

async function tagStore() {
    const response = await TagServices.Store(tagRecord.value)
    apiResponse.value = response

    if (response.status == "success") {
        //reset the tagRecord
        tagRecord.value = {
            name: "",
            color: "bg-purple-600",
            description: ""
        }
    }
}

async function projectStore() {
    const response = await ProjectServices.Store(projectRecord.value)
    apiResponse.value = response

    if (response.status == "success") {
        //reset the projectRecord
        projectRecord.value = {
            name: "",
            color: "bg-purple-600",
            description: ""
        }
    }
}

async function taskStore() {
    const response = await TaskServices.Store(taskRecord.value)
    apiResponse.value = response

    if (response.status == "success") {
        //reset the tagRecord
        taskRecord.value = {
            name: "",
            color: "bg-purple-600",
            description: ""
        }
    }
}

TaskServices.Fetch().then((response) => {
    if (response.status == "success") {
        store.tasks = response.data
    }
})

TagServices.Fetch().then((response) => {
    if (response.status == "success") {
        store.tags = response.data
    }
})

ProjectServices.Fetch().then((response) => {
    if (response.status == "success") {
        projects.value = response.data
    }
})

CategoryServices.Fetch().then((response) => {
    if (response.status == "success") {
        store.categories = response.data
    }
})

PriorityServices.Fetch().then((response) => {
    if (response.status == "success") {
        store.priorities = response.data
    }
})

StatusServices.Fetch().then((response) => {
    if (response.status == "success") {
        store.statuses = response.data
    }
})

UserServices.Fetch().then((response) => {
    if (response.status == "success") {
        store.users = response.data
    }
})
</script>

<style>
select {
    /* reset */

    margin: 0;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    -webkit-appearance: none;
    -moz-appearance: none;
}

select.minimal {
    background-image: linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%), linear-gradient(to right, #ccc, #ccc);
    background-position: calc(100% - 20px) calc(1.5em + 3px), calc(100% - 15px) calc(1.5em + 3px), calc(100% - 2.5em) 0.5em;
    background-size: 5px 5px, 5px 5px, 2px 3em;
    background-repeat: no-repeat;
}

select.minimal-small {
    background-image: linear-gradient(45deg, transparent 50%, white 50%), linear-gradient(135deg, white 50%, transparent 50%), linear-gradient(to right, grey, grey);
    background-position: calc(100% - 15px) calc(1em + 2px), calc(100% - 10px) calc(1em + 2px), 100% 0;
    background-size: 5px 5px, 5px 5px, 2.5em 3.5em;
    background-repeat: no-repeat;
}
</style>
