<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import RoleSelector from '@/components/RoleSelector.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Entrar',
        description: 'Acesse sua conta como Solicitante ou Especialista',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const selectedRole = ref('');
</script>

<template>
    <Head title="Entrar" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="max-w-md mx-auto bg-white/80 dark:bg-slate-900/80 p-6 rounded-lg shadow-sm text-slate-900 dark:text-slate-100"
    >
        <h1 class="text-2xl font-semibold text-center">Entrar</h1>
        <p class="text-sm text-center text-muted-foreground mb-4">Acesse sua conta como Solicitante ou Especialista</p>

        <div class="grid gap-4">
            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="seu@exemplo.com"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password">Senha</Label>
                    <TextLink v-if="canResetPassword" :href="request()" class="text-sm" :tabindex="5">Esqueceu a senha?</TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError :message="errors.password" />
            </div>

                <div>
                    <Label for="role">Entrar como</Label>
                    <RoleSelector v-model="selectedRole" name="role" />
                    <p class="text-xs text-muted-foreground mt-2">Se preferir, deixe em branco e o sistema detectará seu perfil automaticamente.</p>
                </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span>Lembrar-me</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-2 w-full flex items-center justify-center gap-2"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
                aria-live="polite"
            >
                <Spinner v-if="processing" />
                Entrar
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground mt-4">
            Ainda não tem conta?
            <TextLink :href="register()" :tabindex="5">Cadastre-se</TextLink>
        </div>
    </Form>
</template>
