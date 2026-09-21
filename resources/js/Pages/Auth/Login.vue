<script setup>
import { useForm } from '@inertiajs/vue3'
import Icon from '../../Components/Icon.vue'

const accounts = [
  { role: 'Admin', email: 'admin@sourcex.test', description: 'All client workspaces and agency metrics' },
  { role: 'Client - Acme', email: 'client-a@sourcex.test', description: 'Acme Digital client portal' },
  { role: 'Team', email: 'team@sourcex.test', description: 'Acme Digital delivery workspace' },
  { role: 'Client - Beta', email: 'client-b@sourcex.test', description: 'Beta Studio client portal' },
]

const form = useForm({ email: accounts[0].email, password: 'password', remember: false })
const chooseAccount = (account) => {
  form.email = account.email
  form.password = 'password'
  form.clearErrors()
}
const submit = () => form.post('/login')
</script>

<template>
  <div class="login-page">
    <div class="login-art">
      <div class="art-grid"></div>
      <div class="login-brand"><div class="brand-mark"><Icon name="spark" :size="19" /></div><div><strong>Source X</strong><span>Agency OS</span></div></div>
      <div class="art-copy"><span class="eyebrow">MULTI-TENANT AGENCY OS</span><h1>Every client.<br><em>One secure workspace.</em></h1><p>Projects, tasks, files and updates—isolated by tenant and designed for modern agency teams.</p></div>
      <div class="art-footer"><span>Secure by design</span><span>Laravel 12 · Inertia · Vue 3</span></div>
    </div>
    <div class="login-panel">
      <div class="login-box">
        <span class="eyebrow">WELCOME BACK</span><h2>Sign in</h2><p>Access your workspace dashboard.</p>
        <form @submit.prevent="submit">
          <label>Email<input v-model="form.email" type="email" autocomplete="email" /></label>
          <div v-if="form.errors.email" class="form-error">{{ form.errors.email }}</div>
          <label>Password<input v-model="form.password" type="password" autocomplete="current-password" /></label>
          <div v-if="form.errors.password" class="form-error">{{ form.errors.password }}</div>
          <label class="check"><input v-model="form.remember" type="checkbox" /> Remember me</label>
          <button class="primary-btn full" :disabled="form.processing">{{ form.processing ? 'Signing in…' : 'Sign in' }}</button>
        </form>
        <section class="demo-hint" aria-label="Demo accounts">
          <strong>Demo accounts</strong><span>Choose an account to test its dashboard. Password: <code>password</code></span>
          <button v-for="account in accounts" :key="account.email" type="button" class="demo-account" :class="{ selected: form.email === account.email }" @click="chooseAccount(account)">
            <span><b>{{ account.role }}</b><small>{{ account.description }}</small></span><code>{{ account.email }}</code>
          </button>
        </section>
      </div>
    </div>
  </div>
</template>
