<template>
  <div>
    <!-- Modal -->
    <div 
      class="modal fade" 
      :id="modalId" 
      tabindex="-1" 
      aria-labelledby="modalLabel" 
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <!-- Cabeçalho -->
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="modalLabel">Detalhes do Contato</h5>
            <button 
              type="button" 
              class="btn-close" 
              data-bs-dismiss="modal" 
              aria-label="Fechar"
            ></button>
          </div>

          <!-- Corpo -->
          <div class="modal-body">
            <div v-if="contact">
                <div class="row">
                  <div class="col-6">
                    <h6 class="mt-3">Informaões de contato:</h6>
                    <p><strong>Nome:</strong> {{ contact.name }}</p>
                    <p><strong>E-mail:</strong> {{ contact.email }}</p>
                    <p><strong>Telefone:</strong> {{ contact.phone }}</p>
                    <p v-if="contact.age"><strong>Idade:</strong> {{ contact.age }}</p>
                  </div>

                  <!-- Dados de Endereço -->
                  <div class="col-6" v-if="contact.address">
                    <h6 class="mt-3">Dados de Endereço:</h6>
                    <p><strong>Rua:</strong> {{ contact.address.street }}</p>
                    <p><strong>Número:</strong> {{ contact.address.number }}</p>
                    <p v-if="contact.address.complement"><strong>Complemento:</strong> {{ contact.address.complement }}</p>
                    <p><strong>Bairro:</strong> {{ contact.address.neighborhood }}</p>
                    <p><strong>Cidade:</strong> {{ contact.address.city }}</p>
                    <p><strong>Estado:</strong> {{ contact.address.state }}</p>
                    <p><strong>CEP:</strong> {{ contact.address.zip }}</p>
                  </div>
                </div>
            </div>
            <div v-else>
              <p>Sem dados para exibir.</p>
            </div>
          </div>

          <!-- Rodapé -->
          <div class="modal-footer">
            <button 
              type="button" 
              class="btn btn-secondary" 
              data-bs-dismiss="modal"
            >
              Fechar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    contact: {
      type: Object,
      required: true
    },
    modalId: {
      type: String,
      default: 'contactModal' // Identificador único para o modal
    }
  }
};
</script>

<style scoped>
.modal-header {
  border-bottom: 1px solid #dee2e6;
}

.modal-footer {
  border-top: 1px solid #dee2e6;
}

.modal-body p {
  margin-bottom: 0.5rem;
}

.modal-body h6 {
  font-weight: bold;
}
</style>
