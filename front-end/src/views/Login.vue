<template>
  <div class="text-start login">
    <form class="form-signin" v-if="!is_register">
      <div class="text-center w-100">
        <img class="mb-4" src="../assets/logo.png" alt="" height="100">
      </div>
      <h1 class="h3 mb-3 font-weight-normal text-center">Faça login</h1>
      <label for="inputEmail" class="sr-only">Endereço de email</label>
      <input type="email" v-model="login.email" id="inputEmail" class="form-control mb-3" placeholder="Seu email" required
        autofocus>
      <label for="inputPassword" class="sr-only">Senha</label>
      <input type="password" v-model="login.password" id="inputPassword" class="form-control" placeholder="Senha"
        required>
      <div class="text-center w-100 mt-3">
        <button class="btn btn-lg btn-primary btn-block w-100 mb-2" type="button"
          @click.prevent="SendLogin()">Login</button>
        <a href="" @click.prevent="is_register = true">criar conta</a>
      </div>
    </form>

    <!--Formulario de cadastro-->

    <form class="form-signin" v-else>
      <div class="text-center w-100">
        <img class="mb-4" src="../assets/logo.png" alt="" height="100">
      </div>
      <h1 class="h3 mb-3 font-weight-normal text-center">Criar Conta</h1>
      <label for="inputName" class="sr-only">Nome</label>
      <input type="name" v-model="register.name" id="inputName" class="form-control mb-3" placeholder="Seu nome" required
        autofocus>
      <label for="inputEmail" class="sr-only">Endereço de email</label>
      <input type="email" v-model="register.email" id="inputEmail" class="form-control mb-3" placeholder="Seu email"
        required>
      <label for="inputPassword" class="sr-only">Senha</label>
      <input type="password" v-model="register.password" id="inputPassword" class="form-control" placeholder="Senha"
        required>
      <div class="text-center w-100 mt-3">
        <button class="btn btn-lg btn-primary btn-block w-100 mb-2" type="button"
          @click.prevent="SendRegister()">Cadastrar</button>
        <a href="" @click.prevent="is_register = false">Login</a>
      </div>
    </form>
  </div>
</template>
<script>
import api from "@/services/api";
import Swal from 'sweetalert2'
export default {
  data() {
    return {
      is_register: false,
      login: {
        email: null,
        password: null,
      },
      register: {
        email: null,
        password: null,
        name: null
      }

    }
  },
  methods: {
    async SendLogin() {
      await api.post("/login", this.login).then((response) => {
        if (response.data.token) {
          localStorage.setItem('token', response.data.token)
          this.$router.push({ name: 'home' })
        } else {
          Swal.fire({
            title: 'OPPS',
            text: 'Confira seu e-mail e senha',
            icon: 'error',

          });
        }
      }).catch(function (error) {
        Swal.fire({
          title: 'OPPS',
          text: error.response.data.detail ?? 'Confira seu e-mail e senha',
          icon: 'error',
        });
      });
    },

    async SendRegister() {
      await api.post("/register", this.register).then((response) => {
        if (response.data.detail) {
          Swal.fire({
            title: 'OPPS',
            text: response.data.detail,
            icon: 'error',

          });
        } else {
          Swal.fire({
            title: 'Sucesso',
            text: 'Cadastro realizado com sucesso',
            icon: 'success',

          });
          this.is_register = false
        }
      }).catch(function (error) {
        Swal.fire({
          title: 'OPPS',
          text: error.response.data.detail ?? 'Confira seus dados',
          icon: 'error',
        });
      });
    }
  },
}

</script>
<style>
.login {
  height: 100vh;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}

.form-signin .checkbox {
  font-weight: 400;
}

.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}

.form-signin .form-control:focus {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}</style>
