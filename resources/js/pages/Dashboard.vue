<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, provide } from 'vue';
import DashboardFilters from '@/components/Dashboard/Filters.vue';
import DetalhesTeleconsultoriaModal from '@/components/Dashboard/Modals/DetalhesTeleconsultoriaModal.vue';
import NovaTeleconsultoria from '@/components/Dashboard/Modals/NovaTeleconsultoria.vue';
import DashboardTable from '@/components/Dashboard/Table.vue';
import { useTeleconsultoriaFilters } from '@/composables/useTeleconsultoriaFilters';
import dashboard from '@/routes/dashboard';

import type { Teleconsultoria } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard.index(),
            },
        ],
    },
});

const props = withDefaults(
    defineProps<{
        teleconsultorias: Teleconsultoria[];
        specialities: [];
        canCreateTeleconsultoria: boolean;
        canCreateParecer: boolean;
    }>(),
    {
        teleconsultorias: () => [],
        specialities: () => [],
        canCreateTeleconsultoria: false,
        canCreateParecer: false,
    },
);

const { setTeleconsultorias } = useTeleconsultoriaFilters();

onMounted(() => {
    setTeleconsultorias(props.teleconsultorias);
});

provide('specialities', props.specialities);
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
        <section
            class="rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border"
        >
            <div
                class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
            >
                <div>
                    <p class="text-sm font-semibold text-muted-foreground">
                        Teleconsultorias
                    </p>
                    <h1
                        class="mt-2 text-2xl font-semibold tracking-tight text-foreground"
                    >
                        Dashboard
                    </h1>
                    <p
                        class="mt-2 max-w-2xl text-sm leading-6 text-muted-foreground"
                    >
                        Consulte solicitações, filtre por status e acompanhe os
                        atendimentos mais recentes.
                    </p>
                </div>
                <NovaTeleconsultoria />
            </div>
        </section>

        <DashboardFilters />
        <DetalhesTeleconsultoriaModal />
        <DashboardTable />
    </div>
</template>
