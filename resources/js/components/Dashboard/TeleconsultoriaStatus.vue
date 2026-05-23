<script setup lang="ts">
import { computed } from 'vue';
import type { Teleconsultoria } from '@/types';

const props = defineProps<{
    teleconsultoria: Teleconsultoria;
}>();

const statusClass = computed(() => {
    return (
        {
            Pendente:
                'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-300',
            'Em andamento':
                'border-sky-200 bg-sky-50 text-sky-700 dark:border-sky-900/60 dark:bg-sky-950/40 dark:text-sky-300',
            Concluída:
                'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300',
            Cancelada:
                'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-300',
        }[props.teleconsultoria.status] || 'Não disponível'
    );
});
</script>

<template>
    <span
        class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium"
        :class="statusClass"
    >
        {{ teleconsultoria.status }}
    </span>
</template>
