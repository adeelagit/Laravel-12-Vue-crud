<script setup lang="ts">
import { Head, Link, usePage, router  } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/admin/AdminSidebarLayout.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Plus, Rocket } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import Button from '@/components/ui/button/Button.vue';


const page = usePage();

const props = defineProps<{
  roles: any,
  permissions: any,
}>();

const handleDelete = (id:number) => {
    if(confirm('Do you want to delete this role?')){
        router.delete(route('admin.roles.destroy', {id}));
    }
}

</script>

<template>
  <AdminLayout title="Roles & Permission">
    <Head title="Roles and Permissions" />

    <div class="space-y-6 space-x-4">

        <!-- Header -->
        <div class="space-x-4">
            <h1 class="text-2xl font-semibold text-gray-900 p-2">Admin Roles and Permissions</h1>
            <p class="text-gray-600">Manage roles, permissions</p>
        </div>

        <div>
            <Link :href="route('admin.roles.create')" class="px-4 py-2 bg-slate-600 text-white rounded-md">
                <Plus class="inline-block space-x-2" />
                Create Roles
            </Link>
        </div>

        <!-- flash message -->
        <div v-if="page.props.flash?.message" class="mb-4">
            <Alert class="bg-blue-200">
                <Rocket class="h-4 w-4"/>
                <AlertTitle>Notification!</AlertTitle>
                <AlertDescription>
                    {{ page.props.flash?.message }}
                </AlertDescription>
            </Alert>
        </div>

        <div v-if="page.props.flash?.error" class="mb-4">
            <Alert class="bg-red-200">
                <Rocket class="h-4 w-4"/>
                <AlertTitle>Notification!</AlertTitle>
                <AlertDescription>
                    {{ page.props.flash?.error }}
                </AlertDescription>
            </Alert>
        </div>

        <div class="">
            <!-- ROLES LIST -->
            <Card>
                <CardHeader>
                <CardTitle>Roles</CardTitle>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Role Name</TableHead>
                                <TableHead>Permissions</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow v-for="role in props.roles" :key="role.id">
                                <TableCell class="font-medium">
                                {{ role.name }}
                                </TableCell>

                                <TableCell>
                                    <div class="flex flex-wrap gap-1">
                                        <Badge
                                        v-for="perm in role.permissions"
                                        :key="perm.id"
                                        variant="secondary"
                                        >
                                        {{ perm.name }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell class=" space-x-2">
                                    <!-- Show Edit button only if user has permission -->
                                    <Link  :href="route('admin.roles.edit', [role.id])"> 
                                        <Button class="bg-slate-600 text-white">Edit</Button>
                                    </Link>
                                    <!-- Show Delete button only if user has permission -->
                                    <Button  class="bg-red-600 text-white" @click="handleDelete(role.id)">Delete</Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>

    </div>
  </AdminLayout>
</template>