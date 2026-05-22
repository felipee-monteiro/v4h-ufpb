<script setup lang="ts">
import { Eye } from 'lucide-vue-next';
import { inject } from 'vue';
import { useDetalheTeleconsultoriaModal } from '@/composables/useDetalheTeleconsultoriaModal';
import type { Teleconsultoria, TeleconsultoriaStatus } from '@/types';

const statusClasses = {
    Pendente:
        'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-300',
    'Em andamento':
        'border-sky-200 bg-sky-50 text-sky-700 dark:border-sky-900/60 dark:bg-sky-950/40 dark:text-sky-300',
    Concluída:
        'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300',
    Cancelada:
        'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-300',
};

function formattedDate(date: string): string {
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(new Date(`${date}T00:00:00`));
}

function statusClass(status: TeleconsultoriaStatus): string {
    return statusClasses[status];
}

const teleconsultorias: Teleconsultoria[] = inject('teleconsultorias', []);

const { openDetailsModal } = useDetalheTeleconsultoriaModal();
</script>

<template>
    <section
        class="overflow-hidden rounded-xl border border-sidebar-border/70 bg-background dark:border-sidebar-border"
    >
        <div
            class="dark:border-border-dark flex items-center justify-between gap-4 border-b border-border p-4"
        >
            <div>
                <h2 class="text-base font-semibold text-foreground">
                    Lista de teleconsultorias
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ teleconsultorias.length }} registro(s) encontrado(s)
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table
                class="dark:divide-border-dark min-w-full divide-y divide-border text-left text-sm"
            >
                <thead
                    class="bg-muted text-xs tracking-[0.12em] text-muted-foreground uppercase"
                >
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Paciente</th>
                        <th class="px-4 py-3">Especialidade</th>
                        <th class="px-4 py-3">Data</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody
                    class="dark:divide-border-dark divide-y divide-border bg-background"
                >
                    <tr v-if="teleconsultorias.length === 0">
                        <td
                            class="px-4 py-8 text-center text-sm text-muted-foreground"
                            colspan="6"
                        >
                            Nenhuma teleconsultoria encontrada com os filtros
                            atuais.
                        </td>
                    </tr>
                    <tr
                        v-for="teleconsultoria in teleconsultorias"
                        :key="teleconsultoria.id"
                        class="transition hover:bg-muted/50"
                    >
                        <td
                            class="px-4 py-4 font-medium whitespace-nowrap text-foreground"
                        >
                            {{ teleconsultoria.id }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex size-9 shrink-0 items-center justify-center rounded-full border border-border bg-muted text-xs font-semibold text-foreground"
                                >
                                    {{ teleconsultoria.initials }}
                                </span>
                                <span class="font-medium text-foreground">{{
                                    teleconsultoria.patient
                                }}</span>
                            </div>
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap text-muted-foreground"
                        >
                            {{ teleconsultoria.specialty }}
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap text-muted-foreground"
                        >
                            {{ formattedDate(teleconsultoria.date) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium"
                                :class="statusClass(teleconsultoria.status)"
                            >
                                {{ teleconsultoria.status }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            <button
                                type="button"
                                @click="openDetailsModal(teleconsultoria)"
                                class="inline-flex h-9 cursor-pointer items-center justify-center gap-2 rounded-md border border-border px-3 text-sm font-medium text-foreground transition hover:bg-muted"
                            >
                                <Eye class="size-4" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>
