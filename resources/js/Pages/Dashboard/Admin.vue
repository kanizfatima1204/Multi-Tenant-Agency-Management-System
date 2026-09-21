<script setup>
import Layout from '../../Components/Layout.vue'
import Icon from '../../Components/Icon.vue'
import { Link } from '@inertiajs/vue3'
defineProps({ stats:Object, projects:Array, tenants:Array })
const tone = s => ({active:'green',on_hold:'amber',completed:'blue'}[s] || 'gray')
</script>
<template>
<Layout>
  <div class="hero admin-hero"><div><div class="eyebrow">CONTROL CENTER</div><h1>Good morning, Admin.</h1><p>One command view across every client workspace.</p></div><Link href="/projects/create" class="primary-btn"><Icon name="plus" :size="17"/> New project</Link></div>
  <div class="metric-grid four">
    <div class="metric-card"><div class="metric-icon purple"><Icon name="users"/></div><span>Clients</span><strong>{{stats.tenants}}</strong><small>Active workspaces</small></div>
    <div class="metric-card"><div class="metric-icon blue"><Icon name="folder"/></div><span>All projects</span><strong>{{stats.projects}}</strong><small>{{stats.active_projects}} active now</small></div>
    <div class="metric-card"><div class="metric-icon green"><Icon name="activity"/></div><span>Active projects</span><strong>{{stats.active_projects}}</strong><small>Across all clients</small></div>
    <div class="metric-card"><div class="metric-icon orange"><Icon name="check"/></div><span>Open tasks</span><strong>{{stats.open_tasks}}</strong><small>Needs attention</small></div>
  </div>
  <div class="admin-columns">
    <div class="panel"><div class="panel-head"><div><span class="eyebrow">PORTFOLIO</span><h2>Recent projects</h2></div><Link href="/projects">View all <Icon name="arrow" :size="15"/></Link></div>
      <div class="table-wrap"><table class="modern-table"><thead><tr><th>Project</th><th>Client</th><th>Status</th><th>Tasks</th><th>Due</th></tr></thead><tbody><tr v-for="p in projects" :key="p.id"><td><Link :href="`/projects/${p.id}`" class="project-link">{{p.name}}</Link></td><td>{{p.tenant}}</td><td><span :class="`status ${tone(p.status)}`">{{p.status.replace('_',' ')}}</span></td><td>{{p.tasks_count}}</td><td>{{p.due_date || '—'}}</td></tr></tbody></table></div>
    </div>
    <div class="panel"><div class="panel-head"><div><span class="eyebrow">TENANTS</span><h2>Client workspaces</h2></div><Icon name="users" class="muted-icon"/></div><div class="tenant-list"><div v-for="t in tenants" :key="t.id" class="tenant-row"><div class="tenant-avatar">{{t.name.slice(0,2).toUpperCase()}}</div><div class="tenant-main"><strong>{{t.name}}</strong><span>{{t.projects_count}} projects · {{t.users_count}} users</span></div><span class="live-dot"></span></div></div></div>
  </div>
</Layout>
</template>
