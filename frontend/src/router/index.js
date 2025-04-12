import { route } from 'quasar/wrappers'
import { createRouter, createMemoryHistory, createWebHistory, createWebHashHistory } from 'vue-router'
import routes from './routes'

export default route(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : (process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory)

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,
    history: createHistory(process.env.VUE_ROUTER_BASE)
  })

  Router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const isAuthenticated = !!localStorage.getItem('access_token')
    
    console.log('Navigation guard - Route:', to.path)
    console.log('Is authenticated:', isAuthenticated)
    console.log('Requires auth:', requiresAuth)

    if (requiresAuth && !isAuthenticated) {
      console.log('Redirecting to login')
      next('/login')
    } else if (to.path === '/login' && isAuthenticated) {
      console.log('Already logged in, redirecting to blogs')
      next('/blogs')
    } else {
      console.log('Proceeding with navigation')
      next()
    }
  })

  return Router
})