<script setup lang="ts">
import AppLayout from '@/layouts/admin/AdminSidebarLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { Plus, Rocket } from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import Button from '@/components/ui/button/Button.vue';
import Pagination from '@/components/Pagination.vue';
import { usePermission } from '@/composables/usePermission';

const { can } = usePermission();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/admin/users',
    },
];
const page = usePage();

interface User{
    id: number,
    name: string,
    email: string,
}

interface Props{
    users: User[],
}
const props = defineProps<Props>();

const handleDelete = (id:number) => {
    if(confirm('Do you want to delete this user?')){
        router.delete(route('admin.users.destroy', {id}));
    }
}

</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 m-2">
            <div v-if="page.props.flash?.message" class="mb-4">
                <Alert class="bg-blue-200">
                    <Rocket class="h-4 w-4"/>
                    <AlertTitle>Notification!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash?.message }}
                    </AlertDescription>
                </Alert>
            </div>
            
            <!-- Show Create button only if user has permission -->
            <div>
                <Link :href="route('admin.users.create')" class="px-4 py-2 bg-slate-600 text-white rounded-md">
                    <Plus class="inline-block space-x-2" />
                    Create User
                </Link>
            </div>

            <div>
                <Table class="mt-4">
                    <TableCaption>A list of users.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">Id</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead class="text-center" v-if="can('edit users') || can('delete users')">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in props.users.data" :key="user.id">
                            <TableCell class="font-medium">{{ user.id }}</TableCell>
                            <TableCell class="font-medium">{{ user.name }}</TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell class="text-center space-x-2">
                                <!-- Show Edit button only if user has permission -->
                                <Link :href="route('admin.users.edit', [user.id])" v-if="can('edit users')" > 
                                    <Button class="bg-slate-600 text-white">Edit</Button>
                                </Link>
                                <!-- Show Delete button only if user has permission -->
                                <Button class="bg-red-600 text-white" @click="handleDelete(user.id)" v-if="can('delete users')" >Delete</Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <Pagination :links="props.users.links"/>
            </div>
        </div>
    </AppLayout>
</template>
