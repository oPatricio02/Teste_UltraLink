
import React, { useState } from 'react';
import { Navigate, useNavigate} from 'react-router-dom';

const Login: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault();
    // Lógica para verificar o login (pode ser adicionada posteriormente)
    console.log('Email:', email);
    console.log('Senha:', password);
  };

  const navegarCadastro = () => {
    // Navegar para a página de cadastro
    navigate('/Cadastro');
  };

  return (
    <div>
      <h2>Login</h2>
      <form onSubmit={handleSubmit}>
        <label htmlFor="email">Email:</label>
        <input
          type="email"
          id="email"
          name="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />

        <label htmlFor="password">Senha:</label>
        <input
          type="password"
          id="password"
          name="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />

        <button type="submit">Entrar</button>
      </form>

      <div>
        <p>
          Não tem uma conta?{' '}
          <button onClick={navegarCadastro}>Cadastre-se</button>
        </p>
      </div>
    </div>
  );
};

export default Login;
