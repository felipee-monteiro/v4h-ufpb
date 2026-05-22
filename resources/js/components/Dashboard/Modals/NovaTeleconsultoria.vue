<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { inject } from 'vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import Label from '@/components/ui/label/Label.vue';
import teleconsultoriaRoutes from '@/routes/solicitante/teleconsultorias';

const canCreateTeleconsultoria = inject('canCreateTeleconsultoria', false);
const createDialogOpen = inject('createDialogOpen');
const specialties = inject('specialties', []);
</script>

<template>
    <Dialog v-if="canCreateTeleconsultoria" v-model:open="createDialogOpen">
        <DialogTrigger as-child>
            <Button class="h-10 cursor-pointer gap-2">
                <Plus class="size-4" />
                Nova Teleconsultoria
            </Button>
        </DialogTrigger>
        <DialogContent
            class="max-h-[calc(100vh-2rem)] overflow-y-auto sm:max-w-2xl"
        >
            <Form
                v-bind="teleconsultoriaRoutes.store.form()"
                reset-on-success
                :options="{ preserveScroll: true }"
                class="space-y-5"
                @success="createDialogOpen = false"
                v-slot="{ errors, processing, reset, clearErrors }"
            >
                <DialogHeader>
                    <DialogTitle>Nova Teleconsultoria</DialogTitle>
                </DialogHeader>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="patient_name">Paciente</Label>
                        <input
                            id="patient_name"
                            name="patient_name"
                            type="text"
                            autocomplete="name"
                            class="dark:border-border-dark h-10 w-full rounded-md border border-border bg-background px-3 text-sm text-foreground transition outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20"
                        />
                        <InputError :message="errors.patient_name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="patient_initials">Iniciais</Label>
                        <input
                            id="patient_initials"
                            name="patient_initials"
                            type="text"
                            maxlength="8"
                            class="dark:border-border-dark h-10 w-full rounded-md border border-border bg-background px-3 text-sm text-foreground transition outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20"
                        />
                        <InputError :message="errors.patient_initials" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="patient_birthday">Data de nascimento</Label>
                        <input
                            id="patient_birthday"
                            name="patient_birthday"
                            type="date"
                            class="dark:border-border-dark h-10 w-full rounded-md border border-border bg-background px-3 text-sm text-foreground transition outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
                        />
                        <InputError :message="errors.patient_birthday" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="service_uuid">Especialidade</Label>
                        <select
                            id="service_uuid"
                            name="service_uuid"
                            class="dark:border-border-dark h-10 w-full rounded-md border border-border bg-background px-3 text-sm text-foreground transition outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
                        >
                            <option value="">Selecione</option>
                            <option
                                v-for="specialty in specialties"
                                :key="specialty.uuid"
                                :value="specialty.uuid"
                            >
                                {{ specialty.title }}
                            </option>
                        </select>
                        <InputError :message="errors.service_uuid" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="diagnostic_hypothesis"
                        >Hipótese diagnóstica</Label
                    >
                    <textarea
                        id="diagnostic_hypothesis"
                        name="diagnostic_hypothesis"
                        rows="3"
                        class="dark:border-border-dark min-h-24 w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground transition outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20"
                    />
                    <InputError :message="errors.diagnostic_hypothesis" />
                </div>

                <div class="grid gap-2">
                    <Label for="clinical_history"
                        >História clínica resumida</Label
                    >
                    <textarea
                        id="clinical_history"
                        name="clinical_history"
                        rows="4"
                        class="dark:border-border-dark min-h-28 w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground transition outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20"
                    />
                    <InputError :message="errors.clinical_history" />
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button
                            type="button"
                            variant="secondary"
                            class="cursor-pointer"
                            @click="
                                () => {
                                    clearErrors();
                                    reset();
                                }
                            "
                        >
                            Cancelar
                        </Button>
                    </DialogClose>

                    <Button
                        type="submit"
                        :disabled="processing"
                        class="cursor-pointer"
                    >
                        Salvar
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>
