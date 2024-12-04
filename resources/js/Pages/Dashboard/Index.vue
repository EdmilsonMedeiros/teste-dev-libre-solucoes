<!-- Dashboard.vue -->
<template>
    <Head title="Listagem" />
    <div class="container-fluid p-4">
      <div class="flex justify-end">
        <FlashMessage :message="showFlashMessage.message" :type="showFlashMessage.type" />
        <h3 class="mb-4">{{ user.name }}</h3>
        <RightTopMenu :user="user" />
      </div>
      
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Lista de Contatos</h4>
          <div class="d-flex">
            <input 
              v-model="searchTerm" 
              placeholder="Buscar contato..." 
              class="form-control form-control-sm me-2" 
              style="width: 200px;"
            />
            <Link href="/contact/create" @click="openCreateContact" class="btn btn-success btn-sm">
              Novo Contato
            </Link>
          </div>
        </div>
        
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="table-success">
                <tr>
                  <th @click="sort('id')">ID ↕</th>
                  <th @click="sort('nome')">Nome ↕</th>
                  <th @click="sort('email')">Email ↕</th>
                  <th @click="sort('telefone')">Telefone ↕</th>
                  <th @click="sort('idade')">Idade ↕</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="contact in paginatedContacts" :key="contact.id">
                  <td>{{ contact.id }}</td>
                  <td>{{ contact.name }}</td>
                  <td>{{ contact.email }}</td>
                  <td>{{ maskPhone(contact.phone) }}</td>
                  <td>{{ contact.age }}</td>
                  <td>
                    <div class="btn-group" role="group">
                      <button 
                        class="btn btn-sm btn-info"
                        data-bs-toggle="modal"
                        :data-bs-target="`#contact_${contact.id}`"
                      >
                        <i class="bi bi-eye"></i>
                      </button>
                      <Link 
                        :href="'/contact/edit/'+contact.id"
                        class="btn btn-sm btn-warning"
                      >
                        <i class="bi bi-pencil"></i>
                      </Link>
                      <button 
                        @click="deleteContact(contact)" 
                        class="btn btn-sm btn-danger"
                      >
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                    <ContactModal :contact="contact" :modalId="`contact_${contact.id}`"/>
                  </td>
                  
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Paginação -->
          <nav v-if="contacts.length > 5">
            <ul class="pagination justify-content-center">
              <li 
                class="page-item" 
                :class="{ disabled: currentPage === 1 }"
              >
                <button 
                  class="page-link" 
                  @click="currentPage--"
                >
                  Anterior
                </button>
              </li>
              
              <li 
                v-for="page in totalPages" 
                :key="page" 
                class="page-item" 
                :class="{ active: currentPage === page }"
              >
                <button 
                  class="page-link" 
                  @click="currentPage = page"
                >
                  {{ page }}
                </button>
              </li>
              
              <li 
                class="page-item" 
                :class="{ disabled: currentPage === totalPages }"
              >
                <button 
                  class="page-link" 
                  @click="currentPage++"
                >
                  Próximo
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
  
    </div>
  </template>
  
  <script>
    import { Head, Link } from '@inertiajs/vue3';
    import RightTopMenu from '../Components/RightTopMenu.vue';
    import FlashMessage from '../Components/FlashMessage.vue';
    import ContactModal from '../Components/ContactModal.vue';

  export default {
    components: {
        RightTopMenu,
        Link,
        Head,
        FlashMessage,
        ContactModal
    },
    watch: {

    },

    props: {
      user: {
        type: Object,
        required: true
      },
      contacts: Object,
    },
    data() {
      return {
        showFlashMessage: {
            type: null,
            message: null,
        },
        searchTerm: '',
        currentPage: 1,
        itemsPerPage: 5,
        sortKey: 'id',
        sortOrder: 'asc'
      }
    },
    computed: {
      // Filtra os contatos baseado no termo de busca
      filteredContacts() {
        return this.contacts.filter(contact => 
          Object.values(contact).some(value => 
            String(value).toLowerCase().includes(this.searchTerm.toLowerCase())
          )
        )
      },
      // Ordena os contatos
      sortedContacts() {
        return [...this.filteredContacts].sort((a, b) => {
          let modifier = this.sortOrder === 'asc' ? 1 : -1
          return a[this.sortKey] > b[this.sortKey] ? modifier : -modifier
        })
      },
      // Pagina os contatos
      paginatedContacts() {
        const start = (this.currentPage - 1) * this.itemsPerPage
        const end = start + this.itemsPerPage
        return this.sortedContacts.slice(start, end)
      },
      // Calcula o total de páginas
      totalPages() {
        return Math.ceil(this.filteredContacts.length / this.itemsPerPage)
      }
    },
    methods: {
        showMessage(type, message){
            if(this.showFlashMessage.message){ // Limpa
                this.showFlashMessage.message = null;
                this.showFlashMessage.type = null;
            }
            // Injeta
            this.showFlashMessage.message = message;
            this.showFlashMessage.type = type;

            setTimeout(() => { // Re-Limpa
                this.showFlashMessage.message = null;
                this.showFlashMessage.type = null;
            }, 5000);
        },
        maskPhone(value) {
            // Remove todos os caracteres que não são números
            const cleaned = value.replace(/\D/g, '');

            // Formata o número de acordo com o padrão
            const formatted = cleaned
                .replace(/^(\d{2})(\d)/, '($1)$2') // Adiciona parênteses ao DDD
                .replace(/(\d{1})(\d{4})(\d{4})$/, '$1 $2-$3'); // Espaço após o primeiro dígito e hífen no número

            return formatted;
        },
        // Método de ordenação
        sort(key) {
            if (this.sortKey === key) {
            this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc'
            } else {
            this.sortKey = key
            this.sortOrder = 'asc'
            }
        },
        // Métodos de ação para cada botão
        viewContact(contact) {
            // Lógica para visualizar contato
            console.log('Visualizar contato:', contact)
            // Pode abrir um modal com detalhes
        },
        deleteContact(contact) {
            const index = this.contacts.findIndex(c => c.id === contact.id);
            if (index !== -1) {
                if (confirm("Tem certeza que quer excluir?")) {
                this.$inertia.delete(`/contact/destroy/${contact.id}`, {
                    onSuccess: () => {
                        // Remover contato da lista local
                        this.showMessage('success', "Contato excluído com sucesso!");
                    },
                    onError: (errors) => {
                        console.error("Erro ao excluir contato:", errors);
                        this.showMessage("error", "Ocorreu um erro ao excluir o contato. Por favor, tente novamente.")
                    },
                });
                }
            } else {
                console.warn("Contato não encontrado na lista.");
            }
        },
    }
  }
  </script>
  
  <style scoped>
  /* Estilos customizados podem ser adicionados aqui */
  thead th {
    cursor: pointer;
  }
  .table-success {
    background-color: #c3e6cb;
  }
  .btn-group .btn {
    padding: 0.2rem 0.5rem;
  }
  </style>