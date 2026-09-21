<script setup>
import Layout from '../../Components/Layout.vue'
import Icon from '../../Components/Icon.vue'
import { Link } from '@inertiajs/vue3'
defineProps({ stats:Object, projects:Array, updates:Array })
const progress = p => p.status==='completed' ? 100 : p.status==='on_hold' ? 35 : Math.min(92, Math.max(18, 45 + p.tasks_count * 7))
</script>
<template>
<Layout>
  <div class="client-welcome"><div><span class="eyebrow">CLIENT PORTAL</span><h1>Your work, clearly.</h1><p>Track delivery, review progress and stay close to your agency team.</p></div><div class="portal-badge"><span class="live-dot"></span> Workspace secure</div></div>
  <div class="metric-grid four client-metrics"><div class="soft-metric"><span>Projects</span><strong>{{stats.projects}}</strong><Icon name="folder"/></div><div class="soft-metric"><span>Active</span><strong>{{stats.active}}</strong><Icon name="activity"/></div><div class="soft-metric"><span>Completed</span><strong>{{stats.completed}}</strong><Icon name="check"/></div><div class="soft-metric"><span>Open tasks</span><strong>{{stats.tasks}}</strong><Icon name="clock"/></div></div>
  <div class="client-grid"><div class="panel projects-panel"><div class="panel-head"><div><span class="eyebrow">DELIVERY</span><h2>Your projects</h2></div><Link href="/projects">All projects <Icon name="arrow" :size="15"/></Link></div><div class="client-project" v-for="p in projects" :key="p.id"><div class="project-top"><div><Link :href="`/projects/${p.id}`"><h3>{{p.name}}</h3></Link><p>{{p.description || 'Project delivery is underway.'}}</p></div><span class="status blue">{{p.status.replace('_',' ')}}</span></div><div class="progress-line"><span :style="{width:progress(p)+'%'}"></span></div><div class="project-meta"><span>{{p.tasks_count}} tasks</span><span>{{p.updates_count}} updates</span><span>Due {{p.due_date || '—'}}</span></div></div></div>
    <div class="panel activity-panel"><div class="panel-head"><div><span class="eyebrow">LIVE FEED</span><h2>Recent updates</h2></div><Icon name="activity"/></div><div v-if="!updates.length" class="empty-state">No updates yet.</div><div v-for="u in updates" :key="u.id" class="feed-item"><div class="feed-dot"></div><div><strong>{{u.project}}</strong><p>{{u.body}}</p><small>{{u.author}} · {{u.created_at}}</small></div></div></div></div>
</Layout>
</template>
