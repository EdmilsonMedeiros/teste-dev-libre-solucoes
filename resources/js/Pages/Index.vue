<template>
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6 col-sm-6 col-lg-4 col-xl-4">
          <div class="card border-success">
            <div class="card-header bg-success text-white text-center">
              <h3>Login</h3>
            </div>
            <div class="card-body">
              <form @submit.prevent="submit">
                <div class="mb-3">
                  <label for="email" class="form-label text-success">Email</label>
                  <input 
                    type="email" 
                    class="form-control border-warning" 
                    id="email" 
                    v-model="form.email" 
                    required
                    placeholder="Digite seu email"
                  >
                  <!-- Exibição de erro específico para o campo de email -->
                  <div v-if="form.errors.email" class="text-danger small">
                    {{ form.errors.email }}
                  </div>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label text-success">Senha</label>
                  <input 
                    type="password" 
                    class="form-control border-warning" 
                    id="password" 
                    v-model="form.password" 
                    required
                    placeholder="Digite sua senha"
                  >
                  <!-- Exibição de erro específico para o campo de senha -->
                  <div v-if="form.errors.password" class="text-danger small">
                    {{ form.errors.password }}
                  </div>
                </div>
                <div class="d-grid">
                  <button 
                    type="submit" 
                    class="btn btn-success"
                  >
                    Entrar
                  </button>
                </div>
              </form>
            </div>
            <!-- <div class="card-footer text-center">
              <a href="#" class="text-warning text-decoration-none">Esqueceu sua senha?</a>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { useForm } from '@inertiajs/vue3';
  
  export default {
    setup() {
      const form = useForm({
        email: '',
        password: '',
      });
  
      function submit() {
        form.post('/login', {
          onError: (errors) => {
            console.error("Erros no login:", errors); // Opcional: log para depuração
          },
        });
      }
  
      return { form, submit };
    },
  };
  </script>
  
  <style scoped>
  .card {
    border-color: #28a745 !important;
  }
  .card-header {
    background-color: #28a745 !important;
  }
  .form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
  }
  .btn-success:hover {
    background-color: #218838;
  }
  .text-danger {
    font-size: 0.875rem;
  }
  </style>
  