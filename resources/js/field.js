import DetailField from './components/DetailField.vue'
import FormField from './components/FormField.vue'
import IndexField from './components/IndexField.vue'

Nova.booting((app) => {
    app.component('index-nova-mermaid-field', IndexField)
    app.component('form-nova-mermaid-field', FormField)
    app.component('detail-nova-mermaid-field', DetailField)
})
