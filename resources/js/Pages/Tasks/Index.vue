<script setup>
import { router } from '@inertiajs/vue3'
import Layout from '../../Components/Layout.vue'
import Icon from '../../Components/Icon.vue'

defineProps({ tasks: Array })
const priority = value => ({ high: 'danger', medium: 'warning', low: 'neutral' }[value] || 'neutral')
const updateStatus = (id, status) => router.put(`/tasks/${id}`, { status }, { preserveScroll: true })
</script>

<template>
  <Layout>
    <div class="page-heading"><div><span class="eyebrow">EXECUTION DESK</span><h1>My task queue</h1><p>Prioritized work for your tenant workspace.</p></div></div>
    <div class="panel"><div v-if="!tasks.length" class="empty-state">No tasks are assigned to this workspace.</div><div v-else class="task-list"><div v-for="task in tasks" :key="task.id" class="task-card"><div class="task-accent" :class="priority(task.priority)"></div><div class="task-body"><div class="task-row"><strong>{{ task.title }}</strong><span class="priority" :class="priority(task.priority)">{{ task.priority }}</span></div><p>{{ task.project }}</p><div class="task-foot"><span><Icon name="clock" :size="14" /> {{ task.due_date || 'No due date' }}</span><select :value="task.status" @change="updateStatus(task.id, $event.target.value)"><option value="todo">To do</option><option value="in_progress">In progress</option><option value="done">Done</option></select></div></div></div></div></div>
  </Layout>
</template>
