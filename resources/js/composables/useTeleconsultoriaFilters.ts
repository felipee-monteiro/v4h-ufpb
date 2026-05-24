import { reactive, toRefs, computed } from 'vue';
import type { Teleconsultoria, TeleconsultoriaFilters, TeleconsultoriaStatus } from '@/types';

const state = reactive<TeleconsultoriaFilters>({
    search: '',
    statuses: [],
    dateFrom: '',
    dateTo: '',
    teleconsultorias: []
});

const statusOptions: TeleconsultoriaStatus[] = [
    'Pendente',
    'Em andamento',
    'Concluída',
    'Cancelada',
];

const normalizedSearch = computed(() =>
    state.search.trim().toLocaleLowerCase('pt-BR'),
);

const activeFiltersCount = computed(() => {
    return [
        normalizedSearch.value.length > 0,
        state.statuses.length > 0,
        state.dateFrom.length > 0,
        state.dateTo.length > 0,
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

export function useTeleconsultoriaFilters() {

    const filteredTeleconsultorias = computed(() => {
        return state.teleconsultorias.filter((teleconsultoria) => {
            return (
                matchesSearch(teleconsultoria, normalizedSearch.value) &&
                matchesStatus(teleconsultoria.status, state.statuses) &&
                matchesDateRange(teleconsultoria, state.dateFrom, state.dateTo)
            );
        });
    });

    function setTeleconsultorias(teleconsultorias: Teleconsultoria[]) {
        state.teleconsultorias = teleconsultorias;
    }

    function toggleStatus(status: TeleconsultoriaStatus): void {
        if (state.statuses.includes(status)) {
            state.statuses = state.statuses.filter(
                (currentStatus) => currentStatus !== status,
            );

            return;
        }

        state.statuses = [...state.statuses, status];
    }

    function clearFilters(): void {
        state.search = '';
        state.statuses = [];
        state.dateFrom = '';
        state.dateTo = '';
    }

    return {
        ...toRefs(state),
        normalizedSearch,
        activeFiltersCount,
        statusOptions,
        filteredTeleconsultorias,
        setTeleconsultorias,
        toggleStatus,
        clearFilters,
    };
}
