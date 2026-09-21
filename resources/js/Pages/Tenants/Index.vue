<script setup>
import { Link } from '@inertiajs/vue3'
import Layout from '../../Components/Layout.vue'
import Icon from '../../Components/Icon.vue'

defineProps({ tenants: Array })
</script>

<template>
  <Layout>
    <div class="page-heading"><div><span class="eyebrow">AGENCY DIRECTORY</span><h1>Client workspaces</h1><p>Manage visibility across every client tenant.</p></div><Link href="/projects/create" class="primary-btn"><Icon name="plus" :size="17" /> New project</Link></div>
    <div class="project-grid">
      <article v-for="tenant in tenants" :key="tenant.id" class="project-card">
        <div class="project-card-top"><div class="project-icon"><Icon name="users" /></div><span class="status green">{{ tenant.status }}</span></div>
        <h2>{{ tenant.name }}</h2><p>{{ tenant.users_count }} workspace users · {{ tenant.projects_count }} projects</p>
        <div class="project-card-foot"><span>{{ tenant.slug }}</span></div>
      </article>
    </div>
  </Layout>
</template>
