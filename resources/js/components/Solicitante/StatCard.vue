<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = withDefaults(defineProps<{
  title: string
  value: string | number
  description: string
  class?: HTMLAttributes['class']
}>(), {
  class: '',
})
</script>

<template>
  <div :class="cn('rounded-2xl border border-sidebar-border/70 bg-card p-6 dark:border-sidebar-border', props.class)">
    <p class="text-sm font-semibold text-muted-foreground">{{ title }}</p>
    <p class="mt-5 text-4xl font-semibold text-foreground">{{ value }}</p>
    <p class="mt-3 text-sm leading-6 text-muted-foreground">{{ description }}</p>
  </div>
</template>
