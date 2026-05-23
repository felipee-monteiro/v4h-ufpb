<script setup lang="ts">
import { CalendarDays, Search } from 'lucide-vue-next';
import { useTeleconsultoriaFilters } from '@/composables/useTeleconsultoriaFilters';

const fieldClass =
    'dark:border-border-dark h-10 w-full rounded-md border border-border bg-background pr-3 pl-9 text-sm text-foreground transition outline-none focus:border-primary focus:ring-2 focus:ring-primary/20';

const dateFields = [
    { key: 'dateFrom', label: 'Data inicial' },
    { key: 'dateTo', label: 'Data final' },
];

const {
    search,
    statuses,
    dateFrom,
    dateTo,
    activeFiltersCount,
    statusOptions,
    toggleStatus,
    clearFilters,
} = useTeleconsultoriaFilters();
</script>

<template>
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
                    v-model="search"
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
                    v-if="field.key === 'dateFrom'"
                    v-model="dateFrom"
                    type="date"
                    :class="fieldClass"
                />
                <input
                    v-else
                    v-model="dateTo"
                    type="date"
                    :class="fieldClass"
                />
            </label>
        </div>

        <div
            class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between"
        >
            <div class="flex flex-wrap gap-2" aria-label="Filtrar por status">
                <button
                    v-for="status in statusOptions"
                    :key="status"
                    type="button"
                    class="h-9 rounded-md border px-3 text-sm font-medium transition"
                    :class="
                        statuses.includes(status)
                            ? 'border-primary bg-primary text-primary-foreground'
                            : 'border-border bg-background text-muted-foreground hover:bg-muted'
                    "
                    :aria-pressed="statuses.includes(status)"
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
</template>
