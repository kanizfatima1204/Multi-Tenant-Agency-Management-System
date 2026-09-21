<script setup>
import Layout from '../../Components/Layout.vue'
import Icon from '../../Components/Icon.vue'
import { Link, router } from '@inertiajs/vue3'
defineProps({ stats:Object, projects:Array, tasks:Array })
const priority = p => p==='high'?'danger':p==='medium'?'warning':'neutral'
const move = (id,status) => router.put(`/tasks/${id}`, {status}, {preserveScroll:true})
</script>
<template>
<Layout>
  <div class="team-hero"><div><span class="eyebrow">EXECUTION DESK</span><h1>Ship great work.</h1><p>Your daily command board for delivery and momentum.</p></div><div class="focus-chip"><Icon name="spark" :size="16"/> Focus mode</div></div>
  <div class="metric-grid four team-metrics"><div class="dark-metric"><span>Projects</span><strong>{{stats.projects}}</strong><small>In your workspace</small></div><div class="dark-metric"><span>To do</span><strong>{{stats.todo}}</strong><small>Queued work</small></div><div class="dark-metric"><span>In progress</span><strong>{{stats.in_progress}}</strong><small>Being worked on</small></div><div class="dark-metric"><span>Done</span><strong>{{stats.done}}</strong><small>Completed tasks</small></div></div>
  <div class="team-grid"><div class="panel task-board"><div class="panel-head"><div><span class="eyebrow">MY WORK QUEUE</span><h2>Priority tasks</h2></div><span class="queue-count">{{tasks.length}} items</span></div><div class="task-list"><div v-for="t in tasks" :key="t.id" class="task-card"><div class="task-accent" :class="priority(t.priority)"></div><div class="task-body"><div class="task-row"><strong>{{t.title}}</strong><span :class="`priority ${priority(t.priority)}`">{{t.priority}}</span></div><p>{{t.project}}</p><div class="task-foot"><span><Icon name="clock" :size="14"/> {{t.due_date || 'No due date'}}</span><select :value="t.status" @change="move(t.id,$event.target.value)"><option value="todo">To do</option><option value="in_progress">In progress</option><option value="done">Done</option></select></div></div></div></div></div>
    <div class="panel"><div class="panel-head"><div><span class="eyebrow">ACTIVE PIPELINE</span><h2>Projects</h2></div></div><div class="mini-project" v-for="p in projects" :key="p.id"><div class="mini-project-icon"><Icon name="folder" :size="17"/></div><div><Link :href="`/projects/${p.id}`"><strong>{{p.name}}</strong></Link><span>{{p.tasks_count}} tasks · {{p.status}}</span></div></div></div></div>
</Layout>
</template>
