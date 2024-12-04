<template>
    <a 
      href="#" 
      class="text-decoration-none text-danger fs-4 ml-5 text-dark"
      @click.prevent="logOut"
    >
      LogOut <i class="bi bi-box-arrow-right"></i>
    </a>
  </template>
  
  <script>
  export default {
    props: {
      user: Object, // Informações do usuário autenticado (se necessário)
    },
    methods: {
      logOut() {
        this.$inertia.post('/logout', {}, {
          onSuccess: () => {
            console.log("Usuário desconectado");
          },
          onError: (errors) => {
            console.error("Erro ao tentar desconectar:", errors);
          },
        });
      },
    },
  };
  </script>
  