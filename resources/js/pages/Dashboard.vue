<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { CalendarDays, Search } from 'lucide-vue-next';
import { computed, reactive, ref, provide } from 'vue';
import DetalhesTeleconsultoria from '@/components/Dashboard/Modals/DetalhesTeleconsultoria.vue';
import NovaTeleconsultoria from '@/components/Dashboard/Modals/NovaTeleconsultoria.vue';
import DashboardTable from '@/components/Dashboard/Table.vue';

import dashboard from '@/routes/dashboard';
import especialistaTeleconsultoriaRoutes from '@/routes/especialista/teleconsultorias';

import type {
    Teleconsultoria,
    TeleconsultoriaDateFilterKey,
    TeleconsultoriaFilters,
    TeleconsultoriaSpecialty,
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
        specialties: TeleconsultoriaSpecialty[];
        canCreateTeleconsultoria: boolean;
    }>(),
    {
        teleconsultorias: () => [],
        specialties: () => [],
        canCreateTeleconsultoria: false,
    },
);

const createDialogOpen = ref(false);
const detailDialogOpen = ref(false);
const selectedTeleconsultoria = ref<Teleconsultoria | null>(null);

const currentUser = computed<{ uuid?: string } | null>(() => {
    return null;
});

const canRegisterOpinion = computed(() => {
    return (
        currentUser.value?.uuid !== undefined &&
        selectedTeleconsultoria.value?.professional_uuid !== undefined &&
        currentUser.value.uuid ===
            selectedTeleconsultoria.value.professional_uuid
    );
});

const statuses: TeleconsultoriaStatus[] = [
    'Pendente',
    'Em andamento',
    'Concluída',
    'Cancelada',
];

const fieldClass =
    'dark:border-border-dark h-10 w-full rounded-md border border-border bg-background pr-3 pl-9 text-sm text-foreground transition outline-none focus:border-primary focus:ring-2 focus:ring-primary/20';

const dateFields: Array<{ key: TeleconsultoriaDateFilterKey; label: string }> =
    [
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
            matchesSearch(teleconsultoria) &&
            matchesStatus(teleconsultoria) &&
            matchesDateRange(teleconsultoria)
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

function matchesSearch(teleconsultoria: Teleconsultoria): boolean {
    if (normalizedSearch.value.length === 0) {
        return true;
    }

    return [teleconsultoria.patient, teleconsultoria.specialty].some((value) =>
        value.toLocaleLowerCase('pt-BR').includes(normalizedSearch.value),
    );
}

function matchesStatus(teleconsultoria: Teleconsultoria): boolean {
    return (
        filters.statuses.length === 0 ||
        filters.statuses.includes(teleconsultoria.status)
    );
}

function matchesDateRange(teleconsultoria: Teleconsultoria): boolean {
    return (
        (filters.dateFrom.length === 0 ||
            teleconsultoria.date >= filters.dateFrom) &&
        (filters.dateTo.length === 0 || teleconsultoria.date <= filters.dateTo)
    );
}

function toggleStatus(status: TeleconsultoriaStatus): void {
    if (filters.statuses.includes(status)) {
        filters.statuses = filters.statuses.filter(
            (currentStatus) => currentStatus !== status,
        );

        return;
    }

    filters.statuses = [...filters.statuses, status];
}

function clearFilters(): void {
    filters.search = '';
    filters.statuses = [];
    filters.dateFrom = '';
    filters.dateTo = '';
}

function exportSummaryToPdf(): void {
    if (!selectedTeleconsultoria.value) {
        return;
    }
}

function openDetailsModal(teleconsultoria: Teleconsultoria): void {
    selectedTeleconsultoria.value = teleconsultoria;
    detailDialogOpen.value = true;
}

function closeDetailsModal(): void {
    detailDialogOpen.value = false;
    selectedTeleconsultoria.value = null;
}

provide('teleconsultorias', filteredTeleconsultorias.value);
provide('openDetailsModal', openDetailsModal);
provide('createDialogOpen', createDialogOpen);
provide('canCreateTeleconsultoria', props.canCreateTeleconsultoria);
provide('selectedTeleconsultoria', selectedTeleconsultoria);
provide('detailDialogOpen', detailDialogOpen);
provide('canRegisterOpinion', canRegisterOpinion);
provide('especialistaTeleconsultoriaRoutes', especialistaTeleconsultoriaRoutes);
provide('exportSummaryToPdf', exportSummaryToPdf);
provide('closeDetailsModal', closeDetailsModal);
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
                    @click="clearFilters"
                >
                    Limpar filtros
                </button>
            </div>
        </section>

        <DashboardTable />
        <DetalhesTeleconsultoria />
    </div>
</template>
