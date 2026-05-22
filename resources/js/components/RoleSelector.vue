<script setup lang="ts">
import { computed } from 'vue';
import useRoles from '@/composables/useRoles';

const props = defineProps({
  modelValue: { type: String, default: '' },
  roles: { type: Array as () => Array<{ key: string; label: string }>, required: false },
  name: { type: String, default: 'role' },
});

const emit = defineEmits(['update:modelValue']);

function select(key: string) {
  emit('update:modelValue', key);
}

function isSelected(key: string) {
  return props.modelValue === key;
}

const { roles: defaultRoles } = useRoles();

const localRoles = computed(() => {
  return props.roles && props.roles.length ? props.roles : defaultRoles.value
});

function buttonClass(key: string) {
  return computed(() => {
    const base = 'px-4 py-2 rounded-md border transition-colors focus:outline-none';

    if (isSelected(key)) {
      return base + ' bg-primary text-white border-transparent';
    }

    return (
      base +
      ' bg-white text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700'
    );
  });
}
</script>

<template>
  <div>
    <div class="flex gap-2 mt-2" role="tablist" aria-label="Selecione perfil">
      <button
        v-for="role in localRoles"
        :key="role.key"
        :class="buttonClass(role.key)"
        type="button"
        @click="select(role.key)"
        :aria-pressed="isSelected(role.key)"
      >
        {{ role.label }}
      </button>
    </div>
    <input type="hidden" :name="name" :value="modelValue" />
  </div>
</template>
