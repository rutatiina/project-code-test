<template>
    <div
        v-for="item in props.items"
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
                v-for="item in item.subList"
                :key="item">
                <div
                    :class="{ 'bg-yellow-300': item.name === 'Statra Insurance' }"
                    class="flex items-center space-x-2 cursor-pointer p-1 rounded">
                    <span :class="`h-3 w-3 ${item.color} rounded-full`"></span>
                    <span class="text-sm">{{ item.name }}</span>
                </div>
            </div>
            <div
                class="flex items-center space-x-2 cursor-pointer text-sm"
                @click="showForm(item.name)">
                <component
                    class="h-5 w-5 stroke-2"
                    :is="Icons['PlusCircleIcon']"
                    v-if="item.subList.length > 0 && subListsOpen.includes(item.label)"></component>
                <span>Add {{ item.name }}</span>
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
                    v-model="tagRecord.color"
                    placeholder="Select a color"
                    class="col-span-2 w-full h-12 border p-2 rounded text-xs minimal">
                    <option
                        value=""
                        disabled>
                        Please select one
                    </option>
                    <option v-for="project in projects">{{ project.name }}</option>
                </select>

                <!-- Project field error -->
                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.color"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.color">{{ error }}</div>
                </div>

                <!-- Name field -->
                <Input
                    v-model="tagRecord.name"
                    placeholder="Name"
                    class="col-span-2" />

                <span class="inline-block align-baseline text-gray-400 font-bold text-xs mb-0 pl-1">Start date</span>
                <span class="inline-block align-baseline text-gray-400 font-bold text-xs mb-0 pl-1">End date</span>
                <Input
                    placeholder="Start Date"
                    class="mt-0"
                    type="date" />
                <Input
                    placeholder="End Date"
                    type="date" />

                <!-- Name field error-->
                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.name"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.name">{{ error }}</div>
                </div>

                <!-- start: category -->
                <select
                    v-model="tagRecord.color"
                    placeholder="Select a color"
                    class="col-span-2 w-full h-12 border p-2 rounded text-xs minimal">
                    <option
                        value=""
                        disabled>
                        Please select one
                    </option>
                    <option v-for="category in categories">{{ category.name }}</option>
                </select>

                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.color"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.color">{{ error }}</div>
                </div>
                <!-- end: category field -->

                <!-- start: priorities -->
                <select
                    v-model="tagRecord.color"
                    placeholder="Select a color"
                    class="col-span-2 w-full h-12 border p-2 rounded text-xs minimal">
                    <option
                        value=""
                        disabled>
                        Please select one
                    </option>
                    <option v-for="priority in priorities">{{ priority.name }}</option>
                </select>

                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.color"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.color">{{ error }}</div>
                </div>
                <!-- end: priorities field -->

                <!-- start: status -->
                <select
                    v-model="tagRecord.color"
                    placeholder="Select a color"
                    class="col-span-2 w-full h-12 border p-2 rounded text-xs minimal">
                    <option
                        value=""
                        disabled>
                        Please select one
                    </option>
                    <option v-for="status in statuses">{{ status.name }}</option>
                </select>

                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.color"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.color">{{ error }}</div>
                </div>
                <!-- end: status field -->

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
</template>

<script setup>
import { ref, provide } from "vue"
import axios from "axios"
import * as Icons from "@heroicons/vue/24/outline"
import * as TagServices from "../../services/TagServices"
import * as ProjectServices from "../../services/ProjectServices"
import * as CategoryServices from "../../services/CategoryServices"
import * as PriorityServices from "../../services/PriorityServices"
import * as StatusServices from "../../services/StatusServices"
import Modal from "./Modal.vue"
import Input from "./Input.vue"
import Button from "./Button.vue"
import TextArea from "./TextArea.vue"

const props = defineProps({
    name: [String, Number],
    label: [String, Number],
    icon: [String, Number],
    subList: [Array, Object],
    items: [Array, Object]
})

const subListsOpen = ref([])

const isOpen = ref(false)

const selectedForm = ref("tag")

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
    console.log(form, isOpen.value)

    //clear any api response data
    apiResponse.value = {}
}

///////////////////////
var apiResponse = ref({})
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

const projects = ref([])
ProjectServices.Fetch(projectRecord.value).then((response) => {
    if (response.status == "success") {
        projects.value = response.data
    }
})

const categories = ref([])
CategoryServices.Fetch(projectRecord.value).then((response) => {
    if (response.status == "success") {
        categories.value = response.data
    }
})

const priorities = ref([])
PriorityServices.Fetch(projectRecord.value).then((response) => {
    if (response.status == "success") {
        priorities.value = response.data
    }
})

const statuses = ref([])
StatusServices.Fetch(projectRecord.value).then((response) => {
    if (response.status == "success") {
        statuses.value = response.data
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
</style>
