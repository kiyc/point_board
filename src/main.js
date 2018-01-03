import Vue from 'vue'
import App from './App.vue'
import { Button, Input, Dialog } from 'element-ui'
import 'element-ui/lib/theme-chalk/icon.css'
import 'element-ui/lib/theme-chalk/button.css'
import 'element-ui/lib/theme-chalk/input.css'
import 'element-ui/lib/theme-chalk/dialog.css'

Vue.use(Button)
Vue.use(Input)
Vue.use(Dialog)

new Vue({
  el: '#app',
  render: h => h(App)
})
