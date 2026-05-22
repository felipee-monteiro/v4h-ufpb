<script setup lang="ts">
import { computed } from 'vue';
import { Form, Head, Link } from '@inertiajs/vue3';
import dashboard from '@/routes/dashboard';

defineOptions({
    layout: {
        title: 'Users',
        description: 'Search and browse all registered users.',
    },
});

const props = defineProps<{
    filters: {
        search: string;
    };
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            email_verified_at: string | null;
            created_at: string;
        }>;
        prev_page_url: string | null;
        next_page_url: string | null;
        current_page: number;
    };
}>();

const formattedUsers = computed(() => {
    return props.users.data.map((user) => ({
        ...user,
        createdAt: new Date(user.created_at).toLocaleDateString(),
    }));
});
</script>

<template>
    <Head title="Users" />

    <div class="flex min-h-full flex-col gap-4 rounded-xl border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border">
        <div class="flex flex-col gap-4 rounded-xl border border-border bg-card p-6 shadow-sm dark:border-border-dark">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-foreground">Registered users</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Search and browse all registered users in the application.
                    </p>
                </div>

                <Form v-bind="dashboard.users.index.form()" class="flex w-full items-end gap-2 md:w-auto">
                    <label class="sr-only" for="search">Search users</label>
                    <input
                        id="search"
                        name="search"
                        type="search"
                        :value="filters.search"
                        placeholder="Search by name or email"
                        class="min-w-0 flex-1 rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground shadow-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-border-dark dark:bg-secondary-dark"
                    />
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white transition hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-75"
                    >
                        Search
                    </button>
                </Form>
            </div>

            <div class="overflow-x-auto rounded-xl border border-border bg-background/50 p-0.5 dark:border-border-dark">
                <table class="min-w-full divide-y divide-border text-left text-sm dark:divide-border-dark">
                    <thead class="bg-muted p-3 text-xs uppercase tracking-[0.12em] text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Verified</th>
                            <th class="px-4 py-3">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border bg-background text-sm dark:divide-border-dark">
                        <tr v-if="users.data.length === 0">
                            <td class="px-4 py-6 text-sm text-muted-foreground" colspan="4">
                                No users found.
                            </td>
                        </tr>
                        <tr v-for="user in formattedUsers" :key="user.id">
                            <td class="px-4 py-4 font-medium text-foreground">{{ user.name }}</td>
                            <td class="px-4 py-4 text-muted-foreground">{{ user.email }}</td>
                            <td class="px-4 py-4 text-muted-foreground">
                                {{ user.email_verified_at ? 'Yes' : 'No' }}
                            </td>
                            <td class="px-4 py-4 text-muted-foreground">{{ user.createdAt }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 border-t border-border px-4 py-4 text-sm text-muted-foreground dark:border-border-dark md:flex-row md:items-center md:justify-between">
                <p>
                    Page {{ users.current_page }}
                </p>
                <div class="flex flex-wrap gap-2">
                    <Link
                        v-if="users.prev_page_url"
                        :href="users.prev_page_url"
                        class="rounded-md border border-border px-3 py-2 text-sm text-foreground transition hover:bg-muted-foreground/10 dark:border-border-dark"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="users.next_page_url"
                        :href="users.next_page_url"
                        class="rounded-md border border-border px-3 py-2 text-sm text-foreground transition hover:bg-muted-foreground/10 dark:border-border-dark"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
