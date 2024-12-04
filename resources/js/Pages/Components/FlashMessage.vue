<template>
    <transition name="fade">
      <div v-if="message && type" :class="flashClass" class="flash-message">
        {{ message }}
      </div>
    </transition>
  </template>
  
  <script>
  export default {
    props: {
      message: {
        type: String,
        required: false,
        default: ""
      },
      type: {
        type: String,
        default: "success", // success or error
        validator: (value) => ["success", "error"].includes(value)
      }
    },
    data() {

    },
    computed: {
      flashClass() {
        return {
          "flash-success": this.type === "success",
          "flash-error": this.type === "error"
        };
      }
    }
  };
  </script>
  
  <style scoped>
  .flash-message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    padding: 15px 20px;
    border-radius: 8px;
    color: white;
    font-size: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    animation: fade-in-out 10s ease-in-out;
    
  }
  
  /* Estilos para sucesso e erro */
  .flash-success {
    background-color: #28a745;
  }
  
  .flash-error {
    background-color: #dc3545;
  }
  
  /* Transições */
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter, 
  .fade-leave-to {
    opacity: 0;
  }
  </style>
  