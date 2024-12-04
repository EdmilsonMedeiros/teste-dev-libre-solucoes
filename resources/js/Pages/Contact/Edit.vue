<template>
    <Head title="Cadastrar" />
    <div class="container-fluid p-4">
        <div class="flex justify-end">
            <FlashMessage :message="showFlashMessage.message" :type="showFlashMessage.type" />
            <h3 class="mb-4">{{ user.name }}</h3>
            <RightTopMenu :user="user" />
        </div>
    </div>

    <div class="container py-4">
      <div class="card border-success shadow-sm">
        <div class="card-header bg-success text-white">
          <h4 class="mb-0">Atualização de Contato</h4>
        </div>
        <div class="card-body bg-white">
          <form @submit.prevent="submitForm">
            <div class="row">
                        <!--  -->
             <div class="col-6">
              <h5 class="text-success">Informações de contato</h5>
                          <!-- Nome -->
              <div class="mb-1">
                <label for="name" class="form-label text-success">Nome</label>
                <input 
                  type="text" 
                  id="name" 
                  class="form-control border-warning" 
                  v-model="form.name" 
                  required
                />
              </div>
    
              <!-- E-mail -->
              <div class="mb-1">
                <label for="email" class="form-label text-success">E-mail</label>
                <input 
                  type="email" 
                  id="email" 
                  class="form-control border-warning" 
                  v-model="form.email" 
                  required
                />
              </div>
    
              <!-- Telefone -->
              <div class="mb-1">
                <label for="phone" class="form-label text-success">Telefone</label>
                <input 
                  type="text" 
                  id="phone" 
                  class="form-control border-warning" 
                  v-model="form.phone" 
                  required
                  @input="form.phone = maskPhone(form.phone)"
                />
              </div>
    
              <!-- Idade -->
              <div class="mb-1">
                <label for="age" class="form-label text-success">Idade</label>
                <input 
                  type="number" 
                  id="age" 
                  class="form-control border-warning" 
                  v-model="form.age" 
                  min="0"
                />
              </div>
          </div>

                      <!-- Endereço -->
          <div class="mb-3 col-6">
            <h5 class="text-success">Endereço (Opcional)</h5>
            <div class="row">
                          <!-- CEP -->
            <div class="mb-1 col-6">
              <label for="zip" class="form-label">CEP</label>
              <input 
                type="text" 
                id="zip" 
                class="form-control border-warning" 
                v-model="form.address.zip"
                @input="maskCEPAndFetchAddress"
              />
            </div>
            <!-- Rua -->
            <div class="mb-1 col-6">
              <label for="street" class="form-label">Rua</label>
              <input 
                type="text" 
                id="street" 
                class="form-control border-warning" 
                v-model="form.address.street"
              />
            </div>

            <!-- Número -->
            <div class="mb-1 col-6">
              <label for="number" class="form-label">Número</label>
              <input 
                type="text" 
                id="number" 
                class="form-control border-warning" 
                v-model="form.address.number"
              />
            </div>

            <!-- Complemento -->
            <div class="mb-1 col-6">
              <label for="neighborhood" class="form-label">Complemento</label>
              <input 
                type="text" 
                id="neighborhood" 
                class="form-control border-warning" 
                v-model="form.address.complement"
              />
            </div>

            <!-- Bairro -->
            <div class="mb-1">
              <label for="neighborhood" class="form-label">Bairro</label>
              <input 
                type="text" 
                id="neighborhood" 
                class="form-control border-warning" 
                v-model="form.address.neighborhood"
              />
            </div>

            <!-- Cidade -->
            <div class="mb-1 col-6">
              <label for="city" class="form-label">Cidade</label>
              <input 
                type="text" 
                id="city" 
                class="form-control border-warning" 
                v-model="form.address.city"
              />
            </div>

            <!-- Estado -->
            <div class="mb-1 col-6">
              <label for="state" class="form-label">Estado</label>
              <input 
                type="text" 
                id="state" 
                class="form-control border-warning" 
                v-model="form.address.state"
              />
            </div>
            </div>

          </div>
            </div>
  
            <!-- Botão de Cadastro -->
            <div class="d-flex justify-content-end">
              <button 
                type="submit" 
                class="btn btn-warning text-dark fw-bold px-4"
              >
                Atualizar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
    import { Head } from '@inertiajs/vue3';
    import RightTopMenu from '../Components/RightTopMenu.vue';
    import FlashMessage from '../Components/FlashMessage.vue';

  export default {
    components: {Head, RightTopMenu, FlashMessage},
    props: {
        user: Object,
        contact: Object,
    },
    data() {
      return {
        showFlashMessage: {
            type: null,
            message: null,
        },
        form: {
          id: this.contact.id,
          name: this.contact.name,
          email: this.contact.email,
          phone: this.contact.phone,
          age: this.contact.age,
          address: {
            street: this.contact.address ? this.contact.address.street : "",
            number: this.contact.address ? this.contact.address.number : "",
            complement: this.contact.address ? this.contact.address.complement : "",
            neighborhood: this.contact.address ? this.contact.address.neighborhood : "",
            city: this.contact.address ? this.contact.address.city : "",
            state: this.contact.address ? this.contact.address.state : "",
            zip: this.contact.address ? this.contact.address.zip : "",
          }
        }
      };
    },
    methods: {
          maskCEPAndFetchAddress() {
          const cep = this.form.address.zip.replace(/\D/g, '');
          if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
              .then((response) => response.json())
              .then((data) => {
                if (data.erro) {
                  this.showMessage("error", "CEP não encontrado.");
                } else {
                  this.form.address.street = data.logradouro || "";
                  this.form.address.neighborhood = data.bairro || "";
                  this.form.address.city = data.localidade || "";
                  this.form.address.state = data.uf || "";
                }
              })
              .catch(() => {
                this.showMessage("error", "Erro ao buscar o endereço.");
              });
          }
        },
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
        // Envia o formulário ao backend
        submitForm() {
            this.$inertia.put(`/contact/update/${this.form.id}`, this.form, {
                onSuccess: () => {
                    this.showMessage("success", "Contato atualizado com sucesso!");

                },
                onError: (errors) => {
                    this.showMessage("error", "Erro ao atualizar contato.");
                    console.error("Erros retornados:", errors);
                }
            });
        },
        // Reseta os campos do formulário
        resetForm() {
            this.form = {
                name: "",
                email: "",
                phone: "",
                age: null,
                address: {
                  street: "",
                  number: "",
                  complement: "",
                  neighborhood: "",
                  city: "",
                  state: "",
                  zip: ""
                }
            };
        },
        // Aplica a máscara de telefone
        maskPhone(value) {
            // Remove todos os caracteres que não são números
            const cleaned = value.replace(/\D/g, '').slice(0, 11); // Garante no máximo 11 dígitos

            // Formata o número de acordo com o padrão
            const formatted = cleaned
                .replace(/^(\d{2})(\d)/, '($1)$2') // Adiciona parênteses ao DDD
                .replace(/(\d{1})(\d{4})(\d{4})$/, '$1 $2-$3'); // Espaço após o primeiro dígito e hífen no número

            return formatted;
        },
    }
  };
  </script>
  
  <style scoped>
  .card-header {
    border-radius: 0;
  }
  
  .form-control {
    border-radius: 8px;
  }
  
  .btn {
    border-radius: 8px;
  }
  </style>
  