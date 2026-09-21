<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import Icon from './Icon.vue'

const page = usePage()
const user = computed(() => page.props.auth?.user)
const tenant = computed(() => page.props.auth?.tenant)
const open = ref(false)
const role = computed(() => user.value?.role)
const logout = () => router.post('/logout')
const roleLabel = computed(() => ({admin:'Administrator',client:'Client Portal',team:'Team Workspace'}[role.value] || 'Workspace'))
const navigation = computed(() => {
  const items = [{ label: 'Dashboard', href: '/', icon: 'grid' }, { label: 'Projects', href: '/projects', icon: 'folder' }]

  if (role.value === 'admin') {
    items.push({ label: 'Client workspaces', href: '/clients', icon: 'users' }, { label: 'New project', href: '/projects/create', icon: 'plus' })
  }
  if (role.value === 'team') {
    items.push({ label: 'My tasks', href: '/tasks', icon: 'check' }, { label: 'New project', href: '/projects/create', icon: 'plus' })
  }
  if (role.value === 'client') {
    items.push({ label: 'Updates', href: '/updates', icon: 'activity' })
  }

  return items
})
const isActive = href => href === '/' ? page.url === '/' : page.url.startsWith(href)
</script>
<template>
  <div class="app-shell" :class="`role-${role}`">
    <aside class="sidebar" :class="{open}">
      <div class="brand"><div class="brand-mark"><Icon name="spark" :size="18"/></div><div><strong>Source X</strong><span>Agency OS</span></div></div>
      <div class="workspace"><span class="eyebrow">WORKSPACE</span><div class="workspace-name">{{ tenant?.name || 'Global Agency' }}</div></div>
      <nav class="side-nav">
        <Link v-for="item in navigation" :key="item.href" :href="item.href" :class="{active:isActive(item.href)}"><Icon :name="item.icon"/> <span>{{ item.label }}</span></Link>
      </nav>
      <div class="sidebar-bottom">
        <div class="security-pill"><span class="status-dot"></span><div><strong>Tenant protected</strong><small>Isolation active</small></div></div>
        <button class="logout-link" @click="logout"><Icon name="logout"/><span>Sign out</span></button>
      </div>
    </aside>
    <main class="main-area">
      <header class="topbar">
        <button class="mobile-menu" @click="open=!open"><Icon name="menu"/></button>
        <div><div class="topbar-title">{{ roleLabel }}</div><div class="topbar-sub">{{ tenant?.name || 'All tenants' }}</div></div>
        <div class="top-actions"><button class="icon-btn"><Icon name="bell"/></button><div class="avatar">{{ user?.name?.slice(0,1)?.toUpperCase() }}</div><div class="user-chip"><strong>{{ user?.name }}</strong><span>{{ user?.email }}</span></div></div>
      </header>
      <div v-if="page.props.flash?.success" class="toast success"><Icon name="check" :size="16"/>{{ page.props.flash.success }}</div>
      <div v-if="page.props.flash?.error" class="toast error"><Icon name="activity" :size="16"/>{{ page.props.flash.error }}</div>
      <section class="content"><slot/></section>
    </main>
  </div>
</template>
