import { createRouter, createWebHistory } from "vue-router"

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        /*
        {
            path: '/',
            name: 'home',
            component: HomeView
            },
            {
            path: '/about',
            name: 'about',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/AboutView.vue')
        },
        */
        {
            path: "/",
            alias: "/login",
            name: "login",
            component: () => import("../views/LoginView.vue")
        },
        {
            path: "/app",
            name: "app",
            component: () => import("../views/MainView.vue")
        }
    ]
})

export default router
