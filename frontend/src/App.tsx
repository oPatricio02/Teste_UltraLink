// src/App.tsx

import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Login from './Components/TelaLogin';
import Cadastro from './Components/Cadastro';

const App: React.FC = () => {
  return (
    <Router>
      <Routes>
        <Route path="/cadastro" element={<Cadastro />} />
        <Route path="/" element={<Login />} />
      </Routes>
    </Router>
  );
};

export default App;
