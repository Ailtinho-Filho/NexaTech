# 🤖 NexaTech — Sistema de Gestão com IA

Sistema web de gestão empresarial desenvolvido como trabalho acadêmico para a disciplina de Web na **Fatec Bauru**. Conta com autenticação de usuários, CRUDs completos, módulo de análise com IA e chat interativo.

## 🔗 Demo

[Acesse o sistema](https://ailtinho-filho.github.io/NexaTech)

---

## 📋 Sobre

A NexaTech é uma plataforma de gestão fictícia que simula um sistema real de consultoria empresarial. Permite cadastro e login de usuários com diferentes níveis de acesso, gerenciamento de empresas, geração de análises e um chat simulado com IA.

---

## ✨ Funcionalidades

- **Autenticação** — login e cadastro de usuários com validação
- **Controle de acesso** — perfis Administrador, Consultor e Cliente com permissões diferentes
- **CRUD de Usuários** — criar, listar, editar e excluir usuários
- **CRUD de Empresas** — gerenciamento completo com nome, CNPJ, setor e telefone
- **Módulo de Análise** — formulário para geração de soluções baseadas em desafios e objetivos
- **Relatórios** — histórico de análises realizadas por usuário
- **Chat IA** — interface de chat simulado com respostas da inteligência artificial
- **Persistência** — todos os dados salvos via localStorage

---

## 🛠️ Tecnologias

| Tecnologia | Uso |
|---|---|
| HTML5 | Estrutura e interface |
| CSS3 | Estilização inline |
| JavaScript | Lógica, CRUDs e navegação SPA |
| PHP | Conexão com banco de dados |
| MySQL | Banco de dados de usuários |
| localStorage | Persistência de dados no front-end |

---

## 👥 Perfis de Acesso

| Perfil | Permissões |
|---|---|
| Administrador | Acesso total — usuários, empresas, análises, relatórios e chat |
| Consultor | Acesso a empresas, análises, relatórios e chat |
| Cliente | Acesso apenas ao chat e relatórios próprios |

---

## 📁 Estrutura

```
NexaTech/
├── index.html                  # Sistema completo (SPA)
├── conexao.php                 # Conexão com banco de dados
└── if0_40105852_usuarios.sql   # Script SQL da tabela de usuários
```

---

## 🚀 Como rodar

**Front-end (localStorage):**
1. Baixe ou clone o repositório
2. Abra o `index.html` no navegador

**Back-end (PHP + MySQL):**
1. Configure um servidor local (XAMPP, WAMP, etc.)
2. Importe o arquivo `.sql` no seu banco de dados
3. Ajuste as credenciais em `conexao.php`
4. Acesse via servidor local

---

## 👨‍💻 Autores

**Ailton Rodrigo**
Estudante de ADS — Fatec Bauru | Bauru, SP
**João Victor Godoy**
Estudante de ADS — Fatec Bauru | Bauru, SP
**Arthur Herique da Silva Gomes**
Estudante de ADS — Fatec Bauru | Bauru, SP

[LinkedIn](https://www.linkedin.com/in/ailton-rodrigo-da-silva-filho-59228a189/) • [GitHub](https://github.com/Ailtinho-Filho)
