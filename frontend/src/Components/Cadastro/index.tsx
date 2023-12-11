import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

interface FormData {
  Nome: string;
  DataNascimento: string; // Alterado para string
  Cpf: string;
  Email: string;
  Senha: string;
  Cep: string;
  Complemento: string;
  NumeroEndereco: string;
  Logradouro: string;
  Bairro: string;
  Localidade: string;
  Uf: string;
}

const Cadastro: React.FC = () => {
  const [formData, setFormData] = useState<FormData>({
    Nome: '',
    DataNascimento: '', // Inicializado como string
    Cpf: '',
    Email: '',
    Senha: '',
    Cep: '',
    Complemento: '',
    NumeroEndereco: '',
    Logradouro: '',
    Bairro: '',
    Localidade: '',
    Uf: '',
  });
  const navigate = useNavigate();

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleCepBlur = async () => {
    const { Cep } = formData;
    if (Cep.length === 8) {
      try {
        const response = await axios.get(`https://viacep.com.br/ws/${Cep}/json/`);
        const Logradouro = response.data.logradouro;
        const Bairro = response.data.bairro;
        const Localidade = response.data.localidade;
        const Uf = response.data.uf;
    
        setFormData({
          ...formData,
          Logradouro,
          Bairro,
          Localidade,
          Uf,
        });
      } catch (error) {
        console.error('Erro ao buscar CEP:', error);
      }
    }
  };

  const formatarData = (data: Date): string => {
    const ano = data.getFullYear();
    const mes = (data.getMonth() + 1).toString().padStart(2, '0');
    const dia = data.getDate().toString().padStart(2, '0');
    return `${ano}-${mes}-${dia}`;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    try {
        console.log(formData);
      const response = await axios.post('http://localhost:8000/registrar', {
        nome_completo: formData.Nome,
        data_nascimento: formatarData(new Date(formData.DataNascimento)),
        cpf: formData.Cpf,
        email: formData.Email,
        senha: formData.Senha,
        cep: formData.Cep,
        logradouro: formData.Logradouro,
        bairro: formData.Bairro,
        localidade: formData.Localidade,
        uf: formData.Uf,
        complemento: formData.Complemento,
        numero_endereco: formData.NumeroEndereco,
      });
      
      alert('Cadastro realizado com sucesso!');
      navigate('/');
    } catch (error) {
      console.error('Erro ao cadastrar:', error);
      alert(error);
    }
    
  };

  return (
    <div>
      <h2>Cadastro</h2>
      <form onSubmit={handleSubmit}>
        {/* ... Outros campos ... */}
        <label htmlFor="nome">Nome:</label>
        <input type="text" id="nome" name="Nome" value={formData.Nome} onChange={handleChange} required />

        <label htmlFor="dataNascimento">Data de Nascimento:</label>
        <input type="date" id="dataNascimento" name="DataNascimento" value={formData.DataNascimento} onChange={handleChange} required />

        <label htmlFor="cpf">CPF:</label>
        <input type="text" id="cpf" name="Cpf" value={formData.Cpf} onChange={handleChange} required />

        <label htmlFor="email">Email:</label>
        <input type="email" id="email" name="Email" value={formData.Email} onChange={handleChange} required />

        <label htmlFor="senha">Senha:</label>
        <input type="password" id="senha" name="Senha" value={formData.Senha} onChange={handleChange} required />

        <label htmlFor="cep">CEP:</label>
        <input type="text" id="cep" name="Cep" value={formData.Cep} onChange={handleChange} onBlur={handleCepBlur} required />

        {/* Campos de endereço preenchidos automaticamente */}
        <label htmlFor="logradouro">Logradouro:</label>
        <input type="text" id="logradouro" name="Logradouro" value={formData.Logradouro} onChange={handleChange} required />

        <label htmlFor="bairro">Bairro:</label>
        <input type="text" id="bairro" name="Bairro" value={formData.Bairro} onChange={handleChange} required />

        <label htmlFor="localidade">Localidade:</label>
        <input type="text" id="localidade" name="Localidade" value={formData.Localidade} onChange={handleChange} required />

        <label htmlFor="uf">UF:</label>
        <input type="text" id="uf" name="Uf" value={formData.Uf} onChange={handleChange} required />

        <label htmlFor="complemento">Complemento:</label>
        <input type="text" id="complemento" name="Complemento" value={formData.Complemento} onChange={handleChange} />

        <label htmlFor="numeroEndereco">Número do Endereço:</label>
        <input type="text" id="numeroEndereco" name="NumeroEndereco" value={formData.NumeroEndereco} onChange={handleChange} />

        <button type="submit">Cadastrar</button>
      </form>
    </div>
  );
};

export default Cadastro;
