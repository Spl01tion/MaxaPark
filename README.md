# MaxaPark — Gestão de Parque de Estacionamento

> Sistema informático de gestão do parque de estacionamento de viaturas do **Município de Machaquene**: controlo de entrada/saída de viaturas, gestão de lugares e pagamentos, e relatórios contabilísticos de receitas e utilização.

![Painel inicial](docs/screenshots/home_dashboard.png)

---

## 📋 Contexto

O parque funciona diariamente das **6h às 23h** e serve dois grupos de utentes: **estudantes** e **outros**. Todos os utentes são cadastrados e possuem um **cartão magnético pessoal** (com QRCode) que pode ser usado com qualquer viatura.

## ⚙️ Funcionamento geral

- **Entrada** — ao passar o cartão, ficam registados a data, a hora de entrada e a identificação do utente. É emitido um **talão** com o nº de utente, data, hora e um **QRCode**, e o painel actualiza os lugares disponíveis.
- **Saída** — o talão é lido (QRCode), o sistema apresenta a data, a hora de saída e o **valor a pagar**, imprime no talão e **liberta a vaga**.
- **Painel público** — mostra, em tempo real, os lugares livres e se o parque está **Aberto/Fechado**.

## 💰 Regras de pagamento

| Modalidade | Estudante | Outro |
|------------|-----------|-------|
| **Mensal** | 50% de desconto | 25% de desconto |
| **Por hora** | 50% de desconto | sem desconto |
| **Após as 23h** (parque fechado) | por hora, **sem desconto** | por hora, **sem desconto** |

A partir das 23h paga-se por hora, sem descontos: **todo o tempo** que a viatura ficou no parque (quem paga por hora) ou **das 23h até à retirada** (mensalistas). Os mensalistas, dentro do horário, não pagam à saída.

## 🧩 Funcionalidades

**Área do funcionário**
- Registo de **Entrada** e **Saída** (com leitura de QRCode por câmara)
- **Pagamento de mensalidades**
- **Registo de utentes** e **impressão de cartão** (com QRCode, tamanho real CR80)
- **Actividade** — viaturas actualmente no parque
- Painel inicial com estatísticas e acessos rápidos

**Área de administração**
- **Dashboard** com receitas e ocupação
- Gestão de **Utilizadores**, **Utentes** e **Vagas**
- **Mensalidades** e livro de **Pagamentos/Receitas**
- **Relatórios** contabilísticos por período
- **Configurações** — horário de funcionamento editável

## 🖼️ Capturas de ecrã

### Login e Painel público
| Login | Painel de Vagas |
|-------|-----------------|
| ![Login](docs/screenshots/login.png) | ![Painel](docs/screenshots/painel.png) |

### Operações (funcionário)
| Entrada | Saída |
|---------|-------|
| ![Entrada](docs/screenshots/entrada.png) | ![Saída](docs/screenshots/saida.png) |

| Pagamentos | Actividade |
|------------|------------|
| ![Pagamentos](docs/screenshots/pagamentos.png) | ![Actividade](docs/screenshots/ocorrencia.png) |

| Imprimir Cartão |
|-----------------|
| ![Imprimir Cartão](docs/screenshots/imprimir.png) |

### Administração
| Dashboard | Relatório |
|-----------|-----------|
| ![Dashboard](docs/screenshots/admin_dashboard.png) | ![Relatório](docs/screenshots/admin_relatorio.png) |

| Utentes | Vagas |
|---------|-------|
| ![Utentes](docs/screenshots/admin_utentes.png) | ![Vagas](docs/screenshots/admin_vagas.png) |

| Mensalidades | Configurações |
|--------------|---------------|
| ![Mensalidades](docs/screenshots/admin_mensalidades.png) | ![Configurações](docs/screenshots/admin_configuracoes.png) |

## 🛠️ Tecnologias

- **Backend:** PHP 8.2 (router MVC simples)
- **Base de dados:** MySQL / MariaDB (PDO)
- **Frontend:** Bootstrap 5, Bootstrap Icons, fonte Poppins, tema próprio da marca (`#9C1980`)
- **QRCode:** phpqrcode (geração) + html5-qrcode (leitura por câmara)
- **Ambiente:** XAMPP (Apache + MySQL)

## 🗄️ Base de dados

Principais tabelas: `users` (funcionários/admin), `utentes`, `vagas`, `registos` (sessões de entrada/saída), `mensalidades` e `configuracoes`.

## 🚀 Instalação

1. Coloque o projecto em `c:\xampp\htdocs\maxapark` e inicie o **Apache** e o **MySQL** (XAMPP).
2. Active a extensão **GD** no `php.ini` (necessária para gerar QRCodes): descomente `extension=gd` e reinicie o Apache.
3. Aceda a **`/maxapark/public/setup`** no navegador para criar as tabelas e os dados iniciais.
4. Faça login em **`/maxapark/public/login`**.

**Credenciais por omissão:**
- Administrador — `admin` / `admin123`
- Funcionário — `func` / `func123`

## 📁 Estrutura

```
app/
  core/        configuração, ligação à BD, funções e biblioteca QRCode
  pages/       páginas e controladores (home/, admin/)
public/
  index.php    ponto de entrada (router)
  assets/      bootstrap, css (tema maxa.css), imagens
docs/
  screenshots/ capturas de ecrã usadas neste README
```

---

© 2026 — MaxaPark · Município de Machaquene
