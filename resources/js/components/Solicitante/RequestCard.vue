<script setup lang="ts">
import type HTMLAttributes from 'vue';
import { computed } from 'vue';
import { cn } from '@/lib/utils';

const props = withDefaults(defineProps<{
  title: string
  subtitle: string
  badge: string
  badgeClass?: string
  class?: HTMLAttributes['class']
}>(), {
  badgeClass: 'bg-amber-500/10 text-amber-600',
  class: '',
})

const badgeClasses = computed(() =>
  cn(
    'rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em]',
    props.badgeClass,
  ),
)
</script>

<template>
  <div :class="cn('rounded-2xl border border-border/70 bg-card p-4', props.class)">
    <div class="flex items-center justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-foreground">{{ title }}</p>
        <p class="mt-1 text-sm text-muted-foreground">{{ subtitle }}</p>
      </div>
      <span :class="badgeClasses">{{ badge }}</span>
    </div>
  </div>
</template>
