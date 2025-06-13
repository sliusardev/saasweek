<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { defineProps } from 'vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { Trash, UserPen } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: route('users.list'),
    },
];

defineProps<{
    users: {
        data: Array<{
            id: number,
            name: string,
            email: string,
            phone: string,
            created_at: string,
            roles: string[]
        }>,
        links: Array<{
            url: string | null,
            label: string,
            active: boolean
        }>,
        current_page: number,
        last_page: number,
        from: number,
        to: number,
        total: number
    }
}>();
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <Table>
                <TableCaption>A list of users.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>ID</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Phone</TableHead>
                        <TableHead>Roles</TableHead>
                        <TableHead>Created At</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in users.data" :key="user.id">
                        <TableCell>{{ user.id }}</TableCell>
                        <TableCell>{{ user.name }}</TableCell>
                        <TableCell>{{ user.email }}</TableCell>
                        <TableCell>{{ user.phone }}</TableCell>
                        <TableCell>{{ user.roles }}</TableCell>
                        <TableCell>{{ user.created_at }}</TableCell>
                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger :as-child="true">
                                    <Button
                                        size="icon"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-icon lucide-ellipsis"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" class="w-full">
                                    <DropdownMenuItem :as-child="true">
                                        <Link class="block w-full" :href="route('users.edit', user)" prefetch as="button">
                                            <UserPen class="mr-2 h-4 w-4" />
                                            Edit
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem :as-child="true">
                                        <Link class="block w-full" :href="route('users.delete', user)" prefetch as="button">
                                            <Trash class="mr-2 h-4 w-4" />
                                            Delete
                                        </Link>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4">
                <div class="text-sm text-gray-700">
                    Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
                </div>
                <div class="flex space-x-2">
                    <template v-for="(link, i) in users.links" :key="i">
                        <!-- Skip link if URL is null -->
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="px-4 py-2 text-sm border rounded-md"
                            :class="{
                    'bg-primary text-white': link.active,
                    'bg-white text-gray-700 hover:bg-gray-50': !link.active
                }"
                        >
                            <span v-html="link.label"></span>
                        </Link>
                        <!-- Display disabled button when URL is null -->
                        <button
                            v-else
                            class="px-4 py-2 text-sm border rounded-md opacity-50 cursor-not-allowed bg-white text-gray-700"
                            disabled
                        >
                            <span v-html="link.label"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
