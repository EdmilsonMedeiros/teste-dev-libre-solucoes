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
        <h4 class="mb-0">Cadastro de Contato</h4>
      </div>
      <div class="card-body bg-white">
        <form @submit.prevent="submitForm">
          <div class="row">
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
              Cadastrar
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
  components: { Head, RightTopMenu, FlashMessage },
  props: {
    user: Object,
  },
  data() {
    return {
      showFlashMessage: {
        type: null,
        message: null,
      },
      form: {
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
      }
    };
  },
  methods: {
    maskCEP(value) {
      // Remove todos os caracteres que não são números
      const cleaned = value.replace(/\D/g, '');
      // Limita a quantidade de caracteres para 8
      const limited = cleaned.slice(0, 8);
      // Aplica a máscara no formato "59.114-117"
      const formatted = limited
        .replace(/^(\d{2})(\d{3})/, '$1.$2')  // Adiciona ponto após os dois primeiros dígitos
        .replace(/(\d{3})(\d{3})$/, '$1-$2');  // Adiciona hífen após o quarto dígito

      return formatted;
    },
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
    showMessage(type, message) {
      if (this.showFlashMessage.message) {
        this.showFlashMessage.message = null;
        this.showFlashMessage.type = null;
      }
      this.showFlashMessage.message = message;
      this.showFlashMessage.type = type;

      setTimeout(() => {
        this.showFlashMessage.message = null;
        this.showFlashMessage.type = null;
      }, 5000);
    },
    submitForm() {
      this.$inertia.post('/contact/store', this.form, {
        onSuccess: () => {
          this.showMessage("success", "Contato cadastrado com sucesso!");
          this.resetForm();
        },
        onError: (errors) => {
          this.showMessage("error", "Erro ao cadastrar contato.");
          console.error("Erros retornados:", errors);
        }
      });
    },
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
    maskPhone(value) {
      const cleaned = value.replace(/\D/g, '').slice(0, 11);
      const formatted = cleaned
        .replace(/^(\d{2})(\d)/, '($1)$2')
        .replace(/(\d{1})(\d{4})(\d{4})$/, '$1 $2-$3');
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
