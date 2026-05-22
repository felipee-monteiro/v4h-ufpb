<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { inject } from 'vue';
import type { Ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import type { Teleconsultoria } from '@/types';

const selectedTeleconsultoria = inject<Ref<Teleconsultoria | null>>(
    'selectedTeleconsultoria',
) as Ref<Teleconsultoria | null>;
const detailDialogOpen = inject<Ref<boolean>>(
    'detailDialogOpen',
) as Ref<boolean>;
const canRegisterOpinion = inject<Ref<boolean>>(
    'canRegisterOpinion',
) as Ref<boolean>;
const especialistaTeleconsultoriaRoutes = inject(
    'especialistaTeleconsultoriaRoutes',
) as { registerOpinion: (args: { teleconsultoria?: string }) => any };
const exportSummaryToPdf = inject<() => void>(
    'exportSummaryToPdf',
) as () => void;
const closeDetailsModal = inject<() => void>('closeDetailsModal') as () => void;
</script>

<template>
    <Dialog v-model:open="detailDialogOpen">
        <DialogContent
            class="max-h-[calc(100vh-2rem)] overflow-y-auto sm:max-w-2xl"
        >
            <DialogHeader>
                <DialogTitle>Detalhes da teleconsultoria</DialogTitle>
            </DialogHeader>

            <div class="space-y-4 py-2">
                <div class="rounded-xl border border-border bg-muted p-4">
                    <p class="text-sm font-semibold text-foreground">
                        Especialidade
                    </p>
                    <p class="mt-2 text-sm text-muted-foreground">
                        {{ selectedTeleconsultoria?.specialty ?? '—' }}
                    </p>
                </div>

                <div class="rounded-xl border border-border bg-muted p-4">
                    <p class="text-sm font-semibold text-foreground">
                        Hipótese diagnóstica
                    </p>
                    <p
                        class="mt-2 text-sm leading-6 whitespace-pre-line text-muted-foreground"
                    >
                        {{
                            selectedTeleconsultoria?.diagnostic_hypothesis ??
                            '—'
                        }}
                    </p>
                </div>

                <div class="rounded-xl border border-border bg-muted p-4">
                    <p class="text-sm font-semibold text-foreground">
                        História clínica
                    </p>
                    <p
                        class="mt-2 text-sm leading-6 whitespace-pre-line text-muted-foreground"
                    >
                        {{ selectedTeleconsultoria?.clinical_history ?? '—' }}
                    </p>
                </div>

                <div class="rounded-xl border border-border bg-muted p-4">
                    <p class="text-sm font-semibold text-foreground">
                        Especialista responsável
                    </p>
                    <p class="mt-2 text-sm text-muted-foreground">
                        {{ selectedTeleconsultoria?.professional ?? '—' }}
                    </p>
                </div>

                <div class="rounded-xl border border-border bg-muted p-4">
                    <p class="text-sm font-semibold text-foreground">
                        Parecer registrado
                    </p>
                    <p
                        v-if="selectedTeleconsultoria?.professional_opinion"
                        class="mt-2 text-sm leading-6 whitespace-pre-line text-muted-foreground"
                    >
                        {{ selectedTeleconsultoria?.professional_opinion }}
                    </p>
                    <p v-else class="mt-2 text-sm text-muted-foreground">
                        Nenhum parecer registrado.
                    </p>
                </div>

                <Form
                    v-if="canRegisterOpinion"
                    v-bind="
                        especialistaTeleconsultoriaRoutes
                            .registerOpinion({
                                teleconsultoria: selectedTeleconsultoria?.id,
                            })
                            .form()
                    "
                    class="space-y-4"
                >
                    <div class="grid gap-2">
                        <Label for="professional_opinion"
                            >Registrar Parecer</Label
                        >
                        <textarea
                            id="professional_opinion"
                            name="professional_opinion"
                            v-model="
                                selectedTeleconsultoria!.professional_opinion
                            "
                            rows="4"
                            class="dark:border-border-dark min-h-28 w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground transition outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20"
                        />
                    </div>

                    <p class="text-sm text-muted-foreground">
                        O botão fica habilitado apenas para o especialista
                        responsável pela teleconsultoria.
                    </p>

                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <Button type="submit" class="h-10 rounded-md">
                                Registrar Parecer
                            </Button>
                        </div>
                    </div>
                </Form>
            </div>

            <DialogFooter
                class="flex flex-wrap items-center justify-between gap-3"
            >
                <Button
                    type="button"
                    variant="secondary"
                    class="h-10"
                    @click="exportSummaryToPdf"
                >
                    Exportar resumo em PDF
                </Button>

                <DialogClose as-child>
                    <button
                        type="button"
                        class="h-10 cursor-pointer rounded-md border border-border bg-background px-4 text-sm font-medium text-foreground transition hover:bg-muted"
                        @click="closeDetailsModal"
                    >
                        Fechar
                    </button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
