<script setup>
import Layout from '../../Components/Layout.vue'
import Icon from '../../Components/Icon.vue'

defineProps({ updates: Array })
</script>

<template>
  <Layout>
    <div class="page-heading"><div><span class="eyebrow">CLIENT PORTAL</span><h1>Project updates</h1><p>Every update from projects in your workspace.</p></div></div>
    <div class="panel updates-panel"><div v-if="!updates.length" class="empty-state">No project updates yet.</div><div v-else v-for="update in updates" :key="update.id" class="feed-item"><div class="feed-dot"></div><div><strong>{{ update.project }}</strong><p>{{ update.body }}</p><small><Icon name="activity" :size="12" /> {{ update.author }} · {{ update.created_at }}</small></div></div></div>
  </Layout>
</template>
