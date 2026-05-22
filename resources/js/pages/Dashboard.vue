<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { CalendarDays, Search } from 'lucide-vue-next';
import { computed, reactive, provide } from 'vue';
import DetalhesTeleconsultoriaModal from '@/components/Dashboard/Modals/DetalhesTeleconsultoriaModal.vue';
import NovaTeleconsultoria from '@/components/Dashboard/Modals/NovaTeleconsultoria.vue';
import DashboardTable from '@/components/Dashboard/Table.vue';
import dashboard from '@/routes/dashboard';

import type {
    Teleconsultoria,
    TeleconsultoriaFilters,
    TeleconsultoriaStatus,
} from '@/types';

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

const statuses = ['Pendente', 'Em andamento', 'Concluída', 'Cancelada'];

const fieldClass =
    'dark:border-border-dark h-10 w-full rounded-md border border-border bg-background pr-3 pl-9 text-sm text-foreground transition outline-none focus:border-primary focus:ring-2 focus:ring-primary/20';

const dateFields = [
    { key: 'dateFrom', label: 'Data inicial' },
    { key: 'dateTo', label: 'Data final' },
];

const filters = reactive<TeleconsultoriaFilters>({
    search: '',
    statuses: [],
    dateFrom: '',
    dateTo: '',
});

const normalizedSearch = computed(() =>
    filters.search.trim().toLocaleLowerCase('pt-BR'),
);

const filteredTeleconsultorias = computed(() => {
    return props.teleconsultorias.filter((teleconsultoria) => {
        return (
            matchesSearch(teleconsultoria, normalizedSearch.value) &&
            matchesStatus(teleconsultoria.status, filters.statuses) &&
            matchesDateRange(teleconsultoria, filters.dateFrom, filters.dateTo)
        );
    });
});

const activeFiltersCount = computed(() => {
    return [
        normalizedSearch.value.length > 0,
        filters.statuses.length > 0,
        filters.dateFrom.length > 0,
        filters.dateTo.length > 0,
    ].filter(Boolean).length;
});

function matchesSearch(
    teleconsultoria: Teleconsultoria,
    normalizedSearch: string,
): boolean {
    if (normalizedSearch.length === 0) {
        return true;
    }

    return [teleconsultoria.patient, teleconsultoria.specialty].some((value) =>
        value.toLocaleLowerCase('pt-BR').includes(normalizedSearch),
    );
}

function matchesStatus(
    status: TeleconsultoriaStatus,
    statuses: TeleconsultoriaStatus[],
): boolean {
    return statuses.length === 0 || statuses.includes(status);
}

function matchesDateRange(
    teleconsultoria: Teleconsultoria,
    dateFrom: string,
    dateTo: string,
): boolean {
    return (
        (dateFrom.length === 0 || teleconsultoria.date >= dateFrom) &&
        (dateTo.length === 0 || teleconsultoria.date <= dateTo)
    );
}

function toggleStatus(status: any): void {
    if (filters.statuses.includes(status)) {
        filters.statuses = filters.statuses.filter(
            (currentStatus) => currentStatus !== status,
        );

        return;
    }

    filters.statuses = [...filters.statuses, status];
}

function clearFilters(filters: TeleconsultoriaFilters): void {
    filters.search = '';
    filters.statuses = [];
    filters.dateFrom = '';
    filters.dateTo = '';
}

provide('teleconsultorias', filteredTeleconsultorias.value);
provide('canCreateTeleconsultoria', props.canCreateTeleconsultoria);
provide('canCreateParecer', props.canCreateParecer);
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

        <section
            class="rounded-xl border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border"
        >
            <div
                class="grid gap-3 xl:grid-cols-[minmax(220px,1fr)_minmax(180px,220px)_minmax(180px,220px)]"
            >
                <label class="relative block">
                    <span class="sr-only"
                        >Buscar por especialidade ou paciente</span
                    >
                    <Search
                        class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground"
                    />
                    <input
                        v-model="filters.search"
                        type="search"
                        placeholder="Buscar por especialidade ou paciente"
                        class="dark:border-border-dark h-10 w-full rounded-md border border-border bg-background pr-3 pl-9 text-sm text-foreground transition outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20"
                    />
                </label>

                <label
                    v-for="field in dateFields"
                    :key="field.label"
                    class="relative block"
                >
                    <span class="sr-only">{{ field.label }}</span>
                    <CalendarDays
                        class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground"
                    />
                    <input
                        v-model="filters[field.key]"
                        type="date"
                        :class="fieldClass"
                    />
                </label>
            </div>

            <div
                class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between"
            >
                <div
                    class="flex flex-wrap gap-2"
                    aria-label="Filtrar por status"
                >
                    <button
                        v-for="status in statuses"
                        :key="status"
                        type="button"
                        class="h-9 rounded-md border px-3 text-sm font-medium transition"
                        :class="
                            filters.statuses.includes(status)
                                ? 'border-primary bg-primary text-primary-foreground'
                                : 'border-border bg-background text-muted-foreground hover:bg-muted'
                        "
                        :aria-pressed="filters.statuses.includes(status)"
                        @click="toggleStatus(status)"
                    >
                        {{ status }}
                    </button>
                </div>

                <button
                    type="button"
                    class="h-9 rounded-md border border-border px-3 text-sm font-medium text-foreground transition hover:bg-muted disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="activeFiltersCount === 0"
                    @click="() => clearFilters(filters)"
                >
                    Limpar filtros
                </button>
            </div>
        </section>

        <DetalhesTeleconsultoriaModal />
        <DashboardTable />
    </div>
</template>
