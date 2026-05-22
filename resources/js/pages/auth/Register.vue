<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { BadgeCheck, BriefcaseBusiness, UserRound } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import RoleSelector from '@/components/RoleSelector.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

const props = defineProps<{
    passwordRules: string;
    serviceCategories: Array<{ value: string; label: string }>;
}>();

const selectedRole = ref('Solicitante');
const selectedServiceCategory = ref(props.serviceCategories[0]?.value ?? '');

const isSpecialist = computed(() => selectedRole.value === 'Especialista');

watch(isSpecialist, (specialist) => {
    if (specialist) {
        selectedServiceCategory.value =
            selectedServiceCategory.value ||
            (props.serviceCategories[0]?.value ?? '');

        return;
    }

    selectedServiceCategory.value = '';
});

defineOptions({
    layout: {
        title: 'Cadastro',
        description:
            'Crie seu acesso para solicitar ou oferecer atendimento especializado.',
    },
});
</script>

<template>
    <Head title="Cadastre-se" />

    <div class="mx-auto w-full max-w-md px-1 sm:px-0">
        <Form
            v-bind="store.form()"
            :reset-on-success="[
                'password',
                'password_confirmation',
                'service_category',
            ]"
            v-slot="{ errors, processing }"
            class="overflow-hidden rounded-xl border bg-card text-card-foreground shadow-sm"
        >
            <div class="border-b bg-muted/30 px-5 py-4 sm:px-6">
                <div class="flex items-start gap-3">
                    <div
                        class="mt-0.5 flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <BadgeCheck class="size-5" aria-hidden="true" />
                    </div>

                    <div class="min-w-0">
                        <h1
                            class="text-lg leading-6 font-semibold text-foreground"
                        >
                            Criar conta
                        </h1>
                        <p class="mt-1 text-sm leading-5 text-muted-foreground">
                            Informe seus dados e escolha o perfil que melhor
                            representa seu uso.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 p-5 sm:p-6">
                <div class="grid gap-2">
                    <Label for="name">Nome completo</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        name="name"
                        placeholder="Seu nome completo"
                        :aria-invalid="!!errors.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autocomplete="email"
                        name="email"
                        placeholder="email@exemplo.com"
                        :aria-invalid="!!errors.email"
                    />
                    <InputError :message="errors.email" />
                </div>

                <section
                    class="grid gap-3 rounded-lg border bg-background p-4"
                    aria-labelledby="role-label"
                >
                    <div class="flex items-start gap-3">
                        <div
                            class="flex size-9 shrink-0 items-center justify-center rounded-md bg-muted text-muted-foreground"
                        >
                            <UserRound
                                v-if="!isSpecialist"
                                class="size-4"
                                aria-hidden="true"
                            />
                            <BriefcaseBusiness
                                v-else
                                class="size-4"
                                aria-hidden="true"
                            />
                        </div>

                        <div class="min-w-0">
                            <Label id="role-label" for="role">Seu perfil</Label>
                            <p
                                class="mt-1 text-sm leading-5 text-muted-foreground"
                            >
                                Solicitantes pedem atendimento. Especialistas
                                oferecem serviços e precisam informar a
                                categoria.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <RoleSelector v-model="selectedRole" name="role" />
                    </div>
                </section>

                <div
                    v-if="isSpecialist"
                    class="grid gap-2 rounded-lg border border-primary/20 bg-primary/5 p-4"
                >
                    <Label for="service_category">Categoria de serviço</Label>
                    <select
                        id="service_category"
                        name="service_category"
                        v-model="selectedServiceCategory"
                        :aria-invalid="!!errors.service_category"
                        :required="isSpecialist"
                        class="h-10 w-full min-w-0 rounded-md border border-input bg-background px-3 py-2 text-base text-foreground shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 md:text-sm dark:aria-invalid:ring-destructive/40"
                    >
                        <option value="" disabled>
                            Selecione uma categoria
                        </option>
                        <option
                            v-for="category in props.serviceCategories"
                            :key="category.value"
                            :value="category.value"
                        >
                            {{ category.label }}
                        </option>
                    </select>
                    <p class="text-xs leading-5 text-muted-foreground">
                        Essa informação ajuda solicitantes a encontrarem o
                        especialista certo.
                    </p>
                    <InputError :message="errors.service_category" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Senha</Label>
                    <PasswordInput
                        id="password"
                        required
                        autocomplete="new-password"
                        name="password"
                        placeholder="Senha"
                        :passwordrules="passwordRules"
                        :aria-invalid="!!errors.password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirme a senha</Label>
                    <PasswordInput
                        id="password_confirmation"
                        required
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirme a senha"
                        :passwordrules="passwordRules"
                        :aria-invalid="!!errors.password_confirmation"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-1 w-full gap-2"
                    :disabled="processing"
                    data-test="register-user-button"
                    aria-live="polite"
                >
                    <Spinner v-if="processing" />
                    {{ processing ? 'Criando conta...' : 'Criar conta' }}
                </Button>

                <p class="text-center text-xs leading-5 text-muted-foreground">
                    Ao continuar, seus dados serão usados para configurar seu
                    acesso na plataforma.
                </p>
            </div>

            <div
                class="border-t px-5 py-4 text-center text-sm text-muted-foreground sm:px-6"
            >
                Já tem conta?
                <TextLink
                    :href="login()"
                    class="font-medium underline underline-offset-4"
                    >Entrar</TextLink
                >
            </div>
        </Form>
    </div>
</template>
